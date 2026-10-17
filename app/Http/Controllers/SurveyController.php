<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Support\SurveyStats;
use App\Support\SurveySubmission;
use App\Support\Zones;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class SurveyController extends Controller
{
    private const MAX_BYTES = 16384;
    private const LIMIT_MAX = 120;
    private const LIMIT_WINDOW = 600; // secunde
    private const MIN_ZONE_RESPONSES = 5; // sub acest prag o zonă nu se publică

    /** Primește un chestionar completat (JSON) și îl salvează. */
    public function submit(Request $request): JsonResponse
    {
        if (strlen($request->getContent()) > self::MAX_BYTES) {
            return $this->json(['ok' => false, 'error' => 'too_large'], 413);
        }
        $in = json_decode($request->getContent(), true);
        if (! is_array($in)) {
            return $this->json(['ok' => false, 'error' => 'json'], 400);
        }

        // Adresa IP nu se salvează: limita folosește un cod criptat, ținut în cache doar pe durata ferestrei.
        $key = 'submit:'.hash_hmac('sha256', (string) $request->ip(), (string) config('app.key'));
        if (RateLimiter::tooManyAttempts($key, self::LIMIT_MAX)) {
            return $this->json(['ok' => false, 'error' => 'rate'], 429);
        }

        [$ok, $res] = SurveySubmission::validate($in);
        if (! $ok) {
            return $this->json(['ok' => false, 'error' => 'invalid', 'field' => $res], 422);
        }

        SurveyResponse::create($res + ['created_at' => now()]);
        RateLimiter::hit($key, self::LIMIT_WINDOW);

        return $this->json(['ok' => true]);
    }

    /** Statistici publice: fără trasee, comentarii sau ora răspunsurilor. Zonele apar la cel puțin 2 elevi. */
    public function stats(): JsonResponse
    {
        return $this->json(SurveyStats::dataset(false))->header('Cache-Control', 'public, max-age=30');
    }

    /**
     * Statistici agregate pentru alte site-uri (Green Map). Fără răspunsuri individuale:
     * doar totaluri, iar zonele apar numai dacă au cel puțin MIN_ZONE_RESPONSES mențiuni.
     */
    public function publicSummary(): JsonResponse
    {
        $students = SurveyResponse::where('in_stats', true);

        // Zile raportate pe săptămână, nu număr de elevi.
        $transport = [];
        foreach (['foot', 'bike', 'public', 'car', 'taxi', 'other'] as $mode) {
            $transport[$mode] = (int) (clone $students)->whereNotNull('m_'.$mode)->sum('m_'.$mode);
        }

        $travelTime = array_fill_keys(['sub15', '15-20', '20-30', 'peste30'], 0);
        foreach ((clone $students)->whereNotNull('time_to_school')->selectRaw('time_to_school, COUNT(*) as c')->groupBy('time_to_school')->pluck('c', 'time_to_school') as $k => $c) {
            if (array_key_exists($k, $travelTime)) {
                $travelTime[$k] = (int) $c;
            }
        }

        $help = array_fill_keys(['info', 'rute', 'piste', 'altceva'], 0);
        foreach ((clone $students)->whereNotNull('help_opts')->pluck('help_opts') as $csv) {
            foreach (explode(',', (string) $csv) as $opt) {
                if (array_key_exists($opt, $help)) {
                    $help[$opt]++;
                }
            }
        }

        $zones = array_map(
            fn ($z) => ['name' => $z['name'], 'responses' => $z['n']],
            Zones::counts((clone $students)->whereNotNull('route')->pluck('route'), self::MIN_ZONE_RESPONSES),
        );

        $co2 = $this->co2Summary();

        return $this->json([
            'ok' => true,
            'updatedAt' => now()->utc()->format('Y-m-d\TH:i:s\Z'),
            'totalResponses' => SurveyResponse::count(),
            'studentResponses' => (clone $students)->count(),
            'transport' => $transport,
            'travelTime' => $travelTime,
            'help' => $help,
            'zones' => $zones,
            'co2' => $co2,
        ])->header('Cache-Control', 'public, max-age=30');
    }

    /**
     * Emisii estimate pentru o săptămână raportată (până la 5 zile de școală), pe baza mijlocului de transport
     * și a mijlocului intervalului de distanță. Răspunsurile fără distanță cunoscută sunt excluse.
     */
    private function co2Summary(): array
    {
        $factors = config('co2.factors');
        $distances = config('co2.distance_km');
        $legs = (int) config('co2.legs_per_day');

        $total = 0.0;
        $included = 0;
        $excluded = 0;
        $rows = SurveyResponse::where('in_stats', true)->whereNotNull('m_foot')
            ->get(['dist', 'm_foot', 'm_bike', 'm_public', 'm_car', 'm_taxi', 'm_other']);
        foreach ($rows as $r) {
            $km = $distances[$r->dist] ?? null;
            if ($km === null) {
                $excluded++;

                continue;
            }
            foreach ($factors as $mode => $factor) {
                $total += (int) $r->{'m_'.$mode} * $legs * $km * $factor;
            }
            $included++;
        }

        return [
            'totalKg5Days' => round($total, 1),
            'averageKg5Days' => $included ? round($total / $included, 2) : 0,
            'includedResponses' => $included,
            'excludedResponses' => $excluded,
            'unit' => 'kgCO2e',
            'period' => '5 school days',
            'method' => config('co2.method'),
        ];
    }

    private function json(array $data, int $status = 200): JsonResponse
    {
        return response()->json($data, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ->header('Cache-Control', 'no-store');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Support\SurveyStats;
use App\Support\SurveySubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class SurveyController extends Controller
{
    private const MAX_BYTES = 16384;
    private const LIMIT_MAX = 120;
    private const LIMIT_WINDOW = 600; // secunde

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

    private function json(array $data, int $status = 200): JsonResponse
    {
        return response()->json($data, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ->header('Cache-Control', 'no-store');
    }
}

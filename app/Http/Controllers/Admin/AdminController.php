<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyResponse;
use App\Support\SurveyStats;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AdminController extends Controller
{
    private const PER_PAGE = 50;

    public function loginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login', ['configured' => (string) config('admin.password_hash') !== '', 'error' => null]);
    }

    public function login(Request $request): View|RedirectResponse
    {
        $hash = (string) config('admin.password_hash');
        $key = 'login_fail:'.hash_hmac('sha256', (string) $request->ip(), (string) config('app.key'));
        $error = null;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $error = 'Prea multe încercări greșite. Încearcă din nou peste 15 minute.';
        } else {
            $okUser = hash_equals((string) config('admin.user'), (string) $request->input('user'));
            $okPass = $hash !== '' && Hash::check((string) $request->input('pass'), $hash);
            if ($okUser && $okPass) {
                RateLimiter::clear($key);
                $request->session()->regenerate();
                $request->session()->put(['admin' => true, 'admin_at' => time()]);

                return redirect()->route('admin.dashboard');
            }
            RateLimiter::hit($key, 900);
            usleep(400000);
            $error = 'Utilizator sau parolă greșite.';
        }

        return view('admin.login', ['configured' => $hash !== '', 'error' => $error]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard(): View
    {
        $byStatus = ['complete' => 0, 'no_car' => 0, 'short_trip' => 0, 'zero_days' => 0, 'not_student' => 0];
        foreach (SurveyResponse::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status') as $s => $c) {
            $byStatus[$s] = (int) $c;
        }

        // Răspunsuri pe zile, ultimele 14 zile
        $from = Carbon::today()->subDays(13);
        $perDay = SurveyResponse::where('created_at', '>=', $from)
            ->get(['created_at'])->countBy(fn ($r) => $r->created_at->format('Y-m-d'));
        $days = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $days[$d->format('d.m')] = (int) ($perDay[$d->format('Y-m-d')] ?? 0);
        }

        return view('admin.dashboard', [
            'total' => array_sum($byStatus),
            'byStatus' => $byStatus,
            'students' => $byStatus['complete'] + $byStatus['no_car'] + $byStatus['short_trip'] + $byStatus['zero_days'],
            'last24' => SurveyResponse::where('created_at', '>=', now()->subDay())->count(),
            'lastAt' => SurveyResponse::max('created_at'),
            'days' => $days,
            'maxDay' => max(1, max($days)),
            'bothers' => SurveyResponse::whereNotNull('bother')->where('bother', '<>', '')->orderByDesc('id')->limit(15)->get(['created_at', 'bother']),
            'comments' => SurveyResponse::whereNotNull('comment')->where('comment', '<>', '')->orderByDesc('id')->limit(15)->get(['created_at', 'comment']),
        ]);
    }

    /** Datele complete pentru grafice (cu trasee și comentarii). */
    public function data(): JsonResponse
    {
        return response()->json(SurveyStats::dataset(true), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function responses(Request $request): View
    {
        $statuses = ['' => 'Toate', 'complete' => 'Complete', 'no_car' => 'Fără mașină', 'short_trip' => 'Drum sub 15 min', 'zero_days' => '0 zile la școală', 'not_student' => 'Nu sunt elevi/studenți'];
        $status = (string) $request->query('status', '');
        if (! isset($statuses[$status])) {
            $status = '';
        }
        $q = trim((string) $request->query('q', ''));
        $onlyComments = (bool) $request->query('comentarii');

        $query = SurveyResponse::query()
            ->when($status !== '', fn ($w) => $w->where('status', $status))
            ->when($onlyComments, fn ($w) => $w->whereNotNull('comment')->where('comment', '<>', ''))
            ->when($q !== '', function ($w) use ($q) {
                $like = '%'.addcslashes($q, '\\%_').'%';
                $w->where(fn ($x) => $x->where('route', 'like', $like)->orWhere('comment', 'like', $like)->orWhere('bother', 'like', $like));
            })
            ->orderByDesc('id');

        return view('admin.responses', [
            'rows' => $query->paginate(self::PER_PAGE)->withQueryString(),
            'statuses' => $statuses,
            'status' => $status,
            'q' => $q,
            'onlyComments' => $onlyComments,
        ]);
    }

    public function destroy(Request $request, SurveyResponse $response): RedirectResponse
    {
        $response->delete();

        return redirect()->route('admin.responses', $request->query());
    }
}

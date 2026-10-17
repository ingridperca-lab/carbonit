<?php

namespace App\Support;

use App\Models\SurveyResponse;

class SurveyStats
{
    /** Etichetele răspunsurilor, în română, pentru panoul de admin. */
    public static function labels(): array
    {
        return [
            'status' => ['complete' => 'Complet', 'no_car' => 'Fără mașină', 'zero_days' => '0 zile la școală', 'not_student' => 'Nu e elev'],
            'modes' => ['foot' => 'Pe jos', 'bike' => 'Bicicletă', 'public' => 'Transport public', 'car' => 'Mașină', 'taxi' => 'Taxi', 'other' => 'Altul'],
            'dist' => ['sub1' => 'Sub 1 km', '1-3' => '1–3 km', '3-5' => '3–5 km', '5-10' => '5–10 km', 'peste10' => 'Peste 10 km', 'nu_stiu' => 'Nu știu'],
            'traffic' => ['deloc' => 'Aproape deloc', 'sub5' => 'Sub 5 min', '5-10' => '5–10 min', '10-20' => '10–20 min', '20-30' => '20–30 min', 'peste30' => 'Peste 30 min', 'nu_stiu' => 'Nu știu'],
            'why' => ['special' => 'Special pentru școală', 'ocol' => 'Face un ocol', 'oricum' => 'Merge oricum pe acolo', 'diferit' => 'Situații diferite', 'nu_stiu' => 'Nu știu'],
            'shared' => ['nu' => 'Nu', 'da' => 'Da', 'uneori' => 'Uneori', 'nu_stiu' => 'Nu știu'],
            'alt' => ['foot' => 'Pe jos', 'bike' => 'Bicicletă', 'public' => 'Transport public', 'other' => 'Altă variantă', 'niciuna' => 'Niciuna acum'],
            'block' => ['dist' => 'Distanța', 'timp' => 'Timpul', 'legaturi' => 'Legăturile de transport', 'traseu' => 'Traseul dificil', 'orar' => 'Orarul', 'vreme' => 'Vremea', 'alt' => 'Alt motiv', 'nu_raspund' => 'Prefer să nu răspund'],
            'help' => ['info' => 'Informații despre transport', 'rute' => 'Rute noi de autobuz/tramvai', 'piste' => 'Piste pentru bicicletă', 'altceva' => 'Altceva'],
        ];
    }

    public static function label(string $group, ?string $code): string
    {
        if ($code === null || $code === '') {
            return '–';
        }

        return self::labels()[$group][$code] ?? $code;
    }

    public static function labelList(string $group, ?string $csv): string
    {
        if ($csv === null || $csv === '') {
            return '–';
        }

        return implode(', ', array_map(fn ($c) => self::label($group, $c), explode(',', $csv)));
    }

    /** Rândul din baza de date, în forma pe care o folosește scriptul de statistici din site. */
    public static function payload(SurveyResponse $r, bool $withText): array
    {
        $a = ['student' => $r->status === 'not_student' ? 'nu' : 'da'];
        if ($r->status !== 'not_student') {
            $a['days'] = (int) $r->days;
            if ($r->status !== 'zero_days') {
                $a['modes'] = [
                    'foot' => (int) $r->m_foot, 'bike' => (int) $r->m_bike, 'public' => (int) $r->m_public,
                    'car' => (int) $r->m_car, 'taxi' => (int) $r->m_taxi, 'other' => (int) $r->m_other,
                ];
                $a['dist'] = $r->dist;
                if ($r->traffic !== null && $r->traffic !== '') {
                    $a['traffic'] = $r->traffic;
                }
                if ($withText && ! empty($r->route)) {
                    $a['route'] = $r->route;
                }
                if ($r->status === 'complete') {
                    $a['why'] = $r->why;
                    $a['shared'] = $r->shared;
                    $a['alt'] = $r->alt;
                    $a['replace'] = (int) $r->replace_days;
                    $a['block'] = $r->block_opts ? explode(',', $r->block_opts) : [];
                    $a['help'] = $r->help_opts ? explode(',', $r->help_opts) : [];
                }
            }
        }
        if ($withText && ! empty($r->comment)) {
            $a['comment'] = $r->comment;
        }

        return [
            'stare' => $r->status,
            'inStatisticaElevilor' => (bool) $r->in_stats,
            'raspunsuri' => $a,
        ];
    }

    /** Statisticile pentru site (fără trasee/comentarii) sau pentru admin (complete). */
    public static function dataset(bool $full): array
    {
        $responses = [];
        $routes = [];
        foreach (SurveyResponse::where('in_stats', true)->orderBy('id')->get() as $r) {
            $responses[] = self::payload($r, $full);
            if (! empty($r->route)) {
                $routes[] = $r->route;
            }
        }

        return [
            'ok' => true,
            'total' => count($responses),
            'responses' => $responses,
            'zones' => Zones::counts($routes, $full ? 1 : 2),
            'zones_base' => count($routes),
        ];
    }
}

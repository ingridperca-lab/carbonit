<?php

namespace App\Support;

/**
 * Verificarea răspunsurilor primite de la site. Nu avem încredere în ce trimite browserul:
 * fiecare valoare se verifică, iar starea (elev, 0 zile etc.) se calculează aici.
 */
class SurveySubmission
{
    private const MODE_KEYS = ['foot', 'bike', 'public', 'car', 'taxi', 'other'];
    private const OPT_DIST = ['sub1', '1-3', '3-5', '5-10', 'peste10', 'nu_stiu'];
    private const OPT_TRAFFIC = ['deloc', 'sub5', '5-10', '10-20', '20-30', 'peste30', 'nu_stiu'];
    private const OPT_WHY = ['special', 'ocol', 'oricum', 'diferit', 'nu_stiu'];
    private const OPT_SHARED = ['nu', 'da', 'uneori', 'nu_stiu'];
    private const OPT_ALT = ['foot', 'bike', 'public', 'other', 'niciuna'];
    private const OPT_BLOCK = ['dist', 'timp', 'legaturi', 'traseu', 'orar', 'vreme', 'alt', 'nu_raspund'];
    private const OPT_HELP = ['info', 'rute', 'piste', 'altceva'];

    /**
     * @return array{0: bool, 1: array|string} [true, rândul de salvat] sau [false, câmpul greșit]
     */
    public static function validate(mixed $in): array
    {
        if (! is_array($in) || ! isset($in['raspunsuri']) || ! is_array($in['raspunsuri'])) {
            return [false, 'format'];
        }
        $a = $in['raspunsuri'];
        $row = [
            'status' => null, 'in_stats' => false, 'days' => null,
            'm_foot' => null, 'm_bike' => null, 'm_public' => null, 'm_car' => null, 'm_taxi' => null, 'm_other' => null,
            'dist' => null, 'traffic' => null, 'route' => null,
            'why' => null, 'shared' => null, 'alt' => null, 'replace_days' => null,
            'block_opts' => null, 'help_opts' => null, 'comment' => null, 'taxi_separate' => false,
        ];

        $student = $a['student'] ?? null;
        if ($student === 'nu') {
            $row['status'] = 'not_student';

            return [true, $row];
        }
        if ($student !== 'da') {
            return [false, 'student'];
        }
        $row['in_stats'] = true;

        $days = $a['days'] ?? null;
        if (! is_int($days) || $days < 0 || $days > 5) {
            return [false, 'days'];
        }
        $row['days'] = $days;
        if ($days === 0) {
            $row['status'] = 'zero_days';

            return [true, $row];
        }

        $m = $a['modes'] ?? null;
        if (! is_array($m)) {
            return [false, 'modes'];
        }
        $sum = 0;
        foreach (self::MODE_KEYS as $k) {
            $v = $m[$k] ?? null;
            if (! is_int($v) || $v < 0 || $v > 5) {
                return [false, 'modes'];
            }
            $row['m_'.$k] = $v;
            $sum += $v;
        }
        if ($sum !== $days) {
            return [false, 'modes_sum'];
        }
        $row['taxi_separate'] = $m['taxi'] > 0;

        if (! self::inSet($a['dist'] ?? null, self::OPT_DIST)) {
            return [false, 'dist'];
        }
        $row['dist'] = $a['dist'];

        if ($m['car'] + $m['public'] + $m['taxi'] + $m['other'] > 0) {
            if (! self::inSet($a['traffic'] ?? null, self::OPT_TRAFFIC)) {
                return [false, 'traffic'];
            }
            $row['traffic'] = $a['traffic'];
        }
        $row['route'] = self::cleanText($a['route'] ?? null, 300);

        $car = $m['car'];
        if ($car === 0) {
            $row['status'] = 'no_car';

            return [true, $row];
        }

        if (! self::inSet($a['why'] ?? null, self::OPT_WHY)) {
            return [false, 'why'];
        }
        if (! self::inSet($a['shared'] ?? null, self::OPT_SHARED)) {
            return [false, 'shared'];
        }
        if (! self::inSet($a['alt'] ?? null, self::OPT_ALT)) {
            return [false, 'alt'];
        }
        $row['why'] = $a['why'];
        $row['shared'] = $a['shared'];
        $row['alt'] = $a['alt'];

        if ($a['alt'] === 'niciuna') {
            $row['replace_days'] = 0;
        } else {
            $rep = $a['replace'] ?? null;
            if (! is_int($rep) || $rep < 0 || $rep > $car) {
                return [false, 'replace'];
            }
            $row['replace_days'] = $rep;
        }

        $block = self::cleanMulti($a['block'] ?? null, self::OPT_BLOCK, 2);
        if ($block === null) {
            return [false, 'block'];
        }
        $help = self::cleanMulti($a['help'] ?? null, self::OPT_HELP, 4);
        if ($help === null) {
            return [false, 'help'];
        }
        $row['block_opts'] = implode(',', $block);
        $row['help_opts'] = implode(',', $help);
        $row['comment'] = self::cleanText($a['comment'] ?? null, 300);
        $row['status'] = 'complete';

        return [true, $row];
    }

    private static function inSet(mixed $v, array $set): bool
    {
        return is_string($v) && in_array($v, $set, true);
    }

    private static function cleanText(mixed $v, int $max): ?string
    {
        if (! is_string($v)) {
            return null;
        }
        $v = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $v);
        if ($v === null) {
            return null;
        }
        $v = trim((string) preg_replace('/\s+/u', ' ', $v));
        if ($v === '') {
            return null;
        }

        return mb_strlen($v) > $max ? mb_substr($v, 0, $max) : $v;
    }

    /** Alegeri multiple: valori permise, fără dubluri, între 1 și $max. Returnează lista sau null. */
    private static function cleanMulti(mixed $v, array $set, int $max): ?array
    {
        if (! is_array($v)) {
            return null;
        }
        foreach ($v as $x) {
            if (! self::inSet($x, $set)) {
                return null;
            }
        }
        $out = array_values(array_filter($set, fn ($opt) => in_array($opt, $v, true)));
        if (in_array('nu_raspund', $out, true)) {
            $out = ['nu_raspund'];
        }

        return (count($out) >= 1 && count($out) <= $max) ? $out : null;
    }
}

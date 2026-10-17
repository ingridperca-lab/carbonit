<?php

namespace App\Support;

/**
 * Potrivește traseele scrise de elevi cu zonele din resources/data/zones.json și numără, pentru fiecare zonă,
 * câți elevi au menționat-o. Se trimit doar numere, niciodată textul traseelor.
 * Pozițiile din zones.json sunt aproximative (centrul zonei). Se pot corecta sau adăuga zone direct acolo.
 */
class Zones
{
    private static function defs(): array
    {
        static $defs = null;

        return $defs ??= json_decode((string) @file_get_contents(resource_path('data/zones.json')), true) ?: [];
    }

    /** Text fără diacritice, litere mici, doar litere și cifre separate prin spațiu. */
    private static function norm(string $s): string
    {
        $s = strtr(mb_strtolower($s), [
            'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ş' => 's', 'ț' => 't', 'ţ' => 't',
            'á' => 'a', 'à' => 'a', 'é' => 'e', 'è' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        ]);

        return trim(preg_replace('/[^a-z0-9]+/', ' ', $s) ?? '');
    }

    /** Aliasurile de 5+ litere se potrivesc și ca început de cuvânt (copou, copoul), cele scurte doar ca cuvânt întreg. */
    private static function hit(string $text, string $alias): bool
    {
        $needle = ' '.$alias.(strlen($alias) >= 5 ? '' : ' ');

        return str_contains(' '.$text.' ', $needle);
    }

    /** Zonele menționate de cel puțin $min elevi, cele mai menționate primele. */
    public static function counts(iterable $routes, int $min = 2): array
    {
        $defs = self::defs();
        $cnt = [];
        foreach ($routes as $t) {
            $text = self::norm((string) $t);
            if ($text === '') {
                continue;
            }
            foreach ($defs as $z) {
                foreach ($z['aliases'] as $a) {
                    if (self::hit($text, self::norm($a))) {
                        $cnt[$z['id']] = ($cnt[$z['id']] ?? 0) + 1;
                        break;
                    }
                }
            }
        }
        $out = [];
        foreach ($defs as $z) {
            $n = $cnt[$z['id']] ?? 0;
            if ($n >= $min) {
                $out[] = ['id' => $z['id'], 'name' => $z['name'], 'lat' => $z['lat'], 'lng' => $z['lng'], 'r' => $z['r'], 'n' => $n];
            }
        }
        usort($out, fn ($a, $b) => $b['n'] <=> $a['n']);

        return $out;
    }
}

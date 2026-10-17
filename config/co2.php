<?php

/*
 * Ipoteze pentru estimarea emisiilor (metoda "distance-band-midpoint-v1").
 * Valorile sunt orientative: înainte de a le publica ca rezultate, verifică-le față de o sursă
 * citabilă (de exemplu DEFRA/UK GOV GHG conversion factors sau Agenția Europeană de Mediu) și notează anul.
 */
return [
    'method' => 'distance-band-midpoint-v1',

    // kg CO2e pe km parcurs de elev (dus sau întors), fără împărțire la numărul de persoane din vehicul
    'factors' => [
        'foot' => 0.0,
        'bike' => 0.0,
        'public' => 0.08,
        'car' => 0.17,
        'taxi' => 0.17,
        'other' => 0.08,
    ],

    // Distanța într-un sens, în km, pentru fiecare interval din chestionar. "nu_stiu" lipsește: acele răspunsuri sunt excluse.
    'distance_km' => [
        'sub1' => 0.5,
        '1-3' => 2.0,
        '3-5' => 4.0,
        '5-10' => 7.5,
        'peste10' => 12.0,
    ],

    // Dus-întors
    'legs_per_day' => 2,
];

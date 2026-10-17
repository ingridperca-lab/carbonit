<?php

return [
    // Site-urile care au voie să includă statisticile (iframe) sau să citească /api/stats (CORS), separate prin virgulă.
    'origins' => array_values(array_filter(array_map('trim', explode(',', env('EMBED_ORIGINS', 'https://erikaa0323.github.io'))))),
];

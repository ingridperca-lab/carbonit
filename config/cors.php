<?php

return [
    // Statisticile publice pot fi citite din site-urile permise în config/embed.php
    'paths' => ['api/stats'],
    'allowed_methods' => ['GET'],
    'allowed_origins' => (require __DIR__.'/embed.php')['origins'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Accept', 'Content-Type'],
    'exposed_headers' => [],
    'max_age' => 600,
    'supports_credentials' => false,
];

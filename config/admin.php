<?php

return [
    // Hosturile pe care răspunde panoul de admin (separate prin virgulă în .env)
    'hosts' => array_values(array_filter(array_map('trim', explode(',', env('ADMIN_HOSTS', 'admin.carbonit.localdev'))))),

    // Hash-ul parolei: php artisan tinker --execute="echo Hash::make('parola')"
    'user' => env('ADMIN_USER', 'admin'),
    'password_hash' => env('ADMIN_PASSWORD_HASH', ''),
];

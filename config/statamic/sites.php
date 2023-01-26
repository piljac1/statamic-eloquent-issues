<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sites
    |--------------------------------------------------------------------------
    |
    | Each site should have root URL that is either relative or absolute. Sites
    | are typically used for localization (eg. English/French) but may also
    | be used for related content (eg. different franchise locations).
    |
    */

    'sites' => [

        'fr' => [
            'name' => config('app.name').' (FR)',
            'locale' => 'fr',
            'url' => rtrim(env('APP_URL'), '/'),
        ],

        'en' => [
            'name' => config('app.name').' (EN)',
            'locale' => 'en',
            'url' => rtrim(env('APP_URL'), '/').'/en/',
        ],

    ],
];

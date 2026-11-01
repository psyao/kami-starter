<?php

declare(strict_types=1);

// The admin's labels for each Content Type in config/kami.php, by handle.
return [
    'home' => [
        'label' => 'Accueil',
        'fields' => [
            'intro' => ['label' => 'Introduction'],
        ],
    ],
    'tag' => [
        'label' => 'Étiquette',
        'plural' => 'Étiquettes',
    ],
    'page' => [
        'label' => 'Page',
        'plural' => 'Pages',
        'fields' => [
            'body' => ['label' => 'Texte', 'help' => 'Texte brut ; les retours à la ligne sont conservés.'],
            'tags' => ['label' => 'Étiquettes'],
        ],
    ],
];

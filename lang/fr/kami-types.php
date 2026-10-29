<?php

declare(strict_types=1);

// Les libellés de l'admin pour chaque Content Type de config/kami.php, par handle.
return [
    'page' => [
        'label' => 'Page',
        'plural' => 'Pages',
        'fields' => [
            'body' => ['label' => 'Texte', 'help' => 'Texte brut ; les retours à la ligne sont conservés.'],
        ],
    ],
];

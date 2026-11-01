<?php

declare(strict_types=1);

// The admin's labels for each Content Type in config/kami.php, by handle.
return [
    'home' => [
        'label' => 'Home',
        'fields' => [
            'intro' => ['label' => 'Introduction'],
        ],
    ],
    'tag' => [
        'label' => 'Tag',
        'plural' => 'Tags',
    ],
    'page' => [
        'label' => 'Page',
        'plural' => 'Pages',
        'fields' => [
            'body' => ['label' => 'Text', 'help' => 'Plain text; line breaks are kept.'],
            'tags' => ['label' => 'Tags'],
        ],
    ],
];

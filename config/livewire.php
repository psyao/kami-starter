<?php

declare(strict_types=1);

return [

    /*
    | No `max` rule: PHP's upload_max_filesize and post_max_size alone cap an upload, since Kami's Media library
    | accepts photos up to 40 MP and shows the lowest of PHP's and Livewire's limits (Livewire's default is 12 MB).
    */
    'temporary_file_upload' => [
        'rules' => ['required', 'file'],
    ],

];

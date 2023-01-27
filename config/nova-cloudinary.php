<?php

declare(strict_types=1);

return [

    'default' => [
        'cloud' => env('NOVA_CLOUDINARY_DEFAULT_CLOUD'),
        'username' => env('NOVA_CLOUDINARY_DEFAULT_USERNAME'),
        'key' => env('NOVA_CLOUDINARY_DEFAULT_KEY'),
        'secret' => env('NOVA_CLOUDINARY_DEFAULT_SECRET'),
    ],

];

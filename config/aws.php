
<?php
return [
    'credentials' => [
        'key'    => env('AWS_KEY'),
        'secret' => env('AWS_SECRET'),
    ],
    'region' => env('AWS_REGION'),
    'version' => 'latest',

    'cloudfront' => [
        'web' => env('AWS_CLOUDFRONT'),
    ]
];
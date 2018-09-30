<?php
return [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'mongodb_p'=> [
            'driver' => "mongodb",
            'host' => 'host',
            'port' => 27017,
            'database' => 'database',
            'username' => "username",
            'password' => "password",
            'options' => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ]
        ],
        'mongodb_c'=> [
            'driver' => "mongodb",
            'host' => 'host',
            'port' => 27017,
            'database' => 'database',
            'username' => "username",
            'password' => "password",
            'options' => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ]
        ],
    ],
];

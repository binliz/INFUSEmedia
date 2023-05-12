<?php

return [
    'mysql' => [
        'class' => \app\Connections\MysqlDatabase::class,
        'dsn' => 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname=' . getenv('MYSQL_DATABASE'),
        'user' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD')
    ]
];

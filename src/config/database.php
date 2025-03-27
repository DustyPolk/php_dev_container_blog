<?php
// Database configuration
return [
    'path' => getenv('SQLITE_DATABASE_PATH') ?: '/var/www/database/database.sqlite',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
]; 
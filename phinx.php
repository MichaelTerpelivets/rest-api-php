<?php

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'pgsql',
            'host' => 'db',
            'name' => 'market',
            'user' => 'john',
            'pass' => 'qwerty123',
            'port' => '5432',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
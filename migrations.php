<?php

return [
    'name' => 'SIGED Migrations',
    'migrations_namespace' => 'Classes\\Migrations',
    'table_name' => '__doctrine_migration_versions',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => 'Classes/Migrations',
    'all_or_nothing' => true,
    'check_database_platform' => true,
];

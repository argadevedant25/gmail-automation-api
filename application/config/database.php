<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn' => '',
    'hostname' => '', // Set dynamically from DATABASE_URL
    'username' => '', // Set dynamically from DATABASE_URL
    'password' => '', // Set dynamically from DATABASE_URL
    'database' => '', // Set dynamically from DATABASE_URL
    'dbdriver' => 'postgre',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'), // Disable in production
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => array('sslmode' => 'require', 'channel_binding' => 'require'), // Neon-specific SSL settings
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE,
    'port' => 5432 // Default PostgreSQL port
);

// Parse DATABASE_URL environment variable for Neon connection
$database_url = getenv('DATABASE_URL') ?: 'postgresql://neondb_owner:npg_U6g5sCwXIPoZ@ep-dry-hill-a12opvrh-pooler.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require';
if ($database_url) {
    $url = parse_url($database_url);
    $db['default']['hostname'] = $url['host'];
    $db['default']['username'] = $url['user'];
    $db['default']['password'] = $url['pass'];
    $db['default']['database'] = ltrim($url['path'], '/');
    $db['default']['port'] = $url['port'] ?? 5432;
}
?>
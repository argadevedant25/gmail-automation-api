<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn' => '',
    'hostname' => '', // Will be set dynamically from DATABASE_URL
    'username' => '', // Will be set dynamically from DATABASE_URL
    'password' => '', // Will be set dynamically from DATABASE_URL
    'database' => '', // Will be set dynamically from DATABASE_URL
    'dbdriver' => 'postgre',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'), // Disable in production to prevent error output
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => TRUE, // Enable SSL for Neon
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

// Parse DATABASE_URL environment variable for Neon connection
$database_url = getenv('DATABASE_URL') ?: 'postgresql://neondb_owner:npg_U6g5sCwXIPoZ@ep-noisy-bonus-a1n85050.ap-southeast-1.aws.neon.tech/ticket_system?sslmode=require&options=endpoint%3Dep-noisy-bonus-a1n85050';
if ($database_url) {
    $url = parse_url($database_url);
    $db['default']['hostname'] = $url['host'];
    $db['default']['username'] = $url['user'];
    $db['default']['password'] = $url['pass'];
    $db['default']['database'] = ltrim($url['path'], '/');
    $db['default']['port'] = $url['port'] ?? 5432; // Default PostgreSQL port
    $db['default']['sslmode'] = 'require'; // Enforce SSL for Neon
}
?>
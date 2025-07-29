<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn' => getenv('DATABASE_URL') ?: 'pgsql:host=ep-noisy-bonus-a1n85050.ap-southeast-1.aws.neon.tech;port=5432;dbname=ticket_system;user=neondb_owner;password=npg_U6g5sCwXIPoZ;sslmode=require;options=endpoint=ep-noisy-bonus-a1n85050',
    'hostname' => 'ep-noisy-bonus-a1n85050.ap-southeast-1.aws.neon.tech',
    'username' => 'neondb_owner',
    'password' => 'npg_U6g5sCwXIPoZ',
    'database' => 'ticket_system',
    'dbdriver' => 'postgre',
    'pconnect' => FALSE,
    'db_debug' => FALSE, // Disable to prevent header errors
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => array('sslmode' => 'require', 'options' => 'endpoint=ep-noisy-bonus-a1n85050'),
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE,
    'port' => 5432 // Explicit port
);

// Debug log for DATABASE_URL
log_message('debug', 'DATABASE_URL: ' . getenv('DATABASE_URL'));
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->database();
    }

    public function index() {
        try {
            // Test database connection
            if ($this->db->simple_query('SELECT 1')) {
                echo "Database connection successful!<br>";
            } else {
                echo "Failed to connect to database!<br>";
                echo "Error: " . $this->db->error()['message'] . "<br>";
            }

            // Check if tickets table exists
            $tables = $this->db->list_tables();
            echo "Available tables:<br>";
            print_r($tables);
            echo "<br>";

            if (in_array('tickets', $tables)) {
                echo "Tickets table exists!<br>";
                // Show table structure
                $fields = $this->db->field_data('tickets');
                echo "Table structure:<br>";
                print_r($fields);
            } else {
                echo "Tickets table does not exist!<br>";
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} 
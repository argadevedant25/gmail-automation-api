<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TicketModel extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_ticket($data) {
        try {
            return $this->db->insert('tickets', $data);
        } catch (Exception $e) {
            log_message('error', 'Failed to insert ticket: ' . $e->getMessage());
            return false;
        }
    }
}
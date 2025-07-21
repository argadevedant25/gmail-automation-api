<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TicketController extends CI_Controller {
    
    private $input;
    private $output;
    private $Ticket_model;

    public function __construct() {
        parent::__construct();
        
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Load required models and helpers
        $this->load->model('Ticket_model');
        $this->load->helper('url');
        
        // Initialize input and output
        $this->input = $this->input;
        $this->output = $this->output;
        
        // Enable CORS for Google Apps Script
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    public function create() {
        // Handle CORS preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }

        try {
            // Get JSON input
            $raw_input = file_get_contents('php://input');
            log_message('debug', 'Raw input received: ' . $raw_input);
            
            $data = json_decode($raw_input, true);
            log_message('debug', 'Decoded data: ' . print_r($data, true));

            // Validate input
            if (!$data || !isset($data['ticket_id']) || !isset($data['subject']) || !isset($data['sender']) || !isset($data['date']) || !isset($data['content'])) {
                $this->_send_response('error', 'Invalid or missing ticket details', 400);
                return;
            }

            // Validate Authorization token
            $auth_header = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
            $expected_token = '8858bc41fc4690641ec25c4b0ce43e81';
            
            if ($auth_header !== "Bearer $expected_token") {
                $this->_send_response('error', 'Unauthorized', 401);
                return;
            }

            // Prepare ticket data
            $ticket_data = [
                'ticket_id' => $data['ticket_id'],
                'subject' => $data['subject'],
                'sender' => $data['sender'],
                'date' => $data['date'],
                'content' => $data['content'],
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Save to database
            if ($this->Ticket_model->create_ticket($ticket_data)) {
                $this->_send_response('success', 'Ticket created successfully', 201, ['ticket_id' => $data['ticket_id']]);
            } else {
                log_message('error', 'Failed to save ticket: Database insert failed');
                $this->_send_response('error', 'Failed to save ticket', 500);
            }

        } catch (Exception $e) {
            log_message('error', 'Exception while processing ticket: ' . $e->getMessage());
            $this->_send_response('error', 'Internal server error', 500);
        }
    }

    private function _send_response($status, $message, $code = 200, $additional_data = []) {
        $response = array_merge([
            'status' => $status,
            'message' => $message
        ], $additional_data);

        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($response);
        exit();
    }
}
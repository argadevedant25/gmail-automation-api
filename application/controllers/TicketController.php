<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TicketController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load database
        header('Access-Control-Allow-Origin: *'); // Restrict to https://script.google.com in production
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        log_message('debug', 'TicketController::create called with method: ' . $_SERVER['REQUEST_METHOD']);
        log_message('debug', 'Request URI: ' . $_SERVER['REQUEST_URI']);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            log_message('debug', 'Handling OPTIONS request');
            http_response_code(200);
            exit();
        }

        $json = file_get_contents('php://input');
        log_message('debug', 'Raw input: ' . $json);
        $data = json_decode($json, true);

        log_message('debug', 'Parsed data: ' . print_r($data, true));

        if (!$data || !isset($data['ticket_id'], $data['subject'], $data['sender'], $data['date'], $data['content'])) {
            log_message('error', 'Invalid or missing ticket data: ' . print_r($data, true));
            http_response_code(400);
            echo json_encode(['error' => 'Invalid or missing ticket data']);
            return;
        }

        $auth_header = $this->input->get_request_header('Authorization');
        log_message('debug', 'Authorization header: ' . ($auth_header ?: 'None'));
        $expected_token = 'ed1087280daea66b576e155c17795609';
        if ($auth_header !== "Bearer $expected_token") {
            log_message('error', 'Unauthorized: Invalid token. Received: ' . ($auth_header ?: 'None'));
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $ticket_data = [
            'ticket_id' => $data['ticket_id'],
            'subject' => $data['subject'],
            'sender' => $data['sender'],
            'date' => $data['date'],
            'content' => $data['content']
        ];

        // Save to Neon PostgreSQL
        $this->db->insert('ticket_system', $ticket_data);
        if ($this->db->affected_rows() > 0) {
            log_message('debug', 'Ticket saved to database: ' . print_r($ticket_data, true));
        } else {
            log_message('error', 'Failed to save ticket to database');
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save ticket']);
            return;
        }

        log_message('debug', 'Ticket created: ' . print_r($ticket_data, true));
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Ticket created successfully',
            'ticket_id' => $data['ticket_id']
        ]);
    }
}

        // Example: Save to database (uncomment if using a model)
        // $this->load->model('Ticket_model');
        // $this->Ticket_model->create_ticket($ticket_data);

        // log_message('debug', 'Ticket created: ' . print_r($ticket_data, true));
        // http_response_code(200);
        // echo json_encode([
        //     'status' => 'success',
        //     'message' => 'Ticket created successfully',
        //     'ticket_id' => $data['ticket_id']
        // ]);

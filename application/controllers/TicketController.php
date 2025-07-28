<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TicketController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Ticket_model');
        $this->load->helper('url');
        // Enable CORS for Google Apps Script
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    public function create() {
        // Check if request is POST
        if ($this->input->method() !== 'post') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Method not allowed']));
            return;
        }

        // Get JSON input
        $json = $this->input->raw_input_stream;
        $data = json_decode($json, true);

        // Validate input
        if (!$data || !isset($data['ticket_id']) || !isset($data['subject']) || !isset($data['sender']) || !isset($data['date']) || !isset($data['content'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid or missing ticket details']));
            return;
        }

        // Validate API key
        $api_key = $this->input->get_request_header('Authorization');
        if ($api_key !== 'Bearer ed1087280daea66b576e155c17795609') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
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
        try {
            if ($this->Ticket_model->create_ticket($ticket_data)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(201)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Ticket created successfully', 'ticket_id' => $data['ticket_id']]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to save ticket']));
            }
        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]));
        }
    }
}
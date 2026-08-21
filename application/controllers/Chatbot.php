<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbot extends CI_Controller {

    public function index() {
        // Hanya memuat tampilan antarmuka chat
        $this->load->view('chatbot_ui');
    }
}
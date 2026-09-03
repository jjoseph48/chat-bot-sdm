<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbot_ui extends CI_Controller {

    public function index() {
        // Fungsi ini bertugas memanggil file chatbot_ui.php yang ada di folder views
        $this->load->view('chatbot_ui');
    }
}
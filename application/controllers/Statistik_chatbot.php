<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistik_chatbot extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pastikan model sudah diload
        $this->load->model('Log_chatbot_model');
    }

    public function index() {
        // Mengambil kumpulan data dari Model
        $data['kpi'] = $this->Log_chatbot_model->get_kpi_statistik();
        $data['top_pertanyaan'] = $this->Log_chatbot_model->get_top_pertanyaan(10);
        $data['top_faq_matched'] = $this->Log_chatbot_model->get_top_faq_matched(10);

        // Menampilkan ke layar (pastikan nama file view-nya benar)
        $this->load->view('dashboard_statistik', $data);
    }

    public function riwayat() {
        $data['logs'] = $this->Log_chatbot_model->get_semua_log();

        // render ke halaman view
        $this->load->view('list_log_chatbot', $data);
    }

}
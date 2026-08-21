<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_chatbot extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Faq_detail_model');
        $this->load->library('Nlp_engine'); // Memanggil library TF-IDF kita
        header('Content-Type: application/json');
    }

    public function balas_pesan() {
        $pesan_masuk = $this->input->post('pesan', TRUE);

        if (empty($pesan_masuk)) {
            echo json_encode(['status' => 'error', 'balasan' => 'Pesan kosong.']);
            return;
        }

        // 1. Ambil semua data FAQ dari database
        $data_faq = $this->Faq_detail_model->get_semua_faq_aktif();

        if (empty($data_faq)) {
            echo json_encode(['status' => 'error', 'balasan' => 'Database FAQ masih kosong.']);
            return;
        }

        // 2. Hitung kemiripan menggunakan TF-IDF & Cosine Similarity
        $hasil_kemiripan = $this->nlp_engine->hitung_kemiripan($pesan_masuk, $data_faq);

        // 3. Ambil hasil terbaik (skor tertinggi)
        $jawaban_terbaik = $hasil_kemiripan[0];

        // Threshold (Batas Toleransi Skor) - Jika skor Cosine terlalu rendah, berarti tidak nyambung
        if ($jawaban_terbaik['skor'] > 0.1) { 
            $balasan_bot = $jawaban_terbaik['jawaban'];
        } else {
            $balasan_bot = "Maaf, saya tidak memahami pertanyaan Anda. Bisa dijelaskan dengan cara lain?";
        }

        echo json_encode([
            'status' => 'sukses',
            'pesan_user' => $pesan_masuk,
            'skor_cosine' => round($jawaban_terbaik['skor'], 4), // Opsional: Tampilkan skor di API untuk kebutuhan jurnal
            'balasan' => $balasan_bot
        ]);
    }
}
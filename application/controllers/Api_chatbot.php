<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_chatbot extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Faq_detail_model');
        $this->load->library('Nlp_engine'); // Memanggil library TF-IDF kita
        $this->load->database();
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

        // 4. Ekstrak Top-1 dan Filter Top-3 (hanya ambil ID dan Skor)
        $jawaban_terbaik  = $hasil_kemiripan[0];

        $top_3_kandidat = [];
        $batas_loop = min(3, count($hasil_kemiripan));

        for($i = 0; $i < $batas_loop; $i++) {
            $top_3_kandidat[] = [
                'id_faq' => $hasil_kemiripan[$i]['id_faq'],
                'skor' => round($hasil_kemiripan[$i]['skor'], 4)
            ];
        }
        // $jawaban_terbaik = $hasil_kemiripan[0];

        // // Threshold (Batas Toleransi Skor) - Jika skor Cosine terlalu rendah, berarti tidak nyambung
        // if ($jawaban_terbaik['skor'] > 0.25) { 
        //     $balasan_bot = $jawaban_terbaik['jawaban'];
        // } else {
        //     $balasan_bot = "Maaf, saya tidak memahami pertanyaan Anda. Bisa dijelaskan dengan cara lain?";
        // }

        // 4. Threshold Decision (Batas Toleransi: 0.25)
        // 4. Threshold Decision (0.25)
        $threshold = 0.25;
        $is_match = ($jawaban_terbaik['skor'] >= $threshold) ? 1 : 0;

        if ($is_match) { 
            $balasan_bot = $jawaban_terbaik['jawaban'];
        } else {
            // Ambil 3 opsi alternatif dari database
            $faq_alternatif = $this->Faq_detail_model->get_faq_alternatif();
            
            // Susun kalimat permohonan maaf
            $balasan_bot = "Maaf, Bisma tidak memahami pertanyaan Anda. Apakah mungkin Anda bermaksud menanyakan salah satu topik ini?<br><br>";
            
            // Tambahkan daftar FAQ alternatif jika datanya ada
            if (!empty($faq_alternatif)) {
                $balasan_bot .= "<ul class='mb-0 text-start' style='padding-left: 18px;'>";
                foreach ($faq_alternatif as $faq) {
                    $balasan_bot .= "<li class='mb-1'><i>" . $faq['pertanyaan'] . "</i></li>";
                }
                $balasan_bot .= "</ul>";
            }
        }

        // 5. SIMPAN KE TABEL log_chatbot
        $data_log = [
            'pesan_user' => $pesan_masuk,
            'top_1_id_faq' => $jawaban_terbaik['id_faq'],
            'top_1_skor' => round($jawaban_terbaik['skor'], 4),
            'top_3_kandidat' => json_encode($top_3_kandidat),
            'status_match' => $is_match,
            'waktu_interaksi' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('log_chatbot', $data_log);

        // 6. Kembalikan response ke UI Chatbot
        echo json_encode([
            'status' => 'sukses',
            'pesan_user' => $pesan_masuk,
            'skor_cosine' => round($jawaban_terbaik['skor'], 4), // Opsional: Tampilkan skor di API untuk kebutuhan jurnal
            'balasan' => $balasan_bot
        ]);
    }
}
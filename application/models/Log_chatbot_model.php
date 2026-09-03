<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_chatbot_model extends CI_Model {

    // Menghitung 4 KPI Utama
    public function get_kpi_statistik() {
        // 1. Total Chats
        $total_chats = $this->db->count_all('log_chatbot');
        
        // 2. Chats Hari Ini
        $this->db->where('DATE(waktu_interaksi)', date('Y-m-d'));
        $chats_today = $this->db->count_all_results('log_chatbot');
        
        // 3. Rata-rata Skor (Semua)
        $this->db->select_avg('top_1_skor');
        $avg_all = $this->db->get('log_chatbot')->row()->top_1_skor;
        
        // 4. Rata-rata Skor (Matched)
        $this->db->select_avg('top_1_skor');
        $this->db->where('status_match', 1);
        $avg_matched = $this->db->get('log_chatbot')->row()->top_1_skor;
        
        return [
            'total_chats'      => $total_chats,
            'chats_hari_ini'   => $chats_today,
            'avg_skor_semua'   => round($avg_all, 4),
            'avg_skor_matched' => round($avg_matched, 4)
        ];
    }

    // Mengambil Top 10 Pertanyaan yang paling sering diketik user
    public function get_top_pertanyaan($limit = 10) {
        $this->db->select('pesan_user, COUNT(*) as jumlah');
        $this->db->group_by('pesan_user');
        $this->db->order_by('jumlah', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('log_chatbot')->result_array();
    }

    // Mengambil Top 10 FAQ yang paling sering berhasil dicocokkan (Matched)
    public function get_top_faq_matched($limit = 10) {
        $this->db->select('log_chatbot.top_1_id_faq, faq_detail.pertanyaan, COUNT(*) as jumlah_match');
        $this->db->from('log_chatbot');
        $this->db->join('faq_detail', 'faq_detail.id_faq_detail = log_chatbot.top_1_id_faq', 'left');
        $this->db->where('log_chatbot.status_match', 1); // Hanya yang lolos threshold
        $this->db->group_by('log_chatbot.top_1_id_faq, faq_detail.pertanyaan');
        $this->db->order_by('jumlah_match', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }
}
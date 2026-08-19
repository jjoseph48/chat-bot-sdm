<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_detail_model extends CI_Model {
    private $table = 'faq_detail';
    private $primary_key = 'id_faq_detail';

    // Menampilkan semua data FAQ (digabungkan dengan Kategori & Status)
    public function get_all(){
        $this->db->select('faq_detail.*, faq_kategori.judul_kategori, faq_status.judul_status');
        $this->db->from($this->table);

        // JOIN ke tabel Kategori
        $this->db->join('faq_kategori', 'faq_kategori.id_faq_kategori = faq_detail.id_faq_kategori_fk', 'left');

        // JOIN ke tabel Status
        $this->db->join('faq_status', 'faq_status.id_faq_status = faq_detail.faq_detail_status_fk', 'left');

        // Menyembunyikan FAQ yang berstatus "Dihapus"
        $this->db->where('faq_detail.faq_detail_status_fk !=', 2);

        // Mengurutkan dari yang terbaru
        // $this->db->order_by('faq_detail.dibuat_pada', 'DESC');

        return $this->db->get()->result_array();
    }

    // READ: Mengambil satu data spesifik berdasarkan ID
    public function get_by_id($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->table)->row_array();
    }

    // CREATE: Menyimpan data FAQ baru, dan mengembalikan ID yang baru dibuat
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id(); // penting untuk relasi tag
    }

    // UPDATE: Mengubah data FAQ yang sudah ada
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    // --- FITUR RELASI TAG (MANY-TO-MANY) ---
    
    // Menyimpan banyak Tag sekaligus ke tabel jembatan

}
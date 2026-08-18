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
        $this->db->join('faq_kategori', 'faq_kategori.id_faq_kategori = faq_detail.kategori_faq_fk', 'left');

        // JOIN ke tabel Status
        $this->db->join('faq_status', 'faq_status.id_faq_status = faq_detail.status_faq_fk', 'left');

        // Menyembunyikan FAQ yang berstatus "Dihapus"
        $this->db->where('faq_detail.status_faq_fk !=', 2);

        // Mengurutkan dari yang terbaru
        $this->db->order_by('faq_detail.dibuat_pada', 'DESC');

        return $this->db->get()->result_array();
    }

}
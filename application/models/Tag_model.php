<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tag_model extends CI_Model
{
    private $table = 'faq_tag';

    // Read: mengambil semua data tag faq
    public function get_all(){
        $this->db->select('faq_tag.*, faq_status.judul_status');
        $this->db->from($this->table);
        // Menggabungkan tabel tag dengan tabel status
        $this->db->join('faq_status', 'faq_status.id_faq_status = faq_tag.status_taq_fk', 'left');
        // Filter: sembunyikan data yang memiliki status 2
        $this->db->where('faq_tag.status_tag_fk !=', 2);

        return $this->db->get()->result_array();
    }

    // Read: mengambil 1 data tag berdasarkan ID (untuk edit)
    public function get_by_id($id){
        if(empty($id)) {
            return [];
        }

        return $this->db->get_where($this->table, ['id_faq_tag' => $id])->row_array();
    }

    // Create: menambah data tag baru
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Delete: menghapus data tag
    public function delete($id) {
        // Trapping: cegah penghapusan jika ID kosong
        if(empty($id)) {
            return false;
        }

        $this->db->where('id_faq_tag', $id);
        return $this->db->delete($this->table);
    }


}
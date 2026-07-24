<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tag_model extends CI_Model
{
    private $table = 'faq_tag';

    // Read: mengambil semua data tag faq
    public function get_all(){
        return $this->db->get($this-table)->result_array();
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
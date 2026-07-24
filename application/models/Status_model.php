<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Status_model extends CI_Model
{
    private $table = 'faq_status';

    // Read: mengambil semua data status faq
    public function get_all(){
        return $this->db->get($this-table)->result_array();
    }

    // Read: mengambil 1 data status berdasarkan ID (untuk edit)
    public function get_by_id($id){
        if(empty($id)) {
            return [];
        }

        return $this->db->get_where($this->table, ['id_faq_status' => $id])->row_array();
    }

    // Create: menambah data status baru
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Delete: menghapus data status
    public function delete($id) {
        // Trapping: cegah penghapusan jika ID kosong
        if(empty($id)) {
            return false;
        }

        $this->db->where('id_faq_status', $id);
        return $this->db->delete($this->table);
    }


}
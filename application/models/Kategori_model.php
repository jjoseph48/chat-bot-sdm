<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model
{
    private $table = 'faq_kategori';

    // Read: mengambil semua data kategor
    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    // Read: mengambil 1 data kategori berdasarkan ID (untuk edit)
    public function get_by_id($id){
        if (empty($id)) {
            return [];
        }

        return $this->db->get_where($this->table, ['id_faq_kategori' => $id])->row_array();
    }

    // Create : menambah data kategori baru
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update : mengubah data kategori
    public function update($id, $data) {
        // Trapping : cegah eksekusi jika id kosong atau data kosong
        if(empty($id) || empty($data)) {
            return false;
        }

        $this->db->where('id_faq_kategori', $id);
        return $this->db->update($this->table, $data);
    }

    // Delete: menghapus data kategori
    public function delete($id) {
        // Trapping : cegah penghapusan jika ID kosong
        if(empty($id)) {
            return false;
        }

        $this->db->where('id_faq_kategori', $id);
        return $this->db->delete($this->table);
    }

}
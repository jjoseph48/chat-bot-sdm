<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends CI_Controller
{
    public function __construct() {
        parent:: __construct();
        // Memuat Model Kategori
        $this->load->model('Tag_model');
    }

    // Read: Menampilkan halaman utama
    public function index() {
        $data['tag'] = $this->Tag_model->get_all();
        
        if(empty($data['tag'])) {
            $this->load->view('tag/empty_tag');
        } else {
            $this->load->view('tag/list_tag', $data);
        }
    }

    // Create: Proses menyimpan data baru
    public function simpan() {
        $judul = $this->input->post('judul_tag');

        // Trapping lapis Controller: Pastikan input tidak kosong
        if(!empty($judul)) {
            $data = [
                'judul_tag' => $judul,
                'status_tag_fk' => 1 // 1 adalah status "Aktif" di tabel faq_status
            ];
            $this->Tag_model->insert($data);

            // Set pesan sukses
            $this->session->set_flashdata('sukses', 'Tag baru berhasil ditambahkan.');
        } else {
            // Set pesan error
            $this->session->set_flashdata('error', 'Judul Tag tidak boleh kosong!');
        }

        redirect('tag');
    }

    // Update: Proses mengubah data dari form Pop-up/Modal Edit
    public function ubah() {
        $id = $this->input->post('id_faq_tag');
        $judul = $this->input->post('judul_tag');
        $status = $this->input->post('status_faq_tag');

        // Trapping lapis Controller: Pastikan ID, Judul dan status ada
        if(empty($id) || empty($judul) || empty($status)) {
            $this->session->set_flashdata('error', 'Data tidak valid. ID atau Judul Kosong!');
            redirect('tag');
        }

        $data = [
            'judul_tag' => $judul,
            'status_tag_fk' => $status
        ];

        // Eksekusi Model dan cek nilai boolean yang dikembalikan
        if($this->Tag_model->update($id, $data)) {
            $this->session->set_flashdata('sukses', 'Data tag berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan. Data gagal diperbarui');
        }

        redirect('tag');
    }

    // Delete: Proses menghapus data dengan metode soft delete
    public function hapus($id = null) {
        // Trapping
        if(empty($id)){show_404();}

        $data = ['status_kategor_fk' => 2];

        if($this->Tag_model->update($id, $data)) {
            $this->session->set_flashdata('Sukses'. 'Kategori berhasil diarsipkan.');
        }

        redirect('tag');
    }

}
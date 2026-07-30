<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends CI_Controller
{
    public function __construct() {
        parent:: __construct();
        $this->load->model('Status_model');
    }

    // Read: Menampilkan halaman utama
    public function index() {
        $data['status'] = $this->Status_model->get_all();

        // Pengecekan: Apakah array kategori kosong?
        if (empty($data['status'])) {
            // jika kosong, muat tampilan empty state
            $this->load->view('status/empty_status');
        } else {
            // jika ada data, muat tampilan tabel (list)
            $this->load->view('status/list_status', $data);
        }
        
    }

    // Create: Proses menyimpan data baru
    public function simpan() {
        $judul = $this->input->post('judul_status');

        // Trapping lapis Controller
        if(!empty($judul)){
            $data = ['judul_status' => $judul];
            $this->Status_model->insert($data);

            // Set pesan sukses
            $this->session->set_flashdata('sukses', 'Status baru berhasil ditambahkan.');
        } else {
            // Set pesan error
            $this->session->set_flashdata('error', 'Judul Status tidak boleh kosong!');
        }

        redirect('status');
    }

    // Update
    public function ubah() {
        $id = $this->input->post('id_faq_status');
        $judul = $this->input->post('judul_status');

        // Trapping
        if(empty($id) || empty($judul)) {
            $this->session->set_flashdata('error', 'Data tidak valid. ID atau Judul Kosong!');
            redirect('status');
        }

        $data = ['judul_status' => $judul];

        // Eksekusi Model
        if($this->Status_model->update($id, $data)) {
            $this->session->set_flashdata('sukses', 'Data status berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan. Data gagal diperbarui');
        }

        redirect('status');
    }

}
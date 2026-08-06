<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller 
{

    public function __construct() {
        parent:: __construct();
        // Memuat Model Kategori
        $this->load->model('Kategori_model');
    }

    // Read: Menampilkan halaman utama
    public function index() {
        $data['kategori'] = $this->Kategori_model->get_all();
        
        // Pengecekan: Apakah array kategori kosong?
        if(empty($data['kategori'])) {
            // jika kosong, muat tampilan empty state
            $this->load->view('kategori/empty_kategori');
        } else {
            // jika ada data, muat tampilan tabel (list)
            $this->load->view('kategori/list_kategori', $data);
        }
    }

    // Create: Proses menyimpan data baru
    public function simpan() {
        $judul = $this->input->post('judul_kategori');

        // Trapping lapis Controller: Pastikan input tidak kosong
        if(!empty($judul)) {
            $data = [
                'judul_kategori' => $judul,
                'status_kategori_fk' => 1 // 1 adalah status "Aktif" di tabel faq_status
            ];
            $this->Kategori_model->insert($data);

            // Set pesan sukses
            $this->session->set_flashdata('sukses', 'Kategori baru berhasil ditambahkan.');
        } else {
            // Set pesan error
            $this->session->set_flashdata('error', 'Judul kategori tidak boleh kosong!');
        }

        redirect('kategori');
    }

    // Update: Proses mengubah data dari form Pop-up/Modal Edit
    public function ubah() {
        $id = $this->input->post('id_faq_kategori');
        $judul = $this->input->post('judul_kategori');
        $status = $this->input->post('status_kategori_fk');

        // Trapping lapis Controller: Pastikan ID, Judul dan Status ada
        if(empty($id) || empty($judul) || empty($status)) {
            $this->session->set_flashdata('error', 'Data tidak valid. ID atau Judul kosong!');
            redirect('kategori');
        }

        $data = [
            'judul_kategori' => $judul,
            'status_kategori_fk' => $status
        ];


        // Eksekusi Model dan cek nilai boolean yang dikembalikan
        if($this->Kategori_model->update($id, $data)) {
            $this->session->set_flashdata('suskes', 'Data kategori berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan. Data gagal diperbarui.');
        }

        redirect('kategori');
    }

    // Delete: Proses menghapus data dengan metode soft delete
    public function hapus($id = null) {
        // Trapping lapis controller: Cegah eksekusi jika URL diakses tanpa ID
        if(empty($id)){
            // Menghentikan proses dan menampilkan halaman 404 bawaan CodeIgniter
            show_404();
        }

        // Anggap ID 2 adalah status "Dihapus" atau "Arsip' di tabel faq_status
        $data = ['status_kategori_fk' => 2];

        // Kita tidak memanggil fungsi delete(), melainkan update()
        if($this->Kategori_model->update($id, $data)) {
            $this->session->set_flashdata('sukses', 'Kategori berhasil diarsipkan.');
        }

        redirect('kategori');
    }

}
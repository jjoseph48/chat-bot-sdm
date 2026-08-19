<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_detail extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat dua model sekaligus
        $this->load->model('Faq_detail_model');
        $this->load->model('Kategori_model');
    }

    // READ: Halaman utama
    public function index() {
        // Mengambil data utama FAQ
        $data['faq'] = $this->Faq_detail_model->get_all();

        // Mengambil data Kategori untuk ditampilkan di Dropdown saat Tambah/Edit
        $data['kategori'] = $this->Kategori_model->get_all();

        // Logika Empty State
        if(empty($data['faq'])) {
            $this->load->view('faq_detail/empty_faq', $data);
        } else {
            $this->load->view('faq_detail/list_faq', $data);
        }
    }

    // CREATE: Menyimpan Data
    public function simpan() {
        $pertanyaan = $this->input->post('pertanyaan');
        $jawaban    = $this->input->post('jawaban');
        // PERBAIKAN 1: Sesuaikan dengan 'name' di form dropdown HTML
        $kategori   = $this->input->post('id_faq_kategori_fk'); 

        if(!empty($pertanyaan) && !empty($jawaban) && !empty($kategori)) {
            $data = [
                'pertanyaan'           => $pertanyaan,
                'jawaban'              => $jawaban,
                // PERBAIKAN 2: Sesuaikan nama kolom dengan struktur database
                'id_faq_kategori_fk'   => $kategori, 
                'faq_detail_status_fk' => 1,
                // PERBAIKAN 3: Beri nilai default agar tidak ditolak database
                'komentar'             => '-' 
            ];

            // Menyimpan FAQ dan menangkap ID-nya
            $id_faq_baru = $this->Faq_detail_model->insert($data);

            $this->session->set_flashdata('sukses', 'FAQ baru berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal! Semua kolom (Pertanyaan, Jawaban, Kategori) wajib diisi.');
        }

        redirect('faq_detail');
    }

    // UPDATE: Mengubah data
    public function ubah() {
        $id         = $this->input->post('id_faq_detail');
        $pertanyaan = $this->input->post('pertanyaan');
        $jawaban    = $this->input->post('jawaban');
        // PERBAIKAN 4: Ubah nama POST mengikuti standar form
        $kategori   = $this->input->post('id_faq_kategori_fk'); 

        if(empty($id) || empty($pertanyaan) || empty($jawaban) || empty($kategori)) {
            $this->session->set_flashdata('error', 'Data tidak valid. Pastikan semua kolom terisi!');
            redirect('faq_detail');
        }

        $data = [
            'pertanyaan'         => $pertanyaan,
            'jawaban'            => $jawaban,
            // PERBAIKAN 5: Sesuaikan nama kolom untuk proses Edit
            'id_faq_kategori_fk' => $kategori 
        ];

        if($this->Faq_detail_model->update($id, $data)) {
            $this->session->set_flashdata('sukses', 'Data FAQ berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui data.');
        }

        redirect('faq_detail');
    }

    // DELETE: Hapus data (soft delete)
    public function hapus($id = null) {
        if(empty($id)) { show_404(); }

        // PERBAIKAN 6: Sesuaikan nama kolom status untuk proses Hapus
        $data = ['faq_detail_status_fk' => 2]; 

        if($this->Faq_detail_model->update($id, $data)) {
            $this->session->set_flashdata('sukses', 'FAQ berhasil dihapus dari daftar.');
        }

        redirect('faq_detail');
    }

}
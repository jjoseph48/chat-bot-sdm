<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_detail extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat dua model sekaligus
        $this->load->model('Faq_detail_model');
        $this->load->model('Kategori_model');
        $this->load->model('Tag_model');
    }

    // READ: Halaman utama
    public function index() {
        // Mengambil data utama FAQ
        $data['faq'] = $this->Faq_detail_model->get_all();

        // Mengambil data Kategori untuk ditampilkan di Dropdown saat Tambah/Edit
        $data['kategori'] = $this->Kategori_model->get_all();

        $data['tag_list'] = $this->Tag_model->get_all();

        // Looping untuk menyisipkan data Tag ke masing-masing baris FAQ
        foreach ($data['faq'] as $key => $value) {
            $data['faq'][$key]['tags'] = $this->Faq_detail_model->get_tags_by_faq($value['id_faq_detail']);
        }

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
        $kategori   = $this->input->post('id_faq_kategori_fk'); 
        $tags = $this->input->post('faq_tag_id'); // Menangkap array dari Checkbox

        if(!empty($pertanyaan) && !empty($jawaban) && !empty($kategori)) {
            $data_faq = [
                'pertanyaan'           => $pertanyaan,
                'jawaban'              => $jawaban,
                'id_faq_kategori_fk'   => $kategori, 
                'faq_detail_status_fk' => 1 // status aktif 
            ];

            // Menyimpan FAQ dan menangkap ID-nya
            $id_faq_baru = $this->Faq_detail_model->insert($data_faq);

            // Jika ada Tag yg dicentang, simpan ke tabel jembatan
            if(!empty($tags)) {
                $data_tags = [];
                foreach($tags as $id_tag) {
                    $data_tags[] = [
                        'faq_detail_id' => $id_faq_baru,
                        'faq_tag_id' => $id_tag
                    ];
                }
                $this->Faq_detail_model->insert_tags($data_tags);
            }

            $this->session->set_flashdata('sukses', 'FAQ baru berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal! Semua kolom (Pertanyaan, Jawaban, Kategori) wajib diisi.');
        }

        redirect('faq_detail');
    }

    // UPDATE: Mengubah data
    public function ubah() {
        $id = $this->input->post('id_faq_detail');
        $pertanyaan = $this->input->post('pertanyaan');
        $jawaban = $this->input->post('jawaban');
        $kategori = $this->input->post('id_faq_kategori_fk');
        $tags = $this->input->post('faq_tag_id');

        if(empty($id) || empty($pertanyaan) || empty($jawaban) || empty($kategori)) {
            $this->session->set_flashdata('error', 'Data tidak valid. Pastikan semua kolom terisi!');
            redirect('faq_detail');
        }

        $data_faq = [
            'pertanyaan' => $pertanyaan,
            'jawaban' => $jawaban,
            'id_faq_kategori_fk' => $kategori
        ];

        // update data utama FAQ
        if ($this->Faq_detail_model->update($id, $data_faq)) {

            // Hapus semua riwayat Tag lama di tabel jembatan
            $this->Faq_detail_model->delete_tags($id);

            if(!empty(tags)) {
                $data_tags = [];
                foreach($tags as $id_tag) {
                    $data_tags[] = [
                        'faq_detail_id' => $id,
                        'faq_tag_id' => $id_tag
                    ];
                }
                $this->Faq_detail_model->insert_tags($data_tags);
            }

            $this->session->set_flashdata('sukses', 'Data FAQ dan Tag berhasil diperbarui.');
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
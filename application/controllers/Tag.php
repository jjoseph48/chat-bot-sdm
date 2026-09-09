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
        $this->load->library('pagination');

        // Konfigurasi dasar
        $config['base_url'] = base_url('index.php/tag/index');
        $config['total_rows'] = $this->Tag_model->get_hitung_tag_aktif();
        $config['per_page'] = 10; // Jumlah data per halaman

        $start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

        // Sytling pagination untuk bootstrap 6
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center mb-0">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = 'Pertama';
        $config['first_tag_open'] = '<li class="page-item">'; 
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Terakhir';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        // Menambahkan class "page-link" ke semua link pagination
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $data['tag'] = $this->Tag_model->get_all($config['per_page'], $start);
        $data['pagination'] = $this->pagination->create_links();

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
        // $status = $this->input->post('status_faq_tag');

        // Trapping lapis Controller: Pastikan ID, Judul dan status ada
        if(empty($id) || empty($judul)) {
            $this->session->set_flashdata('error', 'Data tidak valid. ID atau Judul Kosong!');
            redirect('tag');
        }

        $data = ['judul_tag' => $judul];

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

        $data = ['status_tag_fk' => 2];

        if($this->Tag_model->update($id, $data)) {
            $this->session->set_flashdata('sukses', 'Tag berhasil diarsipkan.');
        }

        redirect('tag');
    }

}
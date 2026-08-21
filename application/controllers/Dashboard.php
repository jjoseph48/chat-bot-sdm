<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Faq_detail_model');
        $this->load->model('Kategori_model');
        $this->load->model('Tag_model');
    }

    public function index() {
        // menghitung jumlah data dari masing-masing tabel
        // kita menggunakan fungsi get_all() yg sudah ada lalu menghitungnya dengan count()
        $data['total_faq'] = count($this->Faq_detail_model->get_all());
        $data['total_kategori'] = count($this->Kategori_model->get_all());
        $data['total_tag'] = count($this->Tag_model->get_all());

        // menampilkan halaman dashboard
        $this->load->view('dashboard', $data);

    }
}
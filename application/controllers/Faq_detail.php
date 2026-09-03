<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

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
        $tags       = $this->input->post('faq_tag_id'); // Menangkap array dari Checkbox
        
        // Menangkap opsi alternatif (default 0 jika kosong)
        $is_fallback = $this->input->post('is_fallback_option') ? $this->input->post('is_fallback_option') : 0; 

        if(!empty($pertanyaan) && !empty($jawaban) && !empty($kategori)) {
            $data_faq = [
                'pertanyaan'           => $pertanyaan,
                'jawaban'              => $jawaban,
                'id_faq_kategori_fk'   => $kategori, 
                'faq_detail_status_fk' => 1, // status aktif 
                'is_fallback_option'   => $is_fallback // Menyimpan pilihan Opsi Alternatif
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
        
        // Menangkap opsi alternatif
        $is_fallback = $this->input->post('is_fallback_option') ? $this->input->post('is_fallback_option') : 0;

        if(empty($id) || empty($pertanyaan) || empty($jawaban) || empty($kategori)) {
            $this->session->set_flashdata('error', 'Data tidak valid. Pastikan semua kolom terisi!');
            redirect('faq_detail');
        }

        $data_faq = [
            'pertanyaan'         => $pertanyaan,
            'jawaban'            => $jawaban,
            'id_faq_kategori_fk' => $kategori,
            'is_fallback_option' => $is_fallback // Memperbarui pilihan Opsi Alternatif
        ];

        // update data utama FAQ
        if ($this->Faq_detail_model->update($id, $data_faq)) {

            // Hapus semua riwayat Tag lama di tabel jembatan
            $this->Faq_detail_model->delete_tags($id);

            // [PERBAIKAN BUG]: Sebelumnya tertulis !empty(tags), sudah diperbaiki menjadi !empty($tags)
            if(!empty($tags)) {
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

        $data = ['faq_detail_status_fk' => 2]; 

        if($this->Faq_detail_model->update($id, $data)) {
            $this->session->set_flashdata('sukses', 'FAQ berhasil dihapus dari daftar.');
        }

        redirect('faq_detail');
    }

    public function unduh_template() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Pertanyaan');
        $sheet->setCellValue('B1', 'Jawaban');
        $sheet->setCellValue('C1', 'Kategori');
        $sheet->setCellValue('D1', 'Tag (Pisahkan dengan koma)');

        // Contoh Data
        $sheet->setCellValue('A2', 'Apa itu manajemen talenta?');
        $sheet->setCellValue('B2', 'Manajemen talenta adalah sistem manajemen karir PNS');
        $sheet->setCellValue('C2', 'Informasi umum dan akses aplikasi');
        $sheet->setCellValue('D2', 'Definisi manajemen talenta');

        // Styling
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $writer = new WriterXlsx($spreadsheet);
        $filename = 'Template_Import_FAQ.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    // Fungsi: Memproses import excel
    public function import_excel() {
        $file_mimes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        
        if(isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {
            $reader = new Xlsx(); 
            $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            
            // Memulai pengaman transaksi database
            $this->db->trans_start(); 
            
            for($i = 1; $i < count($sheetData); $i++) { 
                $pertanyaan = $sheetData[$i][0];
                $jawaban = $sheetData[$i][1];
                $kategori_teks = $sheetData[$i][2];
                $tag_teks = $sheetData[$i][3];
                
                // Proses hanya jika pertanyaan dan jawaban tidak kosong
                if(!empty($pertanyaan) && !empty($jawaban)) {
                    
                    // 1. LOGIKA KATEGORI OTOMATIS
                    $id_kategori = null;
                    if(!empty($kategori_teks)) {
                        $kategori_teks = trim($kategori_teks);
                        $cek_kategori = $this->db->get_where('faq_kategori', ['judul_kategori' => $kategori_teks])->row();
                        
                        if($cek_kategori) {
                            $id_kategori = $cek_kategori->id_faq_kategori;
                        } else {
                            $this->db->insert('faq_kategori', ['judul_kategori' => $kategori_teks, 'status_kategori_fk' => 1]);
                            $id_kategori = $this->db->insert_id();
                        }
                    }

                    // 2. SIMPAN FAQ UTAMA
                    $data_faq = [
                        'pertanyaan' => $pertanyaan,
                        'jawaban' => $jawaban,
                        'id_faq_kategori_fk' => $id_kategori,
                        'faq_detail_status_fk' => 1
                        // is_fallback_option akan otomatis menjadi 0 berdasarkan struktur default MariaDB
                    ];
                    $this->db->insert('faq_detail', $data_faq);
                    $id_faq_baru = $this->db->insert_id(); 

                    // 3. LOGIKA TAG OTOMATIS (DIPECAH DENGAN KOMA)
                    if(!empty($tag_teks)) {
                        $tags_array = explode(',', $tag_teks);
                        $data_jembatan_tag = [];
                        
                        foreach($tags_array as $t) {
                            $t = trim($t); // Hapus spasi di awal/akhir
                            if(empty($t)) continue; 
                            
                            $cek_tag = $this->db->get_where('faq_tag', ['judul_tag' => $t])->row();
                            if($cek_tag) {
                                $id_tag = $cek_tag->id_faq_tag;
                            } else {
                                $this->db->insert('faq_tag', ['judul_tag' => $t, 'status_tag_fk' => 1]);
                                $id_tag = $this->db->insert_id();
                            }
                            
                            $data_jembatan_tag[] = [
                                'faq_detail_id' => $id_faq_baru,
                                'faq_tag_id' => $id_tag
                            ];
                        }
                        
                        if(!empty($data_jembatan_tag)) {
                            $this->db->insert_batch('faq_detail_has_tag', $data_jembatan_tag);
                        }
                    }
                }
            }
            
            $this->db->trans_complete(); 
            
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Gagal! Terjadi kesalahan pada database saat mengimpor.');
            } else {
                $this->session->set_flashdata('sukses', 'Data FAQ beserta Kategori dan Tag berhasil diimpor!');
            }
            
        } else {
            $this->session->set_flashdata('error', 'Format file tidak valid. Gunakan file .xlsx');
        }
        
        redirect('faq_detail');
    }

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nlp_engine {

    // --- FUNGSI INI YANG SEBELUMNYA HILANG ATAU SALAH TEMPAT ---
    // Memecah teks menjadi array kata (Tokenization & Case Folding sederhana)
    private function tokenize($teks) {
        $teks = strtolower($teks);
        $teks = preg_replace('/[^a-z0-9 ]/', '', $teks); // Hapus tanda baca
        return explode(' ', $teks);
    }

    // Menghitung Cosine Similarity antara input pengguna dan daftar FAQ
    public function hitung_kemiripan($input_user, $data_faq) {
        $corpus = [];
        $dokumen = [];
        
        // 1. Gabungkan semua pertanyaan FAQ ke dalam Corpus
        foreach ($data_faq as $faq) {
            // Baris ini yang tadi memicu error karena mencari fungsi tokenize
            $dokumen[] = $this->tokenize($faq['pertanyaan']);
        }
        $dokumen_input = $this->tokenize($input_user);
        array_push($dokumen, $dokumen_input); // Masukkan input user di akhir array

        // 2. Bangun Kamus Kata (Vocabulary)
        $vocabulary = [];
        foreach ($dokumen as $doc) {
            foreach ($doc as $kata) {
                if (!empty($kata) && !in_array($kata, $vocabulary)) {
                    $vocabulary[] = $kata;
                }
            }
        }

        // 3. Hitung DF (Document Frequency) & IDF (Inverse Document Frequency)
        $jumlah_dokumen = count($dokumen);
        $idf = [];
        foreach ($vocabulary as $kata) {
            $df = 0;
            foreach ($dokumen as $doc) {
                if (in_array($kata, $doc)) {
                    $df++;
                }
            }
            // Rumus IDF: Log(N / DF)
            $idf[$kata] = log($jumlah_dokumen / $df);
        }

        // 4. Hitung bobot TF-IDF untuk setiap dokumen
        $tfidf_matrix = [];
        foreach ($dokumen as $i => $doc) {
            foreach ($vocabulary as $kata) {
                $tf = 0;
                $jumlah_kata = count($doc);
                foreach ($doc as $k) {
                    if ($k == $kata) $tf++;
                }
                $tf_weight = ($jumlah_kata > 0) ? ($tf / $jumlah_kata) : 0;
                $tfidf_matrix[$i][$kata] = $tf_weight * $idf[$kata];
            }
        }

        // 5. Hitung Cosine Similarity antara Input User (index terakhir) dan setiap FAQ
        $index_input = $jumlah_dokumen - 1;
        $vektor_input = $tfidf_matrix[$index_input];
        
        $hasil_skor = [];
        for ($i = 0; $i < $jumlah_dokumen - 1; $i++) {
            $vektor_faq = $tfidf_matrix[$i];
            
            $dot_product = 0;
            $magnitude_input = 0;
            $magnitude_faq = 0;
            
            foreach ($vocabulary as $kata) {
                $dot_product += ($vektor_input[$kata] * $vektor_faq[$kata]);
                $magnitude_input += pow($vektor_input[$kata], 2);
                $magnitude_faq += pow($vektor_faq[$kata], 2);
            }
            
            $magnitude = sqrt($magnitude_input) * sqrt($magnitude_faq);
            $skor_cosine = ($magnitude > 0) ? ($dot_product / $magnitude) : 0;
            
            $hasil_skor[] = [
                'id_faq' => $data_faq[$i]['id_faq_detail'],
                'pertanyaan' => $data_faq[$i]['pertanyaan'],
                'jawaban' => $data_faq[$i]['jawaban'],
                'skor' => $skor_cosine
            ];
        }

        // Urutkan dari skor tertinggi ke terendah
        usort($hasil_skor, function($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return $hasil_skor;
    }
}
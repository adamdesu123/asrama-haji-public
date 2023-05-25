<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostsModel;

class Article extends BaseController
{

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_posts = new PostsModel();
        helper('global_fungsi_helper');
        $this->halaman_controller = "article"; //konfigurasi internal
        $this->halaman_label = "Artikel";
    }

    public function index()
    {
        $data = [];
        if ($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('post_id')) {
            $dataPost = $this->m_posts->getPost($this->request->getVar('post_id'));
            //** Memastikan Apakah Data Ini Ada Atau Tidak */
            if ($dataPost['post_id']) {
                @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                $aksi = $this->m_posts->deletePost($this->request->getVar('post_id'));
                if ($aksi == true) {
                    session()->setFlashdata('success', "Data Berhasil Di Hapus");
                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Di Hapus']);
                }
            }
            return redirect()->to("admin/" . $this->halaman_controller);
        }
        $data['templateJudul'] = "Halaman " . $this->halaman_label;

        $post_type = $this->halaman_controller;
        $jumlahBaris = 10;
        $katakunci = $this->request->getVar('katakunci');
        $group_dataset = "dt";

        $hasil = $this->m_posts->listPost($post_type, $jumlahBaris, $katakunci, $group_dataset);

        $data['record'] = $hasil['record'];
        $data['pager'] = $hasil['pager'];
        $data['katakunci'] = $katakunci;

        $currentPage = $this->request->getVar('page_dt');
        $data['nomor'] = nomor($currentPage, $jumlahBaris);

        echo view('admin/v_template_header', $data); //header
        echo view('admin/v_article', $data); //isi
        echo view('admin/v_template_footer', $data); //footer
    }

    public function tambah()
    {
        $data = [];
        if ($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();
            $aturan = [
                'post_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Tambahkan Judul!',
                    ],
                ],
                'post_content' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Tambahkan Konten!',
                    ],
                ],
                'post_thumbnail' => [
                    'rules' => 'is_image[post_thumbnail]',
                    'errors' => [
                        'is_image' => 'File Yang Diterima Hanya Gambar!',
                    ],
                ],
            ];
            $file = $this->request->getFile('post_thumbnail');
            if (!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $post_thumbnail = '';
                if ($file->getName()) {
                    $post_thumbnail = $file->getRandomName();
                }
                $record = [
                    'username' => session()->get('akun_username'),
                    'post_title' => $this->request->getVar('post_title'),
                    'post_status' => $this->request->getVar('post_status'),
                    'post_thumbnail' => $post_thumbnail,
                    'post_description' => $this->request->getVar('post_description'),
                    'post_content' => $this->request->getVar('post_content'),
                ];
                $post_type = $this->halaman_controller;
                $aksi = $this->m_posts->insertPost($record, $post_type);
                if ($aksi != false) {
                    $page_id = $aksi;
                    if ($file->getName()) {
                        $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        $file->move($lokasi_direktori, $post_thumbnail);
                    }
                    session()->setFlashdata('success', 'Data Berhasil Ditambahkan!');
                    return redirect()->to("admin/$this->halaman_controller/edit/" . $page_id);

                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Ditambahkan!']);
                    return redirect()->to("admin/$this->halaman_controller/tambah");
                }
            }
        }
        $data['templateJudul'] = "Tambah " . $this->halaman_label;
        echo view('admin/v_template_header', $data);
        echo view('admin/v_article_tambah', $data);
        echo view('admin/v_template_footer', $data);
    }

    public function edit($post_id)
    {
        $data = [];
        $dataPost = $this->m_posts->getPost($post_id);
        if (empty($dataPost)) {
            return redirect()->to('admin/' . $this->halaman_controller);
        }
        $data = $dataPost;

        if ($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();
            $aturan = [
                'post_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Tambahkan Judul!',
                    ],
                ],
                'post_content' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Tambahkan Konten!',
                    ],
                ],
                'post_thumbnail' => [
                    'rules' => 'is_image[post_thumbnail]',
                    'errors' => [
                        'is_image' => 'File Yang Diterima Hanya Gambar!',
                    ],
                ],
            ];
            $file = $this->request->getFile('post_thumbnail');
            if (!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $post_thumbnail = $dataPost['post_thumbnail'];
                if ($file->getName()) {
                    $post_thumbnail = $file->getRandomName();
                }
                $record = [
                    'username' => session()->get('akun_username'),
                    'post_title' => $this->request->getVar('post_title'),
                    'post_status' => $this->request->getVar('post_status'),
                    'post_thumbnail' => $post_thumbnail,
                    'post_description' => $this->request->getVar('post_description'),
                    'post_content' => $this->request->getVar('post_content'),
                    'post_id' => $post_id, //update data memerlukan post id sebagai primary key
                ];
                $post_type = $this->halaman_controller;
                $aksi = $this->m_posts->insertPost($record, $post_type);
                if ($aksi != false) {
                    $page_id = $aksi;
                    if ($file->getName()) {
                        if ($dataPost['post_thumbnail']) {
                            @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                        }
                        $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        $file->move($lokasi_direktori, $post_thumbnail);
                    }
                    session()->setFlashdata('success', 'Data Berhasil Di Ubah!');
                    return redirect()->to("admin/$this->halaman_controller/edit/$post_id");
                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Di Ubah!']);
                    return redirect()->to("admin/$this->halaman_controller/edit/$post_id");
                }
            }
        }

        $data['templateJudul'] = "Edit " . $this->halaman_label;
        echo view('admin/v_template_header', $data);
        echo view('admin/v_article_tambah', $data);
        echo view('admin/v_template_footer', $data);
    }
}

<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KonfigurasiModel;
use App\Models\PostsModel;

class Page extends BaseController
{

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_posts = new PostsModel();
        $this->m_konfigurasi = new KonfigurasiModel();
        helper('global_fungsi_helper');
        $this->halaman_controller = "page"; //konfigurasi internal
        $this->halaman_label = "Page";
    }

    public function index()
    {
        $data = [];
        if ($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('post_id')) {
            $dataPost = $this->m_posts->getPost($this->request->getVar('post_id'));
            //** Memastikan Apakah Data Ini Ada Atau Tidak */
            // dd("ini hapus");
            if ($dataPost['post_id']) {
                @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                $aksi = $this->m_posts->deletePost($this->request->getVar('post_id'));
                $this->m_konfigurasi->deleteKonfigurasiPost($this->request->getVar('post_id'));
                // // --------------------- //
                // if ($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id')) {
                //     $dataKonfigurasi = $this->m_konfigurasi->getData($this->request->getVar('id'));
                //     //** Memastikan Apakah Data Ini Ada Atau Tidak */
                //     // dd("ini hapus");
                //     if ($dataKonfigurasi['id']) {
                //         // @unlink(LOKASI_UPLOAD . "/" . $dataKonfigurasi['post_thumbnail']);
                //         $aksi = 'a';
                //     }
                // }

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
        echo view('admin/v_page', $data); //isi
        echo view('admin/v_template_footer', $data); //footer
    }

    public function deleteKonfigurasiPost($id)
    {
        $data = [];
        if ($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id')) {
            $dataKonfigurasi = $this->m_posts->getData($this->request->getVar('id'));
            //** Memastikan Apakah Data Ini Ada Atau Tidak */
            // dd("ini hapus");
            if ($dataKonfigurasi['id']) {
                // @unlink(LOKASI_UPLOAD . "/" . $dataKonfigurasi['post_thumbnail']);
                $aksi = $this->m_posts->deleteKonfigurasiPost($this->request->getVar('id'));
                if ($aksi == true) {
                    session()->setFlashdata('success', "Data Berhasil Di Hapus");
                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Di Hapus']);
                }
            }
            return redirect()->to("admin/" . $this->halaman_controller);
        }
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
                $aksi = $this->m_posts->insertPost($record, $post_type); // insert ke table posts

                if ($file->getName()) {
                    if ($record['post_thumbnail']) {
                        @unlink(LOKASI_UPLOAD . "/" . $record['post_thumbnail']);
                    }
                    $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                    $file->move($lokasi_direktori, $post_thumbnail);
                }

                // dd($aksi);
                if ($aksi != false) {
                    $page_id = $aksi;
                    /** masukkan konfigurasi */
                    // kalau dimaksudkan sebagai halaman depan => tabel konfigurasi kombinasi name = set_halaman_depan value = $page_id
                    $set_halaman_depan = $this->request->getVar('set_halaman_depan');
                    $set_halaman_kontak = $this->request->getVar('set_halaman_kontak');
                    $page_id_depan = '';
                    $page_id_kontak = '';

                    /** set halaman depan */
//                    $konfigurasi_name = $set_halaman_depan;
                    // Query select dari database
                    // dd($set_halaman_kontak);

                    // jika depan & kontak di cellis
                    if ($set_halaman_depan == '1' && $set_halaman_kontak == '1') {
                        $konfigurasi_name = "set_halaman_depan";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database,  *Q1
                        $page_id_depan = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_depan, $dataSet); // Query save data *Q2

                        $konfigurasi_name = "set_halaman_kontak";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_kontak = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_kontak, $dataSet);

                        // dd("test");
                        session()->setFlashdata('success', 'Data Berhasil Di Ubah!');
                        return redirect()->to("admin/$this->halaman_controller");

                    } else

                    // Jika halann depan saja yang diceklis
                    if ($set_halaman_depan == '1') {
                        $konfigurasi_name = "set_halaman_depan";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_depan = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_depan, $dataSet); // insert jika belum ada update jika sudah ada
                        // dd("depan");
                        session()->setFlashdata('success', 'Data Berhasil Di Ubah!');
                        return redirect()->to("admin/$this->halaman_controller");

                        // Jika kontak saha yang diceklis
                    } else if ($set_halaman_kontak == '1') {
                        $konfigurasi_name = "set_halaman_kontak";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_kontak = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_kontak, $dataSet); // insert jika belum ada update jika sudah ada

// ==================

                        session()->setFlashdata('success', 'Data Berhasil Ditambahkan!');
                        return redirect()->to("admin/$this->halaman_controller");

                    }

                    // if ($set_halaman_depan == '1') {
                    //     $page_id_depan = $page_id;
                    //     $konfigurasi_name = "set_halaman_depan";

                    //     $dataSet = [
                    //         'konfigurasi_value' => $page_id_depan,
                    //     ];

                    //     // ==================

                    //     if ($file->getName()) {
                    //         $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                    //         $file->move($lokasi_direktori, $post_thumbnail);
                    //     }
                    //     konfigurasi_set($konfigurasi_name, $dataSet);

                    //     session()->setFlashdata('success', 'Data Berhasil Ditambahkan!');
                    //     return redirect()->to("admin/$this->halaman_controller");
                    // } else if ($set_halaman_kontak == '1') {
                    //     $set_halaman_kontak = $page_id;
                    //     $konfigurasi_name = "set_halaman_kontak";

                    //     $dataSet = [
                    //         'konfigurasi_value' => $set_halaman_kontak,
                    //     ];
                    //     konfigurasi_set($konfigurasi_name, $dataSet);
                    //     session()->setFlashdata('success', 'Data Berhasil Ditambahkan!');
                    //     return redirect()->to("admin/$this->halaman_controller");

                    // }

                    // Kedouble

                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Ditambahkan!']);
                    return redirect()->to("admin/$this->halaman_controller/tambah");
                }

                // Cek jika hasil query null
                // if (!$dataGet) {
                //     // maka masukkan datanya sebagai databaru
                //     konfigurasi_set($konfigurasi_name, $dataSet);
                // }

                // klao ada langsung masukkan (update)
                // konfigurasi_set($konfigurasi_name, $dataSet);
                // vardump($dataGet);

                /** set halaman kontak */
                // $konfigurasi_name = $set_halaman_kontak;
                // $dataGet = konfigurasi_get($konfigurasi_name);
                // if ($set_halaman_kontak == '1') {
                //     $page_id_kontak = $page_id;
                // }

                // // sama aja
                // if (!$dataGet) {
                //     konfigurasi_set($konfigurasi_name, $dataSet);
                // } else if ($dataGet['konfigurasi_value'] == $page_id && $set_halaman_kontak != '1') {
                //     $page_id_kontak = '';
                // }
                // $dataSet = [
                //     'konfigurasi_value' => $page_id_kontak,
                // ];
                // konfigurasi_set($konfigurasi_name, $dataSet);
                // /** selesai konfigurasi */

            }
        }
        $data['templateJudul'] = "Tambah " . $this->halaman_label;
        echo view('admin/v_template_header', $data);
        echo view('admin/v_page_tambah', $data);
        echo view('admin/v_template_footer', $data);
    }

// ============================================================ //
    public function edit($post_id)
    {
        $data = [];
        $dataPost = $this->m_posts->getPost($post_id);
        if (empty($dataPost)) {
            return redirect()->to('admin/' . $this->halaman_controller);
        }

        $data = $dataPost;

        $getValue = konfigurasi_get_value($post_id);

// dd($getValue['id']);

        if ($getValue) {
            if ($getValue->getNumRows() > 1) {
                $data['set_halaman_kontak'] = 1;
                $data['set_halaman_depan'] = 1;
            }
            $value = $getValue->getRowArray();
            // dd($getValue->getNumRows());
            if ($value) {
                if ($value['konfigurasi_name'] == "set_halaman_kontak") {
                    $data['set_halaman_kontak'] = 1;

                } else if ($value['konfigurasi_name'] == "set_halaman_depan") {
                    $data['set_halaman_depan'] = 1;

                }

            }
        } else {
            $data['set_halaman_kontak'] = 1;
            $data['set_halaman_depan'] = 1;
        }

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
                $aksi = $this->m_posts->insertPost($record, $post_type); // query update
                if ($file->getName()) {
                    if ($dataPost['post_thumbnail']) {
                        @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                    }
                    $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                    $file->move($lokasi_direktori, $post_thumbnail);
                }

                if ($aksi != false) { // jika update berhasil
                    $page_id = $aksi;

                    /** masukkan konfigurasi */
                    // kalau dimaksudkan sebagai halaman depan => tabel konfigurasi kombinasi name = set_halaman_depan value = $page_id
                    $set_halaman_depan = $this->request->getVar('set_halaman_depan');
                    $set_halaman_kontak = $this->request->getVar('set_halaman_kontak');
                    $page_id_depan = '';
                    $page_id_kontak = '';

                    /** set halaman depan */
                    if ($set_halaman_depan == '1' && $set_halaman_kontak == '1') {
                        $konfigurasi_name = "set_halaman_depan";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_depan = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_depan, $dataSet);

                        $konfigurasi_name = "set_halaman_kontak";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_kontak = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_kontak, $dataSet);
                        // if ($file->getName()) {
                        //     if ($dataPost['post_thumbnail']) {
                        //         @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                        //     }
                        //     $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        //     $file->move($lokasi_direktori, $post_thumbnail);
                        // }
                        // dd("test");
                        session()->setFlashdata('success', 'Data Berhasil Di Ubah!');
                        return redirect()->to("admin/$this->halaman_controller/edit/$post_id");

                    } else if ($set_halaman_depan == '1') {
                        $konfigurasi_name = "set_halaman_depan";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_depan = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id_depan,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_depan, $dataSet);
                        if ($file->getName()) {
                            if ($dataPost['post_thumbnail']) {
                                @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                            }
                            $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                            $file->move($lokasi_direktori, $post_thumbnail);
                        }

                        $konfigurasi_name = "set_halaman_kontak";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_kontak = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => '',
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_kontak, $dataSet);
                        // if ($file->getName()) {
                        //     if ($dataPost['post_thumbnail']) {
                        //         @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                        //     }
                        //     $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        //     $file->move($lokasi_direktori, $post_thumbnail);
                        // }
// dd("test");
                        session()->setFlashdata('success', 'Data Berhasil Di Ubah!');
                        return redirect()->to("admin/$this->halaman_controller/edit/$post_id");
                    } else if ($set_halaman_kontak == '1') {
                        $konfigurasi_name = "set_halaman_depan";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_depan = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => '',
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_depan, $dataSet);
                        // if ($file->getName()) {
                        //     if ($dataPost['post_thumbnail']) {
                        //         @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                        //     }
                        //     $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        //     $file->move($lokasi_direktori, $post_thumbnail);
                        // }

                        $konfigurasi_name = "set_halaman_kontak";
                        $dataGet = konfigurasi_get($konfigurasi_name, $page_id); // Query select dari database
                        $page_id_kontak = $page_id;
                        $dataSet = [
                            'konfigurasi_value' => $page_id_kontak,
                        ];

                        konfigurasi_set($konfigurasi_name, $page_id_kontak, $dataSet);
                        // if ($file->getName()) {
                        //     if ($dataPost['post_thumbnail']) {
                        //         @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                        //     }
                        //     $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        //     $file->move($lokasi_direktori, $post_thumbnail);
                        // }
                        // dd('disini');
// dd("test");
                        session()->setFlashdata('success', 'Data Berhasil Di Ubah!');
                        return redirect()->to("admin/$this->halaman_controller/edit/$post_id");
                    }
                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Di Ubah!']);
                    return redirect()->to("admin/$this->halaman_controller/edit/$post_id");
                }

                // Cek jika hasil query null
                // if (!$dataGet) {
                //     // maka masukkan datanya sebagai databaru
                //     konfigurasi_set($konfigurasi_name, $dataSet);
                // }

                // klao ada langsung masukkan (update)
//                    konfigurasi_set($konfigurasi_name, $dataSet);
                // vardump($dataGet);

                /** set halaman kontak */
                // $konfigurasi_name = $set_halaman_kontak;
                // $dataGet = konfigurasi_get($konfigurasi_name);

                // if ($set_halaman_kontak == '1') {
                //     $page_id_kontak = $page_id;
                // }

                // // sama aja
                // if (!$dataGet) {
                //     konfigurasi_set($konfigurasi_name, $dataSet);
                // } else if ($dataGet['konfigurasi_value'] == $page_id && $set_halaman_kontak != '1') {
                //     $page_id_kontak = '';
                // // }
                // $dataSet = [
                //     'konfigurasi_value' => $page_id_kontak,
                // ];
                // konfigurasi_set($konfigurasi_name, $dataSet);
                /** selesai konfigurasi */

                //     if ($file->getName()) {
                //         if ($dataPost['post_thumbnail']) {
                //             @unlink(LOKASI_UPLOAD . "/" . $dataPost['post_thumbnail']);
                //         }
                //         $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                //         $file->move($lokasi_direktori, $post_thumbnail);
                //     }
                //     session()->setFlashdata('success', 'Data Berhasil Di Ubah!');
                //     return redirect()->to("admin/$this->halaman_controller/edit/$post_id");
                // } else {
                //     session()->setFlashdata('warning', ['Data Tidak Berhasil Di Ubah!']);
                //     return redirect()->to("admin/$this->halaman_controller/edit/$post_id");
                // }
            }
        }

        // /** Ambil dari konfigurasi */
        // $set_halaman_depan = $this->request->getVar('set_halaman_depan');
        // $set_halaman_kontak = $this->request->getVar('set_halaman_kontak');

        // $konfigurasi_name = $set_halaman_depan;

        // $dataGet = konfigurasi_get($konfigurasi_name); // Query select dari database
        // if ($set_halaman_depan == '1') {
        //     $page_id_depan = $page_id;
        // }
        // $konfigurasi_name = $set_halaman_kontak;
        // $dataGet = konfigurasi_get($konfigurasi_name);
        // if ($set_halaman_kontak == '1') {
        //     $page_id_kontak = $page_id;
        // }
        $data['templateJudul'] = "Edit " . $this->halaman_label;

        echo view('admin/v_template_header', $data);
        echo view('admin/v_page_tambah', $data);
        echo view('admin/v_template_footer', $data);
    }
}

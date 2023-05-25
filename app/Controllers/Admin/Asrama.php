<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AsramaModel;

class Asrama extends BaseController
{

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_posts = new AsramaModel();
        helper('global_fungsi_helper');
        $this->halaman_controller = "asrama"; //konfigurasi internal
        $this->halaman_label = "Asrama";
    }

    public function index()
    {
        $data = [];
        if ($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('asrama_id')) {
            $dataAsrama = $this->m_posts->getAsrama($this->request->getVar('asrama_id'));
            //** Memastikan Apakah Data Ini Ada Atau Tidak */
            if ($dataAsrama['asrama_id']) {
                @unlink(LOKASI_UPLOAD . "/" . $dataAsrama['asrama_gambar']);
                $aksi = $this->m_posts->deleteAsrama($this->request->getVar('asrama_id'));
                if ($aksi == true) {
                    session()->setFlashdata('success', "Data Berhasil Di Hapus");
                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Di Hapus']);
                }
            }
            return redirect()->to("admin/" . $this->halaman_controller);
        }
        $data['templateJudul'] = "Halaman " . $this->halaman_label;

        $asrama_status = $this->halaman_controller;
        $jumlahBaris = 10;
        $katakunci = $this->request->getVar('katakunci');
        $group_dataset = "dt";

        $hasil = $this->m_posts->listAsrama($asrama_status, $jumlahBaris, $katakunci, $group_dataset);

        $data['record'] = $hasil['record'];
        $data['pager'] = $hasil['pager'];
        $data['katakunci'] = $katakunci;

        $currentPage = $this->request->getVar('page_dt');
        $data['nomor'] = nomor($currentPage, $jumlahBaris);

        echo view('admin/v_template_header', $data); //header
        echo view('admin/v_asrama', $data); //isi
        echo view('admin/v_template_footer', $data); //footer
    }

    public function tambah()
    {
        $data = [];
        if ($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();
            $aturan = [
                'asrama_nama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Tambahkan Nama Asrama!',
                    ],
                ],
                'asrama_fasilitas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Fasilitas Asrama!',
                    ],
                ],
                'asrama_gambar' => [
                    'rules' => 'is_image[asrama_gambar]',
                    'errors' => [
                        'is_image' => 'File Yang Diterima Hanya Gambar!',
                    ],
                ],
            ];
            $file = $this->request->getFile('asrama_gambar');
            if (!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $asrama_gambar = '';
                if ($file->getName()) {
                    $asrama_gambar = $file->getRandomName();
                }
                $record = [
                    'username' => session()->get('akun_username'),
                    'asrama_nama' => $this->request->getVar('asrama_nama'),
                    'asrama_status' => $this->request->getVar('asrama_status'),
                    'asrama_gambar' => $asrama_gambar,
                    'asrama_fasilitas' => $this->request->getVar('asrama_fasilitas'),
                ];
                $asrama_status = $this->halaman_controller;
                $aksi = $this->m_posts->insertAsrama($record, $asrama_status);
                if ($aksi != false) {
                    $page_id = $aksi;
                    if ($file->getName()) {
                        $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        $file->move($lokasi_direktori, $asrama_gambar);
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
        echo view('admin/v_asrama_tambah', $data);
        echo view('admin/v_template_footer', $data);
    }

    public function edit($asrama_id)
    {
        $data = [];
        $dataAsrama = $this->m_posts->getAsrama($asrama_id);
        if (empty($dataAsrama)) {
            return redirect()->to('admin/' . $this->halaman_controller);
        }
        $data = $dataAsrama;

        if ($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();
            $aturan = [
                'asrama_nama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Tambahkan Nama Asrama!',
                    ],
                ],
                'asrama_fasilitas' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap Fasilitas Asrama!',
                    ],
                ],
                'asrama_gambar' => [
                    'rules' => 'is_image[asrama_gambar]',
                    'errors' => [
                        'is_image' => 'File Yang Diterima Hanya Gambar!',
                    ],
                ],
            ];
            $file = $this->request->getFile('asrama_gambar');
            if (!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $asrama_gambar = '';
                if ($file->getName()) {
                    $asrama_gambar = $file->getRandomName();
                }
                $record = [
                    'username' => session()->get('akun_username'),
                    'asrama_nama' => $this->request->getVar('asrama_nama'),
                    'asrama_status' => $this->request->getVar('asrama_status'),
                    'asrama_gambar' => $asrama_gambar,
                    'asrama_fasilitas' => $this->request->getVar('asrama_fasilitas'),
                ];
                $asrama_status = $this->halaman_controller;
                $aksi = $this->m_posts->insertAsrama($record, $asrama_status);
                if ($aksi != false) {
                    $page_id = $aksi;
                    if ($file->getName()) {
                        $lokasi_direktori = LOKASI_UPLOAD; //diambil melalui file constant yang ada di config
                        $file->move($lokasi_direktori, $asrama_gambar);
                    }
                    session()->setFlashdata('success', 'Data Berhasil Ditambahkan!');
                    return redirect()->to("admin/$this->halaman_controller/edit/" . $page_id);

                } else {
                    session()->setFlashdata('warning', ['Data Tidak Berhasil Ditambahkan!']);
                    return redirect()->to("admin/$this->halaman_controller/tambah");
                }
            }
        }

        $data['templateJudul'] = "Edit " . $this->halaman_label;
        echo view('admin/v_template_header', $data);
        echo view('admin/v_asrama_tambah', $data);
        echo view('admin/v_template_footer', $data);
    }
}

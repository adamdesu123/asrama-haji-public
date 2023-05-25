<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostsModel;

class Socials extends BaseController
{

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_posts = new PostsModel();
        helper('global_fungsi_helper');
        $this->halaman_controller = "socials"; //konfigurasi internal
        $this->halaman_label = "Sosial Media";
    }

    public function index()
    {
        $data = [];
        if ($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();

            $set_socials_twitter = $this->request->getVar('set_socials_twitter');
            $set_socials_facebook = $this->request->getVar('set_socials_facebook');
            $set_socials_instagram = $this->request->getVar('set_socials_instagram');
            $set_socials_whatsapp = $this->request->getVar('set_socials_whatsapp');

            if ($this->request->getVar('set_socials_twitter')) {
                $konfigurasi_name = "set_socials_twitter";
                $dataGet = konfigurasi_sosial_get($konfigurasi_name);
                $dataSimpan = [
                    'konfigurasi_value' => $this->request->getVar('set_socials_twitter'),
                ];
                konfigurasi_sosial_set($konfigurasi_name, $dataSimpan);
            }
            if ($this->request->getVar('set_socials_facebook')) {
                $konfigurasi_name = "set_socials_facebook";
                $dataGet = konfigurasi_sosial_get($konfigurasi_name);
                $dataSimpan = [
                    'konfigurasi_value' => $this->request->getVar('set_socials_facebook'),
                ];
                konfigurasi_sosial_set($konfigurasi_name, $dataSimpan);
            }

            if ($this->request->getVar('set_socials_instagram')) {
                $konfigurasi_name = "set_socials_instagram";
                $dataGet = konfigurasi_sosial_get($konfigurasi_name);
                $dataSimpan = [
                    'konfigurasi_value' => $this->request->getVar('set_socials_instagram'),
                ];
                konfigurasi_sosial_set($konfigurasi_name, $dataSimpan);
            }

            if ($this->request->getVar('set_socials_whatsapp')) {
                $konfigurasi_name = "set_socials_whatsapp";
                $dataGet = konfigurasi_sosial_get($konfigurasi_name);
                $dataSimpan = [
                    'konfigurasi_value' => $this->request->getVar('set_socials_whatsapp'),
                ];
                konfigurasi_sosial_set($konfigurasi_name, $dataSimpan);
            }

//            if ($set_socials_facebook == 'set_socials_facebook') {
            //     $konfigurasi_name = "set_socials_twiter";
            //     $dataGet = konfigurasi_get($konfigurasi_name);
            //     $dataSimpan = [
            //         'konfigurasi_value' => $this->request->getVar('set_socials_facebook'),
            //     ];
            //     konfigurasi_sosial_set($konfigurasi_name, $dataSimpan);
            // }if ($set_socials_instagram == 'set_socials_instagram') {
            //     $konfigurasi_name = "set_socials_twiter";
            //     $dataGet = konfigurasi_get($konfigurasi_name);
            //     $dataSimpan = [
            //         'konfigurasi_value' => $this->request->getVar('set_socials_instagram'),
            //     ];
            //     konfigurasi_set($konfigurasi_name, $dataSimpan);
            // }if ($set_socials_whatsapp == 'set_socials_whatsapp') {
            //     $konfigurasi_name = "set_socials_twiter";
            //     $dataGet = konfigurasi_get($konfigurasi_name);
            //     $dataSimpan = [
            //         'konfigurasi_value' => $this->request->getVar('set_socials_whatsapp'),
            //     ];
            //     konfigurasi_set($konfigurasi_name, $dataSimpan);
            // }

            // kombinasi nama dengan value
            // $konfigurasi_name = 'set_socials_twitter';
            // $dataSimpan = [
            //     'konfigurasi_value' => $this->request->getVar('set_socials_twitter'),
            // ];
            // konfigurasi_set($konfigurasi_name, $dataSimpan);

            // $konfigurasi_name = 'set_socials_facebook';
            // $dataSimpan = [
            //     'konfigurasi_value' => $this->request->getVar('set_socials_facebook'),
            // ];
            // konfigurasi_set($konfigurasi_name, $dataSimpan);

            // $konfigurasi_name = 'set_socials_instagram';
            // $dataSimpan = [
            //     'konfigurasi_value' => $this->request->getVar('set_socials_instagram'),
            // ];
            // konfigurasi_set($konfigurasi_name, $dataSimpan);

            // $konfigurasi_name = 'set_socials_whatsapp';
            // $dataSimpan = [
            //     'konfigurasi_value' => $this->request->getVar('set_socials_whatsapp'),
            // ];
            // konfigurasi_set($konfigurasi_name, $dataSimpan);

            session()->setFlashdata('success', 'Data Berhasil Disimpan');
            return redirect()->to('admin/' . $this->halaman_controller);
        }

        if (konfigurasi_sosial_get("set_socials_twitter")) {
            $data['set_socials_twitter'] = konfigurasi_sosial_get("set_socials_twitter")['konfigurasi_value'];

        }

        if (konfigurasi_sosial_get("set_socials_facebook")) {
            $data['set_socials_facebook'] = konfigurasi_sosial_get("set_socials_facebook")['konfigurasi_value'];

        }

        if (konfigurasi_sosial_get("set_socials_instagram")) {
            $data['set_socials_instagram'] = konfigurasi_sosial_get("set_socials_instagram")['konfigurasi_value'];

        }

        if (konfigurasi_sosial_get("set_socials_whatsapp")) {
            $data['set_socials_whatsapp'] = konfigurasi_sosial_get("set_socials_whatsapp")['konfigurasi_value'];

        }

        // $konfigurasi_name = 'set_socials_facebook';
        // $data['set_socials_facebook'] = konfigurasi_sosial_get($konfigurasi_name)['konfigurasi_value'];

        // $konfigurasi_name = 'set_socials_instagram';
        // $data['set_socials_instagram'] = konfigurasi_sosial_get($konfigurasi_name)['konfigurasi_value'];

        // $konfigurasi_name = 'set_socials_whatsapp';
        // $data['set_socials_whatsapp'] = konfigurasi_sosial_get($konfigurasi_name)['konfigurasi_value'];

        $data['templateJudul'] = "Halaman " . $this->halaman_label;

        echo view('admin/v_template_header', $data); //header
        echo view('admin/v_socials', $data); //isi
        echo view('admin/v_template_footer', $data); //footer
    }
}

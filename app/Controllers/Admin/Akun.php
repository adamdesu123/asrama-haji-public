<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Akun extends BaseController
{

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->m_admin = new AdminModel();
        helper('global_fungsi_helper');
        $this->halaman_controller = "akun"; //konfigurasi internal
        $this->halaman_label = "Akun";
    }

    public function index()
    {
        $data = [];
        if ($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();

            $nama_lengkap = $this->request->getVar('nama_lengkap');
            $password_lama = $this->request->getVar('password_lama');
            $password_baru = $this->request->getVar('password_baru');
            $password_baru_konfirmasi = $this->request->getVar('password_baru_konfirmasi');

            $aturan = [
                'nama_lengkap' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Lengkap Harus Di Isi',
                    ],
                ],
            ];

            if ($password_baru != '') {
                $aturan = [
                    'password_lama' => [
                        'rules' => 'required|check_old_password[password_lama]',
                        'errors' => [
                            'required' => 'Password Lama Harus Di Isi',
                            'check_old_password' => 'Password Lama Tidak Sesuai !',
                        ],
                    ],
                    'password_baru' => [
                        'rules' => 'min_length[8]|alpha_numeric',
                        'errors' => [
                            'min_length' => 'Panjang Password Minimal 8 Karakter!',
                            'alpha_numeric' => 'Hanya Angka, Huruf, dan Beberapa Simbol Saja Yang Di Perbolehkan',
                        ],
                    ],
                    'password_baru_konfirmasi' => [
                        'rules' => 'matches[password_baru]',
                        'errors' => [
                            'matches' => 'Password Baru Tidak Sesuai Dengan Konfirmasi! ',
                        ],
                    ],
                ];
            }

            if (!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $dataUpdate = [
                    'email' => session()->get('akun_email'),
                    'nama_lengkap' => $nama_lengkap,
                ];
                $this->m_admin->updateData($dataUpdate);
                $sesi = [
                    'akun_nama_lengkap' => $nama_lengkap,
                ];
                session()->set($sesi);

                if ($password_baru != '') {
                    $password_baru = password_hash($password_baru, PASSWORD_DEFAULT);
                    $dataUpdate = [
                        'email' => session()->get('akun_email'),
                        'password' => $password_baru,
                    ];
                    $this->m_admin->updateData($dataUpdate);

                    helper('cookie');
                    if (get_cookie('cookie_password')) {
                        set_cookie("cookie_username", session()->get('akun_username'), 3600 * 24 * 30);
                        set_cookie("cookie_password", $password_baru, 3600 * 24 * 30);
                    }
                }
                session()->setFlashdata('success', 'Data Akun Berhasil Di Ubah!');
            }

            return redirect()->to('admin/' . $this->halaman_controller)->withCookies();
        }

        $username = session()->get('akun_username');
        $data = $this->m_admin->getData($username);

        $data['templateJudul'] = "Halaman " . $this->halaman_label;
        echo view('admin/v_template_header', $data); //header
        echo view('admin/v_akun', $data); //isi
        echo view('admin/v_template_footer', $data); //footer
    }
}

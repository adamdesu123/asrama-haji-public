<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\AdminModel;

class Admin extends BaseController
{
    function __construct(){
        $this->m_admin = new AdminModel();
        $this->validation = \Config\Services::validation();
        helper("cookie");
        helper("global_fungsi_helper");
    }
    public function login()
    {
        $data= [];
        // session()->destroy();
        // exit();
        if(get_cookie('cookie_username')&& get_cookie('cookie_password')){
            $username = get_cookie('cookie_username');
            $password = get_cookie('cookie_password');

            $dataAkun = $this->m_admin->getData($username);
            if($password !=$dataAkun['password']){
                $err[] = "Akun yang anda masukkan tidak sesuai!";
                session()->setFlashdata('username', $username);
                session()->setFlashdata('warning', $err);

                delete_cookie('cookie_username');
                delete_cookie('cookie_password');
                return redirect()->to('admin/login');
            }
            $akun = [
                'akun_username'=>$username,
                'akun_nama_lengkap'=>$dataAkun['nama_lengkap'],
                'akun_email'=>$dataAkun['email']
            ];
            session()->set($akun);
            return redirect()->to('admin/sukses');
        }
        if($this->request->getMethod() == 'post'){
            $rules = [
                'username'=>[
                    'rules'=> 'required',
                    'errors'=> [
                        'required'=>'Username Wajib Di Isi'
                    ]
                ],
                'password'=>[
                    'rules'=> 'required',
                    'errors'=> [
                        'required'=>'Password Wajib Di Isi'
                    ]
                ]
            ];
            if(!$this->validate($rules)){
                session()->setFlashdata("warning",$this->validation->getErrors());
                return redirect()->to("admin/login");
            }

            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            $remember_me = $this->request->getVar('remember_me');

            $dataAkun = $this->m_admin->getData($username);
            if(!password_verify($password,$dataAkun['password'])){
                $err[]="Akun Yang Dimasukkan Tidak Sesuai";
                session()->setFlashdata('username', $username);
                session()->setFlashdata('warning', $err);
                return redirect()->to("admin/login");
            }

            if($remember_me == '1'){
                set_cookie("cookie_username", $username, 3600 * 24 * 30);
                set_cookie("cookie_password", $dataAkun['password'], 3600*24*30);
            }

            $akun = [
                'akun_username'=>$dataAkun['username'],
                'akun_nama_lengkap'=>$dataAkun['nama_lengkap'],
                'akun_email'=>$dataAkun['email']
            ];
            session()->set($akun);
            return redirect()->to("admin/sukses")->withCookies();
        }
        echo view("admin/v_login", $data);
    }

    function sukses(){
        // print_r(session()->get());
        // echo "COOKIE USERNAME " . get_cookie("cookie_username") . " DAN PASSWORD " . get_cookie("cookie_password");
        return redirect()->to('admin/dashboard');
    }

    function logout(){
        delete_cookie("cookie_username");
        delete_cookie("cookie_password");
        session()->destroy();
        if (session()->get('akun_username')!=''){
            session()->setFlashdata("success","Anda Berhasil Logout");
        }
        echo view("admin/v_login");
    }

    function lupapassword(){
        $err = [];
        if($this->request->getMethod() == 'post'){
            $username = $this->request->getVar('username');
            if($username == ''){
                $err [] = "Silahkan Masukkan Username atau Email yang sudah di daftarkan";
            }
            if(empty($err)){
                $data = $this->m_admin->getData($username);
                if(empty($data)){
                    $err [] = "Akun Yang Anda Masukkan Tidak Terdata";
                }
            }
            if(empty($err)){
                $email = $data['email'];
                $token = md5(date('ymdhis'));

                $link = site_url("admin/resetpassword/?email=$email&token=$token");
                $attachment = "";
                $to = $email;
                $title = "Reset Password";
                $message = "Untuk Mengubah Password Anda Silahkan Klik Link Berikut!";
                $message = "Silahkan Klik Link Ini Untuk Mengubah Password Anda! $link";

                kirim_email($attachment, $to, $title, $message);

                $dataUpdate = [
                    'email' => $email,
                    'token' => $token
                ];
                $this->m_admin->updateData($dataUpdate);
                session()->setFlashdata("success", "Email Reset Password Sudah Dikirim Ke Email Anda!");
            }
            if($err){
                session()->setFlashdata("username", $username);
                session()->setFlashdata("warning", $err);
            }
            return redirect()->to("admin/lupapassword");
        }
        echo view("admin/v_lupapassword");
    }

    function resetpassword(){
        $err = [];
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');
        if($email != '' and $token != ''){
            $dataAkun = $this->m_admin->getData($email);
            if ($dataAkun['token'] != $token){
                $err[] = "token tidak valid";
            }
        }else{
            $err[] = "Parameter Yang Di Kirimkan Tidak Valid";
        }
        if($err){
            session()->setFlashdata("warning", $err);
        }

        if($this->request->getMethod() == 'post'){
            $aturan = [
                'password' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'password harus di isi!',
                        'min_length' => 'panjang password minimal harus 5 karakter'
                    ]
                ],
                'konfirmasi_password' => [
                    'rules' => 'required|min_length[5]|matches[password]',
                    'errors' => [
                        'required' => 'konfirmasi password harus di isi!',
                        'min_length' => 'panjang konfirmasi password minimal harus 5 karakter',
                        'matches' => 'Password Yang Anda Masukkan Tidak Sama!'
                    ]
                ],
            ];

            if(!$this->validate($aturan)){
                session()->setFlashdata('warning', $this->validation->getErrors());
            }else{
                $dataUpdate=[
                    'email'=>$email,
                    'password'=>password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    'token' => null
                ];
                $this->m_admin->updateData($dataUpdate);
                session()->setFlashdata('success', 'Password Berhasil Di Ubah Silahkan Login Kembali!');
                delete_cookie('cookie_username');
                delete_cookie('cookie_password');
                return redirect()->to('admin/login')->withCookies();
            }

        }

        echo view("admin/v_resetpassword");
    }
}

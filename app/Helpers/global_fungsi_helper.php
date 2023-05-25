<?php
function kirim_email($attachment, $to, $title, $message)
{
    $email = \Config\Services::email();
    $email_pengirim = EMAIL_ALAMAT;
    $email_nama = EMAIL_NAMA;

    $config['protocol'] = "smtp";
    $config['SMTPHost'] = "smtp.googlemail.com";
    $config['SMTPUser'] = $email_pengirim;
    $config['SMTPPass'] = EMAIL_PASSWORD;
    $config['SMTPPort'] = 465;
    $config['SMTPCrypto'] = "ssl";
    $config['mailType'] = "html";

    $email->initialize($config);
    $email->setFrom($email_pengirim, $email_nama);
    $email->setTo($to);

    if ($attachment) {
        $email->attach($attachment);
    }

    $email->setSubject($title);
    $email->setMessage($message);

    if (!$email->send()) {
        $data = $email->printDebugger(['headers']);
        print_r($data);
        return false;
    } else {
        return true;
    }
}

function nomor($currentPage, $jumlahBaris)
{
    if (is_null($currentPage)) {
        $nomor = 1;
    } else {
        $nomor = 1 + ($jumlahBaris * ($currentPage - 1));
    }
    return $nomor;
}

function tanggal_id($parameter)
{
    $split1 = explode(" ", $parameter);
    $parameter1 = $split1[0];

    $bulan = [
        '1' => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];
    $hari = [
        '1' => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
    ];

    $num = date('N', strtotime($parameter1));
    $split2 = explode("-", $parameter1);
    return $hari[$num] . ", " . $split2[2] . " " . $bulan[(int) $split2[1]] . " " . $split2[0];
}

function purify($dirty_html)
{
    $config = HTMLPurifier_config::createDefault();
    $config->set('URI.AllowedSchemes', array('data' => true));
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify($dirty_html);
    return $clean_html;
}

/**
 * parameter $konfigurasi_name = bisa diisikan semisal set_halaman_depan, set_halaman_kontak
 */

// *Q1 query ke database dengan paramter (nama konfigurati, id_post)
function konfigurasi_get($konfigurasi_name, $konfigurasi_value)
{
    $model = new \App\Models\KonfigurasiModel;
    $filter = [
        'konfigurasi_name' => $konfigurasi_name,
        'konfigurasi_value  ' => $konfigurasi_value,
    ];
    $data = $model->getData($filter);
    // $filter mirip SELECT * FROM table WHERE
    return $data;
}

function konfigurasi_get_value($konfigurasi_value)
{
    $model = new \App\Models\KonfigurasiModel;
    $filter = [
        'konfigurasi_value' => $konfigurasi_value,
    ];
    $data = $model->getDataValue($filter);
    //dd($data->get()->getNumRows());
    return $data;
}

function konfigurasi_get_konfigurasi($konfigurasi_name)
{
    $model = new \App\Models\KonfigurasiModel;
    $filter = [
        'konfigurasi_name' => $konfigurasi_name,
    ];
    $data = $model->getData($filter);
    return $data;
}

// Jalanin query Inster dengan paramter (nama_konfighrasi, value, $data yang ingin dimasukkan)
function konfigurasi_set($konfigurasi_name, $konfigurasi_value, $data_baru)
{
    $model = new \App\Models\KonfigurasiModel;

    // $konfigurasi_name, $konfigurasi_value digunakan buat query select ke db lagi denagn WHERE
    $dataGet = konfigurasi_get($konfigurasi_name, $konfigurasi_value);

    //  jika dataGet tidak ada
    if (!$dataGet) {

        // Bikin data baru
        $dataUpdate = [
            // 'id' => $dataGet['id'],
            'konfigurasi_name' => $konfigurasi_name,
            'konfigurasi_value' => $data_baru['konfigurasi_value'],
        ];
    } else {
        // Jika ada update datanyha
        $dataUpdate = [
            'id' => $dataGet['id'],
            'konfigurasi_name' => $konfigurasi_name,
            'konfigurasi_value' => $data_baru['konfigurasi_value'],
        ];
    }
    $model->updateData($dataUpdate);
}

// ================= SOSIAL =-==============

function konfigurasi_sosial_get($konfigurasi_name)
{
    $model = new \App\Models\KonfigurasiModel;
    $filter = [
        'konfigurasi_name' => $konfigurasi_name,
    ];
    $data = $model->getData($filter);
    return $data;
}

function konfigurasi_sosial_set($konfigurasi_name, $data_baru)
{
    $model = new \App\Models\KonfigurasiModel;
    $dataGet = konfigurasi_sosial_get($konfigurasi_name);
    if (!$dataGet) {
        $dataUpdate = [
            // 'id' => $dataGet['id'],
            'konfigurasi_name' => $konfigurasi_name,
            'konfigurasi_value' => $data_baru['konfigurasi_value'],
        ];
    } else {
        $dataUpdate = [
            'id' => $dataGet['id'],
            'konfigurasi_name' => $konfigurasi_name,
            'konfigurasi_value' => $data_baru['konfigurasi_value'],
        ];
    }
    $model->updateData($dataUpdate);
}

function post_penulis($username)
{
    $model = new App\Models\AdminModel;
    $data = $model->getData($username);
    return $data['nama_lengkap'];
}
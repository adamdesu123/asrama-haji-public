<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController{
    function index(){
        $data = [];
        $data['templateJudul'] = "DASHBOARD";
        /** HEADER */
        echo view('admin/v_template_header', $data);

        echo view('admin/v_dashboard', $data);
        
        /** FOOTER */
        echo view('admin/v_template_footer', $data);
    }

    function tambah(){
        $data = [];
        echo view('admin/v_template_header', $data);
        echo view('admin/v_article_tambah', $data);
        echo view('admin/v_template_footer', $data);
    }
}
?>
<?php
namespace App\Models;

use CodeIgniter\Model;

class AsramaModel extends Model
{
    protected $table = "asrama";
    protected $primaryKey = "asrama_id";
    protected $allowedFields = ['username', 'asrama_nama', 'asrama_title_seo', 'asrama_status', 'asrama_gambar', 'asrama_fasilitas'];

    public function setAsramaTitleSeo($title)
    {
        $builder = $this->table($this->table);
        $url = strip_tags($title); //untuk menghilangkan tag html
        $url = preg_replace('/[^A-Za-z0-9]/', " ", $url);
        $url = trim($url);
        $url = preg_replace('/[^A-Za-z0-9]/', "-", $url);
        $url = strtolower($url);

        $builder->where('asrama_nama', $title);
        $jumlah = $builder->countAllResults();
        if ($jumlah > 0) {
            $jumlah = $jumlah + 1;
            return $url . "-" . $jumlah;
        }
        return $url;
    }

    public function insertAsrama($data, $asrama_status)
    {
        helper("global_fungsi_helper");
        $builder = $this->table($this->table);
        $data['asrama_status'] = $asrama_status;

        foreach ($data as $key => $value) {
            $data[$key] = purify($value);
        }

        if (isset($data['asrama_id'])) {
            $aksi = $builder->save($data);
            $id = $data['asrama_id'];
        } else {
            $data['asrama_title_seo'] = $this->setAsramaTitleSeo($data['asrama_nama']);
            $aksi = $builder->save($data);
            $id = $builder->getInsertId();
        }
        if ($aksi) {
            return $id;
        } else {
            return false;
        }
    }

    public function listAsrama($asrama_status, $jumlahBaris, $katakunci = null, $group_dataset = null)
    {
        $builder = $this->table($this->table);
        $arr_katakunci = explode(" ", $katakunci);
        #query = "select * from posts where post_type='article' and (post_title like '%hello%' or post_description like '%hello%');
        $builder->groupStart();
        for ($x = 0; $x < count($arr_katakunci); $x++) {
            $builder->orLike('asrama_nama', $arr_katakunci[$x]);
            $builder->orLike('asrama_fasilitas', $arr_katakunci[$x]);
        }
        $builder->groupEnd();

        $builder->where('asrama_status', $asrama_status);

        $data['record'] = $builder->paginate($jumlahBaris, $group_dataset);
        $data['pager'] = $builder->pager;

        return $data;
    }

    public function getAsrama($asrama_id)
    {
        $builder = $this->table($this->table);
        $builder->where('asrama_id', $asrama_id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function deleteAsrama($asrama_id)
    {
        $builder = $this->table($this->table);
        $builder->where('asrama_id', $asrama_id);
        if ($builder->delete()) {
            return true;
        } else {
            return false;
        }
    }

}

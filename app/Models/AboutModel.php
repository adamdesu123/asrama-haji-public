<?php
namespace App\Models;

use CodeIgniter\Model;

class AboutModel extends Model {
    protected $table = "about";
    protected $primaryKey = "id";
    protected $allowedFields = ['about_name', 'about_value'];

    /**Untuk Ambil Data */
    public function getData($parameter){
        $builder = $this->table($this->table);
        $builder->where($parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    /**Untuk Update / Simpan Data */
    public function updateData($data){
        helper("global_fungsi_helper");
        $builder = $this->table($this->table);
        foreach($data as $key => $value){
            $data[$key] = purify($value);
        }
        if($builder->save($data)){
            return true;
        }else{
            return false;
        }
    }
}

?>
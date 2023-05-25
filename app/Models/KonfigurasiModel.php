<?php

namespace App\Models;

use CodeIgniter\Model;

class KonfigurasiModel extends Model
{
    protected $table = "konfigurasi";
    protected $primaryKey = "id";
    protected $allowedFields = ['konfigurasi_name', 'konfigurasi_value'];

    /**
     * untuk ambil data
     */
    public function getData($parameter)
    {
        $builder = $this->table($this->table);
        $builder->where($parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getDataValue($parameter)
    {
        $builder = $this->table($this->table);
        $query = $builder->where($parameter);
        return $query->get();
    }

    /** untuk update / simpan data */
    public function updateData($data)
    {
        helper("global_fungsi_helper");
        $builder = $this->table($this->table);
        foreach ($data as $key => $value) {
            $data[$key] = purify($value);
        }
        if ($builder->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteKonfigurasiPost($post_id)
    {
        $builder = $this->table($this->table);
        $builder->where('konfigurasi_value', $post_id);
        if ($builder->delete()) {
            return true;
        } else {
            return false;
        }
    }
}

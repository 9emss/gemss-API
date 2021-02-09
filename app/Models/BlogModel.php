<?php

namespace App\Models;

use CodeIgniter\Model;


class BlogModel extends Model
{
    protected $table = 'blog';
    protected $primaryKey = 'post_id';
    protected $allowedFields = [
        'post_title',
        'post_description'
    ];

    public function getDataPosts($id = false)
    {
        //Code Here..
        if ($id === false) {
            # code...
            return $this->findAll();
        }

        return $this->getWhere(['post_id' => $id])->getRowArray();
    }
}

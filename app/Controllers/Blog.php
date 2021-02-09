<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Blog extends ResourceController
{
    protected $modelName = 'App\Models\BlogModel';
    protected $format = 'json';

    // Method untuk mendapatkan semua data blog dari DB
    public function index()
    {
        $posts = $this->model->findAll();
        return $this->respond($posts);
    }

    // Method untuk membuat post baru
    public function create()
    {
        //Code Here..
        helper(['form']);

        $rules = [
            'title' => 'required|min_length[6]|is_unique[blog.post_title]',
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            # code...

            // Jika rules dari validasi tidak terpenuhi maka akan mendapat respond 400/4001
            return $this->fail($this->validator->getErrors());
        } else {
            # code...
            // Jika rules terpenuhi maka data akan dikirim ke DB

            $data = [
                'post_title' => $this->request->getVar('title'),
                'post_description' => $this->request->getVar('description')
            ];

            $post_id = $this->model->insert($data);
            $data['post_id'] = $post_id;
            return $this->respondCreated($data);
        }
    }

    public function show($id =  null)
    {
        //Code Here..
        $data = $this->model->find($id);
        return $this->respond($data);
    }

    // Method untuk merubah data
    public function update($id = null)
    {
        helper(['form']);

        // Rulesnya masih belum bekerja
        $oldPosts = $this->model->getDataPosts($this->request->getVar('title'));


        if ($oldPosts == $this->request->getVar('title')) {
            # code...
            $rule_title = 'required';
        } else {
            # code...
            $rule_title = 'required|min_length[6]|is_unique[blog.post_title]';
        }

        $rules = [
            'title' => $rule_title,
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            # code...

            // Jika rules dari validasi tidak terpenuhi maka akan mendapat respond 400/4001
            return $this->fail($this->validator->getErrors());
        } else {

            // Jika rules terpenuhi maka data dengan id yang sesuai dengan parameter akan berubah
            $input = $this->request->getRawInput();
            $data = [
                'post_id'       => $id,
                'post_title'    => $input['title'],
                'post_description'    => $input['description'],
            ];

            $this->model->save($data);
            return $this->respond($data);
        }
    }

    // Method untuk menghapus data
    public function delete($id = null)
    {

        $data = $this->model->find($id);
        if ($data) {
            // Jika id data ditemukan maka data akan terhapus
            $this->model->delete($id);
            return $this->respondDeleted($data);
        } else {

            // Jika id data tidak ditemukan maka akan mendapat respond 400/4001 dan pesan item not found
            return $this->failNotFound('Item not found');
        }
    }
}

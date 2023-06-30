<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class CategoryController extends ResourceController
{
    protected $modelName = 'App\Models\CategoryModel';
    protected $format = 'json';
    public function index()
    {
        $data = [
            'message' => 'success',
            'categories' => $this->model->findAll(),

        ];
        return $this->respond($data, 200);
    }

    public function create()
    {
        $rules = $this->validate([
            'category' => [
                'rules' => 'required|max_length[30]|is_unique[category.category]',
                'errors'=>[
                    'required' => 'field tidak boleh kosong',
                    'max_length' => 'maksimal karakter 30',
                    'is_unique' => 'nama kategori sudah dipakai'
                ]
            ]
        ]);

        if(!$rules){
            return $this->failvalidationError([
                'messages' => $this->validation->getErrors()
            ]);
        }
        $this->model->insert([
            'category' => esc($this->request->getVar('category'))
        ]);
        return $this->respondCreated([
            'message' => 'category berhasil ditambahkan',
        ]);
    }


    public function update($id = null)
    {
        $rules = $this->validate([
            'category' => [
                'rules' => 'required|max_length[30]|is_unique[category.category]',
                'errors'=>[
                    'required' => 'field tidak boleh kosong',
                    'max_length' => 'maksimal karakter 30',
                    'is_unique' => 'nama kategori sudah dipakai'
                ]
            ]
        ]);

        if(!$rules){
            return $this->failvalidationError([
                'messages' => $this->validation->getErrors()
            ]);
        }
        $this->model->update($id,[
            'category' => esc($this->request->getVar('category'))
        ]);
        return $this->respondCreated([
            'message' => 'category berhasil diupdate',
        ]);
    }


    public function delete($id = null)
    {
        $findCategory = $this->model->find($id);
        if($findCategory){
            $this->model->delete($id);
            return $this->respondDeleted([
                'message' => 'User Berhasil dihapus',
            ]);
        }else{
            return $this->failNotFound("category tidak ditemukan");
        }
    }
}

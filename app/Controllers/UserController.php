<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserAccount';
    protected $format = 'json';

    private function hash_password($password){
        return password_hash($password, PASSWORD_BCRYPT);
     }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'message' => 'success',
            'data' => $this->model->findAll(),

        ];
        return $this->respond($data, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = [
            'message' => 'success',
            'user' => $this->model->find($id),
        ];
        if($data['user']==null){
            return $this->failNotFound("user tidak ditemukan");
        }
        return $this->respond($data, 200);
    }


    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = $this->validate([
            'fname' => 'required|max_length[30]',
            'lname' => 'required|max_length[30]',
            'username' => 'required|max_length[30]',
            'alamat' => 'required',
            // 'prof-pict' => 'max_size[prof-pict,2048]|is_image[prof-pict]
            // |mime_in[prof-pict,image/jpg,image/jpeg,image/png,image/svg]',
            'email' => 'required|max_length[60]',
            'password' => 'required|max_length[8]',
        ]);
        if (!$rules){
            return $this->failValidationErrors([
                'message'=> $this->validator->getErrors(),
            ]);
        }
        
        // $prof_pict = $this->request->getFile('prof-pict');
        // $namaProfPic = $prof_pict->getRandomName();
        // $prof_pict->move('profile',$namaProfPic);  
        // $profPict = base_url("profile/$namaProfPic");
        
        
       $this->model->insert([
        'first_name' => esc($this->request->getVar('fname')),
        'last_name' => esc($this->request->getVar('lname')),
        'username' => esc($this->request->getVar('username')),
        'alamat' => esc($this->request->getVar('alamat')),
        'email' => esc($this->request->getVar('email')),
        // 'first_name' => esc($this->request->getVar('fname')),
        'password' => esc($this->hash_password($this->request->getVar('password'))),
        
       ]);
       return $this->respondCreated([
        'message' => 'User Berhasil dregis'
       ]);
    }

    public function upload($id=null){
        $rules = $this->validate([
            'prof-pict' => 'max_size[prof-pict,2048]|is_image[prof-pict]|mime_in[prof-pict,image/jpg,image/jpeg,image/png,image/svg]'
        ]);
        if (!$rules){
            return $this->failValidationError([
                'message'=> $this->validator->getErrors(),
            ]);
        }
        $prof_pict = $this->request->getFile('prof-pict');
        $namaProfPic = $prof_pict->getRandomName();
        $prof_pict->move('profile',$namaProfPic);  
        $profPict = base_url("profile/$namaProfPic");
        $this->model->update($id,[
            'profile' => $profPict,
           ]);
        return $this->respondCreated([
            'message' => 'User Berhasil diupdate'
        ]);

    }

    public function AddAdmin($id=null){
        $user=$this->model->find($id);
        $this->model->update($id,[
            'is_admin' => !$user['is_admin'],
        ]);
        if(!$user['is_admin']){
            
            $message = [
                'message' => 'Admin Berhasil ditambahkan',
            ];
        }else{
            $message = [
                'message' => 'Admin telah dihapus',
            ];
        }
        return $this->respond($message, 200);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $rules = $this->validate([
            'fname' => 'required|max_length[30]',
            'lname' => 'required|max_length[30]',
            'username' => 'required|max_length[30]',
            'alamat' => 'required',
            'prof-pict' => 'max_size[prof-pict,2048]|is_image[prof-pict]
            |mime_in[prof-pict,image/jpg,image/jpeg,image/png,image/svg]',
            'email' => 'required|max_length[60]',
            'password' => 'required|max_length[8]',
        ]);
        if (!$rules){
            return $this->failValidationErrors([
                'message'=> $this->validator->getErrors(),
            ]);
        }
        $prof_pict = $this->request->getFile('prof-pict');
        if($prof_pict){
            $namaProfPic = $prof_pict->getRandomName();
            $prof_pict->move('profile',$namaProfPic);
            $profDb = $this->model->find($id);
            if($profDb['profile'] == base_url("profile/".$this->request->getPost('prof-lama'))){
                unlink('profile/'.$this->request->getPost('prof-lama'));
            }
        } else {
            $namaProfPic = $this->request->getPost('prof-lama');
        }
       $this->model->update($id,[
        'first_name' => esc($this->request->getVar('fname')),
        'last_name' => esc($this->request->getVar('lname')),
        'username' => esc($this->request->getVar('username')),
        'alamat' => esc($this->request->getVar('alamat')),
        'profile' => base_url("profile/$namaProfPic"),
        'email' => esc($this->request->getVar('email')),
        // 'first_name' => esc($this->request->getVar('fname')),
        'password' => esc($this->hash_password($this->request->getVar('password'))),
        
       ]);
       return $this->respondCreated([
        'message' => 'User Berhasil diupdate'
       ]);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $profDb = $this->model->find($id);
        if($profDb['profile'] != base_url('no-pict.png')){
            $prof = explode('/',$profDb['profile']);
            $deletedProf=end($prof);
            unlink('profile/'.$deletedProf);
        }
        $this->model->delete($id);
        return $this->respondDeleted([
            'message' => 'User Berhasil dihapus',
        ]);
    }
}

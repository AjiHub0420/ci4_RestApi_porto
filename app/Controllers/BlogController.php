<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BlogModel;
use App\Models\CategoryModel;
use App\Models\UserAccount;

class BlogController extends ResourceController
{
    protected $modelName = 'App\Models\BlogModel';
    protected $format = 'json';

    public function index()
    {
        $blogmodel = new BlogModel();
        $categorymodel = new CategoryModel();
        $usermodel = new UserAccount();
        $data = [
            'message' => 'success',
            'blogs' => $blogmodel->select('blog.id, blog.title, blog.image, blog.slug, blog.contents,  user_account.first_name, user_account.last_name, user_account.profile, category.category')
            ->join('user_account', 'user_account.id = blog.user_id')
            ->join('category', 'category.id = blog.category_id')
            ->findAll(),
        ];
        return $this->respond($data,200);
    }

    public function show($slug = null)
    {
        $data = [
            'message' => 'success',
            'blog' => $this->model->where('slug',$slug)->first(),
        ];
        if($data['blog'] == null){
            return $this->failNotFound("blog tidak ditemukan");
        }
        return $this->respond($data,200);
        
    }

    public function showmyblog()
    {
        $userId = $this->request->user->user_id;

        $data = [
            'message' => 'success',
            'blogs' => $this->model->where('user_id',$userId)->findAll(),
        ];
        if($data['blogs'] == null){
            return $this->failNotFound("anda tidak memiliki blog apapun");
        }
        return $this->respond($data,200);
        
    }

    public function create()
    {
        $rules = $this->validate([
            'title' => [
                'rules' => 'required|max_length[60]|is_unique[blog.title]',
                'errors' => [
                    'required' => 'field title wajib diisi',
                    'is_unique' => 'judul ini telah dipakai',
                    'max_length' => 'karakter tidak boleh lebih dari 60 chars',
                ]
            ],
            'contents' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'field contents  wajib diisi',
                ]
            ],
            'category_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'field category_id  wajib diisi',
                ]
            ],
        ]);

        if(!$rules){
            return $this->failvalidatonErrors([
                'message' => $this->validator->getErrors(),
            ]);
        }
        helper('text');
        $this->model->insert([
            'title' => esc($this->request->getVar('title')),
            'slug' => esc(url_title($this->request->getVar('title'),'-',true)),
            'contents' => esc($this->request->getVar('contents')),
            'user_id' => $this->request->user->user_id,
            'category_id' => esc($this->request->getVar('category_id')),
        ]);
        return $this->respondCreated([
            'message' => 'Blog berhasil dibuat',
        ]);
    }

    public function upload($id){
        $Userid = $this->request->user->user_id;
        $blogmodel = new BlogModel();
        $rules = $this->validate([
            'image-pict' => [
                'rules' => 'max_size[image-pict,2048]|is_image[image-pict]|mime_in[image-pict,image/jpg,image/jpeg,image/png,image/svg]',
                'errors' => [
                    'max_size' => 'file harus lebih kecil atau sama dengan 2MB',
                    'mime_in' => 'Hanya menerima file dengan ekstensi .jpg,.jpeg,.png,.svg',
                ]
            ]
        ]);
        if (!$rules){
            return $this->failValidationError($this->validator->getError());
        }
        $image_pict = $this->request->getFile('image-pict');
        $namaPic = $image_pict->getRandomName();
        $image_pict->move('images',$namaPic);  
        $Pict = base_url("images/$namaPic");
        
        $blogmodel->update(['id'=> $id,'user_id'=>$Userid], ['image' => $Pict]);;
        return $this->respondCreated([
            'message' => 'image Berhasil diupdate'
        ]);
    }

    public function update($id = null)
    {
        $userId=$this->request->user->user_id;
        $rules = $this->validate([
            'title' => [
                'rules' => 'required|max_length[60]',
                'errors' => [
                    'required' => 'field title wajib diisi',
                    'max_length' => 'karakter tidak boleh lebih dari 60 chars',
                ]
            ],
            'contents' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'field contents  wajib diisi',
                ]
            ],
            'category_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'field category_id  wajib diisi',
                ]
            ],
        ]);

        if(!$rules){
            return $this->failvalidationErrors([
                'message' => $this->validator->getErrors(),
            ]);
        }
        $this->model->where('id',$id)->where('user_id',$userId)->set([
            'title' => esc($this->request->getVar('title')),
            'slug' => esc(url_title($this->request->getVar('title'),'-',true)),
            'contents' => esc($this->request->getVar('contents')),
            // 'image' => base_url('images/'.$namaImage),
            'user_id' => $this->request->user->user_id,
            'category_id' => esc($this->request->getVar('category_id')),
        ])->update();
        return $this->respondCreated([
            'message' => 'blog post Berhasil diupdate'
           ]);

    }


    public function delete($id = null)
    {
        $userId=$this->request->user->user_id;
        $imgDb = $this->model->where('id',$id)->where('user_id',$userId)->first();
        if ($imgDb){
            if($imgDb['image'] != base_url('no-pict.png')){
                $img = explode('/',$imgDb['image']);
                $deletedImg=end($img);
                unlink('images/'.$deletedImg);
            }
            $this->model->where('id',$id)->where('user_id',$userId)->delete();
            return $this->respondDeleted([
                'message' => 'Blog Berhasil dihapus',
            ]);
        }else{
            return $this->failNotFound("id user tidak ditemukan");
        }
    }
}

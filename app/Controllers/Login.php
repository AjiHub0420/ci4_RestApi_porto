<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserAccount;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class Login extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $userModel = new UserAccount;
        $user = $userModel->where('username',$username)->first();
        if(!$user){
            return $this->respond(["status"=>false,"message"=>"username/password salah"],401);
        }
        if(!password_verify($password,$user["password"])){
            return $this->respond(["status"=>false,"message"=>"username/password salah"],401);
        }

        $key = getenv('JWT_SECRET_KEY');
        $iat = time();
        $exp = $iat + ((60*60)/2);
        $payload = [
            'iat'=>$iat,
            'exp'=>$exp,
            'user_id'=>$user['id'],
            'is_admin'=>$user['is_admin'],
        ];
        $token = JWT::encode($payload,$key,"HS256");
        return $this->respond(['status'=>true,'token'=>$token],200);

    }
}

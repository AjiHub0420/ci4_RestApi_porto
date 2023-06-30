<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAccount extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'user_account';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['first_name','last_name','username','profile','is_admin'
    ,'email','alamat','password'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}

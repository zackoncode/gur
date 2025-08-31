<?php

namespace App\Controller;
use App\Models\User;


class UserController extends Controller
{

    public  function isAdmin()
    {

        $this->view('dashboard', ['admin' => 'admin']);
    }
}

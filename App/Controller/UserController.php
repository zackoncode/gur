<?php

use App\Controller\Controller;
use App\Models\User;

class UserController extends Controller
{

    public  function isAdmin(User $user)
    {

        $this->view('dashboard', ['admin'=> $user]);
    }
}

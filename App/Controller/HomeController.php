<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $user = $this->model('User');
        $users = $user->me();
     
        // Passa os dados para a view
        $this->view('home', [
            'users' => $users,
            'title' => 'Página Inicial'
        ]);
    }
}

<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function index()
    {

        $this->view('home', [
            'users' => 'teste',
            'title' => 'Página Inicial'
        ]);
    }
}

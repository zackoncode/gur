<?php

namespace App\Controller;

use App\Core\TokenCsfr;
use App\Models\User;


class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public  function isAdmin()
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            die("Acesso negado!");
        }

        if ($_SESSION['user']['role'] != "admin") {
            http_response_code(403);
            die("Acesso negado");
        }

        if (!isset($_SESSION['token_csfr'])) {
            echo "nao ";
        } else {
           echo $_SESSION['token_csfr'];    
        }


        $this->view('dashboard', ['admin' => ['admin' => $_SESSION['user']]]);
    }
}

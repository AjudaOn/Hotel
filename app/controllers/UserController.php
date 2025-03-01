<?php
namespace App\Controllers;

class UserController {
    public function dashboard() {
        require_once ROOT_PATH . '/app/views/dashboard/tela_user.php';
    }
}
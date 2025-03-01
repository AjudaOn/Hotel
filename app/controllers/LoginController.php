<?php
namespace App\Controllers;
use App\Models\Model_User;
use App\Config\Database;

require_once __DIR__ . '/../models/Model_User.php';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)));
}

class LoginController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new Model_User($this->db);
    }

    public function index() {
        require_once __DIR__ . '/../views/login.php';
    }

    public function login() {
        error_log("=== INÍCIO DO PROCESSO DE LOGIN ===");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $cpf = $_POST['cpf'] ?? '';
                $senha = $_POST['senha'] ?? '';
                
                error_log("Dados recebidos - CPF: {$cpf}, Senha: {$senha}");
                
                if (!$this->user) {
                    throw new \Exception("Model_User não inicializado");
                }
                
                if (!method_exists($this->user, 'login')) {
                    error_log("Método login não existe na classe Model_User");
                    throw new \Exception("Método login não encontrado");
                }
                
                error_log("Chamando método login do Model_User");
                $result = $this->user->login($cpf, $senha);
                error_log("Resultado do login: " . var_export($result, true));
                
                if ($result) {
                    session_start();
                    $_SESSION['user_id'] = $result['cd_usuario'];
                    $_SESSION['user_name'] = $result['nm_usuario'];
                    $_SESSION['user_type'] = $result['tipo'];
                    
                    error_log("Sessão criada com sucesso");
                    
                    if ($result['tipo'] === 'Administrador') {
                        header('Location: /Hotel/admin');
                    } else {
                        header('Location: /Hotel/user');
                    }
                    exit;
                }
            } catch (\Exception $e) {
                error_log("ERRO: " . $e->getMessage());
            }
            
            header('Location: /Hotel/login?error=1');
            exit;
        }
        
        $this->index();
    }
    
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /Hotel/login');
        exit;
    }
}
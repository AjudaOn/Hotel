<?php
namespace App\Controllers;

class AdminController {
    public function __construct() {
        // Verifica se o usuário está logado e é administrador
        session_start();
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Administrador') {
            header('Location: /Hotel/login');
            exit;
        }
    }

    public function index() {
        require_once ROOT_PATH . '/app/views/dashboard/tela_admin.php';
    }
    
    public function reserva() {
        // Garantir que a sessão está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Conectar ao banco de dados
        $database = new \App\Config\Database();
        $db = $database->getConnection();
        
        // Instanciar o modelo de reserva
        $reservaModel = new \App\Models\Model_reserva($db);
        
        // Buscar motivos de viagem
        $motivos = $reservaModel->getAllMotivosViagem();
        
        // Buscar status de hóspede
        $statusHospede = $reservaModel->getAllStatusHospede();
        
        // Buscar UFs
        $ufs = $reservaModel->getAllUfs();
        
        // Buscar dados adicionais
        $graduacoes = $reservaModel->getAllGraduacoes();
        $tiposHospede = $reservaModel->getAllTiposHospede();
        $sexos = $reservaModel->getAllSexos();
        
        // Definir o caminho para o conteúdo do formulário
        $formContent = ROOT_PATH . '/app/views/reserva/fazer_reserva.php';
        
        // Carregar a view principal do admin
        require_once ROOT_PATH . '/app/views/dashboard/tela_admin.php';
    }
    public function listarReservas() {
        // Garantir que a sessão está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Definir o caminho para o conteúdo do formulário
        $formContent = ROOT_PATH . '/app/views/reserva/listar_reserva.php';
        
        // Carregar a view principal do admin
        require_once ROOT_PATH . '/app/views/dashboard/tela_admin.php';
    }
    public function salvarReserva() {
        // Verificar se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Garantir que a sessão está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            // Salvar os dados do formulário na sessão para caso de erro
            $_SESSION['form_data'] = $_POST;
            
            try {
                // Conectar ao banco de dados
                $database = new \App\Config\Database();
                $db = $database->getConnection();
                
                if (!$db) {
                    $_SESSION['erro_msg'] = "Não foi possível conectar ao banco de dados";
                    header('Location: /Hotel/admin/reserva');
                    exit;
                }
                
                // Instanciar o modelo de reserva
                $reservaModel = new \App\Models\Model_reserva($db);
                
                // Processar os dados do formulário
                $resultado = $reservaModel->salvarReserva($_POST);
                
                if ($resultado === true) {
                    // Limpar os dados do formulário da sessão
                    unset($_SESSION['form_data']);
                    
                    // Redirecionar para a página de reserva com mensagem de sucesso
                    $_SESSION['sucesso_msg'] = "Reserva salva com sucesso!";
                    header('Location: /Hotel/admin/reserva');
                    exit;
                } else {
                    // Manter os dados e redirecionar com mensagem de erro
                    $_SESSION['erro_msg'] = is_string($resultado) ? $resultado : "Erro ao salvar a reserva. Por favor, tente novamente.";
                    header('Location: /Hotel/admin/reserva');
                    exit;
                }
            } catch (\Exception $e) {
                // Registrar o erro e redirecionar
                error_log("Erro ao salvar reserva: " . $e->getMessage());
                $_SESSION['erro_msg'] = "Erro: " . $e->getMessage();
                header('Location: /Hotel/admin/reserva');
                exit;
            }
        } else {
            // Se não for POST, redirecionar para o formulário
            header('Location: /Hotel/admin/reserva');
            exit;
        }
    }
    public function getCidadesByUf($uf_id) {
        // Conectar ao banco de dados
        $database = new \App\Config\Database();
        $db = $database->getConnection();
        
        // Instanciar o modelo de reserva
        $reservaModel = new \App\Models\Model_reserva($db);
        
        // Buscar cidades por UF
        $cidades = $reservaModel->getCidadesByUf($uf_id);
        
        // Retornar como JSON
        header('Content-Type: application/json');
        echo json_encode($cidades);
        exit;
    }
}
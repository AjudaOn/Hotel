<?php
// Verificar se há uma sessão ativa
session_start();

// Definir o caminho raiz
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Hotel');

// Verificar se o ID da reserva foi fornecido
$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id) {
    // Armazenar o caminho do formulário e o ID da reserva na sessão
    $_SESSION['formContent'] = '/app/views/reserva/editar_reserva.php';
    $_SESSION['reserva_id'] = $id;
    
    // Buscar os acompanhantes da reserva
    require_once ROOT_PATH . '/app/models/AcompanhanteModel.php';
    $acompanhanteModel = new AcompanhanteModel();
    $acompanhantes = $acompanhanteModel->buscarPorReservaId($id);
    
    // Armazenar os acompanhantes na sessão para uso na view
    $_SESSION['acompanhantes'] = $acompanhantes;
    
    // Redirecionar para o dashboard
    header('Location: /Hotel/admin/dashboard');
    exit;
} else {
    // Redirecionar para a lista de reservas se não houver ID
    header('Location: /Hotel/admin/reserva/listar');
    exit;
}
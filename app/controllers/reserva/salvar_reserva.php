<?php
require_once '../../config/database.php';
require_once '../../models/public_functions/reserva_salvarReserva.php';

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar os dados do formulário
    $dados = $_POST;
    
    // Após salvar a reserva com sucesso
    $resultado = reserva_salvarReserva($db, $dados);

    if ($resultado === true) {
        header('Location: /Hotel/admin');
        exit;
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    header('Location: /Hotel/admin');
    exit;
}
<?php
// Garantir que a sessão está iniciada
session_start();

// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Administrador') {
    header('Location: /Hotel/login');
    exit;
}

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $reserva_id = $_POST['reserva_id'] ?? null;
    $quarto_id = $_POST['quarto_id'] ?? null;

    if ($reserva_id && $quarto_id) {
        try {
            // Conectar ao banco de dados
            require_once $_SERVER['DOCUMENT_ROOT'] . '/Hotel/app/config/database.php';
            $database = new \App\Config\Database();
            $db = $database->getConnection();
            
            // Verificar se a reserva já tinha um quarto atribuído
            $query_check = "SELECT quartos_id FROM reservas WHERE id = ?";
            $stmt_check = $db->prepare($query_check);
            $stmt_check->bind_param("i", $reserva_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();
            $row = $result->fetch_assoc();
            $quarto_anterior = $row['quartos_id'] ?? null;
            
            // Atualizar a reserva com o novo quarto selecionado
            $query = "UPDATE reservas SET quartos_id = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ii", $quarto_id, $reserva_id);

            if ($stmt->execute()) {
                // Atualizar o status do novo quarto para reservado
                $query_quarto = "UPDATE quartos SET status = 'reservado' WHERE id = ?";
                $stmt_quarto = $db->prepare($query_quarto);
                $stmt_quarto->bind_param("i", $quarto_id);
                $stmt_quarto->execute();
                
                // Se havia um quarto anterior, atualizar seu status para disponível
                if ($quarto_anterior && $quarto_anterior != $quarto_id) {
                    $query_anterior = "UPDATE quartos SET status = 'disponivel' WHERE id = ?";
                    $stmt_anterior = $db->prepare($query_anterior);
                    $stmt_anterior->bind_param("i", $quarto_anterior);
                    $stmt_anterior->execute();
                }

                $_SESSION['sucesso_msg'] = "Quarto definido com sucesso!";
            } else {
                $_SESSION['erro_msg'] = "Erro ao definir o quarto: " . $db->error;
            }
        } catch (Exception $e) {
            $_SESSION['erro_msg'] = "Erro: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro_msg'] = "Dados incompletos.";
    }
    
    // Redirecionar de volta para a lista de reservas
    header('Location: /Hotel/admin/reserva/listar');
    exit;
} else {
    // Se não for POST, redirecionar para a lista de reservas
    header('Location: /Hotel/admin/reserva/listar');
    exit;
}
?>
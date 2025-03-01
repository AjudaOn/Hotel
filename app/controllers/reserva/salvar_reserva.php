<?php
// Garantir que não há saída antes dos cabeçalhos
if (!headers_sent()) {
    ob_start();
}

// Ajustar o caminho para o arquivo de configuração do banco de dados
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/public_functions/reserva_salvarReserva.php';

// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Criar instância do banco de dados e obter conexão
$dbInstance = new \App\Config\Database();
$db = $dbInstance->getConnection();

// Verificar se a conexão com o banco está funcionando
if (!$db) {
    // Exibir mensagem de erro
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Erro</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
            }
            .popup {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                text-align: center;
                max-width: 500px;
                width: 90%;
            }
            h3 {
                margin-top: 0;
                color: #333;
            }
            .ok-button {
                background: #0d6efd;
                color: white;
                border: none;
                padding: 10px 25px;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 20px;
                font-size: 16px;
            }
            .ok-button:hover {
                background: #0b5ed7;
            }
        </style>
    </head>
    <body>
        <div class="popup">
            <h3>Erro</h3>
            <p>Erro de conexão com o banco de dados: Não foi possível estabelecer conexão com o banco de dados</p>
            <button class="ok-button" onclick="history.back()">OK</button>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar os dados do formulário
    $dados = $_POST;
    
    // Após salvar a reserva com sucesso
    $resultado = reserva_salvarReserva($db, $dados);

    if ($resultado === true) {
        // Salvar a mensagem de sucesso na sessão
        $_SESSION['sucesso_msg'] = "Reserva salva com sucesso!";
        
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Sucesso</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                }
                .popup {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                    text-align: center;
                    max-width: 500px;
                    width: 90%;
                }
                h3 {
                    margin-top: 0;
                    color: #28a745;
                }
                .ok-button {
                    background: #0d6efd;
                    color: white;
                    border: none;
                    padding: 10px 25px;
                    border-radius: 5px;
                    cursor: pointer;
                    margin-top: 20px;
                    font-size: 16px;
                }
                .ok-button:hover {
                    background: #0b5ed7;
                }
            </style>
        </head>
        <body>
            <div class="popup">
                <h3>Sucesso!</h3>
                <p>Reserva salva com sucesso!</p>
                <button class="ok-button" onclick="window.location.href='/Hotel/admin/reserva'">OK</button>
            </div>
        </body>
        </html>
        <?php
        exit;
    } else {
        // Definir mensagem de erro na sessão
        $_SESSION['erro_msg'] = $resultado;
        
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Erro</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                }
                .popup {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                    text-align: center;
                    max-width: 500px;
                    width: 90%;
                }
                h3 {
                    margin-top: 0;
                    color: #dc3545;
                }
                .ok-button {
                    background: #0d6efd;
                    color: white;
                    border: none;
                    padding: 10px 25px;
                    border-radius: 5px;
                    cursor: pointer;
                    margin-top: 20px;
                    font-size: 16px;
                }
                .ok-button:hover {
                    background: #0b5ed7;
                }
            </style>
        </head>
        <body>
            <div class="popup">
                <h3>Erro</h3>
                <p><?php echo htmlspecialchars($resultado); ?></p>
                <button class="ok-button" onclick="history.back()">OK</button>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
} else {
    // Se não for POST, redirecionar para a página inicial
    header('Location: /Hotel/admin/reserva');
    exit;
}
<?php
// Garantir que não há saída antes dos cabeçalhos
if (!headers_sent()) {
    ob_start();
}

// Ajustar o caminho para o arquivo de configuração do banco de dados
require_once __DIR__ . '/../../config/database.php';

// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Criar instância do banco de dados e obter conexão
$dbInstance = new \App\Config\Database();
$db = $dbInstance->getConnection();

// Verificar se a conexão com o banco está funcionando
if (!$db) {
    $_SESSION['erro_msg'] = "Não foi possível conectar ao banco de dados";
    header('Location: /Hotel/admin');
    exit;
}

// Consulta para buscar todas as reservas
$query = "SELECT 
    r.id, 
    u.nm_usuario AS nome, 
    u.cpf_usuario AS cpf, 
    r.data_entrada, 
    r.data_saida, 
    (r.qtd_pessoas - 1) AS acompanhantes
FROM 
    reservas r
JOIN 
    usuarios u ON r.usuario_id = u.cd_usuario
ORDER BY 
    r.data_entrada DESC";

// Executar a consulta
$result = $db->query($query);

// Inicializar array para armazenar os resultados
$reservas = [];

// Verificar se a consulta retornou resultados
if ($result) {
    // Obter os resultados como um array associativo
    while ($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }
}

// Incluir a view para exibir os resultados
require_once __DIR__ . '/../../views/reserva/listar_reserva.php';
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
    http_response_code(500);
    echo json_encode(['error' => 'Não foi possível conectar ao banco de dados']);
    exit;
}

header('Content-Type: application/json');

if (!isset($_GET['uf_id']) || empty($_GET['uf_id'])) {
    echo json_encode([]);
    exit;
}

$uf_id = intval($_GET['uf_id']);

try {
    $sql = "SELECT id_municipio as id, id_municipio_nome as nome FROM municipios WHERE uf_id = ? ORDER BY id_municipio_nome";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $uf_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cidades = [];
    while ($row = $result->fetch_assoc()) {
        $cidades[] = $row;
    }

    echo json_encode($cidades);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
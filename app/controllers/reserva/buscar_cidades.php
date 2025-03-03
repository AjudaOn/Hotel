<?php
header('Content-Type: application/json');

require_once '../../config/database.php';

if (!isset($_GET['uf_id'])) {
    echo json_encode(['erro' => 'UF não especificada']);
    exit;
}

$uf_id = intval($_GET['uf_id']);

try {
    $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($db->connect_error) {
        throw new Exception("Conexão falhou: " . $db->connect_error);
    }

    $query = "SELECT id_municipio, id_municipio_nome FROM municipios WHERE uf_id = ? ORDER BY id_municipio_nome";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $uf_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cidades = [];
    while ($row = $result->fetch_assoc()) {
        $cidades[] = $row;
    }
    
    echo json_encode($cidades);
    
} catch (Exception $e) {
    echo json_encode(['erro' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($db)) $db->close();
}
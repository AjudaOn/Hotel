<?php
// Evitar output antes dos headers
ob_start();

// Verificar se a sessão já está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Definir o caminho raiz se ainda não estiver definido
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Hotel');
}

// Incluir o arquivo de configuração do banco de dados
require_once ROOT_PATH . '/app/config/database.php';

// Obter o ID da reserva da URL
$url = $_SERVER['REQUEST_URI'];
if (preg_match('/id=(\d+)/', $url, $matches)) {
    $reserva_id = intval($matches[1]);
} else {
    $reserva_id = null;
}

if (!$reserva_id) {
    $acompanhantes = [];
    $erro_acompanhantes = "ID da reserva não definido";
} else {
    // Criar conexão com o banco de dados usando a classe Database
    $database = new \App\Config\Database();
    $db = $database->getConnection();

    if (!$db) {
        $acompanhantes = [];
        $erro_acompanhantes = "Erro na conexão com o banco de dados";
    } else {
        // Consulta para buscar os acompanhantes
        $query = "SELECT 
            a.id AS acompanhante_id,
            a.nm_acomp,
            a.idade_acomp,
            a.sexo_id AS acompanhante_sexo_id,
            a.vinculo_familiar_id,
            s.nm_sexo AS acompanhante_sexo_descricao,
            v.nm_vinculo AS vinculo_descricao
        FROM 
            acompanhantes a
        LEFT JOIN 
            sexo s ON a.sexo_id = s.id
        LEFT JOIN 
            vinculo_familiar v ON a.vinculo_familiar_id = v.id
        WHERE 
            a.reserva_id = ?
        ORDER BY 
            a.id ASC";

        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $reserva_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $acompanhantes = [];
            while ($row = $result->fetch_assoc()) {
                $acompanhantes[] = $row;
            }
        } else {
            $acompanhantes = [];
            $erro_acompanhantes = "Nenhum acompanhante encontrado para a reserva ID: {$reserva_id}";
        }
    }
}

// Armazenar os resultados na sessão
$_SESSION['acompanhantes_query'] = $acompanhantes ?? [];
$_SESSION['erro_acompanhantes'] = $erro_acompanhantes ?? null;
?>
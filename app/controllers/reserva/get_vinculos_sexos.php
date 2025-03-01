<?php
// Conexão direta com o banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'hoteldetransito';

// Criar conexão
$conn = new mysqli($host, $user, $password, $database);
$conn->set_charset("utf8mb4");

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar a estrutura da tabela sexo para encontrar a coluna de descrição
$checkSexoQuery = "DESCRIBE sexo";
$sexoStructure = $conn->query($checkSexoQuery);
$descColumn = 'descricao'; // Coluna padrão

if ($sexoStructure) {
    while ($column = $sexoStructure->fetch_assoc()) {
        // Procurar por colunas que possam conter a descrição
        if (in_array(strtolower($column['Field']), ['descricao', 'desc', 'nome', 'nm_sexo', 'descr'])) {
            $descColumn = $column['Field'];
            break;
        }
    }
}

// Buscar sexos com a coluna de descrição correta
$querySexos = "SELECT id, $descColumn as descricao FROM sexo ORDER BY id";
$resultSexos = $conn->query($querySexos);
$sexos = [];

if ($resultSexos) {
    while ($row = $resultSexos->fetch_assoc()) {
        $sexos[] = $row;
    }
} else {
    // Fallback para valores padrão se a consulta falhar
    $sexos = [
        ['id' => 1, 'descricao' => 'Masculino'],
        ['id' => 2, 'descricao' => 'Feminino']
    ];
}

// Buscar vínculos familiares
$queryVinculos = "SELECT id, nm_vinculo FROM vinculo_familiar ORDER BY nm_vinculo";
$resultVinculos = $conn->query($queryVinculos);
$vinculos = [];

if ($resultVinculos) {
    while ($row = $resultVinculos->fetch_assoc()) {
        $vinculos[] = $row;
    }
}

// Fechar conexão
$conn->close();

// Configurar cabeçalhos para UTF-8
header('Content-Type: application/json; charset=utf-8');

// Retornar dados como JSON
echo json_encode([
    'sexos' => $sexos,
    'vinculos' => $vinculos
], JSON_UNESCAPED_UNICODE);
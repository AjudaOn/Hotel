<?php
// Verificar se há uma sessão ativa
session_start();

// Definir o caminho raiz
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Hotel');

// Incluir conexão com o banco de dados
require_once ROOT_PATH . '/app/config/database.php';

// Verificar se o ID da reserva foi fornecido
$reserva_id = isset($_GET['id']) ? $_GET['id'] : (isset($_SESSION['reserva_id']) ? $_SESSION['reserva_id'] : null);

if (!$reserva_id) {
    // Redirecionar para a lista de reservas se não houver ID
    header('Location: /Hotel/admin/reserva/listar');
    exit;
}

// Criar conexão com o banco de dados
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar conexão
if ($db->connect_error) {
    die("Conexão falhou: " . $db->connect_error);
}

// Buscar dados da reserva, hospede principal e acompanhantes em uma única consulta
$queryCompleta = "SELECT 
    r.id AS reserva_id,
    r.data_entrada,
    r.data_saida,
    r.qtd_diarias,
    r.qtd_pessoas,
    r.motivo_viagem_id,
    h.nome AS nome_hospede,
    h.cpf,
    h.email,
    h.telefone,
    h.status_hospede_id,
    h.graduacao_id,
    h.tipo_hospede_id,
    h.sexo_id,
    h.pet_hospede,
    h.qtde_pet,
    h.necessidades_especiais,
    h.obs_hospede AS observacao,
    h.uf_id,
    h.municipio_id,
    m.nome AS municipio_nome,  /* Adicionando o nome do município */
    a.id AS acompanhante_id,
    a.nm_acomp,
    a.idade_acomp,
    a.sexo_id AS acompanhante_sexo_id,
    a.vinculo_familiar_id,
    s.nm_sexo AS acompanhante_sexo_descricao,
    v.nm_vinculo AS vinculo_descricao
FROM 
    reservas r
LEFT JOIN 
    hospedes h ON r.id = h.reserva_id
LEFT JOIN 
    municipios m ON h.municipio_id = m.id  /* Adicionando join com municípios */
LEFT JOIN 
    acompanhantes a ON r.id = a.reserva_id
LEFT JOIN 
    sexo s ON a.sexo_id = s.id
LEFT JOIN 
    vinculo_familiar v ON a.vinculo_familiar_id = v.id
WHERE 
    r.id = ?";

$stmtCompleta = $db->prepare($queryCompleta);
$stmtCompleta->bind_param("i", $reserva_id);
$stmtCompleta->execute();
$resultCompleta = $stmtCompleta->get_result();

// Processar os resultados
$reserva = null;
$acompanhantes = [];

while ($row = $resultCompleta->fetch_assoc()) {
    // Pegar os dados da reserva apenas uma vez
    if ($reserva === null) {
        $reserva = [
            'reserva_id' => $row['reserva_id'],
            'data_entrada' => $row['data_entrada'],
            'data_saida' => $row['data_saida'],
            'qtd_diarias' => $row['qtd_diarias'],
            'qtd_pessoas' => $row['qtd_pessoas'],
            'motivo_viagem_id' => $row['motivo_viagem_id'],
            'nome_hospede' => $row['nome_hospede'],
            'cpf' => $row['cpf'],
            'email' => $row['email'],
            'telefone' => $row['telefone'],
            'status_hospede_id' => $row['status_hospede_id'],
            'graduacao_id' => $row['graduacao_id'],
            'tipo_hospede_id' => $row['tipo_hospede_id'],
            'sexo_id' => $row['sexo_id'],
            'pet_hospede' => $row['pet_hospede'],
            'qtde_pet' => $row['qtde_pet'],
            'necessidades_especiais' => $row['necessidades_especiais'],
            'observacao' => $row['observacao'],
            'uf_id' => $row['uf_id'],
            'municipio_id' => $row['municipio_id'],
            'municipio_nome' => $row['municipio_nome']  // Adicionando o nome do município
        ];
    }
    
    // Adicionar acompanhante se existir
    if (!empty($row['acompanhante_id'])) {
        $acompanhantes[] = [
            'acompanhante_id' => $row['acompanhante_id'],
            'reserva_id' => $row['reserva_id'],  // Adicionando o ID da reserva
            'hospede_id' => $row['hospede_id'],  // Adicionando o ID do hóspede (precisa estar na query)
            'nm_acomp' => $row['nm_acomp'],
            'idade_acomp' => $row['idade_acomp'],
            'sexo_id' => $row['acompanhante_sexo_id'],
            'vinculo_familiar_id' => $row['vinculo_familiar_id'],
            'acompanhante_sexo_descricao' => $row['acompanhante_sexo_descricao'],
            'vinculo_descricao' => $row['vinculo_descricao']
        ];
    }
}

// Debug para verificar os resultados
echo "<div style='background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd;'>";
echo "<h4>Debug - Consulta Completa:</h4>";
echo "<pre>Query executada com reserva_id = {$reserva_id}</pre>";
echo "<h4>Dados da Reserva:</h4>";
echo "<pre>";
print_r($reserva);
echo "</pre>";
echo "<h4>Acompanhantes encontrados: " . count($acompanhantes) . "</h4>";
echo "<pre>";
print_r($acompanhantes);
echo "</pre>";
echo "</div>";

// Verificar se temos acompanhantes
if (empty($acompanhantes) && isset($reserva['qtd_pessoas']) && $reserva['qtd_pessoas'] > 1) {
    // Se não temos acompanhantes mas a reserva indica que deveria ter,
    // criar entradas vazias para o número correto de acompanhantes
    $numAcompanhantes = $reserva['qtd_pessoas'] - 1;
    for ($i = 0; $i < $numAcompanhantes; $i++) {
        $acompanhantes[] = [
            'acompanhante_id' => null,
            'nm_acomp' => '',
            'idade_acomp' => '',
            'sexo_id' => null,
            'vinculo_familiar_id' => null,
            'acompanhante_sexo_descricao' => '',
            'vinculo_descricao' => ''
        ];
    }
}

// Garantir que a variável $acompanhantes esteja sempre disponível para a view
$_SESSION['acompanhantes'] = $acompanhantes;

// Buscar opções para os selects
// Sexos
$querySexos = "SELECT id, descricao FROM sexo ORDER BY descricao";
$resultSexos = $db->query($querySexos);
$sexos = [];
if ($resultSexos) {
    while ($row = $resultSexos->fetch_assoc()) {
        $sexos[] = $row;
    }
}

// Vínculos familiares
$queryVinculos = "SELECT id, nm_vinculo FROM vinculo_familiar ORDER BY nm_vinculo";
$resultVinculos = $db->query($queryVinculos);
$vinculos = [];
if ($resultVinculos) {
    while ($row = $resultVinculos->fetch_assoc()) {
        $vinculos[] = $row;
    }
}

// Status do hóspede
$queryStatus = "SELECT id, descricao FROM status_hospede ORDER BY descricao";
$resultStatus = $db->query($queryStatus);
$statusHospede = [];
if ($resultStatus) {
    while ($row = $resultStatus->fetch_assoc()) {
        $statusHospede[] = $row;
    }
}

// Graduações
$queryGraduacoes = "SELECT id, descricao FROM graduacao ORDER BY descricao";
$resultGraduacoes = $db->query($queryGraduacoes);
$graduacoes = [];
if ($resultGraduacoes) {
    while ($row = $resultGraduacoes->fetch_assoc()) {
        $graduacoes[] = $row;
    }
}

// Tipos de hóspede
$queryTipos = "SELECT id, descricao FROM tipo_hospede ORDER BY descricao";
$resultTipos = $db->query($queryTipos);
$tiposHospede = [];
if ($resultTipos) {
    while ($row = $resultTipos->fetch_assoc()) {
        $tiposHospede[] = $row;
    }
}

// Motivos de viagem
$queryMotivos = "SELECT id, descricao FROM motivo_viagem ORDER BY descricao";
$resultMotivos = $db->query($queryMotivos);
$motivos = [];
if ($resultMotivos) {
    while ($row = $resultMotivos->fetch_assoc()) {
        $motivos[] = $row;
    }
}

// UFs
$queryUFs = "SELECT id, sigla FROM uf ORDER BY sigla";
$resultUFs = $db->query($queryUFs);
$ufs = [];
if ($resultUFs) {
    while ($row = $resultUFs->fetch_assoc()) {
        $ufs[] = $row;
    }
}
// Verificar a estrutura da tabela sexo
$querySexoStructure = "DESCRIBE sexo";
$resultSexoStructure = $db->query($querySexoStructure);
echo "<div style='background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd;'>";
echo "<h4>Debug - Estrutura da tabela sexo:</h4>";
echo "<pre>";
while ($row = $resultSexoStructure->fetch_assoc()) {
    print_r($row);
}
echo "</pre></div>";

// Buscar acompanhantes com uma consulta mais simples
$queryAcompanhantesSimples = "SELECT * FROM acompanhantes WHERE reserva_id = ?";
$stmtAcompanhantesSimples = $db->prepare($queryAcompanhantesSimples);
$stmtAcompanhantesSimples->bind_param("i", $reserva_id);
$stmtAcompanhantesSimples->execute();
$resultAcompanhantesSimples = $stmtAcompanhantesSimples->get_result();
$acompanhantes = [];

while ($acompanhante = $resultAcompanhantesSimples->fetch_assoc()) {
    $acompanhantes[] = $acompanhante;
}
// Debug mais detalhado
echo "<div style='background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd;'>";
echo "<h4>Debug - Informações da Reserva:</h4>";
echo "<pre>";
print_r($reserva);
echo "</pre>";

echo "<h4>Debug - Query Acompanhantes:</h4>";
echo "<pre>SELECT * FROM acompanhantes WHERE reserva_id = {$reserva_id}</pre>";

// Consulta direta para verificar todos os acompanhantes desta reserva
$queryDebug = "SELECT a.*, s.nm_sexo, v.nm_vinculo 
               FROM acompanhantes a 
               LEFT JOIN sexo s ON a.sexo_id = s.id 
               LEFT JOIN vinculo_familiar v ON a.vinculo_familiar_id = v.id 
               WHERE a.reserva_id = {$reserva_id}";
$resultDebug = $db->query($queryDebug);

echo "<h4>Acompanhantes desta reserva:</h4>";
echo "<pre>";
while ($row = $resultDebug->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

// Teste com a query exata fornecida pelo usuário
echo "<div style='background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd;'>";
echo "<h4>Debug - Teste com Query Completa:</h4>";
$query = "SELECT 
    r.id AS reserva_id,
    r.data_entrada,
    r.data_saida,
    r.qtd_diarias,
    r.qtd_pessoas,
    h.nome AS nome_hospede,
    h.cpf,
    h.email,
    h.telefone,
    h.status_hospede_id,
    h.graduacao_id,
    h.tipo_hospede_id,
    h.sexo_id,
    h.pet_hospede,
    h.necessidades_especiais,
    h.obs_hospede,
    a.id AS acompanhante_id,
    a.nm_acomp,
    a.idade_acomp,
    a.sexo_id AS acompanhante_sexo_id,
    a.vinculo_familiar_id,
    s.nm_sexo AS acompanhante_sexo_descricao,
    v.nm_vinculo AS vinculo_descricao
FROM 
    reservas r
LEFT JOIN 
    hospedes h ON r.id = h.reserva_id
LEFT JOIN 
    acompanhantes a ON r.id = a.reserva_id
LEFT JOIN 
    sexo s ON a.sexo_id = s.id
LEFT JOIN 
    vinculo_familiar v ON a.vinculo_familiar_id = v.id
WHERE 
    r.id = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $reserva_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<pre>";
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";
echo "</div>";

echo "<h4>Total de linhas retornadas: " . $resultCompleta->num_rows . "</h4>";
echo "<h4>Acompanhantes encontrados: " . count($acompanhantesCompleta) . "</h4>";

// Se encontramos acompanhantes com esta query, vamos usá-los
if (!empty($acompanhantesCompleta)) {
    $acompanhantes = $acompanhantesCompleta;
    echo "<div class='alert alert-success'>Acompanhantes encontrados com a query completa!</div>";
}

echo "<h4>SQL Error (se houver):</h4>";
echo "<pre>" . $db->error . "</pre>";
echo "</div>";
echo "<pre>";
while ($row = $resultDebug->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

echo "<h4>SQL Error (se houver):</h4>";
echo "<pre>" . $db->error . "</pre>";
echo "</div>";
// Garantir que a variável $acompanhantes esteja sempre disponível para a view
$_SESSION['acompanhantes'] = $acompanhantes;

// Buscar opções para os selects
// Sexos
$querySexos = "SELECT id, descricao FROM sexo ORDER BY descricao";
$resultSexos = $db->query($querySexos);
$sexos = [];
if ($resultSexos) {
    while ($row = $resultSexos->fetch_assoc()) {
        $sexos[] = $row;
    }
}

// Vínculos familiares
$queryVinculos = "SELECT id, nm_vinculo FROM vinculo_familiar ORDER BY nm_vinculo";
$resultVinculos = $db->query($queryVinculos);
$vinculos = [];
if ($resultVinculos) {
    while ($row = $resultVinculos->fetch_assoc()) {
        $vinculos[] = $row;
    }
}

// Status do hóspede
$queryStatus = "SELECT id, descricao FROM status_hospede ORDER BY descricao";
$resultStatus = $db->query($queryStatus);
$statusHospede = [];
if ($resultStatus) {
    while ($row = $resultStatus->fetch_assoc()) {
        $statusHospede[] = $row;
    }
}

// Graduações
$queryGraduacoes = "SELECT id, descricao FROM graduacao ORDER BY descricao";
$resultGraduacoes = $db->query($queryGraduacoes);
$graduacoes = [];
if ($resultGraduacoes) {
    while ($row = $resultGraduacoes->fetch_assoc()) {
        $graduacoes[] = $row;
    }
}

// Tipos de hóspede
$queryTipos = "SELECT id, descricao FROM tipo_hospede ORDER BY descricao";
$resultTipos = $db->query($queryTipos);
$tiposHospede = [];
if ($resultTipos) {
    while ($row = $resultTipos->fetch_assoc()) {
        $tiposHospede[] = $row;
    }
}

// Motivos de viagem
$queryMotivos = "SELECT id, descricao FROM motivo_viagem ORDER BY descricao";
$resultMotivos = $db->query($queryMotivos);
$motivos = [];
if ($resultMotivos) {
    while ($row = $resultMotivos->fetch_assoc()) {
        $motivos[] = $row;
    }
}

// UFs
$queryUFs = "SELECT id, sigla FROM uf ORDER BY sigla";
$resultUFs = $db->query($queryUFs);
$ufs = [];
if ($resultUFs) {
    while ($row = $resultUFs->fetch_assoc()) {
        $ufs[] = $row;
    }
}

// Fechar a conexão com o banco de dados
$db->close();

// Incluir a view
include_once ROOT_PATH . '/app/views/reserva/editar_reserva.php';

<?php
// Garantir que não há saída antes dos cabeçalhos
if (!headers_sent()) {
    ob_start();
}

// Função para debug
function debug_to_file($data, $error = null) {
    $debug_file = __DIR__ . '/../../debug_atualizar.txt';
    $date = date('Y-m-d H:i:s');
    $content = "Dados POST recebidos em {$date}:\n";
    $content .= print_r($data, true);
    
    if ($error) {
        $content .= "\n\nERRO: {$error}\n";
    }
    
    file_put_contents($debug_file, $content);
}

// Remover a linha que causa o erro
// require_once __DIR__ . '/../../config/auth.php';

// Ajustar o caminho para o arquivo de configuração do banco de dados
require_once __DIR__ . '/../../config/Database.php';

// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro_msg'] = "Método de requisição inválido";
    header('Location: /Hotel/app/views/reserva/listar_reserva.php');
    exit;
}

// Debug dos dados recebidos
debug_to_file($_POST);

try {
    // Criar instância do banco de dados e obter conexão
    $dbInstance = new \App\Config\Database();
    $db = $dbInstance->getConnection();

    // Verificar se a conexão com o banco está funcionando
    if (!$db) {
        throw new Exception("Não foi possível conectar ao banco de dados");
    }

    // Obter o ID da reserva
    $reserva_id = isset($_POST['reserva_id']) ? intval($_POST['reserva_id']) : 0;

    if ($reserva_id <= 0) {
        throw new Exception("ID de reserva inválido");
    }

    // Iniciar transação
    $db->begin_transaction();
    
    // Atualizar a tabela reservas
    $sql_reserva = "UPDATE reservas SET 
                    data_entrada = ?,
                    data_saida = ?,
                    qtd_diarias = ?,
                    qtd_pessoas = ?,
                    motivo_viagem_id = ?
                    WHERE id = ?";
                    
    $stmt_reserva = $db->prepare($sql_reserva);
    $stmt_reserva->bind_param(
        'ssiiii',
        $_POST['data_entrada'],
        $_POST['data_saida'],
        $_POST['qtd_diarias'],
        $_POST['qtd_pessoas'],
        $_POST['motivo_viagem_id'],
        $reserva_id
    );
    
    $stmt_reserva->execute();
    
    // Atualizar a tabela hospedes
    $sql_hospede = "UPDATE hospedes SET 
                    nome = ?,
                    email = ?,
                    cpf = ?,
                    telefone = ?,
                    status_hospede_id = ?,
                    graduacao_id = ?,
                    tipo_hospede_id = ?,
                    sexo_id = ?,
                    municipio_id = ?,
                    uf_id = ?,
                    necessidades_especiais = ?,
                    obs_hospede = ?,
                    pet_hospede = ?
                    WHERE reserva_id = ?";
                    
    $stmt_hospede = $db->prepare($sql_hospede);
    $pet_value = intval($_POST['qtde_pet']);
    $necessidades = ($_POST['necessidades_especiais'] == 'Sim') ? 1 : 0;
    
    $stmt_hospede->bind_param(
        'ssssiiiiiiisii', 
        $_POST['nome_hospede'],
        $_POST['email'],
        $_POST['cpf'],
        $_POST['telefone'],
        $_POST['status'],
        $_POST['graduacao_id'],
        $_POST['tipo'],
        $_POST['sexo'],
        $_POST['cidade_origem'],
        $_POST['uf'],
        $necessidades,
        $_POST['observacao'],
        $pet_value,
        $reserva_id
    );
    
    $stmt_hospede->execute();
    // Atualizar acompanhante
    if (isset($_POST['acompanhante_id_0']) && !empty($_POST['acompanhante_id_0'])) {
        $sql_acomp = "UPDATE acompanhantes SET 
                      nm_acomp = ?,
                      idade_acomp = ?,
                      sexo_id = ?,
                      vinculo_familiar_id = ?
                      WHERE id = ?";
                      
        $stmt_acomp = $db->prepare($sql_acomp);
        $stmt_acomp->bind_param(
            'siiii',
            $_POST['nome_acompanhante_0'],
            $_POST['idade_acompanhante_0'],
            $_POST['sexo_acompanhante_0'],
            $_POST['vinculo_familiar_0'],
            $_POST['acompanhante_id_0']
        );
        
        $stmt_acomp->execute();
    }
    // Após o commit da transação
    $db->commit();
    
    $_SESSION['sucesso_msg'] = "Reserva atualizada com sucesso!";
    
    // Configurar a sessão para manter o usuário logado
    $_SESSION['usuario_logado'] = true;
    $_SESSION['usuario_id'] = $_SESSION['usuario_id'] ?? 1;
    $_SESSION['tipo_usuario'] = $_SESSION['tipo_usuario'] ?? 'admin';
    
    // Redirecionar para a página de listagem de reservas
    header("Location: /Hotel/admin/listar");
    exit;
    // O código abaixo nunca será executado devido ao exit acima
    // Remover este bloco de código
    // if (isset($_POST['redirect_to']) && !empty($_POST['redirect_to'])) {
    //     $redirect = $_POST['redirect_to'] . (strpos($_POST['redirect_to'], '?') !== false ? '&' : '?') . 'auth=1';
    //     header("Location: {$redirect}");
    // } else {
    //     header("Location: /Hotel/app/views/reserva/listar_reserva.php?auth=1");
    // }
    // exit;
    // Redirecionar para a página apropriada
    if (isset($_POST['redirect_to']) && !empty($_POST['redirect_to'])) {
        // Adicionar parâmetro para evitar redirecionamento para login
        $redirect = $_POST['redirect_to'] . (strpos($_POST['redirect_to'], '?') !== false ? '&' : '?') . 'auth=1';
        header("Location: {$redirect}");
    } else {
        header("Location: /Hotel/app/views/reserva/listar_reserva.php?auth=1");
    }
    exit;
    
} catch (Exception $e) {
    // Reverter transação em caso de erro
    if (isset($db) && $db->ping()) {
        $db->rollback();
    }
    
    // Registrar o erro no arquivo de debug
    debug_to_file($_POST, $e->getMessage());
    
    $_SESSION['erro_msg'] = "Erro ao atualizar reserva: " . $e->getMessage();
    header('Location: /Hotel/app/views/reserva/editar_reserva.php?id=' . ($reserva_id ?? 0));
    exit;
}
?>
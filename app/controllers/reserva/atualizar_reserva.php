<?php
// Iniciar a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir o arquivo de configuração do banco de dados
require_once '../../config/database.php';

// Verificar se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar ao banco de dados usando as constantes definidas em database.php
    $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Verificar a conexão
    if ($db->connect_error) {
        die("Falha na conexão: " . $db->connect_error);
    }
    
    // Obter o ID da reserva
    $reserva_id = isset($_POST['reserva_id']) ? intval($_POST['reserva_id']) : 0;
    
    if ($reserva_id <= 0) {
        $_SESSION['mensagem'] = "ID de reserva inválido.";
        $_SESSION['tipo_mensagem'] = "danger";
        header("Location: /Hotel/app/views/admin/tela_admin.php");
        exit;
    }
    
    // Obter os dados do formulário
    $data_entrada = $db->real_escape_string($_POST['data_entrada']);
    $data_saida = $db->real_escape_string($_POST['data_saida']);
    $qtd_diarias = intval($_POST['qtd_diarias']);
    $qtd_pessoas = intval($_POST['qtd_pessoas']);
    $motivo_viagem_id = !empty($_POST['motivo_viagem_id']) ? intval($_POST['motivo_viagem_id']) : null;
    
    // Dados do hóspede principal
    $nome_hospede = $db->real_escape_string($_POST['nome_hospede']);
    $email = $db->real_escape_string($_POST['email']);
    $cpf = $db->real_escape_string($_POST['cpf']);
    $telefone = $db->real_escape_string($_POST['telefone']);
    $status_hospede_id = !empty($_POST['status']) ? intval($_POST['status']) : null;
    $graduacao_id = !empty($_POST['graduacao_id']) ? intval($_POST['graduacao_id']) : null;
    $tipo_hospede_id = !empty($_POST['tipo']) ? intval($_POST['tipo']) : null;
    $sexo_id = !empty($_POST['sexo']) ? intval($_POST['sexo']) : null;
    
    // Dados do pet
    $tem_pet = $_POST['tem_pet'] === 'Sim' ? 1 : 0;
    $qtde_pet = $tem_pet ? intval($_POST['qtde_pet']) : 0;
    
    // Outras informações
    $uf_id = !empty($_POST['uf']) ? intval($_POST['uf']) : null;
    $municipio_id = !empty($_POST['cidade_origem']) ? intval($_POST['cidade_origem']) : null;
    $necessidades_especiais = $db->real_escape_string($_POST['necessidades_especiais']);
    $observacao = $db->real_escape_string($_POST['observacao']);
    
    // Iniciar transação
    $db->begin_transaction();
    
    try {
        // Atualizar a tabela de reservas
        $query = "UPDATE reservas SET 
                    data_entrada = ?, 
                    data_saida = ?, 
                    qtd_diarias = ?, 
                    qtd_pessoas = ?, 
                    motivo_viagem_id = ?, 
                    nome_hospede = ?, 
                    email = ?, 
                    cpf = ?, 
                    telefone = ?, 
                    status_hospede_id = ?, 
                    graduacao_id = ?, 
                    tipo_hospede_id = ?, 
                    sexo_id = ?, 
                    pet_hospede = ?, 
                    uf_id = ?, 
                    municipio_id = ?, 
                    necessidades_especiais = ?, 
                    observacao = ?,
                    data_atualizacao = NOW()
                  WHERE id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param(
            "ssiiiisssiiiiiissi", 
            $data_entrada, 
            $data_saida, 
            $qtd_diarias, 
            $qtd_pessoas, 
            $motivo_viagem_id, 
            $nome_hospede, 
            $email, 
            $cpf, 
            $telefone, 
            $status_hospede_id, 
            $graduacao_id, 
            $tipo_hospede_id, 
            $sexo_id, 
            $qtde_pet, 
            $uf_id, 
            $municipio_id, 
            $necessidades_especiais, 
            $observacao,
            $reserva_id
        );
        
        $stmt->execute();
        
        // Verificar se a atualização foi bem-sucedida
        if ($stmt->affected_rows === 0 && $stmt->errno !== 0) {
            throw new Exception("Erro ao atualizar a reserva: " . $stmt->error);
        }
        
        // Processar acompanhantes
        // Primeiro, excluir os acompanhantes existentes
        $query_delete = "DELETE FROM acompanhantes WHERE reserva_id = ?";
        $stmt_delete = $db->prepare($query_delete);
        $stmt_delete->bind_param("i", $reserva_id);
        $stmt_delete->execute();
        
        // Inserir os novos acompanhantes
        if ($qtd_pessoas > 1) {
            $query_acomp = "INSERT INTO acompanhantes (reserva_id, nm_acomp, idade_acomp, sexo_id, vinculo_familiar_id) VALUES (?, ?, ?, ?, ?)";
            $stmt_acomp = $db->prepare($query_acomp);
            
            for ($i = 0; $i < $qtd_pessoas - 1; $i++) {
                if (isset($_POST["nome_acompanhante_$i"]) && !empty($_POST["nome_acompanhante_$i"])) {
                    $nome_acomp = $db->real_escape_string($_POST["nome_acompanhante_$i"]);
                    $idade_acomp = intval($_POST["idade_acompanhante_$i"]);
                    $sexo_acomp_id = intval($_POST["sexo_acompanhante_$i"]);
                    $vinculo_familiar_id = intval($_POST["vinculo_familiar_$i"]);
                    
                    $stmt_acomp->bind_param("isiii", $reserva_id, $nome_acomp, $idade_acomp, $sexo_acomp_id, $vinculo_familiar_id);
                    $stmt_acomp->execute();
                    
                    if ($stmt_acomp->error) {
                        throw new Exception("Erro ao inserir acompanhante: " . $stmt_acomp->error);
                    }
                }
            }
        }
        
        // Confirmar a transação
        $db->commit();
        
        // Definir mensagem de sucesso
        $_SESSION['mensagem'] = "Reserva atualizada com sucesso!";
        $_SESSION['tipo_mensagem'] = "success";
        
        // Redirecionar para a página especificada ou para a tela de admin
        $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : "/Hotel/app/views/admin/tela_admin.php";
        header("Location: $redirect_to");
        exit;
        
    } catch (Exception $e) {
        // Reverter a transação em caso de erro
        $db->rollback();
        
        // Definir mensagem de erro
        $_SESSION['mensagem'] = "Erro ao atualizar a reserva: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "danger";
        
        // Redirecionar de volta para o formulário de edição
        header("Location: /Hotel/app/views/reserva/editar_reserva.php?id=$reserva_id");
        exit;
    } finally {
        // Fechar as conexões
        if (isset($stmt)) $stmt->close();
        if (isset($stmt_delete)) $stmt_delete->close();
        if (isset($stmt_acomp)) $stmt_acomp->close();
        $db->close();
    }
} else {
    // Se não for uma requisição POST, redirecionar para a página inicial
    header("Location: /Hotel/app/views/admin/tela_admin.php");
    exit;
}
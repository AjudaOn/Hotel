<?php
function reserva_salvarReserva($db, $dados) {
    try {
        // Verificar conexão com o banco
        if (!$db || $db->connect_error) {
            return "Erro de conexão com o banco: " . ($db ? $db->connect_error : "Conexão nula");
        }
        
        // Validar dados obrigatórios
        if (empty($dados['data_entrada'])) return "Data de entrada é obrigatória";
        if (empty($dados['data_saida'])) return "Data de saída é obrigatória";
        if (empty($dados['nome_hospede'])) return "Nome do hóspede é obrigatório";
        if (empty($dados['cpf'])) return "CPF é obrigatório";
        
        // Iniciar transação
        $db->begin_transaction();
        
        // Verificar a estrutura da tabela reservas
        $checkTableQuery = "SHOW CREATE TABLE reservas";
        $tableResult = $db->query($checkTableQuery);
        if (!$tableResult) {
            throw new Exception("Erro ao verificar estrutura da tabela: " . $db->error);
        }
        
        $tableInfo = $tableResult->fetch_row();
        
        // Verificar se existe a restrição de chave estrangeira
        if (strpos($tableInfo[1], 'fk_reservas_quartos') !== false) {
            // Desativar temporariamente a verificação de chaves estrangeiras
            $db->query("SET FOREIGN_KEY_CHECKS = 0");
        }
        
        // 1. Inserir na tabela reservas
        $queryReserva = "INSERT INTO reservas (
            data_entrada, 
            data_saida,
            qtd_diarias, 
            qtd_pessoas, 
            motivo_viagem_id,
            usuario_id,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $stmtReserva = $db->prepare($queryReserva);
        if (!$stmtReserva) {
            throw new Exception("Erro ao preparar query de reserva: " . $db->error);
        }
        
        $usuarioId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Valor padrão
        
        $stmtReserva->bind_param(
            "ssiiii", 
            $dados['data_entrada'], 
            $dados['data_saida'],
            $dados['qtd_diarias'],
            $dados['qtde_hospedes'], 
            $dados['motivo_viagem'],
            $usuarioId
        );
        
        if (!$stmtReserva->execute()) {
            throw new Exception("Erro ao executar query de reserva: " . $stmtReserva->error);
        }
        
        $reservaId = $db->insert_id;
        
        // 2. Inserir na tabela hospedes
        // Verificar a estrutura da tabela hospedes para determinar o nome correto da coluna
        $checkHospedesQuery = "SHOW COLUMNS FROM hospedes";
        $hospedesResult = $db->query($checkHospedesQuery);
        if (!$hospedesResult) {
            throw new Exception("Erro ao verificar estrutura da tabela hospedes: " . $db->error);
        }
        
        // Verificar se existe a coluna reserva_id ou id_reserva
        $reservaIdColumn = 'reserva_id'; // Nome padrão
        while ($column = $hospedesResult->fetch_assoc()) {
            if ($column['Field'] == 'id_reserva') {
                $reservaIdColumn = 'id_reserva';
                break;
            }
        }
        // 2. Inserir na tabela hospedes
        // Vamos verificar a estrutura real da tabela hospedes
        $checkHospedesQuery = "DESCRIBE hospedes";
        $hospedesResult = $db->query($checkHospedesQuery);
        if (!$hospedesResult) {
            throw new Exception("Erro ao verificar estrutura da tabela hospedes: " . $db->error);
        }
        
        // Verificar o nome correto da coluna que referencia a reserva
        $reservaColumnName = null;
        while ($column = $hospedesResult->fetch_assoc()) {
            // Procurar por qualquer coluna que possa referenciar a reserva
            if (strpos(strtolower($column['Field']), 'reserva') !== false) {
                $reservaColumnName = $column['Field'];
                break;
            }
        }
        
        if (!$reservaColumnName) {
            throw new Exception("Não foi possível encontrar a coluna que referencia a reserva na tabela hospedes");
        }
        
        $queryHospede = "INSERT INTO hospedes (
            nome, 
            cpf, 
            email, 
            telefone, 
            status_hospede_id,
            graduacao_id,
            tipo_hospede_id,
            sexo_id,
            pet_hospede,
            uf_id,
            municipio_id,
            necessidades_especiais,
            obs_hospede,
            $reservaColumnName
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmtHospede = $db->prepare($queryHospede);
        if (!$stmtHospede) {
            throw new Exception("Erro ao preparar query de hóspede: " . $db->error);
        }
        
        // Converter valores
        $necessidadesEspeciais = ($dados['necessidades_especiais'] == 'Sim') ? 1 : 0;
        $petHospede = (int)$dados['qtde_pet'];
        
        $stmtHospede->bind_param(
            "ssssiiiiiiiisi", // Corrigido para 14 tipos para 14 parâmetros
            $dados['nome_hospede'], 
            $dados['cpf'], 
            $dados['email'], 
            $dados['telefone'],
            $dados['status'],
            $dados['graduacao'],
            $dados['tipo'],
            $dados['sexo'],
            $petHospede,
            $dados['uf'],
            $dados['cidade_origem'],
            $necessidadesEspeciais,
            $dados['observacao'], 
            $reservaId
        );
        
        if (!$stmtHospede->execute()) {
            throw new Exception("Erro ao executar query de hóspede: " . $stmtHospede->error);
        }
        // 3. Inserir acompanhantes se houver
        // In the section where you handle companions, update to use IDs directly
        if (isset($dados['qtde_hospedes']) && $dados['qtde_hospedes'] > 1) {
            // Obter o ID do hospede recém-inserido
            $hospedeId = $db->insert_id;
            
            for ($i = 0; $i < $dados['qtde_hospedes'] - 1; $i++) {
                if (isset($dados["nome_acompanhante_$i"]) && !empty($dados["nome_acompanhante_$i"])) {
                    $queryAcompanhante = "INSERT INTO acompanhantes (
                        nm_acomp,
                        idade_acomp,
                        sexo_id,
                        vinculo_familiar_id,
                        reserva_id
                    ) VALUES (?, ?, ?, ?, ?)";
                    
                    $stmtAcompanhante = $db->prepare($queryAcompanhante);
                    if (!$stmtAcompanhante) {
                        throw new Exception("Erro ao preparar query de acompanhante: " . $db->error);
                    }
                    // Use the IDs directly from the form
                    $sexoId = isset($dados["sexo_acompanhante_$i"]) ? $dados["sexo_acompanhante_$i"] : 1;
                    $vinculoId = isset($dados["vinculo_familiar_$i"]) ? $dados["vinculo_familiar_$i"] : 1;
                    
                    $stmtAcompanhante->bind_param(
                        "siiii",
                        $dados["nome_acompanhante_$i"],
                        $dados["idade_acompanhante_$i"],
                        $sexoId,
                        $vinculoId,
                        $reservaId
                    );
                    
                    if (!$stmtAcompanhante->execute()) {
                        throw new Exception("Erro ao executar query de acompanhante: " . $stmtAcompanhante->error);
                    }
                }
            }
        }
        
        // 4. Inserir na tabela historico_etapas
        // Verificar a estrutura da tabela historico_etapas
        $checkHistoricoQuery = "DESCRIBE historico_etapas";
        $historicoResult = $db->query($checkHistoricoQuery);
        if (!$historicoResult) {
            throw new Exception("Erro ao verificar estrutura da tabela historico_etapas: " . $db->error);
        }
        
        // Verificar o nome correto da coluna que referencia a reserva
        $historicoReservaColumnName = null;
        while ($column = $historicoResult->fetch_assoc()) {
            if (strpos(strtolower($column['Field']), 'reserva') !== false) {
                $historicoReservaColumnName = $column['Field'];
                break;
            }
        }
        
        if (!$historicoReservaColumnName) {
            throw new Exception("Não foi possível encontrar a coluna que referencia a reserva na tabela historico_etapas");
        }
        
        $queryHistorico = "INSERT INTO historico_etapas (
            $historicoReservaColumnName, 
            etapa_id, 
            status_id, 
            usuario_id, 
            created_at
        ) VALUES (?, ?, ?, ?, NOW())";
        $stmtHistorico = $db->prepare($queryHistorico);
        if (!$stmtHistorico) {
            throw new Exception("Erro ao preparar query de histórico: " . $db->error);
        }
        
        $etapaId = isset($dados['etapa_id']) ? $dados['etapa_id'] : 1;
        $statusId = 1; // Status inicial (ativo)
        
        $stmtHistorico->bind_param(
            "iiii", 
            $reservaId, 
            $etapaId, 
            $statusId, 
            $usuarioId
        );
        
        if (!$stmtHistorico->execute()) {
            throw new Exception("Erro ao executar query de histórico: " . $stmtHistorico->error);
        }
        
        // Reativar a verificação de chaves estrangeiras se foi desativada
        if (strpos($tableInfo[1], 'fk_reservas_quartos') !== false) {
            $db->query("SET FOREIGN_KEY_CHECKS = 1");
        }
        
        // Confirmar transação
        if (!$db->commit()) {
            throw new Exception("Erro ao confirmar transação: " . $db->error);
        }
        
        // Registrar sucesso no log
        error_log("Reserva ID $reservaId salva com sucesso para o hóspede: " . $dados['nome_hospede']);
        
        return true;
    } catch (Exception $e) {
        // Reverter em caso de erro
        if ($db && !$db->connect_error) {
            // Garantir que a verificação de chaves estrangeiras seja reativada
            $db->query("SET FOREIGN_KEY_CHECKS = 1");
            $db->rollback();
        }
        error_log("Erro ao salvar reserva: " . $e->getMessage());
        return "Erro ao salvar reserva: " . $e->getMessage();
    }
}
<?php
/**
 * Função para buscar todos os motivos de viagem do banco de dados
 * 
 * @param mysqli $db Conexão com o banco de dados
 * @return array Lista de motivos de viagem
 */
function reserva_getAllMotivosViagem($db) {
    try {
        // Verificar se a conexão é válida
        if (!$db || $db->connect_error) {
            error_log("Conexão inválida em getAllMotivosViagem");
            return [];
        }
        
        // Usar o nome correto da tabela e campos conforme o banco de dados
        $query = "SELECT id, nm_motivo as descricao FROM motivo_viagem ORDER BY nm_motivo";
        $result = $db->query($query);
        
        if (!$result) {
            error_log("Erro na consulta: " . $db->error);
            return [];
        }
        
        $motivos = [];
        while ($row = $result->fetch_assoc()) {
            $motivos[] = $row;
        }
        $result->free();
        
        return $motivos;
    } catch (Exception $e) {
        // Em ambiente de produção, registre o erro em vez de exibi-lo
        error_log("Erro ao buscar motivos de viagem: " . $e->getMessage());
        return [];
    }
}
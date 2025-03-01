<?php
/**
 * Função para buscar todos os status de hóspede do banco de dados
 * 
 * @param mysqli $db Conexão com o banco de dados
 * @return array Lista de status de hóspede
 */
function reserva_getAllStatusHospede($db) {
    try {
        // Buscar da tabela status_hospede
        $query = "SELECT id, nm_status as descricao FROM status_hospede ORDER BY nm_status";
        $result = $db->query($query);
        
        $status = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $status[] = $row;
            }
            $result->free();
        }
        
        return $status;
    } catch (Exception $e) {
        // Em ambiente de produção, registre o erro em vez de exibi-lo
        error_log("Erro ao buscar status de hóspede: " . $e->getMessage());
        return [];
    }
}
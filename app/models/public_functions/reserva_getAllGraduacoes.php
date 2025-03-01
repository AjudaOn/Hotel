<?php
function reserva_getAllGraduacoes($db) {
    try {
        // Alterando a ordenação para ser por ID em vez de por nm_graduacao
        $query = "SELECT id, nm_graduacao as descricao FROM graduacao ORDER BY id";
        $result = $db->query($query);
        
        $graduacoes = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $graduacoes[] = $row;
            }
            $result->free();
        }
        return $graduacoes;
    } catch (Exception $e) {
        error_log("Erro ao buscar graduações: " . $e->getMessage());
        return [];
    }
}
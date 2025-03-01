<?php
function reserva_getAllTiposHospede($db) {
    try {        
        $query = "SELECT id, nm_tipo as descricao FROM tipo_hospede ORDER BY id";
        $result = $db->query($query);
        
        $tipos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tipos[] = $row;
            }
            $result->free();
        }
        return $tipos;
    } catch (Exception $e) {
        error_log("Erro ao buscar tipos de hóspede: " . $e->getMessage());
        return [];
    }
}
<?php
function reserva_getAllSexos($db) {
    try {
        $query = "SELECT id, nm_sexo as descricao FROM sexo ORDER BY nm_sexo";
        $result = $db->query($query);
        
        $sexos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sexos[] = $row;
            }
            $result->free();
        }
        return $sexos;
    } catch (Exception $e) {
        error_log("Erro ao buscar sexos: " . $e->getMessage());
        return [];
    }
}
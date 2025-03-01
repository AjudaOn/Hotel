<?php
function reserva_getAllUfs($db) {
    try {
        $query = "SELECT id, sigla_uf as sigla FROM uf ORDER BY sigla_uf";
        $result = $db->query($query);
        
        $ufs = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $ufs[] = $row;
            }
            $result->free();
        }
        
        // Debug do resultado
        error_log("UFs encontradas: " . json_encode($ufs));
        
        return $ufs;
    } catch (Exception $e) {
        error_log("Erro ao buscar UFs: " . $e->getMessage());
        return [];
    }
}
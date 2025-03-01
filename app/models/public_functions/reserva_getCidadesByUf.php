<?php
function reserva_getCidadesByUf($db, $uf_id) {
    try {
        // Consulta corrigida para usar os nomes exatos das colunas conforme o esquema
        $query = "SELECT id_municipio as id, id_municipio_nome as nome FROM municipios WHERE uf_id = ? ORDER BY id_municipio_nome";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $uf_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cidades = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $cidades[] = $row;
            }
            $result->free();
        }
        
        return $cidades;
    } catch (Exception $e) {
        error_log("Erro ao buscar cidades: " . $e->getMessage());
        return [];
    }
}
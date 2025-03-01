<?php
namespace App\Models;

class Model_reserva {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Incluir funções públicas
    public function getAllMotivosViagem() {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_getAllMotivosViagem.php';
        return reserva_getAllMotivosViagem($this->db);
    }
    
    public function getAllStatusHospede() {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_getAllStatusHospede.php';
        return reserva_getAllStatusHospede($this->db);
    }
    
    public function getAllGraduacoes() {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_getAllGraduacoes.php';
        return reserva_getAllGraduacoes($this->db);
    }
    
    public function getAllTiposHospede() {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_getAllTiposHospede.php';
        return reserva_getAllTiposHospede($this->db);
    }
    
    public function getAllSexos() {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_getAllSexos.php';
        return reserva_getAllSexos($this->db);
    }
    
    public function getAllUfs() {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_getAllUfs.php';
        return reserva_getAllUfs($this->db);
    }
    
    public function getCidadesByUf($uf_id) {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_getCidadesByUf.php';
        return reserva_getCidadesByUf($this->db, $uf_id);
    }
    public function salvarReserva($dados) {
        include_once ROOT_PATH . '/app/models/public_functions/reserva_salvarReserva.php';
        return reserva_salvarReserva($this->db, $dados);
    }
    public function getReservaById($id) {
        $query = "SELECT 
            r.*,
            h.nome AS nome_hospede,
            h.cpf,
            h.email,
            h.telefone,
            h.status_hospede_id,
            h.graduacao_id,
            h.tipo_hospede_id,
            h.sexo_id,
            h.pet_hospede,
            h.necessidades_especiais,
            h.obs_hospede AS observacao,
            h.uf_id,
            h.municipio_id
        FROM 
            reservas r
        LEFT JOIN 
            hospedes h ON r.id = h.reserva_id
        WHERE 
            r.id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
    }
}
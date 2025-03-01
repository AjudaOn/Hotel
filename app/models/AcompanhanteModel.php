<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Hotel/app/config/database.php';

class AcompanhanteModel {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function buscarPorReservaId($reservaId) {
        $query = "SELECT * FROM acompanhantes WHERE reserva_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $reservaId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $acompanhantes = [];
        while ($row = $result->fetch_assoc()) {
            $acompanhantes[] = $row;
        }
        
        return $acompanhantes;
    }
    
    public function inserir($acompanhante) {
        $query = "INSERT INTO acompanhantes (nm_acomp, idade_acomp, sexo_id, vinculo_familiar_id, reserva_id) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siiii", 
            $acompanhante['nm_acomp'], 
            $acompanhante['idade_acomp'], 
            $acompanhante['sexo_id'], 
            $acompanhante['vinculo_familiar_id'], 
            $acompanhante['reserva_id']
        );
        return $stmt->execute();
    }
    
    public function atualizar($acompanhante) {
        $query = "UPDATE acompanhantes SET 
                  nm_acomp = ?, 
                  idade_acomp = ?, 
                  sexo_id = ?, 
                  vinculo_familiar_id = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siiii", 
            $acompanhante['nm_acomp'], 
            $acompanhante['idade_acomp'], 
            $acompanhante['sexo_id'], 
            $acompanhante['vinculo_familiar_id'], 
            $acompanhante['id']
        );
        return $stmt->execute();
    }
    
    public function excluir($id) {
        $query = "DELETE FROM acompanhantes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function excluirPorReserva($reservaId) {
        $query = "DELETE FROM acompanhantes WHERE reserva_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $reservaId);
        return $stmt->execute();
    }
}
<?php
namespace App\Models;

class Model_User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        error_log("Model_User construído");
    }

    public function login($cpf, $senha) {
        error_log("=== INÍCIO DA AUTENTICAÇÃO NO MODEL ===");
        error_log("Tentando autenticar - CPF: " . $cpf);
        error_log("Conexão com banco: " . ($this->db ? "OK" : "FALHOU"));
        
        try {
            $sql = "SELECT u.cd_usuario, u.nm_usuario, t.tipo 
                    FROM usuarios u 
                    INNER JOIN tipo_usuarios t ON u.tipo_usuarios_id = t.id 
                    WHERE u.cpf_usuario = ? AND u.senha_usuario = ? 
                    LIMIT 1";
            
            error_log("SQL a ser executada: " . str_replace(['?', '?'], [$cpf, $senha], $sql));
            
            if (!$this->db) {
                error_log("ERRO: Conexão com banco não estabelecida");
                return false;
            }
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Erro SQL: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ss", $cpf, $senha);
            
            if (!$stmt->execute()) {
                error_log("Erro na execução: " . $stmt->error);
                return false;
            }
            
            $result = $stmt->get_result();
            error_log("Número de resultados: " . $result->num_rows);
            
            if ($result->num_rows > 0) {
                $dados = $result->fetch_assoc();
                error_log("Dados do usuário: " . print_r($dados, true));
                return $dados;
            }
            
            error_log("Nenhum usuário encontrado");
            return false;
            
        } catch (\Exception $e) {
            error_log("Erro na autenticação: " . $e->getMessage());
            return false;
        }
    }
}
    
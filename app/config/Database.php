<?php
namespace App\Config;

class Database {
    private $host = "localhost";
    private $db_name = "hoteldetransito";
    private $username = "root";
    private $password = "";
    private $conn = null;

    public function getConnection() {
        try {
            // Verificar se o serviço MySQL está rodando
            if (!@fsockopen($this->host, 3306, $errno, $errstr, 5)) {
                error_log("MySQL não está acessível: $errstr ($errno)");
                throw new \Exception("Não foi possível conectar ao servidor MySQL. Verifique se o serviço está ativo.");
            }
            
            $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            if ($this->conn->connect_error) {
                error_log("Erro de conexão MySQL: " . $this->conn->connect_error);
                throw new \Exception("Erro na conexão com o banco de dados: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8");
            return $this->conn;
        } catch(\Exception $e) {
            error_log("Exceção na conexão com o banco: " . $e->getMessage());
            echo "Erro de conexão: " . $e->getMessage();
            return null;
        }
    }
}
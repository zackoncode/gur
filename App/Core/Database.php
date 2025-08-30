<?php
namespace App\Core;
use PDO;
use PDOException;
class Database
{
    private $host;
    private $db;
    private $user;
    private $password;
    private ?PDO $connection = null;

    public function __construct($host = "localhost", $db = "hospital", $user = "root", $password = "")
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
    }
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->db};charset=utf8",
                    $this->user,
                    $this->password
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro ao conectar: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}

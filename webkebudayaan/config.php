<?php
class Database
{
    public $db = null;
    private $servername = "localhost";
    private $username = "root";
    private $password = ""; 
    private $dbname = "webkebudayaan";
    private $port = "3306";

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->servername};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            $this->db = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function show($tabel)
    {
        $query = $this->db->prepare("SELECT * FROM $tabel");
        $query->execute();
        return $query->fetchAll();
    }

    public function getWhere($tabel, $where)
    {
        $setCondition = [];
        foreach ($where as $key => $value) {
            $setCondition[] = "$key = ?";
        }
        $sql = "SELECT * FROM $tabel WHERE " . implode(" AND ", $setCondition);
        $query = $this->db->prepare($sql);
        $query->execute(array_values($where));
        return $query->fetchAll();
    }

    public function get($tabel, $where)
    {
        $setCondition = [];
        foreach ($where as $key => $value) {
            $setCondition[] = "$key = ?";
        }
        $sql = "SELECT * FROM $tabel WHERE " . implode(" AND ", $setCondition) . " LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array_values($where));
        return $query->fetch();
    }

    public function countWhere($tabel, $where)
    {
        $setCondition = [];
        foreach ($where as $key => $value) {
            $setCondition[] = "$key = ?";
        }
        $sql = "SELECT id FROM $tabel WHERE " . implode(" AND ", $setCondition);
        $query = $this->db->prepare($sql);
        $query->execute(array_values($where));
        return $query->rowCount();
    }

    public function del($tabel, $where)
    {
        $setCondition = [];
        foreach ($where as $key => $value) {
            $setCondition[] = "$key = ?";
        }
        $sql = "DELETE FROM $tabel WHERE " . implode(" AND ", $setCondition);
        $query = $this->db->prepare($sql);
        $query->execute(array_values($where));
        return $query->rowCount();
    }

    public function insert($table, $data = [])
    {
        try {
            $columns = array_keys($data);
            $columnsString = implode(', ', $columns);
            $placeholders = implode(', ', array_fill(0, count($data), '?'));

            $query = "INSERT INTO $table ($columnsString) VALUES ($placeholders)";
            $statement = $this->db->prepare($query);
            return $statement->execute(array_values($data));
        } catch (PDOException $e) {
            die("Insert failed: " . $e->getMessage());
        }
    }

    public function save($tabel, $setvals = [], $condition = [])
    {
        try {
            $setParts = [];
            $params = [];

            foreach ($setvals as $key => $value) {
                $setParts[] = "$key = ?";
                $params[] = $value;
            }

            $whereParts = [];
            foreach ($condition as $key => $value) {
                $whereParts[] = "$key = ?";
                $params[] = $value;
            }

            $sql = "UPDATE $tabel SET " . implode(", ", $setParts) . " WHERE " . implode(" AND ", $whereParts);
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("Update failed: " . $e->getMessage());
        }
    }

    public function sql($sql, $params = [])
    {
        $query = $this->db->prepare($sql);
        $query->execute($params);
        return $query->fetchAll();
    }
}
?>
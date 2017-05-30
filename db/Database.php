<?php

class Database {

    const HOST = "localhost";
    const DBNAME = "itech";
    const USERNAME = "root";
    const PASSWORD = "";    
    private $last_id = -1;
    private $success = false;
    private $row_count = 0;

    private function getConnection() {
        $host = self::HOST;
        $dbname = self::DBNAME;
        $username = self::USERNAME;
        $password = self::PASSWORD;
        try {
            $connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        } catch (PDOException $e) {
            die("No se pudo conectar: " . $e->getMessage());
        }
        return $connection;
    }
    
    protected function set($sql, $args){
        $connection = $this->getConnection();        
        $stmt = $connection->prepare($sql);
        try{
            $connection->beginTransaction();
            $this->success = $stmt->execute($args);            
            if($stmt){
                $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
                #$fetch = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->last_id = $connection->lastInsertId();                
                $this->row_count = $stmt->rowCount();                
            }else{
                $connection->rollBack();
                $this->last_id = -1;
                $this->row_count = 0;
                return false;
            }
            $connection->commit();
        } catch (PDOException $ex) {
            die(" " . $ex->getMessage());
        }
        return $fetch;                
    }
    
    public function getLast_id() {
        return $this->last_id;
    }

    public function getSuccess() {
        return $this->success;
    }

    public function getRow_count() {
        return $this->row_count;
    }

}

?>
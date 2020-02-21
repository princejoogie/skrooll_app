<?php

class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() {
        echo "<tr>";
    }

    function endChildren() {
        echo "</tr>" . "\n";
    }
}

class Connection {
    var $servername = "localhost";
    var $username = "root";
    var $password = "";
    var $dbname = "u552604371_demo";
    var $conn = null;

    function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
        } catch (PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }

    function getAll($table) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM " . $table);
            $stmt->execute();

            // --------------RETURN ALL IN JSON FORMAT--------------
            $result = $stmt->fetchAll();
            echo json_encode($result);
            // -----------------------------------------------------

            // -----------CHECKING CONTENTS IN TABLE FORM-----------
            // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // echo "<table style='border: solid 1px black;'>";
            // foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
            //     echo $v;
            // }
            // echo "</table>";
            // -----------------------------------------------------

        } catch(PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }

    function search($keyword) {
        try {
            $columns = array("product_name", "brand_name_model", "price", "manufacturer", "origin", "cat1", "cat2", "cat3", "cat4", "cat5");
            
            for ($i = 0; $i < count($columns); $i++) {
                $stmt = $this->conn->prepare("SELECT * FROM product WHERE " . $columns[$i] . " LIKE '%" . $keyword . "%'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) break;
            }

            // --------------RETURN ALL IN JSON FORMAT--------------
            $result = $stmt->fetchAll();
            echo json_encode($result);
            // -----------------------------------------------------

            // -----------CHECKING CONTENTS IN TABLE FORM-----------
            // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // echo "<table style='border: solid 1px black;'>";
            // foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
            //     echo $v;
            // }
            // echo "</table>";
            // -----------------------------------------------------

        } catch(PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }

    function login($uname, $pword) {
        try {
            $shaPass = sha1($pword);
            $stmt = $this->conn->prepare("SELECT * FROM account WHERE username='". $uname ."' AND password='". $shaPass . "'");
            $stmt->execute();

            // --------------RETURN ALL IN JSON FORMAT--------------
            $result = $stmt->fetchAll();
            echo json_encode($result);
            // -----------------------------------------------------

            // -----------CHECKING CONTENTS IN TABLE FORM-----------
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // echo "<table style='border: solid 1px black;'>";
            // foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
            //     echo $v;
            // }
            // echo "</table>";
            // -----------------------------------------------------

        } catch(PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }
    
}

$conn = new Connection();
$conn->login("Buyer_tester", "buy35");
?>
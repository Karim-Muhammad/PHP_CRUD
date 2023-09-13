<?php
    // File For Database Connection

    // data source name
    $dsn = "mysql:host=localhost;dbname=iti";
    // mongodb://localhost:27017/iti

    $username = "root";
    $password = "";


//    For each database, there is username, password

    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
    ];


    try {
        // PDO = PHP Data Object
        $db = new PDO($dsn, $username, $password, $options);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "DONE!";

    }catch(PDOException $exception) {
        echo $exception->getMessage();
        exit();
    }
?>


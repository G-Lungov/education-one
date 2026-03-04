<?php
    # Database configuration for PDO connection, you can change these values to match your local database/schema setup; this one was developed using MySQL with the database/schema name 'education_one' for XAMPP.

    $host = 'localhost';
    $db   = 'education_one';
    $user = 'root';
    $pass = '';

    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;

?>
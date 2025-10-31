<?php

$bdname='mairie';
$host='localhost';
$user='root';
$password='chef12er';

try {
    $conn = new PDO("mysql:host=$host;dbname=$bdname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
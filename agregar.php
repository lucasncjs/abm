<?php
include('db.php');

$username = 'luc';
$password = password_hash('luc', PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $password]);

echo "Usuario agregado.";
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (password_verify($password, $user['password'])) {
        echo "¡Contraseña verificada correctamente!";
        $_SESSION['username'] = $username;
        header("Location: abm.php");
        exit();
    } else {

        $error = "Nombre de usuario o contraseña incorrectos.";
    }
   
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Usuario:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Ingresar">
    </form>
    <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
</body>
</html>
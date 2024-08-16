<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include('db2.php');

// Función para manejar la adición de un nuevo usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $stmt->execute([$username, $email]);

    header("Location: abm.php");
    exit();
}

// Función para manejar la actualización de un usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $email, $id]);

    header("Location: abm.php");
    exit();
}

// Función para manejar la eliminación de un usuario
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: abm.php");
    exit();
}

// Obtener la lista de usuarios
$users = $pdo->query("SELECT * FROM users")->fetchAll();

// Obtener los datos del usuario para editar
$userToEdit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $userToEdit = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <h2>Gestión de Usuarios</h2>

    <!-- Formulario para agregar un nuevo usuario -->
    <h3>Agregar Nuevo Usuario</h3>
    <form method="post" action="">
        <label for="username">Nombre de Usuario:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" name="add" value="Agregar">
    </form>

    <!-- Formulario para editar un usuario existente -->
    <?php if ($userToEdit): ?>
    <h3>Editar Usuario</h3>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($userToEdit['id']); ?>">
        <label for="username">Nombre de Usuario:</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userToEdit['username']); ?>" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userToEdit['email']); ?>" required><br><br>
        <input type="submit" name="update" value="Actualizar">
    </form>
    <?php endif; ?>

    <!-- Lista de usuarios -->
    <h3>Lista de Usuarios</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre de Usuario</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <a href="?edit=<?php echo $user['id']; ?>">Editar</a>
                <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>

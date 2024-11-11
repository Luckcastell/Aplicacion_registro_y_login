<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "biblioteca");

if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $admin_password = $_POST['admin_password']; // Nueva entrada para la contraseña de administrador

    // Obtener la contraseña del administrador (usuario con id = 0)
    $sql_admin = "SELECT password FROM usuarios WHERE id = 0";
    $result_admin = $mysqli->query($sql_admin);

    if ($result_admin->num_rows > 0) {
        $row = $result_admin->fetch_assoc();
        $hashed_admin_password = $row['password'];

        // Verificar la contraseña de administrador
        if (password_verify($admin_password, $hashed_admin_password)) {
            // Si la contraseña es correcta, se procede a crear el nuevo usuario
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (username, password) VALUES ('$username', '$hashed_password')";
            if ($mysqli->query($sql) === TRUE) {
                header("Location: login.php");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        } else {
            echo "Contraseña de administrador incorrecta.";
        }
    } else {
        echo "No se encontró el usuario administrador.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="CSS/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Registro</h1>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="admin_password" placeholder="Contraseña de Administrador" required> <!-- Campo para contraseña de administrador -->
            <button type="submit">Registrarse</button>
        </form>
        <a href="index.php">Volver</a>
    </div>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "biblioteca");

if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];

    $sql = "INSERT INTO autores (nombre) VALUES (?)";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $nombre);

        if ($stmt->execute()) {
            echo "<script>alert('Autor añadido exitosamente.'); window.location.href='librosYAutores.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Autor</title>
    <link rel="stylesheet" href="css\nuevo.css">
</head>
<body>
    <div class="container">
        <h1>Añadir Nuevo Autor</h1>
        <form action="nuevoAutor.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre del Autor:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <button type="submit">Añadir Autor</button>
        </form>
        <a href="librosYAutores.php">Volver a la lista de libros</a>
    </div>
</body>
</html>

<?php
$mysqli->close();
?>
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

// Consulta para obtener los libros y sus autores
$sql_libros = "SELECT libros.titulo, autores.nombre AS autor FROM libros JOIN autores ON libros.autor_id = autores.id";
$result_libros = $mysqli->query($sql_libros);

// Consulta para obtener todos los autores
$sql_autores = "SELECT id, nombre FROM autores";
$result_autores = $mysqli->query($sql_autores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros y Autores</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="containerLibros">
        <h1>Lista de Libros</h1>
        <table>
            <tr>
                <th>Título</th>
                <th>Autor</th>
            </tr>
            <?php while ($row = $result_libros->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['titulo']; ?></td>
                <td><?php echo $row['autor']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <h1>Lista de Autores</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
            </tr>
            <?php while ($row = $result_autores->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <a href="nuevoAutor.php">Añadir autor</a>
        <a href="nuevoLibro.php">Añadir libro</a>
        <br>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>

<?php
$mysqli->close();
?>
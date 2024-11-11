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
    $titulo = $_POST['titulo'];
    $autor_id = $_POST['autor_id'];

    $sql = "INSERT INTO libros (titulo, autor_id) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) { 
        $stmt->bind_param("si", $titulo, $autor_id);

        if ($stmt->execute()) {
            echo "<script>alert('Libro añadido exitosamente.'); window.location.href='librosYAutores.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close(); 
    } else {
        echo "Error al preparar la consulta: " . $mysqli->error;
    }
}

$sql_autores = "SELECT id, nombre FROM autores";
$result_autores = $mysqli->query($sql_autores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Libro</title>
    <link rel="stylesheet" href="CSS\nuevo.css">
</head>
<body>
    <div class="container">
        <h1>Añadir Nuevo Libro</h1>
        <form action="nuevoLibro.php" method="POST">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="autor_id">Autor:</label>
                <select id="autor_id" name="autor_id" required>
                    <option value="">Seleccione un autor</option>
                    <?php while ($row = $result_autores->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit">Añadir Libro</button>
        </form>
        <a href="librosYAutores.php">Volver a la lista de libros</a>
    </div>
</body>
</html>

<?php
$mysqli->close();
?>
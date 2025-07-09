<?php
$host = "localhost";
$user = "root"; // Cambia si tu usuario es diferente
$pass = "";     // Cambia si tienes contraseña
$db = "escuela";

// Conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear o actualizar estudiante
if (isset($_POST['guardar'])) {
    $nombre = $_POST['Nombre'];
    $apellido = $_POST['Apellido'];
    $edad = $_POST['Edad'];
    if (isset($_POST['ID']) && $_POST['ID'] != '') {
        // Actualizar
        $id = $_POST['ID'];
        $conn->query("UPDATE estudiante SET Nombre='$nombre', Apellido='$apellido', Edad=$edad WHERE ID=$id");
    } else {
        // Insertar
        $conn->query("INSERT INTO estudiante (Nombre, Apellido, Edad) VALUES ('$nombre', '$apellido', $edad)");
    }
    header("Location: index.php");
    exit();
}

// Eliminar estudiante
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM estudiante WHERE ID=$id");
    header("Location: index.php");
    exit();
}

// Editar estudiante
$estudiante = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $res = $conn->query("SELECT * FROM estudiante WHERE ID=$id");
    $estudiante = $res->fetch_assoc();
}

// Obtener todos los estudiantes
$resultado = $conn->query("SELECT * FROM estudiante");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Estudiante</title>
</head>
<body>
    <h2><?php echo $estudiante ? 'Editar' : 'Agregar'; ?> Estudiante</h2>
    <form method="POST" action="index.php">
        <input type="hidden" name="ID" value="<?php echo $estudiante['ID'] ?? ''; ?>">
        <input type="text" name="Nombre" placeholder="Nombre" value="<?php echo $estudiante['Nombre'] ?? ''; ?>" required>
        <input type="text" name="Apellido" placeholder="Apellido" value="<?php echo $estudiante['Apellido'] ?? ''; ?>" required>
        <input type="number" name="Edad" placeholder="Edad" value="<?php echo $estudiante['Edad'] ?? ''; ?>" required>
        <button type="submit" name="guardar"><?php echo $estudiante ? 'Actualizar' : 'Guardar'; ?></button>
    </form>

    <h2>Lista de Estudiantes</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th><th>Nombre</th><th>Apellido</th><th>Edad</th><th>Acciones</th>
        </tr>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['ID']; ?></td>
            <td><?php echo $row['Nombre']; ?></td>
            <td><?php echo $row['Apellido']; ?></td>
            <td><?php echo $row['Edad']; ?></td>
            <td>
                <a href="index.php?editar=<?php echo $row['ID']; ?>">Editar</a>
                <a href="index.php?eliminar=<?php echo $row['ID']; ?>" onclick="return confirm('¿Seguro que deseas eliminar?');">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
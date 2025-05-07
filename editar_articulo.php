<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "autor") {
    header("Location: login.php");
    exit();
}

$id_articulo = $_GET["id"];

// Verificar si el artículo ya fue revisado
$stmt = $conn->prepare("SELECT * FROM revision WHERE id_articulo = ?");
$stmt->bind_param("i", $id_articulo);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    echo "<p>Este artículo ya fue revisado y no puede ser modificado.</p>";
    echo "<p><a href='ver_mis_articulos.php'>Volver</a></p>";
    exit();
}

// Obtener datos actuales del artículo
$stmt = $conn->prepare("SELECT titulo, resumen, fecha_envio FROM articulo WHERE id = ?");
$stmt->bind_param("i", $id_articulo);
$stmt->execute();
$articulo = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar artículo</title>
</head>
<body>
    <h2>Editar artículo</h2>
    <form action="guardar_edicion.php" method="POST">
        <input type="hidden" name="id_articulo" value="<?php echo $id_articulo; ?>">

        <label for="titulo">Título:</label><br>
        <input type="text" name="titulo" value="<?php echo $articulo['titulo']; ?>" required><br><br>

        <label for="resumen">Resumen:</label><br>
        <textarea name="resumen" rows="4" cols="50"><?php echo $articulo['resumen']; ?></textarea><br><br>

        <label for="fecha_envio">Fecha de envío:</label><br>
        <input type="date" name="fecha_envio" value="<?php echo $articulo['fecha_envio']; ?>" required><br><br>

        <input type="submit" value="Guardar cambios">
    </form>

    <p><a href="ver_mis_articulos.php">Cancelar</a></p>
</body>
</html>

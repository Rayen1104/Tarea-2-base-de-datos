<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "revisor") {
    header("Location: login.php");
    exit();
}

$id_articulo = $_GET["id"];
$userid = $_SESSION["userid"];

// Obtener RUT del revisor
$stmt = $conn->prepare("SELECT rut FROM revisor WHERE userid = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();
$revisor = $result->fetch_assoc();
$rut_revisor = $revisor["rut"];

// Obtener datos del artículo
$stmt = $conn->prepare("SELECT * FROM articulo WHERE id = ?");
$stmt->bind_param("i", $id_articulo);
$stmt->execute();
$articulo = $stmt->get_result()->fetch_assoc();

// Revisar si ya hay reseña
$stmt = $conn->prepare("SELECT * FROM revision WHERE id_articulo = ? AND rut_revisor = ?");
$stmt->bind_param("is", $id_articulo, $rut_revisor);
$stmt->execute();
$resena = $stmt->get_result()->fetch_assoc();

$observacion = $resena ? $resena["observacion"] : "";
$nota = $resena ? $resena["nota"] : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reseñar artículo</title>
</head>
<body>
    <h2>Evaluación del artículo: <?php echo $articulo["titulo"]; ?></h2>

    <p><strong>Resumen:</strong> <?php echo $articulo["resumen"]; ?></p>

    <form action="guardar_reseña.php" method="POST">
        <input type="hidden" name="id_articulo" value="<?php echo $id_articulo; ?>">
        <label for="observacion">Observaciones:</label><br>
        <textarea name="observacion" rows="4" cols="50"><?php echo $observacion; ?></textarea><br><br>

        <label for="nota">Nota (1 a 7):</label><br>
        <input type="number" name="nota" min="1" max="7" step="1" value="<?php echo $nota; ?>" required><br><br>

        <input type="submit" value="Guardar reseña">
    </form>

    <?php if ($resena): ?>
        <form action="eliminar_reseña.php" method="POST" onsubmit="return confirm('¿Eliminar esta reseña?')">
            <input type="hidden" name="id_articulo" value="<?php echo $id_articulo; ?>">
            <input type="submit" value="Eliminar reseña">
        </form>
    <?php endif; ?>

    <p><a href="articulos_asignados.php">Volver</a></p>
</body>
</html>

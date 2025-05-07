<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "revisor") {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION["userid"];

// Obtener el RUT del revisor desde su userid
$sql = "SELECT rut FROM revisor WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($fila = $result->fetch_assoc()) {
    $rut_revisor = $fila["rut"];
} else {
    echo "Revisor no encontrado.";
    exit();
}

// Obtener artículos asignados al revisor
$sql = "SELECT a.id, a.titulo, a.resumen, a.fecha_envio
        FROM articulo a
        JOIN revision r ON a.id = r.id_articulo
        WHERE r.rut_revisor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rut_revisor);
$stmt->execute();
$articulos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Artículos asignados</title>
</head>
<body>
    <h2>Artículos que debes revisar</h2>

    <?php while ($fila = $articulos->fetch_assoc()) {
        $id = $fila["id"];
        $titulo = $fila["titulo"];
        $resumen = $fila["resumen"];
        $fecha = $fila["fecha_envio"];

        echo "<hr>";
        echo "<strong>Título:</strong> $titulo<br>";
        echo "<strong>Resumen:</strong> $resumen<br>";
        echo "<strong>Fecha de envío:</strong> $fecha<br>";

        echo "<a href='reseñar.php?id=$id'>Ver / Reseñar</a>";
    } ?>

    <p><a href='dashboard.php'>Volver al menú</a></p>
</body>
</html>

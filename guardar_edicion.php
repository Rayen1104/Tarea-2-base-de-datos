<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "autor") {
    header("Location: login.php");
    exit();
}

$id = $_POST["id_articulo"];
$titulo = $_POST["titulo"];
$resumen = $_POST["resumen"];
$fecha = $_POST["fecha_envio"];

$sql = "UPDATE articulo SET titulo = ?, resumen = ?, fecha_envio = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $titulo, $resumen, $fecha, $id);

if ($stmt->execute()) {
    echo "<p>Artículo actualizado correctamente.</p>";
} else {
    echo "<p>Error al actualizar: " . $stmt->error . "</p>";
}

echo "<p><a href='ver_mis_articulos.php'>Volver a mis artículos</a></p>";
?>

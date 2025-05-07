<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "revisor") {
    header("Location: login.php");
    exit();
}

$id_articulo = $_POST["id_articulo"];
$userid = $_SESSION["userid"];

// Obtener RUT del revisor
$stmt = $conn->prepare("SELECT rut FROM revisor WHERE userid = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$res = $stmt->get_result();
$rut = $res->fetch_assoc()["rut"];

$stmt = $conn->prepare("DELETE FROM revision WHERE id_articulo = ? AND rut_revisor = ?");
$stmt->bind_param("is", $id_articulo, $rut);

if ($stmt->execute()) {
    echo "<p>Reseña eliminada correctamente.</p>";
} else {
    echo "<p>Error al eliminar reseña: " . $stmt->error . "</p>";
}

echo "<p><a href='articulos_asignados.php'>Volver</a></p>";
?>

<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "revisor") {
    header("Location: login.php");
    exit();
}

$id_articulo = $_POST["id_articulo"];
$nota = $_POST["nota"];
$observacion = $_POST["observacion"];
$userid = $_SESSION["userid"];

// Obtener RUT del revisor
$stmt = $conn->prepare("SELECT rut FROM revisor WHERE userid = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$res = $stmt->get_result();
$rut = $res->fetch_assoc()["rut"];

// Verificar si ya existe reseña
$stmt = $conn->prepare("SELECT * FROM revision WHERE id_articulo = ? AND rut_revisor = ?");
$stmt->bind_param("is", $id_articulo, $rut);
$stmt->execute();
$existe = $stmt->get_result()->num_rows > 0;

if ($existe) {
    $stmt = $conn->prepare("UPDATE revision SET nota = ?, observacion = ? WHERE id_articulo = ? AND rut_revisor = ?");
    $stmt->bind_param("isis", $nota, $observacion, $id_articulo, $rut);
} else {
    $stmt = $conn->prepare("INSERT INTO revision (id_articulo, rut_revisor, observacion, nota) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $id_articulo, $rut, $observacion, $nota);
}

if ($stmt->execute()) {
    echo "<p>Reseña guardada correctamente.</p>";
} else {
    echo "<p>Error al guardar reseña: " . $stmt->error . "</p>";
}

echo "<p><a href='articulos_asignados.php'>Volver</a></p>";
?>

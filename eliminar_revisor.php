<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== "jefecomite") {
    echo "Acceso denegado.";
    exit();
}

$rut = $_GET["rut"];

// Verificar si tiene asignaciones
$stmt = $conn->prepare("SELECT * FROM revision WHERE rut_revisor = ?");
$stmt->bind_param("s", $rut);
$stmt->execute();
$asignado = $stmt->get_result()->num_rows > 0;

if ($asignado) {
    echo "<p>No se puede eliminar el revisor porque tiene artículos asignados.</p>";
    echo "<p><a href='gestion_revisores.php'>Volver</a></p>";
    exit();
}

// Eliminar revisor
$stmt = $conn->prepare("DELETE FROM revisor WHERE rut = ?");
$stmt->bind_param("s", $rut);
if ($stmt->execute()) {
    echo "<p>Revisor eliminado correctamente.</p>";
} else {
    echo "<p>Error: " . $stmt->error . "</p>";
}

echo "<p><a href='gestion_revisores.php'>Volver</a></p>";
?>
 
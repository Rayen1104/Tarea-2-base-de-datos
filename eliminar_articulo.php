<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "autor") {
    header("Location: login.php");
    exit();
}

$id = $_GET["id"];

// Verificar si el artículo ya fue revisado
$stmt = $conn->prepare("SELECT * FROM revision WHERE id_articulo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<p>No se puede eliminar el artículo porque ya fue revisado.</p>";
    echo "<p><a href='ver_mis_articulos.php'>Volver</a></p>";
    exit();
}

// Eliminar relaciones con autores
$stmt = $conn->prepare("DELETE FROM articulo_autor WHERE id_articulo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Eliminar relaciones con tópicos
$stmt = $conn->prepare("DELETE FROM articulo_topico WHERE id_articulo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Finalmente, eliminar el artículo
$stmt = $conn->prepare("DELETE FROM articulo WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo "<p>Artículo eliminado correctamente.</p>";
echo "<p><a href='ver_mis_articulos.php'>Volver a mis artículos</a></p>";
?>

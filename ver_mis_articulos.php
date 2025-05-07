<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "autor") {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION["userid"];

// Obtener el RUT del autor desde su userid
$sql = "SELECT rut FROM autor WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($fila = $result->fetch_assoc()) {
    $rut_autor = $fila["rut"];
} else {
    echo "Autor no encontrado.";
    exit();
}

// Obtener artículos en los que aparece como autor
$sql = "SELECT a.id, a.titulo, a.resumen, a.fecha_envio, a.autor_contacto_rut
        FROM articulo a
        JOIN articulo_autor aa ON a.id = aa.id_articulo
        WHERE aa.rut_autor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rut_autor);
$stmt->execute();
$articulos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis artículos</title>
</head>
<body>
    <h2>Artículos donde participas como autor</h2>

    <?php while ($fila = $articulos->fetch_assoc()) {
        $id = $fila["id"];
        $titulo = $fila["titulo"];
        $resumen = $fila["resumen"];
        $fecha = $fila["fecha_envio"];
        $autor_contacto = $fila["autor_contacto_rut"];

        echo "<hr>";
        echo "<strong>Título:</strong> $titulo<br>";
        echo "<strong>Resumen:</strong> $resumen<br>";
        echo "<strong>Fecha de envío:</strong> $fecha<br>";
        echo "<strong>Autor de contacto:</strong> $autor_contacto<br>";

        // Ver si el artículo tiene revisiones
        $sql_rev = "SELECT * FROM revision WHERE id_articulo = ?";
        $stmt_rev = $conn->prepare($sql_rev);
        $stmt_rev->bind_param("i", $id);
        $stmt_rev->execute();
        $revisiones = $stmt_rev->get_result();

        if ($revisiones->num_rows > 0) {
            echo "<p><em>Este artículo ya fue revisado. Solo lectura.</em></p>";
        } else {
            echo "<a href='editar_articulo.php?id=$id'>Editar</a> | ";
            echo "<a href='eliminar_articulo.php?id=$id' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>";
        }
    } ?>

    <p><a href='dashboard.php'>Volver al menú</a></p>
</body>
</html>

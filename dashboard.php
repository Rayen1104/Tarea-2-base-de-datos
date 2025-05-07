<?php
session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION["userid"];
$rol = $_SESSION["rol"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - GESCON</title>
</head>
<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($userid); ?> (<?php echo $rol; ?>)</h2>

    <?php if ($rol === "autor") { ?>
        <ul>
            <li><a href="subir_articulo.php">Enviar artículo</a></li>
            <li><a href="ver_mis_articulos.php">Ver mis artículos</a></li>
            <li><a href="buscar_articulo.php">Buscar artículos</a></li>
        </ul>
    <?php } elseif ($rol === "revisor") { ?>
        <ul>
            <li><a href="articulos_asignados.php">Ver artículos asignados</a></li>
            <li><a href="buscar_articulo.php">Buscar artículos</a></li>
        </ul>
    <?php } ?>
    
    <p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>

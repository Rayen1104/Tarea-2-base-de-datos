<?php
session_start();
if (!isset($_SESSION["userid"]) || $_SESSION["rol"] !== "autor") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Artículo</title>
</head>
<body>
    <h2>Enviar nuevo artículo</h2>
    <form action="guardar_articulo.php" method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" name="titulo" required><br><br>

        <label for="resumen">Resumen:</label><br>
        <textarea name="resumen" rows="4" cols="50"></textarea><br><br>

        <label for="fecha_envio">Fecha de envío:</label><br>
        <input type="date" name="fecha_envio" required><br><br>

        <label for="topicos[]">Tópicos (puedes ingresar varios separados por coma):</label><br>
        <input type="text" name="topicos" required><br><br>

        <label for="autores">RUTs de autores (separados por coma):</label><br>
        <input type="text" name="autores" required><br><br>

        <label for="autor_contacto">RUT del autor de contacto:</label><br>
        <input type="text" name="autor_contacto" required><br><br>

        <input type="submit" value="Enviar artículo">
    </form>

    <p><a href="dashboard.php">Volver al menú</a></p>
</body>
</html>

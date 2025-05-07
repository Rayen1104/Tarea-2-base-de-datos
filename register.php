<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - GESCON</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="registrar_usuario.php" method="POST">
        <label for="rut">RUT:</label>
        <input type="text" name="rut" required><br><br>

        <label for="nombre">Nombre completo:</label>
        <input type="text" name="nombre" required><br><br>

        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" required><br><br>

        <label for="userid">Usuario:</label>
        <input type="text" name="userid" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="autor">Autor</option>
            <option value="revisor">Revisor</option>
        </select><br><br>

        <input type="submit" value="Registrar">
    </form>

    <p><a href="login.php">Volver al login</a></p>
</body>
</html>

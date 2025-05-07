<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - GESCON</title>
</head>
<body>
    <h2>Login GESCON</h2>
    <form action="validar_login.php" method="POST">
        <label for="userid">Usuario:</label>
        <input type="text" name="userid" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <label for="rol">Tipo de usuario:</label>
        <select name="rol" required>
            <option value="autor">Autor</option>
            <option value="revisor">Revisor</option>
        </select><br><br>

        <input type="submit" value="Ingresar">
    </form>

    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
</body>
</html>

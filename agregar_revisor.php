<?php
session_start();
include("conexion.php");

// Solo el jefe de comité puede acceder
if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== "jefecomite") {
    echo "Acceso denegado.";
    exit();
}

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rut = $_POST["rut"];
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $userid = $_POST["userid"];
    $password = $_POST["password"];

    // Validar que no existan duplicados
    $stmt = $conn->prepare("SELECT * FROM revisor WHERE rut = ? OR email = ? OR userid = ?");
    $stmt->bind_param("sss", $rut, $email, $userid);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        echo "<p>Ya existe un revisor con ese RUT, correo o usuario.</p>";
    } else {
        // Insertar nuevo revisor
        $stmt = $conn->prepare("INSERT INTO revisor (rut, nombre, email, userid, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $rut, $nombre, $email, $userid, $password);

        if ($stmt->execute()) {
            echo "<p>Revisor agregado correctamente. 📩 CORREO ENVIADO</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
    }

    echo "<p><a href='gestion_revisores.php'>Volver</a></p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Revisor</title>
</head>
<body>
    <h2>Agregar nuevo revisor</h2>
    <form method="POST">
        <label>RUT:</label><br>
        <input type="text" name="rut" required><br><br>

        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Usuario (userid):</label><br>
        <input type="text" name="userid" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Agregar revisor">
    </form>

    <p><a href="gestion_revisores.php">Cancelar</a></p>
</body>
</html>

<?php
include("conexion.php");

$rut = $_POST["rut"];
$nombre = $_POST["nombre"];
$email = $_POST["email"];
$userid = $_POST["userid"];
$password = $_POST["password"];
$rol = $_POST["rol"];

$tabla = ($rol === "autor") ? "Autor" : "Revisor";

$sql = "INSERT INTO $tabla (rut, nombre, email, userid, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $rut, $nombre, $email, $userid, $password);

if ($stmt->execute()) {
    echo "<p>Usuario registrado correctamente. <a href='login.php'>Iniciar sesión</a></p>";
} else {
    echo "<p>Error al registrar usuario: " . $stmt->error . "</p>";
}
?>

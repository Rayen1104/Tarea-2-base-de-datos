<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";  // Deja vacío si no tienes contraseña
$base_de_datos = "gescon";

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

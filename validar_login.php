<?php
session_start();
include("conexion.php");

// Obtener datos del formulario
$userid = $_POST["userid"];
$password = $_POST["password"];
$rol = $_POST["rol"];

// Definir tabla según tipo de usuario
$tabla = ($rol === "autor") ? "Autor" : "Revisor";

// Preparar y ejecutar consulta
$sql = "SELECT * FROM $tabla WHERE userid = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userid, $password);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    // Usuario válido
    $_SESSION["userid"] = $userid;
    $_SESSION["rol"] = $rol;

    header("Location: dashboard.php");
    exit();
} else {
    // Usuario inválido
    echo "<p>Usuario o contraseña incorrectos. <a href='login.php'>Volver</a></p>";
}
?>

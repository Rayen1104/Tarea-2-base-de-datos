<?php
echo "<pre>DEBUG - UserID: $userid, Rol: $rol</pre>";
session_start();

$userid = $_SESSION["userid"] ?? "";
$rol = $_SESSION["rol"] ?? "";

include("conexion.php");

if (!isset($_SESSION["userid"])) {
    echo "Debes iniciar sesión para ver esta página.";
    exit();
}

$userid = $_SESSION["userid"];
$rol = $_SESSION["rol"];

// Determinar tabla según rol
if ($rol === "Autor") {
    $sql = "SELECT nombre, email, rut FROM autor WHERE userid = ?";
} else {
    $sql = "SELECT nombre, email, rut FROM revisor WHERE userid = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$resultado = $stmt->get_result();

if ($datos = $resultado->fetch_assoc()) {
    echo "<h2>Mi Perfil</h2>";
    echo "<p><strong>Nombre:</strong> {$datos['nombre']}</p>";
    echo "<p><strong>Email:</strong> {$datos['email']}</p>";
    echo "<p><strong>RUT:</strong> {$datos['rut']}</p>";
    echo "<p><strong>Usuario:</strong> $userid</p>";
    echo "<p><strong>Rol:</strong> $rol</p>";
} else {
    echo "<p>Error: Usuario no encontrado.</p>";
}

echo "<p><a href='dashboard.php'>Volver al menú</a></p>";
?>

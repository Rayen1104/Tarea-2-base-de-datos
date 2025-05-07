<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== "jefecomite") {
    echo "Acceso denegado.";
    exit();
}

$rut = $_GET["rut"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];

    $stmt = $conn->prepare("UPDATE revisor SET nombre = ?, email = ? WHERE rut = ?");
    $stmt->bind_param("sss", $nombre, $email, $rut);
    $stmt->execute();

    echo "<p>Revisor actualizado correctamente.</p>";
    echo "<p><a href='gestion_revisores.php'>Volver</a></p>";
    exit();
}

// Obtener datos actuales
$stmt = $conn->prepare("SELECT * FROM revisor WHERE rut = ?");
$stmt->bind_param("s", $rut);
$stmt->execute();
$revisor = $stmt->get_result()->fetch_assoc();
?>

<h2>Editar revisor</h2>
<form method="POST">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo $revisor['nombre']; ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo $revisor['email']; ?>" required><br><br>

    <input type="submit" value="Guardar cambios">
</form>

<p><a href="gestion_revisores.php">Cancelar</a></p>

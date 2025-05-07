<?php
session_start();
include("conexion.php");

// Solo el jefe de comité puede entrar
if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== "jefecomite") {
    echo "<p>Acceso denegado. Solo el jefe de comité puede ver esta sección.</p>";
    exit();
}

// Obtener todos los revisores
$sql = "SELECT r.rut, r.nombre, r.email, r.userid FROM revisor r";
$revisores = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Revisores</title>
</head>
<body>
    <h2>Gestión de Revisores</h2>

    <table border="1">
        <tr>
            <th>RUT</th><th>Nombre</th><th>Email</th><th>Usuario</th><th>Acciones</th>
        </tr>
        <?php while ($rev = $revisores->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$rev['rut']}</td>";
            echo "<td>{$rev['nombre']}</td>";
            echo "<td>{$rev['email']}</td>";
            echo "<td>{$rev['userid']}</td>";

            // Verificar si tiene artículos asignados
            $stmt = $conn->prepare("SELECT * FROM revision WHERE rut_revisor = ?");
            $stmt->bind_param("s", $rev["rut"]);
            $stmt->execute();
            $asignado = $stmt->get_result()->num_rows > 0;

            echo "<td>";
            echo "<a href='editar_revisor.php?rut={$rev['rut']}'>Editar</a> ";
            if (!$asignado) {
                echo "| <a href='eliminar_revisor.php?rut={$rev['rut']}' onclick='return confirm(\"¿Eliminar revisor?\")'>Eliminar</a>";
            } else {
                echo "| <em>No se puede eliminar</em>";
            }
            echo "</td></tr>";
        } ?>
    </table>

    <p><a href="agregar_revisor.php">Agregar nuevo revisor</a></p>
    <p><a href="dashboard.php">Volver al menú</a></p>
</body>
</html>

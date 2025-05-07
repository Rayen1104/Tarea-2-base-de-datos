<?php
include("conexion.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Artículos</title>
</head>
<body>
    <h2>Buscar artículos por filtros</h2>
    <form method="GET" action="">
        <label>Buscar por título:</label><br>
        <input type="text" name="busqueda" placeholder="Ingrese parte del título..."><br><br>

        <label>Autor:</label><br>
        <input type="text" name="autor" placeholder="Ingrese nombre del autor"><br><br>

        <label>Fecha de envío (desde):</label><br>
        <input type="date" name="fecha_desde"><br><br>

        <label>Fecha de envío (hasta):</label><br>
        <input type="date" name="fecha_hasta"><br><br>

        <label>Tópicos:</label><br>
        <input type="text" name="topicos" placeholder="Ingrese tópicos separados por coma"><br><br>

        <label>Revisor:</label><br>
        <input type="text" name="revisor" placeholder="Ingrese nombre del revisor"><br><br>

        <input type="submit" value="Buscar">
    </form>

    <?php
    if (isset($_GET["busqueda"]) || isset($_GET["autor"])) {
        // Filtros opcionales, si no están vacíos se usan, si no, se asignan valores por defecto
        $busqueda = isset($_GET["busqueda"]) && !empty($_GET["busqueda"]) ? "%" . $_GET["busqueda"] . "%" : "%";
        $autor = isset($_GET["autor"]) && !empty($_GET["autor"]) ? "%" . $_GET["autor"] . "%" : "%";
        $fecha_desde = isset($_GET["fecha_desde"]) && !empty($_GET["fecha_desde"]) ? $_GET["fecha_desde"] : "0000-01-01";
        $fecha_hasta = isset($_GET["fecha_hasta"]) && !empty($_GET["fecha_hasta"]) ? $_GET["fecha_hasta"] : "9999-12-31";
        $topicos = isset($_GET["topicos"]) && !empty($_GET["topicos"]) ? "%" . $_GET["topicos"] . "%" : "%";
        $revisor = isset($_GET["revisor"]) && !empty($_GET["revisor"]) ? "%" . $_GET["revisor"] . "%" : "%";

        // Consulta ajustada para búsqueda por título y aplicar los filtros opcionales si están presentes
        $stmt = $conn->prepare("
            SELECT a.titulo, a.resumen, GROUP_CONCAT(DISTINCT t.nombre SEPARATOR ', ') AS topicos,
                   GROUP_CONCAT(DISTINCT au.nombre SEPARATOR ', ') AS autores
            FROM articulo a
            JOIN articulo_topico at ON a.id = at.id_articulo
            JOIN topico t ON at.id_topico = t.id
            JOIN articulo_autor aa ON a.id = aa.id_articulo
            JOIN Autor au ON aa.rut_autor = au.rut
            LEFT JOIN Revision r ON a.id = r.id_articulo
            LEFT JOIN Revisor rev ON r.rut_revisor = rev.rut
            WHERE (a.titulo LIKE ?)
            AND (au.nombre LIKE ?)
            AND a.fecha_envio BETWEEN ? AND ?
            AND t.nombre LIKE ?
            AND (rev.nombre LIKE ? OR rev.nombre IS NULL)
            GROUP BY a.id
        ");

        $stmt->bind_param("ssssss", $busqueda, $autor, $fecha_desde, $fecha_hasta, $topicos, $revisor);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h3>Resultados:</h3>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div style='border:1px solid #ccc; padding:10px; margin:10px'>";
                echo "<strong>Título:</strong> " . htmlspecialchars($row["titulo"]) . "<br>";
                echo "<strong>Resumen:</strong> " . htmlspecialchars($row["resumen"]) . "<br>";
                echo "<strong>Tópicos:</strong> " . htmlspecialchars($row["topicos"]) . "<br>";
                echo "<strong>Autores:</strong> " . htmlspecialchars($row["autores"]) . "<br>";
                echo "</div>";
            }
        } else {
            echo "No se encontraron artículos.";
        }
    }
    ?>

    <p><a href="dashboard.php">Volver al menú</a></p>
</body>
</html>

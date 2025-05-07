<?php
include("conexion.php");
session_start();

$titulo = $_POST["titulo"];
$resumen = $_POST["resumen"];
$fecha = $_POST["fecha_envio"];
$topicos = explode(",", $_POST["topicos"]);
$autores = explode(",", $_POST["autores"]);
$autor_contacto = $_POST["autor_contacto"];

// Validación rápida
if (!in_array($autor_contacto, $autores)) {
    echo "<p>El autor de contacto debe estar en la lista de autores. <a href='subir_articulo.php'>Volver</a></p>";
    exit();
}

// Insertar artículo
$sql = "INSERT INTO articulo (titulo, resumen, fecha_envio, autor_contacto_rut) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $titulo, $resumen, $fecha, $autor_contacto);
$stmt->execute();
$id_articulo = $conn->insert_id;

// Insertar autores
foreach ($autores as $rut_autor) {
    $rut_autor = trim($rut_autor);
    $stmt = $conn->prepare("INSERT INTO articulo_autor (id_articulo, rut_autor) VALUES (?, ?)");
    $stmt->bind_param("is", $id_articulo, $rut_autor);
    $stmt->execute();
}

// Insertar tópicos
foreach ($topicos as $nombre_topico) {
    $nombre_topico = trim($nombre_topico);

    // Verificar si el tópico existe
    $stmt = $conn->prepare("SELECT id FROM topico WHERE nombre = ?");
    $stmt->bind_param("s", $nombre_topico);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($fila = $result->fetch_assoc()) {
        $id_topico = $fila["id"];
    } else {
        // Insertar nuevo tópico
        $stmt = $conn->prepare("INSERT INTO topico (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre_topico);
        $stmt->execute();
        $id_topico = $conn->insert_id;
    }

    // Insertar relación artículo-tópico
    $stmt = $conn->prepare("INSERT INTO articulo_topico (id_articulo, id_topico) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_articulo, $id_topico);
    $stmt->execute();
}

// Simular correo enviado
echo "<p>Artículo enviado correctamente. CORREO ENVIADO al autor de contacto.</p>";
echo "<p><a href='dashboard.php'>Volver al menú</a></p>";
?>

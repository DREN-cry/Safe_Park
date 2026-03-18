<?php
header("Content-Type: application/json; charset=utf-8");
include "conexion.php";

$sql = $conexion->query("
    SELECT h.*, 
           u.nombre, u.apellido_paterno, u.apellido_materno,
           m.matricula
    FROM historial_entradas h
    INNER JOIN usuarios u ON h.id_usuario = u.id_usuario
    LEFT JOIN motocicletas m ON h.id_moto = m.id_moto
    ORDER BY h.fecha DESC
    LIMIT 20
");

$datos = [];

while($fila = $sql->fetch_assoc()){
    $datos[] = $fila;
}

echo json_encode($datos);
?>
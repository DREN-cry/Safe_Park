<?php
require "whatsapp.php";
date_default_timezone_set('America/Mexico_City');
header("Content-Type: application/json; charset=utf-8");

$conexion = new mysqli("localhost","root","","safe_park");

if($conexion->connect_error){
    exit(json_encode(["error"=>"Conexión fallida"]));
}

if(!isset($_POST["uid"])){
    exit(json_encode(["error"=>"No llegó UID"]));
}

$uid = $conexion->real_escape_string($_POST["uid"]);

// 1️ Guardar última UID detectada
$conexion->query("
    INSERT INTO ultimo_uid(id, uid, fecha)
    VALUES(1,'$uid',NOW())
    ON DUPLICATE KEY UPDATE uid='$uid', fecha=NOW()
");

// 2️ Buscar si la UID está registrada
$sql = $conexion->query("
    SELECT r.id_usuario, r.id_moto,
u.nombre, u.apellido_paterno, u.apellido_materno,
u.email,
u.telefono,
m.matricula
       FROM rfid_tags r
       INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
       LEFT JOIN motocicletas m ON r.id_moto = m.id_moto
       WHERE r.uid = '$uid' AND r.activo = 1
       LIMIT 1
    
");

if($sql->num_rows > 0){

    $datos = $sql->fetch_assoc();

    //  3 Insertar en historial
    //  Verificar último movimiento del usuario
$ultimo = $conexion->query("
    SELECT tipo 
    FROM historial_entradas 
    WHERE id_usuario = {$datos['id_usuario']}
    ORDER BY fecha DESC 
    LIMIT 1
");

$tipoMovimiento = "entrada"; // por defecto

if ($ultimo->num_rows > 0) {
    $filaUltimo = $ultimo->fetch_assoc();

    if ($filaUltimo["tipo"] == "entrada") {
        $tipoMovimiento = "salida";
    } else {
        $tipoMovimiento = "entrada";
    }
}

// 4️ Insertar en historial con tipo correcto
$conexion->query("
    INSERT INTO historial_entradas(uid, id_usuario, id_moto, tipo)
    VALUES(
        '$uid',
        {$datos['id_usuario']},
        ".($datos['id_moto'] ? $datos['id_moto'] : "NULL").",
        '$tipoMovimiento'
    )
");
require "mail_config.php";

$nombreCompleto = $datos["nombre"]." ".$datos["apellido_paterno"];
$hora = date("H:i");

// Preparar mensajes dinámicos
if($tipoMovimiento == "entrada"){
    $asunto = "SafePark - Entrada registrada";
    $mensaje = "Hola $nombreCompleto, tu motocicleta con matrícula {$datos["matricula"]} ingresó al estacionamiento a las $hora.";
}else{
    $asunto = "SafePark - Salida registrada";
    $mensaje = "Hola $nombreCompleto, tu motocicleta con matrícula {$datos["matricula"]} salió del estacionamiento a las $hora.";
}

// Correo
enviarCorreo($datos["email"], $asunto, $mensaje);

// WhatsApp usando teléfono del usuario
$telefonoWhats = "52" . preg_replace("/[^0-9]/", "", $datos['telefono']);
$mensajeWhats = "SafePark

Hola $nombreCompleto
Tu motocicleta {$datos['matricula']} registró una $tipoMovimiento a las $hora.";

enviarWhatsApp($telefonoWhats, $mensajeWhats);

    echo json_encode([
        "status" => "ok",
        "uid" => $uid,
        "nombre" => $datos["nombre"],
        "apellido_paterno" => $datos["apellido_paterno"],
        "apellido_materno" => $datos["apellido_materno"],
        "matricula" => $datos["matricula"]
    ]);

} else {

    echo json_encode([
        "status" => "no_registrado",
        "uid" => $uid
    ]);
}
?>
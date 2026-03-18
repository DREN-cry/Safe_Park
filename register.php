<?php

header("Content-Type: application/json");
include "conexion.php";

$data = $_POST;
$tipoUsuario = $data["tipo_usuario"];
$nombre = $data["firstName"];
$apellidoP = $data["lastNameP"];
$apellidoM = $data["lastNameM"];
$email = $data["email"];
$telefono = $data["phone"];
$password = password_hash($data["password"], PASSWORD_BCRYPT);

// --------------------------------------
// 1. Verificar correo repetido
// --------------------------------------
$q = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$q->bind_param("s", $email);
$q->execute();
$q->store_result();

if ($q->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Ese correo ya está registrado."]);
    exit;
}

// --------------------------------------
// 2. Insertar en usuarios
// --------------------------------------
$query = $conexion->prepare("
    INSERT INTO usuarios 
    (tipo_usuario, nombre, apellido_paterno, apellido_materno, email, telefono, password)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$query->bind_param("sssssss",
    $tipoUsuario, $nombre, $apellidoP, $apellidoM, $email, $telefono, $password
);

if (!$query->execute()) {
    echo json_encode(["status" => "error", "message" => "Error al registrar usuario."]);
    exit;
}

$idUsuario = $query->insert_id;

// --------------------------------------
// 3A. Insertar ADMINISTRADOR
// --------------------------------------
if ($tipoUsuario === "administrador") {

    $empleadoId = $data["employeeId"];
    $rol = $data["role"];

    $q2 = $conexion->prepare("
        INSERT INTO administradores (id_usuario, empleado_id, rol)
        VALUES (?, ?, ?)
    ");
    $q2->bind_param("iss", $idUsuario, $empleadoId, $rol);
    $q2->execute();

}

// --------------------------------------
// 3B. Insertar ESTUDIANTE + MOTOCICLETA
// --------------------------------------
if ($tipoUsuario === "estudiante") {

    // Datos estudiante
    $matricula = $data["studentId"];
    $programa = $data["educationalProgram"];

    // Insertar en estudiantes
    $qEst = $conexion->prepare("
        INSERT INTO estudiantes (id_usuario, matricula, programa)
        VALUES (?, ?, ?)
    ");
    $qEst->bind_param("iss", $idUsuario, $matricula, $programa);
    $qEst->execute();

    // Datos moto
    $plate = $data["motorcyclePlate"];
    $brand = $data["motorcycleBrand"];
    $model = $data["motorcycleModel"];
    $color = $data["motorcycleColor"];
    $disp = $data["motorcycleDisplacement"];
    $acc = $data["motorcycleAccessories"];

    // Insertar motocicleta
    $qMoto = $conexion->prepare("
        INSERT INTO motocicletas 
        (id_usuario, matricula, marca, modelo, color, cilindraje, accesorios)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $qMoto->bind_param("issssss", 
        $idUsuario, $plate, $brand, $model, $color, $disp, $acc
    );
    $qMoto->execute();
}

echo json_encode(["status" => "success", "message" => "Usuario registrado correctamente."]);
?>



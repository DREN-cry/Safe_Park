<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.html");
    exit;
}

$usuario = $_SESSION["usuario"];
$moto = $usuario["moto"];
include __DIR__ . "/../api/conexion.php";

$idUsuario = $usuario["id_usuario"];

// Obtener historial ordenado por fecha descendente
$stmt = $conexion->prepare("
    SELECT h.tipo, h.fecha
    FROM historial_entradas h
    INNER JOIN motocicletas m ON h.id_moto = m.id_moto
    WHERE m.id_usuario = ?
    ORDER BY h.fecha ASC
    limit 5
");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

// Agrupar por día
$historialPorDia = [];

while ($fila = $resultado->fetch_assoc()) {
    $fechaDia = date("Y-m-d", strtotime($fila["fecha"]));
    $historialPorDia[$fechaDia][] = $fila;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Registros</title>
  <link rel="stylesheet" href="../styles/historial.css">
  <link rel="stylesheet" type="text/css" href="../styles/fontello.css">
</head>
<body>

<div class="page">
  <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-top">
        <div class="brand">
          <div class="brand-icon"><!-- icon: bike --></div>
          <div class="brand-text">
            <h3>SafePark</h3>
            <small>Sistema de Control</small>
          </div>
        </div>

        <nav class="nav">
          <a href="/views/dashboard-user.php" class="nav-item">
            <span class="nav-icon"><!-- icon: grid --></span>
            <span class="nav-label">Dashboard</span>
          </a>

          <a href="/views/historial.php" class="nav-item active">
            <span class="nav-icon"><!-- icon: history --></span>
            <span class="nav-label">Mi Historial</span>
          </a>
        </nav>
      </div>

      <div class="sidebar-bottom">
        <div class="profile-wrapper">
          <div id="profileToggle" class="profile-row" aria-haspopup="true" aria-expanded="false">
            <div class="profile-avatar"><?php echo strtoupper($usuario['nombre'][0]); ?></div>
            <div class="profile-info">
              <div class="profile-name"><?php echo $usuario['nombre'] . ' ' . $usuario['apellidos']; ?></div>
              <div class="profile-email"><?php echo $usuario['email']; ?></div>
            </div>
            <div class="profile-arrow icon-arrow"><!-- icon: chevron --></div>
          </div>

          <!-- DROPDOWN -->
          <div id="profileMenu" class="profile-menu" role="menu" aria-hidden="true">
            <a href="/views/configuracion.php" class="pm-item">Configuración</a>
            <a href="../api/logout.php" class="pm-item danger">Cerrar Sesión</a>
          </div>
        </div>
      </div>
    </aside>

<main class="main-area">
  <!-- Header -->
   <header class="main-header">
        <div class="header-left">
          <h1>Bienvenido, <?php echo $usuario['nombre'];?></h1>
          <p class="subtitle">Historial de registros en tiempo real</p>
        </div>

        <div class="header-right">
          <!-- 
          <button class="icon-btn" aria-label="notificaciones">
            <span class="icon-mark">icon: bell --></span>
            <span class="badge"></span>
          </button>
        </div>
      </header>

  <!-- Contenido -->
  <div class="container">

<?php if (empty($historialPorDia)): ?>
    <div class="card">
        <div class="card-content">
            <p>No hay registros aún.</p>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($historialPorDia as $fecha => $registros): ?>

<div class="card">
  <div class="card-header">
    <div class="card-title">
      <?= strftime("%A, %d %B %Y", strtotime($fecha)); ?>
    </div>
  </div>

  <div class="card-content">

    <?php foreach ($registros as $registro): ?>

      <div class="entry-row">
        <div class="entry-icon <?= $registro["tipo"] === "entrada" ? "entry-bg icon-right" : "exit-bg icon-left" ?>">
        </div>

        <div class="entry-info">
          <p class="entry-type">
            <?= ucfirst($registro["tipo"]); ?>
          </p>
        </div>

        <div class="entry-time">
          <?= date("H:i", strtotime($registro["fecha"])); ?>
        </div>
      </div>

    <?php endforeach; ?>

  </div>
</div>

<?php endforeach; ?>

</div>
</main>
</div>
 <script src="../javascript/dashboard-user.js"></script>
</body>
</html>

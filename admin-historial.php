<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.html");
    exit;
}

$usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Historial de entradas</title>
  <link rel="stylesheet" href="../styles/admin-historial.css" />
  <link rel="stylesheet" type="text/css" href="../styles/fontello.css">
</head>
<body>

<div class="page">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-top">
    <div class="brand">
      <div class="brand-text">
        <h3>SafePark</h3>
        <small>Sistema de Control</small>
      </div>
    </div>

    <nav class="nav">
      <a href="/views/dashboard-admin.php" class="nav-item">
        <span class="nav-label">Dashboard</span>
      </a>

      <a href="/views/users-view.php" class="nav-item">
        <span class="nav-label">Usuarios Registrados</span>
      </a>

      <a href="/views/admin-historial.php" class="nav-item active">
        <span class="nav-label">Historial de entradas</span>
      </a>

      <a href="/views/asignar_rfid.php" class="nav-item">
        <span class="nav-label">Asignar tarjeta</span>
      </a>
    </nav>
  </div>

  <div class="sidebar-bottom">
    <div class="profile-wrapper">
      <div class="profile-row">
        <div class="profile-avatar">
          <?php echo strtoupper($usuario['nombre'][0]); ?>
        </div>
        <div class="profile-info">
          <div class="profile-name">
            <?php echo $usuario['nombre']; ?>
          </div>
          <div class="profile-email">
            <?php echo $usuario['email']; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</aside>

<!-- MAIN -->
<div class="main-area">
  <header class="header">    
    <div class="header-left">
      <h1>Bienvenido, <?php echo $usuario['nombre']; ?></h1>
      <p class="subtitle">Monitorea el estacionamiento en tiempo real</p>
    </div>
  </header>

  <main class="container">

    <div class="card">

      <div class="card-header">
        <div class="card-title">
          Historial
        </div>
      </div>

      <!-- CONTENEDOR DINÁMICO -->
      <div class="card-content" id="historialContainer">
      </div>

    </div>

  </main>
</div>
</div>

<script>
function cargarHistorial() {
    fetch("../api/obtener_historial.php")
        .then(res => res.json())
        .then(data => {

            const container = document.getElementById("historialContainer");
            container.innerHTML = "";

            data.forEach(registro => {

                container.innerHTML += `
                <div class="record-row">

                    <div class="record-info">
                        <p class="record-id">${registro.matricula ?? 'Sin placa'}</p>

                        <p class="record-student">
                            ${registro.nombre} 
                            ${registro.apellido_paterno} 
                            ${registro.apellido_materno}
                        </p>
                    </div>

                    <div class="record-right">
                        <span class="badge badge-entry">
                            ${registro.tipo}
                        </span>

                        <p class="record-time">
                            ${new Date(registro.fecha).toLocaleTimeString()}
                        </p>
                    </div>

                </div>
                `;
            });

        })
        .catch(error => console.log(error));
}

// Cargar al abrir la página
cargarHistorial();

// Actualizar cada 2 segundos
setInterval(cargarHistorial, 2000);
</script>

</body>
</html>


<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.html");
    exit;
}

$usuario = $_SESSION["usuario"];
$moto = $usuario["moto"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
   <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard - Usuario</title>
  <link rel="stylesheet" href="../styles/dashboard-user.css" />
  <link rel="stylesheet" type="text/css" href="../styles/fontello.css">
</head>
<body>

  <div class="app-layout">

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
          <a href="/views/dashboard-user.php" class="nav-item active">
            <span class="nav-icon"><!-- icon: grid --></span>
            <span class="nav-label">Dashboard</span>
          </a>

          <a href="/views/historial.php" class="nav-item">
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

    <!-- MAIN -->
    <div class="main-area">

      <!-- HEADER -->
      <header class="main-header">
        <div class="header-left">
          <h1>Bienvenido, <?php echo $usuario['nombre'];?></h1>
          <p class="subtitle">Gestiona el estacionamiento en tiempo real</p>
        </div>

        <div class="header-right">
          <!--
          <button class="icon-btn" aria-label="notificaciones">
            <span class="icon-mark"> icon: bell --></span>
            <span class="badge"></span>
          </button>
        </div>
      </header>

      <!-- PAGE CONTENT -->
      <main class="page-content">

        <!-- ROW 1: Personal (left) + Estado (right) -->
        <section class="row grid-2-1">
          <article class="card personal-card">
            <h2 class="card-title">Información Personal</h2>
            <div class="personal-inner">
              <div class="avatar-large"><?php echo strtoupper($usuario['nombre'][0]); ?></div>
              <div class="personal-details">
                <div class="pd-grid">
                  <div class="pd-item">
                     <div class="icon-placeholder icon-name"></div>
                        <div class="pd-text">
                         <label>Nombre Completo</label>
                        <div class="pd-value"><?php echo $usuario['nombre'] . ' ' . $usuario['apellidos']; ?></div>
                    </div>
                  </div>
                  <div class="pd-item">
                    <div class="icon-placeholder icon-hash"></div>
                    <div class="pd-text">
                    <label>Matrícula</label>
                    <div class="pd-value"><?php echo $usuario['matricula']; ?></div>
                    </div>
                  </div>
                  <div class="pd-item">
                    <div class="icon-placeholder icon-mail"></div>
                    <div class="pd-text">
                    <label>Email</label>
                    <div class="pd-value"><?php echo $usuario['email']; ?></div>
                    </div>
                  </div>
                  <div class="pd-item">
                    <div class="icon-placeholder icon-phone"></div>
                    <div class="pd-text">
                    <label>Teléfono</label>
                    <div class="pd-value"><?php echo $usuario['telefono']; ?></div>
                    </div>
                  </div>
                  <div class="pd-item full">
                    <div class="icon-placeholder icon-town-hall"></div>
                    <div class="pd-text">
                    <label>Programa Educativo</label>
                    <div class="pd-value"><?php echo $usuario['programa']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </article>

          <aside class="card state-card">
            <h2 class="card-title">Estado Actual</h2>
            <div class="state-inner">
              <div class="state-icon"><img src="/img/moto.png" width="80" height="80"></div>
              <span class="state-badge">Dentro del Estacionamiento</span>
              <p class="state-text">Tu motocicleta está actualmente en el estacionamiento</p>
              <small class="muted">Última entrada: Hoy a las 14:00</small>
            </div>
          </aside>
        </section>

        <!-- ROW 2: Moto (left) + Hoy (right) -->
        <section class="row grid-2-1">
          <article class="card moto-card">
            <h2 class="card-title">Información de Motocicleta</h2>

            <div class="moto-grid">
              <div class="moto-item">
                <label>ID Motocicleta</label>
                <div class="moto-value accent"><?php echo $moto['id_moto']; ?></div>
              </div>
              <div class="moto-item">
                <label>Matrícula</label>
                <div class="moto-value"><?php echo $moto['matricula']; ?></div>
              </div>
              <div class="moto-item">
                <label>Marca</label>
                <div class="moto-value"><?php echo $moto['marca']; ?></div>
              </div>
              <div class="moto-item">
                <label>Modelo</label>
                <div class="moto-value"><?php echo $moto['modelo']; ?></div>
                </div>
              <div class="moto-item">
                <label>Color</label>
                <div class="moto-value"><?php echo $moto['color']; ?></div>
              </div>
              <div class="moto-item">
                <label>Cilindraje</label>
                <div class="moto-value"><?php echo $moto['cilindraje']; ?></div>
              </div>
              <div class="moto-item wide">
               <label>Accesorio Característico</label>
              <div class="moto-value"><?php echo $moto['accesorios']; ?></div>
              </div>

            </div>
          </article>

          <aside class="card today-card">
            <div class="today-header">
              <h2 class="card-title">Hoy</h2>
              <a class="see-history" href="/views/historial.php">Ver historial</a>
            </div>

            <div class="records">

              <div class="record in">
                <div class="record-icon icon-right"><!-- icon: arrow-down --></div>
                <div class="record-body"> 
                  <div class="record-title">Entrada</div>
                  <div class="record-time">08:15</div>
                </div>
              </div>

              <div class="record out">
                <div class="record-icon icon-left"><!-- icon: arrow-up --></div>
                <div class="record-body">
                  <div class="record-title">Salida</div>
                  <div class="record-time">12:30</div>
                </div>
              </div>

              <div class="record in">
                <div class="record-icon icon-right"><!-- icon: arrow-down --></div>
                <div class="record-body">
                  <div class="record-title">Entrada</div>
                  <div class="record-time">14:00</div>
                </div>
              </div>

            </div>
          </aside>
        </section>

      </main>
    </div>
  </div>
  

  <script src="../javascript/dashboard-user.js"></script>
</body>
</html>


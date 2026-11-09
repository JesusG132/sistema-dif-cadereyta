<?php
// panel.php - Panel de Control Rediseñado
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

$rol_usuario = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Sistema DIF</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #6d1b2b;       /* Vino Profundo */
            --secondary: #2c3e50;     /* Gris Acero */
            --bg-color: #f0f2f5;      /* Fondo Suave */
            --white: #ffffff;
            --gray-disabled: #e9ecef;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* --- HEADER (Igual a Nutrición) --- */
        .header-principal {
            background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            color: white;
        }

        .header-brand { display: flex; align-items: center; gap: 20px; }
        
        .logo-img {
            height: 65px;
            width: 65px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            padding: 3px;
        }

        .header-title h1 { margin: 0; font-size: 1.4em; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        .header-title span { font-size: 0.85em; opacity: 0.9; font-weight: 400; }

        .btn-logout {
            background: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 30px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
            font-size: 0.85em;
            backdrop-filter: blur(5px);
        }
        .btn-logout:hover { background: white; color: var(--primary); font-weight: bold; }

        /* --- CONTENEDOR PRINCIPAL --- */
        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 20px;
            text-align: center;
        }

        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            border-left: 5px solid var(--primary);
            text-align: left;
        }

        .welcome-card h2 { margin: 0 0 10px 0; color: var(--secondary); font-size: 1.8em; }
        .welcome-card p { margin: 0; color: #666; font-size: 1.1em; }

        /* --- GRID DE MÓDULOS --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 30px;
            justify-content: center;
        }

        .module-card {
            background: white;
            border-radius: 15px;
            padding: 40px 20px;
            text-decoration: none;
            color: var(--secondary);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .module-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(109, 27, 43, 0.15); /* Sombra Vino */
            border-color: rgba(109, 27, 43, 0.1);
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            margin-bottom: 20px;
            color: var(--primary);
            transition: 0.3s;
        }

        .module-card:hover .icon-circle {
            background: var(--primary);
            color: white;
        }

        .module-title {
            font-size: 1.2em;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .module-status {
            font-size: 0.8em;
            color: #999;
            margin-top: 10px;
        }

        /* --- ESTADO DESHABILITADO --- */
        .module-card.disabled {
            background-color: #fcfcfc;
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(100%);
            box-shadow: none;
            border: 1px solid #eee;
        }

        .footer-role {
            margin-top: 50px;
            font-size: 0.9em;
            color: #888;
        }
        
    </style>
</head>
<body>

    <nav class="header-principal">
        <div class="header-brand">
            <img src="logodiff.png" alt="Logo DIF" class="logo-img">
            <div class="header-title">
                <h1>Sistema DIF</h1>
                <span>Panel de Administración</span>
            </div>
        </div>
        <a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <div class="container">
        
        <div class="welcome-card">
            <h2>¡Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
            <p>Bienvenido al sistema. Selecciona el módulo al que deseas acceder.</p>
        </div>

        <div class="dashboard-grid">
            
            <a href="nutricion.php" class="module-card">
                <div class="icon-circle">🍎</div>
                <div class="module-title">Nutrición</div>
                <div class="module-status">Acceso Permitido</div>
            </a>
            
           <a href="alimentos_f.php" class="module-card">
    <div class="icon-circle">🍞</div>
    <div class="module-title">Alimentos F</div>
    <div class="module-status">Acceso Permitido</div>
</a>
            
            <a href="alimentos_e.php" class="module-card">
    <div class="icon-circle">🥛</div>
    <div class="module-title">Alimentos E</div>
    <div class="module-status">Acceso Permitido</div>
</a>
            
            <a href="transporte.php" class="module-card <?php echo ($rol_usuario == 'Admin') ? '' : 'disabled'; ?>">
                <div class="icon-circle">🚌</div>
                <div class="module-title">Transporte</div>
                <div class="module-status">
                    <?php echo ($rol_usuario == 'Admin') ? 'Acceso Permitido' : 'Restringido (Solo Admin)'; ?>
                </div>
            </a>

        </div>

        <div class="footer-role">
            Rol actual: <span style="color: var(--primary); font-weight: bold;"><?php echo $rol_usuario; ?></span>
        </div>
    </div>

    <?php if(isset($_SESSION['mensaje_exito'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: '<?php echo $_SESSION['mensaje_exito']; ?>',
            confirmButtonColor: '#6d1b2b',
            timer: 2000
        });
    </script>
    <?php unset($_SESSION['mensaje_exito']); endif; ?>

</body>
</html>
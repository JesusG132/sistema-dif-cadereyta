<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$mensaje = "";
$tipoAlerta = "";

if (isset($_POST['guardar_servicio'])) {
    $folio = "TRANS-" . date("Y") . "-" . rand(1000, 9999);
    $unidad = $conexion->real_escape_string($_POST['unidad']);
    $operador = $conexion->real_escape_string($_POST['operador']);
    $ruta = $conexion->real_escape_string($_POST['ruta_destino']);
    $tipo_servicio = $conexion->real_escape_string($_POST['tipo_servicio']);
    $pasajeros = intval($_POST['pasajeros_pasajeras']);
    $fecha = $_POST['fecha_servicio'];
    $estatus = $conexion->real_escape_string($_POST['estatus_mantenimiento']);
    $observaciones = $conexion->real_escape_string($_POST['observaciones']);
    $usuario_atiende = $_SESSION['usuario'];

    $sql = "INSERT INTO transporte (folio_servicio, unidad, operador, ruta_destino, tipo_servicio, pasajeros_pasajeras, fecha_servicio, estatus_mantenimiento, observaciones, usuario_atiende) 
            VALUES ('$folio', '$unidad', '$operador', '$ruta', '$tipo_servicio', '$pasajeros', '$fecha', '$estatus', '$observaciones', '$usuario_atiende')";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "¡Servicio de transporte registrado con folio $folio!";
        $tipoAlerta = "success";
    } else {
        $mensaje = "Error al registrar: " . $conexion->error;
        $tipoAlerta = "error";
    }
}

$resultado_registros = $conexion->query("SELECT * FROM transporte ORDER BY id_transporte DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema DIF - Transporte Adaptado</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --color-principal: #8B0000;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            color: #333;
        }

        .top-bar {
            background: linear-gradient(90deg, #601424 0%, #324355 100%);
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-circle {
            width: 65px;
            height: 65px;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .logo-circle img {
            width: 85%;
            height: auto;
            object-fit: contain;
        }

        .title-section h1 {
            font-size: 1.5em;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .title-section p {
            font-size: 0.85em;
            color: #dcdcdc;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-nav {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 22px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-nav:hover {
            background: white;
            color: #601424;
        }

        .btn-salir {
            background: rgba(139, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-salir:hover {
            background: #8B0000;
            color: white;
        }

        .main-card {
            max-width: 1050px;
            margin: 35px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            overflow: hidden;
            border-top: 4px solid var(--color-principal);
        }

        .card-content {
            padding: 35px;
        }

        .section-title {
            color: #2d3142;
            font-size: 1.1em;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .sub-header {
            font-size: 0.8em;
            font-weight: 700;
            color: #888;
            letter-spacing: 1px;
            margin-top: 25px;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group.span-2 {
            grid-column: span 2;
        }

        .input-group label {
            font-size: 0.85em;
            font-weight: 700;
            color: #444;
            margin-bottom: 6px;
        }

        .input-group input, .input-group select {
            padding: 10px 14px;
            background-color: #f8f9fa;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            font-size: 0.9em;
            color: #333;
            outline: none;
            transition: all 0.2s;
        }

        .input-group input:focus, .input-group select:focus {
            background-color: #fff;
            border-color: #601424;
            box-shadow: 0 0 0 3px rgba(96, 20, 36, 0.1);
        }

        .btn-submit-container {
            margin-top: 30px;
        }

        .btn-guardar {
            width: 100%;
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 800;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-guardar:hover {
            background-color: #219653;
        }

        .table-section {
            margin-top: 40px;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e1e4e8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.88em;
        }

        th {
            background-color: #2d3142;
            color: white;
            padding: 14px 12px;
            font-weight: 600;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        .badge-folio {
            color: #8B0000;
            font-weight: 700;
        }

        .status-ok {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 0.8em;
        }

        .status-mantenimiento {
            background-color: #fff3cd;
            color: #856404;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 0.8em;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="logo-section">
            <div class="logo-circle">
                <img src="logodiff.png" alt="DIF Logo" onerror="this.src='logo.png'">
            </div>
            <div class="title-section">
                <h1>ÁREA DE TRANSPORTE ADAPTADO Y RUTAS</h1>
                <p>Control de Unidades, Choferes y Traslados Médicos</p>
            </div>
        </div>
        <div class="nav-buttons">
            <a href="panel.php" class="btn-nav">Inicio</a>
            <a href="cerrar_sesion.php" class="btn-nav btn-salir">Salir</a>
        </div>
    </div>

    <div class="main-card">
        <div class="card-content">
            <h2 class="section-title">REGISTRO DE SERVICIO O RUTA DE TRANSPORTE</h2>

            <form action="transporte.php" method="POST">
                
                <div class="sub-header">1. UNIDAD Y OPERADOR</div>
                <div class="form-grid">
                    <div class="input-group">
                        <label>Unidad / Vehículo</label>
                        <select name="unidad" required>
    <option value="Unidad 01 - Nissan Urvan Adaptada">Unidad 01 - Nissan Urvan Adaptada</option>
    <option value="Unidad 02 - Chevrolet Aveo">Unidad 02 - Chevrolet Aveo</option>
    <option value="Unidad 03 - Ford Transit Custom">Unidad 03 - Ford Transit Custom</option>
    <option value="Unidad 04 - Nissan NP300 Pick-Up">Unidad 04 - Nissan NP300 Pick-Up</option>
    <option value="Unidad 05 - Toyota Avanza">Unidad 05 - Toyota Avanza</option>
    <option value="Unidad 06 - Nissan March">Unidad 06 - Nissan March</option>
</select>
                    </div>

                    <div class="input-group">
                        <label>Chofer / Operador Asignado</label>
                        <input type="text" name="operador" placeholder="Ej. Roberto Trejo Cruz" required autocomplete="off">
                    </div>

                    <div class="input-group">
                        <label>Estado de la Unidad</label>
                        <select name="estatus_mantenimiento" required>
                            <option value="En Operación Optima">En Operación Óptima</option>
                            <option value="Servicio Programado">Servicio Programado</option>
                            <option value="En Mantenimiento Preventivo">En Mantenimiento Preventivo</option>
                        </select>
                    </div>
                </div>

                <div class="sub-header">2. RUTA Y DATOS DEL SERVICIO</div>
                <div class="form-grid">
                    <div class="input-group span-2">
                        <label>Ruta / Destino</label>
                        <input type="text" name="ruta_destino" placeholder="Ej. Cadereyta - CRIT Querétaro / Hospital General" required>
                    </div>

                    <div class="input-group">
                        <label>Tipo de Servicio</label>
                        <select name="tipo_servicio" required>
                            <option value="Traslado Médico Especializado">Traslado Médico Especializado</option>
                            <option value="Ruta Comunitaria Inclusiva">Ruta Comunitaria Inclusiva</option>
                            <option value="Traslado Evento Social / Cultural">Traslado Evento Social / Cultural</option>
                            <option value="Apoyo de Alimentos en Campo">Apoyo de Alimentos en Campo</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Pasajeros / Usuarios Transportados</label>
                        <input type="number" name="pasajeros_pasajeras" min="1" value="1" required>
                    </div>

                    <div class="input-group">
                        <label>Fecha de Realización</label>
                        <input type="date" name="fecha_servicio" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Observaciones / Bitácora</label>
                        <input type="text" name="observaciones" placeholder="Ej. Sin incidentes, retorno 16:00 hrs">
                    </div>
                </div>

                <div class="btn-submit-container">
                    <button type="submit" name="guardar_servicio" class="btn-guardar">GUARDAR REGISTRO DE SERVICIO</button>
                </div>
            </form>

            <div class="table-section">
                <div class="sub-header">3. HISTORIAL DE SERVICIOS Y RUTAS REALIZADAS</div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Unidad</th>
                                <th>Operador</th>
                                <th>Ruta / Destino</th>
                                <th>Usuarios</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado_registros && $resultado_registros->num_rows > 0): ?>
                                <?php while ($row = $resultado_registros->fetch_assoc()): ?>
                                    <tr>
                                        <td><span class="badge-folio"><?php echo $row['folio_servicio']; ?></span></td>
                                        <td><?php echo $row['unidad']; ?></td>
                                        <td><?php echo $row['operador']; ?></td>
                                        <td><?php echo $row['ruta_destino']; ?></td>
                                        <td><strong><?php echo $row['pasajeros_pasajeras']; ?> pers.</strong></td>
                                        <td><?php echo $row['fecha_servicio']; ?></td>
                                        <td>
                                            <?php if ($row['estatus_mantenimiento'] == 'En Operación Optima'): ?>
                                                <span class="status-ok">Óptimo</span>
                                            <?php else: ?>
                                                <span class="status-mantenimiento">Mantenimiento</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #888; padding: 25px;">No hay registros de transporte todavía.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <script>
            Swal.fire({
                icon: '<?php echo $tipoAlerta; ?>',
                title: '<?php echo ($tipoAlerta == "success") ? "¡Éxito!" : "Atención"; ?>',
                text: '<?php echo $mensaje; ?>',
                confirmButtonColor: '#601424'
            });
        </script>
    <?php endif; ?>

</body>
</html>
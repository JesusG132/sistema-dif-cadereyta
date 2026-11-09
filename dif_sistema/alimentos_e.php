<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$mensaje = "";
$tipoAlerta = "";

if (isset($_POST['guardar_apoyo'])) {
    $folio = "DES-E-" . date("Y") . "-" . rand(1000, 9999);
    $comedor = $conexion->real_escape_string($_POST['comedor_escuela']);
    $localidad = $conexion->real_escape_string($_POST['localidad']);
    $tipo_programa = $conexion->real_escape_string($_POST['tipo_programa']);
    $raciones = intval($_POST['raciones_diarias']);
    $fecha = $_POST['fecha_entrega'];
    $observaciones = $conexion->real_escape_string($_POST['observaciones']);
    $usuario_atiende = $_SESSION['usuario'];

    $sql = "INSERT INTO alimentos_calientes (folio_apoyo, comedor_escuela, localidad, tipo_programa, raciones_diarias, fecha_entrega, observaciones, usuario_atiende) 
            VALUES ('$folio', '$comedor', '$localidad', '$tipo_programa', '$raciones', '$fecha', '$observaciones', '$usuario_atiende')";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "¡Registro de insumo caliente guardado con folio $folio!";
        $tipoAlerta = "success";
    } else {
        $mensaje = "Error al registrar: " . $conexion->error;
        $tipoAlerta = "error";
    }
}

$resultado_registros = $conexion->query("SELECT * FROM alimentos_calientes ORDER BY id_entrega DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema DIF - Alimentos E (Calientes)</title>
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
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="logo-section">
            <div class="logo-circle">
                <img src="logodiff.png" alt="DIF Logo" onerror="this.src='logo.png'">
            </div>
            <div class="title-section">
                <h1>ÁREA DE ALIMENTOS E (CALIENTES)</h1>
                <p>Programa de Desayunos y Comedores Comunitarios</p>
            </div>
        </div>
        <div class="nav-buttons">
            <a href="panel.php" class="btn-nav">Inicio</a>
            <a href="cerrar_sesion.php" class="btn-nav btn-salir">Salir</a>
        </div>
    </div>

    <div class="main-card">
        <div class="card-content">
            <h2 class="section-title">REGISTRO DE DOTACIÓN A COMEDOR / ESCUELA</h2>

            <form action="alimentos_e.php" method="POST">
                
                <div class="sub-header">1. UBICACIÓN DEL COMEDOR O PLANTEL</div>
                <div class="form-grid">
                    <div class="input-group span-2">
                        <label>Nombre del Comedor Comunitario / Escuela</label>
                        <input type="text" name="comedor_escuela" placeholder="Ej. Comedor Escolar Primaria Miguel Hidalgo" required autocomplete="off">
                    </div>

                    <div class="input-group">
                        <label>Comunidad / Localidad</label>
                        <select name="localidad" required>
                            <option value="">Seleccionar...</option>
                            <option value="Cadereyta Centro">Cadereyta Centro</option>
                            <option value="Boyé">Boyé</option>
                            <option value="Vizarrón de Montes">Vizarrón de Montes</option>
                            <option value="El Doctor">El Doctor</option>
                            <option value="Tzibanzá">Tzibanzá</option>
                            <option value="La Campana">La Campana</option>
                            <option value="Macavaca">Macavaca</option>
                            <option value="Higuerillas">Higuerillas</option>
                            <option value="San Gaspar">San Gaspar</option>
                        </select>
                    </div>
                </div>

                <div class="sub-header">2. DETALLES DE LAS RACIONES Y PROGRAMA</div>
                <div class="form-grid">
                    <div class="input-group">
                        <label>Tipo de Programa</label>
                        <select name="tipo_programa" required>
                            <option value="Desayuno Escolar Caliente">Desayuno Escolar Caliente</option>
                            <option value="Comedor Comunitario Abierto">Comedor Comunitario Abierto</option>
                            <option value="Insumos para Cocina Escolar">Insumos para Cocina Escolar</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Raciones / Beneficiarios Diarios</label>
                        <input type="number" name="raciones_diarias" min="1" value="50" required>
                    </div>

                    <div class="input-group">
                        <label>Fecha de Entrega de Insumos</label>
                        <input type="date" name="fecha_entrega" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="input-group span-2">
                        <label>Observaciones / Insumos Entregados</label>
                        <input type="text" name="observaciones" placeholder="Ej. Entrega de grano, lácteos y verdura para el mes">
                    </div>
                </div>

                <div class="btn-submit-container">
                    <button type="submit" name="guardar_apoyo" class="btn-guardar">GUARDAR REGISTRO DE DOTACIÓN</button>
                </div>
            </form>

            <div class="table-section">
                <div class="sub-header">3. HISTORIAL DE COMEDORES Y DOTACIONES</div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Comedor / Escuela</th>
                                <th>Localidad</th>
                                <th>Programa</th>
                                <th>Raciones/Día</th>
                                <th>Fecha</th>
                                <th>Atendió</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado_registros && $resultado_registros->num_rows > 0): ?>
                                <?php while ($row = $resultado_registros->fetch_assoc()): ?>
                                    <tr>
                                        <td><span class="badge-folio"><?php echo $row['folio_apoyo']; ?></span></td>
                                        <td><?php echo $row['comedor_escuela']; ?></td>
                                        <td><?php echo $row['localidad']; ?></td>
                                        <td><?php echo $row['tipo_programa']; ?></td>
                                        <td><strong><?php echo $row['raciones_diarias']; ?> hab.</strong></td>
                                        <td><?php echo $row['fecha_entrega']; ?></td>
                                        <td><?php echo $row['usuario_atiende']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #888; padding: 25px;">No hay registros de alimentos calientes todavía.</td>
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
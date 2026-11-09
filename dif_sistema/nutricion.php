<?php
session_start();
include 'conexion.php'; 

// Configuración Regional
date_default_timezone_set('America/Mexico_City');

// 1. Seguridad
if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] != 'Admin' && $_SESSION['rol'] != 'Nutri')) {
    header("Location: index.html");
    exit();
}

// 2. Funciones
function calcular_edad_exacta($fecha_nac) {
    if(empty($fecha_nac)) return "S/D";
    $nac = new DateTime($fecha_nac);
    $hoy = new DateTime();
    $diff = $hoy->diff($nac);
    
    // Lógica para mostrar texto amigable
    if ($diff->y > 0) {
        return $diff->y . " años, " . $diff->m . " meses";
    } elseif ($diff->m > 0) {
        return $diff->m . " meses, " . $diff->d . " días";
    } else {
        return $diff->d . " días";
    }
}

// Lógica de Diagnóstico (SESEQ) - Robusta
function obtener_diagnostico_seseq($peso, $talla_m, $sexo, $conexion) {
    if($talla_m <= 0) return "Sin Datos";
    
    $talla_cm = number_format($talla_m * 100, 1); 
    
    $stmt = $conexion->prepare("SELECT * FROM tabla_referencia_seseq WHERE talla_cm = ? AND sexo = ? LIMIT 1");
    $stmt->bind_param("ds", $talla_cm, $sexo);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    // Si no hay tabla de referencia, calculamos IMC básico como respaldo
    if (!$res) {
        $imc = $peso / ($talla_m * $talla_m);
        // Retornamos un estimado para no dejar "Pendiente"
        if($imc < 18.5) return "Bajo Peso (Est)";
        if($imc > 25) return "Sobrepeso (Est)";
        return "Normal (Est)"; 
    }

    if ($peso < $res['grave_max']) return "Desnutrición Grave";
    if ($peso < $res['moderada_max']) return "Desnutrición Moderada";
    if ($peso < $res['leve_max']) return "Desnutrición Leve";
    if ($peso <= $res['normal_max']) return "Normal";
    if ($peso <= $res['riesgo_max']) return "Normal con Riesgo";
    if ($peso <= $res['sobrepeso_max']) return "Sobrepeso";
    return "Obesidad";
}

// 3. Eliminar
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    $stmt = $conexion->prepare("DELETE FROM nutricion WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    header("Location: nutricion.php"); exit();
}

// 4. Guardar Nuevo Registro (Insertar)
if (isset($_POST['guardar_beneficiario'])) {
    $folio = "DIF-" . date("Y") . "-" . strtoupper(substr(uniqid(), -4));
    $nombre = $_POST['nombre'];
    $sexo = $_POST['sexo'];
    $fecha_nac = $_POST['fecha_nacimiento'];
    // Calculamos edad texto inicial
    $edad_txt = calcular_edad_exacta($fecha_nac);
    
    $peso = floatval($_POST['peso']);
    $talla = floatval($_POST['talla']);
    if($talla > 3) $talla = $talla / 100; 

    $imc = ($talla > 0) ? round($peso / ($talla * $talla), 2) : 0;
    
    // Calculamos diagnóstico AQUÍ MISMO para guardar el correcto
    $imc_estado = obtener_diagnostico_seseq($peso, $talla, $sexo, $conexion);
    
    $municipio = "Cadereyta";

    $sql = "INSERT INTO nutricion (folio, nombre, sexo, fecha_nacimiento, edad, peso, talla, imc, imc_normal, diagnostico, municipio, domicilio, telefono, localidad, tipo_localidad, centro_salud, grupo_vulnerable, mes, tutor, id_usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssddssssssssssssi", 
        $folio, $nombre, $sexo, $fecha_nac, $edad_txt, 
        $peso, $talla, $imc, $imc_estado, $_POST['diagnostico'], 
        $municipio, $_POST['domicilio'], $_POST['telefono'], $_POST['localidad'], $_POST['tipo_localidad'], 
        $_POST['centro_salud'], $_POST['grupo_vulnerable'], $_POST['mes'], $_POST['tutor'], $_SESSION['id_usuario']
    );
    
    if($stmt->execute()) $_SESSION['msg'] = "Registro guardado. Estado: " . $imc_estado;
    header("Location: nutricion.php"); exit();
}

// 5. Listado
$busqueda = isset($_GET['q']) ? "%".$_GET['q']."%" : "%";
// Obtenemos el ÚLTIMO registro de cada folio para mostrar el estado actual
$sql = "SELECT * FROM nutricion WHERE id IN (SELECT MAX(id) FROM nutricion GROUP BY folio) AND nombre LIKE ? ORDER BY id DESC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $busqueda);
$stmt->execute();
$registros = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema DIF - Nutrición</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            /* PALETA VINO ELEGANTE */
            --primary: #6d1b2b;       
            --secondary: #2c3e50;     
            --bg-color: #f0f2f5;      
            --text-dark: #333;
            --white: #ffffff;
        }

        body { font-family: 'Montserrat', sans-serif; background-color: var(--bg-color); margin: 0; padding: 0; color: var(--text-dark); }

        /* HEADER */
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

        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }

        /* TARJETA FORMULARIO */
        .card-form {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary);
        }

        .card-header {
            background: #fcfcfc;
            padding: 15px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-header h2 { margin: 0; color: var(--secondary); font-size: 1.1em; text-transform: uppercase; letter-spacing: 0.5px; }

        .card-body { padding: 25px 30px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }

        .form-group label { display: block; font-size: 0.8em; font-weight: 700; color: #555; margin-bottom: 8px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #e1e1e1; border-radius: 6px; background: #f8f9fa; font-size: 0.9em; transition: 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
            background: white; border-color: var(--primary); outline: none;
        }

        .section-label {
            grid-column: 1 / -1; font-size: 0.75em; color: #888; text-transform: uppercase;
            letter-spacing: 1px; border-bottom: 1px solid #eee; margin: 15px 0 10px 0; padding-bottom: 5px; font-weight: 600;
        }

        .btn-submit {
            grid-column: 1 / -1; background: #27ae60; color: white; border: none; padding: 15px;
            border-radius: 8px; font-size: 1em; font-weight: bold; cursor: pointer; transition: all 0.3s;
            margin-top: 15px; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-submit:hover { background: #219150; transform: translateY(-2px); }

        /* TABLA */
        .search-bar { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-input { flex: 1; padding: 12px 20px; border-radius: 30px; border: 1px solid #ddd; outline: none; }
        .search-btn { padding: 0 25px; border-radius: 30px; background: var(--secondary); color: white; border: none; font-weight: bold; cursor: pointer; }

        .table-container { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
        .table-custom { width: 100%; border-collapse: collapse; }
        .table-custom th { background: var(--secondary); color: white; padding: 15px; text-align: left; font-size: 0.85em; font-weight: 600; }
        .table-custom td { padding: 15px; border-bottom: 1px solid #f0f0f0; color: #555; font-size: 0.9em; vertical-align: middle; }
        .table-custom tr:hover { background-color: #fbfbfb; }

        .badge { padding: 5px 10px; border-radius: 6px; font-size: 0.75em; font-weight: 700; display: inline-block; }
        .acciones { display: flex; gap: 5px; }
        .btn-icon { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; text-decoration: none; transition: 0.2s; font-size: 0.8em; }
        .btn-icon:hover { transform: scale(1.1); }
        
    </style>
</head>
<body>

    <nav class="header-principal">
        <div class="header-brand">
            <img src="logodiff.png" alt="Logo" class="logo-img">
            <div class="header-title">
                <h1>Área de Nutrición</h1>
                <span>Control de Beneficiarios</span>
            </div>
        </div>
        <div>
            <a href="panel.php" class="btn-logout">Inicio</a>
            <a href="cerrar_sesion.php" class="btn-logout" style="background:rgba(220, 53, 69, 0.2); border-color:rgba(220, 53, 69, 0.4);">Salir</a>
        </div>
    </nav>

    <div class="container">
        
        <?php if(isset($_SESSION['msg'])): ?>
            <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #2e7d32;">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="card-form">
            <div class="card-header">
                <h2>Nuevo Registro</h2>
            </div>
            <form action="nutricion.php" method="POST" class="card-body">
                <div class="form-grid">
                    
                    <div class="section-label">1. Datos Personales</div>
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" placeholder="Ej. Juan Pérez" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="sexo" required>
                            <option value="">Seleccionar...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tutor</label>
                        <input type="text" name="tutor" placeholder="Responsable">
                    </div>

                    <div class="section-label">2. Ubicación</div>
                    <div class="form-group">
                        <label>Localidad</label>
                        <input type="text" name="localidad" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo Localidad</label>
                        <select name="tipo_localidad" required>
                            <option value="Rural">Rural</option>
                            <option value="Urbana">Urbana</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Domicilio</label>
                        <input type="text" name="domicilio" placeholder="Calle y número">
                    </div>
                    <div class="form-group">
                        <label>Grupo Vulnerable</label>
                        <select name="grupo_vulnerable">
                            <option value="6 a 24 meses">6 a 24 meses</option>
                            <option value="2 a 5 años">2 a 5 años</option>
                            <option value="6 a 17 años">6 a 17 años</option>
                            <option value="Embarazadas lactarias">Embarazadas lactarias</option>
                            <option value="Discapacidad">Discapacidad</option>
                        </select>
                    </div>

                    <div class="section-label">3. Evaluación Médica</div>
                    <div class="form-group">
                        <label>Centro de Salud</label>
                        <input type="text" name="centro_salud">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" maxlength="10">
                    </div>
                    <div class="form-group" style="background:#fff8e1; padding:5px 10px; border-radius:6px;">
                        <label style="color:#f57f17;">Peso (kg)</label>
                        <input type="number" name="peso" step="0.001" placeholder="0.000" required>
                    </div>
                    <div class="form-group" style="background:#fff8e1; padding:5px 10px; border-radius:6px;">
                        <label style="color:#f57f17;">Talla (metros)</label>
                        <input type="number" name="talla" step="0.01" placeholder="Ej. 1.20" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Mes de Registro</label>
                        <select name="mes" required>
                            <?php 
                            $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                            $actual = date('n'); $anio = date('Y');
                            foreach($meses as $k => $m) {
                                $val = "$m $anio";
                                $sel = ($k + 1 == $actual) ? "selected" : "";
                                echo "<option value='$val' $sel>$val</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Observaciones</label>
                        <textarea name="diagnostico" rows="2" placeholder="Notas adicionales..."></textarea>
                    </div>

                    <button type="submit" name="guardar_beneficiario" class="btn-submit">Guardar Registro</button>
                </div>
            </form>
        </div>

        <form method="GET" class="search-bar">
            <input type="text" name="q" class="search-input" placeholder="Buscar por nombre o folio...">
            <button type="submit" class="search-btn">Buscar</button>
        </form>

        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Beneficiario</th>
                        <th>Edad Actual</th>
                        <th>Peso / Talla</th>
                        <th>Diagnóstico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($f = $registros->fetch_assoc()): 
                        // CÁLCULO DE EDAD EN TIEMPO REAL
                        $edad_real = calcular_edad_exacta($f['fecha_nacimiento']);

                        // CÁLCULO DE DIAGNÓSTICO EN TIEMPO REAL (Si dice Pendiente)
                        $st = $f['imc_normal'];
                        if($st == 'Pendiente' || empty($st)) {
                            // Si en la BD no se guardó, lo calculamos ahora mismo
                            $st = obtener_diagnostico_seseq($f['peso'], $f['talla'], $f['sexo'], $conexion);
                        }

                        // Colores
                        $bg = "#e0e0e0"; $fg = "#333";
                        if(strpos($st, 'Normal') !== false) { $bg = "#d4edda"; $fg = "#155724"; } 
                        elseif(strpos($st, 'Leve') !== false) { $bg = "#fff3cd"; $fg = "#856404"; } 
                        elseif(strpos($st, 'Moderada') !== false) { $bg = "#ffecb5"; $fg = "#d39e00"; } 
                        elseif(strpos($st, 'Grave') !== false || strpos($st, 'Obesidad') !== false) { $bg = "#f8d7da"; $fg = "#721c24"; } 
                        elseif(strpos($st, 'Sobrepeso') !== false) { $bg = "#f1aeb5"; $fg = "#721c24"; }
                    ?>
                    <tr>
                        <td style="color:var(--primary); font-weight:700;"><?php echo $f['folio']; ?></td>
                        <td style="text-transform: capitalize; font-weight:600;"><?php echo $f['nombre']; ?></td>
                        <td><?php echo $edad_real; ?></td>
                        <td><?php echo $f['peso']."kg / ".$f['talla']."m"; ?></td>
                        <td>
                            <span class="badge" style="background:<?php echo $bg; ?>; color:<?php echo $fg; ?>;">
                                <?php echo $st; ?>
                            </span>
                        </td>
                        <td class="acciones">
                            <a href="nuevo_mes.php?id=<?php echo $f['id']; ?>" class="btn-icon" style="background:#3498db;" title="Nuevo Mes">+M</a>
                            <a href="historial.php?id=<?php echo $f['id']; ?>" class="btn-icon" style="background:#27ae60;" title="Historial">H</a>
                            <a href="editar.php?id=<?php echo $f['id']; ?>" class="btn-icon" style="background:#f39c12;" title="Editar">E</a>
                            <a href="nutricion.php?accion=eliminar&id=<?php echo $f['id']; ?>" class="btn-icon" style="background:#c0392b;" onclick="return confirm('¿Eliminar?')" title="Eliminar">X</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
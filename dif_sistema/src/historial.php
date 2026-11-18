<?php
session_start();
include 'conexion.php'; 

date_default_timezone_set('America/Mexico_City');

if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

// --- FUNCIÓN PARA CALCULAR EDAD EXACTA ---
function calcular_edad_exacta($fecha_nac) {
    if(empty($fecha_nac)) return "S/D";
    $nac = new DateTime($fecha_nac);
    $hoy = new DateTime();
    $diff = $hoy->diff($nac);
    
    if ($diff->y > 0) {
        return $diff->y . " AÑOS, " . $diff->m . " MESES";
    } elseif ($diff->m > 0) {
        return $diff->m . " MESES, " . $diff->d . " DÍAS";
    } else {
        return $diff->d . " DÍAS";
    }
}

// --- FUNCIÓN DIAGNÓSTICO ---
function obtener_diagnostico_historial($peso, $talla_m, $sexo, $conexion) {
    if($talla_m <= 0) return "Sin Datos";
    $talla_cm = number_format($talla_m * 100, 1); 
    
    $stmt = $conexion->prepare("SELECT * FROM tabla_referencia_seseq WHERE talla_cm = ? AND sexo = ? LIMIT 1");
    $stmt->bind_param("ds", $talla_cm, $sexo);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (!$res) {
        $imc = $peso / ($talla_m * $talla_m);
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

$datos_beneficiario = null;
$primer_registro_fecha = "No registrado";
$historial_array = []; 
$edad_actual_calculada = "";

if (isset($_GET['id'])) {
    $id_base = intval($_GET['id']);
    
    $stmt_base = $conexion->prepare("SELECT * FROM nutricion WHERE id = ?");
    $stmt_base->bind_param("i", $id_base);
    $stmt_base->execute();
    $res_base = $stmt_base->get_result();

    if ($res_base->num_rows === 1) {
        $datos_beneficiario = $res_base->fetch_assoc();
        $folio = $datos_beneficiario['folio'];
        $sexo_paciente = $datos_beneficiario['sexo'];
        
        $edad_actual_calculada = calcular_edad_exacta($datos_beneficiario['fecha_nacimiento']);
        
        $stmt_primero = $conexion->prepare("SELECT mes FROM nutricion WHERE folio = ? ORDER BY id ASC LIMIT 1");
        $stmt_primero->bind_param("s", $folio);
        $stmt_primero->execute();
        $res_primero = $stmt_primero->get_result()->fetch_assoc();
        if ($res_primero) {
            $primer_registro_fecha = $res_primero['mes'];
        }

        $stmt_historial = $conexion->prepare("SELECT * FROM nutricion WHERE folio = ? ORDER BY id DESC");
        $stmt_historial->bind_param("s", $folio);
        $stmt_historial->execute();
        $res_historial = $stmt_historial->get_result();
        
        while($fila = $res_historial->fetch_assoc()) {
            $historial_array[] = $fila;
        }

    } else {
        header("Location: nutricion.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial - <?php echo htmlspecialchars($datos_beneficiario['nombre']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #6d1b2b;       
            --secondary: #2c3e50;     
            --bg-color: #f0f2f5;
            --white: #ffffff;
            --text-color: #333;
        }

        body { font-family: 'Montserrat', sans-serif; background-color: var(--bg-color); margin: 0; padding: 0; color: var(--text-color); }

        .header-principal {
            background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 15px 40px; display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15); color: white;
        }
        .header-brand { display: flex; align-items: center; gap: 20px; }
        .logo-img {
            height: 65px; width: 65px; object-fit: contain; background: white; border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.9); box-shadow: 0 0 20px rgba(0,0,0,0.2); padding: 3px;
        }
        .header-title h1 { margin: 0; font-size: 1.4em; font-weight: 700; text-transform: uppercase; }
        
        .btn-back {
            background: rgba(255,255,255,0.15); color: white; text-decoration: none; padding: 10px 25px;
            border-radius: 30px; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s; font-size: 0.85em;
        }
        .btn-back:hover { background: white; color: var(--primary); font-weight: bold; }

        .container { width: 95%; max-width: 1300px; margin: 30px auto; padding: 0 20px; }

        .encabezado-oficial { text-align: center; margin-bottom: 30px; color: #333; display: none; }
        .encabezado-oficial h2 { font-size: 1.2em; margin: 0; font-weight: 800; text-transform: uppercase; }
        .encabezado-oficial h3 { font-size: 1em; margin: 2px 0; font-weight: 500; }
        .reporte-titulo { margin-top: 15px; font-weight: 700; text-decoration: underline; font-size: 0.9em; }

        /* --- CORRECCIÓN DE LA TARJETA DE INFORMACIÓN (GRID 4 COLUMNAS) --- */
        .info-card {
            background: white; border-radius: 12px; padding: 25px; margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 6px solid var(--primary);
            
            /* AQUÍ ESTÁ EL TRUCO: 4 Columnas exactas */
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 20px; 
            align-items: start;
        }
        
        /* Nombre ocupa toda la fila de arriba */
        .nombre-grande {
            grid-column: 1 / -1; 
            font-size: 1.8em; color: var(--primary); font-weight: 800; text-transform: uppercase; 
            text-align: center; margin-bottom: 10px; border-bottom: 2px solid #eee; padding-bottom: 10px;
        }

        /* Estilos de los datos */
        .info-item { font-size: 0.9em; color: #555; overflow: hidden; }
        .info-item strong { 
            color: var(--secondary); display: block; margin-bottom: 5px; 
            font-weight: 700; text-transform: uppercase; font-size: 0.75em; letter-spacing: 0.5px;
        }
        .dato-mayus { 
            text-transform: uppercase; font-weight: 600; color: #000; font-size: 1.05em; display: block;
        }

        /* Clases para unir columnas si el texto es largo */
        .span-2 { grid-column: span 2; }
        .span-4 { grid-column: 1 / -1; }

        /* TABLA */
        .tabla-container { background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        .tabla-historial { width: 100%; border-collapse: collapse; }
        .tabla-historial th { background: var(--secondary); color: white; padding: 15px; font-size: 0.85em; text-transform: uppercase; letter-spacing: 0.5px; }
        .tabla-historial td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; font-size: 0.9em; vertical-align: middle; }
        .tabla-historial tr:hover { background-color: #f9f9f9; }

        .badge-mes { background: #f0f0f0; padding: 6px 10px; border-radius: 6px; font-weight: 700; font-size: 0.85em; color: #444; }
        .dif-pos { color: #c0392b; font-weight: 700; }
        .dif-neg { color: #27ae60; font-weight: 700; }
        .dif-neu { color: #95a5a6; }
        .txt-normal { color: #27ae60; font-weight: 800; text-transform: uppercase; }
        .txt-alerta { color: #f39c12; font-weight: 800; text-transform: uppercase; }
        .txt-grave { color: #c0392b; font-weight: 800; text-transform: uppercase; }

        .btn-print {
            padding: 12px 25px; background: var(--primary); color: white; border: none; border-radius: 30px;
            font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(109, 27, 43, 0.3);
        }
        .btn-print:hover { transform: translateY(-2px); background: #5a1622; }

        @media (max-width: 900px) {
            .info-card { grid-template-columns: repeat(2, 1fr); }
            .span-2 { grid-column: span 1; }
        }

        @media print {
            @page { margin: 1cm; size: landscape; }
            body { background: white; font-size: 12px; -webkit-print-color-adjust: exact; }
            .header-principal, .btn-print, .btn-back { display: none !important; }
            .container { width: 100%; max-width: 100%; margin: 0; padding: 0; box-shadow: none; }
            .encabezado-oficial { display: block; margin-bottom: 20px; }
            .info-card { 
                box-shadow: none; border: 1px solid #ccc; border-left: 5px solid var(--primary) !important; 
                padding: 15px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;
            }
            .nombre-grande { font-size: 1.5em; border-bottom: 1px solid #ccc; margin-bottom: 15px; }
            .tabla-container { box-shadow: none; border-radius: 0; }
            .tabla-historial th { background-color: var(--primary) !important; color: white !important; padding: 8px; }
            .tabla-historial td { padding: 8px; border-bottom: 1px solid #ddd; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    <nav class="header-principal">
        <div class="header-brand">
            <img src="logodiff.png" alt="Logo" class="logo-img">
            <div class="header-title">
                <h1>Área de Nutrición</h1>
                <span>Historial Clínico</span>
            </div>
        </div>
        <a href="nutricion.php" class="btn-back">Volver</a>
    </nav>

    <div class="container">
        
        <div class="encabezado-oficial">
            <img src="logodiff.png" style="height: 50px; float: left;">
            <img src="logodiff.png" style="height: 50px; float: right; opacity: 0;">
            <h2>SISTEMA MUNICIPAL DIF CADEREYTA</h2>
            <h3>COORDINACIÓN DE PROGRAMAS ALIMENTARIOS</h3>
            <h3>ÁREA DE ORIENTACIÓN ALIMENTARIA</h3>
            <div class="reporte-titulo">REPORTE DE SEGUIMIENTO DE BENEFICIARIOS (PADRÓN DE CAMPO)<br>
            NIÑOS, NIÑAS Y ADOLESCENTES DE 6 MESES A 17 AÑOS 11 MESES</div>
        </div>

        <div class="info-card">
            <div class="nombre-grande"><?php echo strtoupper($datos_beneficiario['nombre']); ?></div>
            
            <div class="info-item"><strong>Folio</strong> <span class="dato-mayus"><?php echo $datos_beneficiario['folio']; ?></span></div>
            <div class="info-item"><strong>Fecha Nac.</strong> <span class="dato-mayus"><?php echo $datos_beneficiario['fecha_nacimiento']; ?></span></div>
            <div class="info-item"><strong>Edad Actual</strong> <span class="dato-mayus" style="color:var(--primary); font-weight:800;"><?php echo $edad_actual_calculada; ?></span></div>
            <div class="info-item"><strong>Sexo</strong> <span class="dato-mayus"><?php echo strtoupper($datos_beneficiario['sexo']); ?></span></div>

            <div class="info-item"><strong>Localidad</strong> <span class="dato-mayus"><?php echo strtoupper($datos_beneficiario['localidad']); ?></span></div>
            <div class="info-item"><strong>Centro Salud</strong> <span class="dato-mayus"><?php echo strtoupper($datos_beneficiario['centro_salud']); ?></span></div>
            <div class="info-item"><strong>Grupo Vuln.</strong> <span class="dato-mayus"><?php echo strtoupper($datos_beneficiario['grupo_vulnerable']); ?></span></div>
            <div class="info-item"><strong>Teléfono</strong> <span class="dato-mayus"><?php echo $datos_beneficiario['telefono']; ?></span></div>
            
            <div class="info-item"><strong>Inicio Registro</strong> <span class="dato-mayus"><?php echo $primer_registro_fecha; ?></span></div>
            <div class="info-item"><strong>Atendido Por</strong> <span class="dato-mayus"><?php echo strtoupper($_SESSION['usuario']); ?></span></div>
            <div class="info-item span-2"><strong>Domicilio</strong> <span class="dato-mayus"><?php echo strtoupper($datos_beneficiario['domicilio']); ?></span></div>
        </div>

        <div class="tabla-container">
            <table class="tabla-historial">
                <thead>
                    <tr>
                        <th>Mes Eval.</th>
                        <th>Peso (kg)</th>
                        <th>Talla (m)</th>
                        <th>IMC</th>
                        <th>Dif. Peso</th>
                        <th>Dif. Talla</th>
                        <th>Dif. IMC</th>
                        <th>Diagnóstico</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($historial_array as $i => $row): 
                        $peso_act = floatval($row['peso']);
                        $talla_act = floatval($row['talla']);
                        if($talla_act > 3) $talla_act = $talla_act / 100;
                        $imc_act = ($talla_act > 0) ? round($peso_act / ($talla_act * $talla_act), 2) : 0;

                        $estado_final = $row['imc_normal'];
                        if (empty($estado_final) || $estado_final == 'Pendiente') {
                            $estado_final = obtener_diagnostico_historial($peso_act, $talla_act, $sexo_paciente, $conexion);
                        }

                        $dif_peso = 0; $dif_talla = 0; $dif_imc = 0;
                        if (isset($historial_array[$i + 1])) {
                            $row_ant = $historial_array[$i + 1];
                            $peso_ant = floatval($row_ant['peso']);
                            $talla_ant = floatval($row_ant['talla']);
                            if($talla_ant > 3) $talla_ant = $talla_ant / 100;
                            $imc_ant = ($talla_ant > 0) ? round($peso_ant / ($talla_ant * $talla_ant), 2) : 0;

                            $dif_peso = $peso_act - $peso_ant;
                            $dif_talla = $talla_act - $talla_ant;
                            $dif_imc = $imc_act - $imc_ant;
                        }
                    ?>
                    <tr>
                        <td><span class="badge-mes"><?php echo $row['mes']; ?></span></td>
                        <td><?php echo number_format($peso_act, 3); ?></td>
                        <td><?php echo number_format($talla_act, 2); ?></td>
                        <td style="font-weight:700;"><?php echo number_format($imc_act, 2); ?></td>
                        
                        <td class="<?php echo ($dif_peso > 0) ? 'dif-pos' : (($dif_peso < 0) ? 'dif-neg' : 'dif-neu'); ?>">
                            <?php echo ($dif_peso > 0 ? '+' : '') . number_format($dif_peso, 2); ?>
                        </td>
                        <td class="dif-neu">
                            <?php echo ($dif_talla > 0 ? '+' : '') . number_format($dif_talla, 2); ?>
                        </td>
                        <td class="<?php echo ($dif_imc > 0) ? 'dif-pos' : (($dif_imc < 0) ? 'dif-neg' : 'dif-neu'); ?>">
                            <?php echo ($dif_imc > 0 ? '+' : '') . number_format($dif_imc, 2); ?>
                        </td>
                        
                        <td>
                            <?php 
                            $clase_dx = 'dif-neu';
                            if (stripos($estado_final, 'Normal') !== false) $clase_dx = 'txt-normal';
                            elseif (stripos($estado_final, 'Sobrepeso') !== false || stripos($estado_final, 'Riesgo') !== false) $clase_dx = 'txt-alerta';
                            elseif (stripos($estado_final, 'Obesidad') !== false || stripos($estado_final, 'Grave') !== false) $clase_dx = 'txt-grave';
                            ?>
                            <span class="<?php echo $clase_dx; ?>"><?php echo strtoupper($estado_final); ?></span>
                        </td>
                        
                        <td style="font-size: 0.8em; color: #555; text-transform: uppercase;"><?php echo htmlspecialchars($row['diagnostico']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()" class="btn-print">🖨️ IMPRIMIR REPORTE OFICIAL</button>
        </div>
    </div>

</body>
</html>
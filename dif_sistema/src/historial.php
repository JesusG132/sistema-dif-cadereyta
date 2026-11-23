<?php
session_start();
include 'conexion.php';

date_default_timezone_set('America/Mexico_City');

if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: nutricion.php");
    exit();
}

// --- FUNCIÓN PARA CALCULAR EDAD EXACTA ---
function calcular_edad_exacta($fecha_nac) {
    if (empty($fecha_nac)) return "S/D";
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
    if ($talla_m <= 0) return "Sin Datos";
    $talla_cm = number_format($talla_m * 100, 1);

    $stmt = $conexion->prepare("SELECT * FROM tabla_referencia_seseq WHERE talla_cm = ? AND sexo = ? LIMIT 1");
    $stmt->bind_param("ds", $talla_cm, $sexo);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (!$res) {
        $imc = $peso / ($talla_m * $talla_m);
        if ($imc < 18.5) return "Bajo Peso (Est)";
        if ($imc > 25) return "Sobrepeso (Est)";
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

$id_base = intval($_GET['id']);
$datos_beneficiario = null;
$primer_registro_fecha = "No registrado";
$historial_array = [];
$edad_actual_calculada = "";

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

    while ($fila = $res_historial->fetch_assoc()) {
        $historial_array[] = $fila;
    }
} else {
    header("Location: nutricion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial - <?php echo htmlspecialchars($datos_beneficiario['nombre']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #6d1b2b;
            --secondary: #2c3e50;
            --bg-color: #f0f2f5;
            --white: #ffffff;
            --text-color: #2b2b2b;
            --border-formal: #cbd5e1;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }

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

        .encabezado-oficial {
            text-align: center;
            margin-bottom: 18px;
            color: #1e293b;
            display: none;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }
        .encabezado-oficial h2 { font-size: 1.15em; margin: 0; font-weight: 800; text-transform: uppercase; color: var(--primary); }
        .encabezado-oficial h3 { font-size: 0.85em; margin: 2px 0; font-weight: 600; color: #334155; }
        .reporte-titulo {
            margin-top: 8px;
            font-weight: 700;
            font-size: 0.8em;
            line-height: 1.35;
            color: #0f172a;
        }

        /* --- FICHA DE BENEFICIARIO --- */
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 22px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px 20px;
            align-items: start;
        }

        .nombre-grande {
            grid-column: 1 / -1;
            font-size: 1.45em;
            color: var(--primary);
            font-weight: 800;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 6px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .info-item { font-size: 0.88em; color: #475569; overflow: hidden; }
        .info-item strong {
            color: var(--secondary);
            display: block;
            margin-bottom: 3px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7em;
            letter-spacing: 0.5px;
        }
        .dato-mayus {
            text-transform: uppercase;
            font-weight: 600;
            color: #0f172a;
            font-size: 0.96em;
            display: block;
            word-wrap: break-word;
        }

        .span-2 { grid-column: span 2; }

        /* --- TABLA DE HISTORIAL --- */
        .tabla-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .tabla-historial { width: 100%; border-collapse: collapse; }
        .tabla-historial th {
            background: var(--secondary);
            color: white;
            padding: 12px 10px;
            font-size: 0.78em;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .tabla-historial td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            font-size: 0.85em;
            vertical-align: middle;
        }
        .tabla-historial tr:hover { background-color: #f8fafc; }

        .badge-mes {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.8em;
            color: #334155;
            white-space: nowrap;
        }
        .dif-pos { color: #b91c1c; font-weight: 700; }
        .dif-neg { color: #15803d; font-weight: 700; }
        .dif-neu { color: #64748b; }
        .txt-normal { color: #15803d; font-weight: 800; text-transform: uppercase; }
        .txt-alerta { color: #b45309; font-weight: 800; text-transform: uppercase; }
        .txt-grave { color: #b91c1c; font-weight: 800; text-transform: uppercase; }

        /* --- BLOQUE DE FIRMAS --- */
        .bloque-firmas {
            display: none;
            margin-top: 32px;
            padding-top: 12px;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        .grid-firmas {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            text-align: center;
            margin-top: 28px;
        }
        .linea-firma {
            border-top: 1px solid #334155;
            padding-top: 6px;
            font-size: 0.78em;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
        }
        .sub-firma {
            display: block;
            font-size: 0.72em;
            font-weight: 500;
            color: #64748b;
            margin-top: 2px;
            text-transform: none;
        }

        .acciones-pie {
            margin-top: 26px;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-print {
            padding: 12px 26px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(109, 27, 43, 0.3);
        }
        .btn-print:hover { transform: translateY(-2px); background: #5a1622; }

        @media (max-width: 900px) {
            .info-card { grid-template-columns: repeat(2, 1fr); }
            .span-2 { grid-column: span 1; }
        }

        /* --- ESTILOS PARA IMPRESIÓN Y MULTIPÁGINA (PDF) --- */
        @media print {
            @page {
                size: letter landscape;
                margin: 12mm;
            }

            body {
                background: #ffffff !important;
                font-size: 10.5pt;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .header-principal,
            .acciones-pie,
            .btn-back {
                display: none !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .encabezado-oficial {
                display: block !important;
                margin-bottom: 12px;
                padding-bottom: 6px;
            }

            .info-card {
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                border-left: 4px solid var(--primary) !important;
                border-radius: 4px !important;
                padding: 10px 14px !important;
                margin-bottom: 14px !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 8px 14px !important;
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .nombre-grande {
                font-size: 1.15em !important;
                margin-bottom: 4px !important;
                padding-bottom: 5px !important;
                border-bottom: 1px solid #cbd5e1 !important;
            }

            .info-item strong { font-size: 0.65em !important; margin-bottom: 1px !important; }
            .dato-mayus { font-size: 0.85em !important; }
            .span-2 { grid-column: span 2 !important; }

            .tabla-container {
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                border-radius: 4px !important;
                overflow: visible !important;
            }

            /* Repite la cabecera de la tabla en cada hoja (2, 3, etc.) */
            .tabla-historial thead {
                display: table-header-group;
            }

            .tabla-historial th {
                background-color: var(--primary) !important;
                color: #ffffff !important;
                padding: 6px 6px !important;
                font-size: 0.72em !important;
                border: 1px solid #6d1b2b;
            }

            .tabla-historial td {
                padding: 5px 6px !important;
                font-size: 0.76em !important;
                border: 1px solid #cbd5e1;
            }

            /* Evita que una fila quede partida entre dos páginas */
            .tabla-historial tr {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .bloque-firmas {
                display: block !important;
                margin-top: 18px !important;
            }
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
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <img src="logodiff.png" style="height: 46px; width: auto;" alt="Logo DIF">
                <div style="flex: 1;">
                    <h2>SISTEMA MUNICIPAL DIF CADEREYTA</h2>
                    <h3>COORDINACIÓN DE PROGRAMAS ALIMENTARIOS</h3>
                    <h3>ÁREA DE ORIENTACIÓN ALIMENTARIA</h3>
                </div>
                <img src="logodiff.png" style="height: 46px; width: auto; opacity: 0;" alt="">
            </div>
            <div class="reporte-titulo">
                REPORTE DE SEGUIMIENTO DE BENEFICIARIOS (PADRÓN DE CAMPO)<br>
                NIÑOS, NIÑAS Y ADOLESCENTES DE 6 MESES A 17 AÑOS 11 MESES
            </div>
        </div>

        <div class="info-card">
            <div class="nombre-grande"><?php echo htmlspecialchars(strtoupper($datos_beneficiario['nombre'])); ?></div>

            <div class="info-item"><strong>Folio</strong> <span class="dato-mayus"><?php echo htmlspecialchars($datos_beneficiario['folio']); ?></span></div>
            <div class="info-item"><strong>Fecha Nac.</strong> <span class="dato-mayus"><?php echo htmlspecialchars($datos_beneficiario['fecha_nacimiento']); ?></span></div>
            <div class="info-item"><strong>Edad Actual</strong> <span class="dato-mayus" style="color:var(--primary); font-weight:800;"><?php echo htmlspecialchars($edad_actual_calculada); ?></span></div>
            <div class="info-item"><strong>Sexo</strong> <span class="dato-mayus"><?php echo htmlspecialchars(strtoupper($datos_beneficiario['sexo'])); ?></span></div>

            <div class="info-item"><strong>Localidad</strong> <span class="dato-mayus"><?php echo htmlspecialchars(strtoupper($datos_beneficiario['localidad'])); ?></span></div>
            <div class="info-item"><strong>Centro Salud</strong> <span class="dato-mayus"><?php echo htmlspecialchars(strtoupper($datos_beneficiario['centro_salud'])); ?></span></div>
            <div class="info-item"><strong>Grupo Vuln.</strong> <span class="dato-mayus"><?php echo htmlspecialchars(strtoupper($datos_beneficiario['grupo_vulnerable'])); ?></span></div>
            <div class="info-item"><strong>Teléfono</strong> <span class="dato-mayus"><?php echo htmlspecialchars($datos_beneficiario['telefono']); ?></span></div>

            <div class="info-item"><strong>Inicio Registro</strong> <span class="dato-mayus"><?php echo htmlspecialchars($primer_registro_fecha); ?></span></div>
            <div class="info-item"><strong>Atendido Por</strong> <span class="dato-mayus"><?php echo htmlspecialchars(strtoupper($_SESSION['usuario'])); ?></span></div>
            <div class="info-item span-2"><strong>Domicilio</strong> <span class="dato-mayus"><?php echo htmlspecialchars(strtoupper($datos_beneficiario['domicilio'])); ?></span></div>
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
                        if ($talla_act > 3) $talla_act = $talla_act / 100;
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
                            if ($talla_ant > 3) $talla_ant = $talla_ant / 100;
                            $imc_ant = ($talla_ant > 0) ? round($peso_ant / ($talla_ant * $talla_ant), 2) : 0;

                            $dif_peso = $peso_act - $peso_ant;
                            $dif_talla = $talla_act - $talla_ant;
                            $dif_imc = $imc_act - $imc_ant;
                        }
                    ?>
                    <tr>
                        <td><span class="badge-mes"><?php echo htmlspecialchars($row['mes']); ?></span></td>
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
                            <span class="<?php echo $clase_dx; ?>"><?php echo htmlspecialchars(strtoupper($estado_final)); ?></span>
                        </td>

                        <td style="font-size: 0.8em; color: #475569; text-transform: uppercase;"><?php echo htmlspecialchars($row['diagnostico']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bloque-firmas">
            <div class="grid-firmas">
                <div class="linea-firma">
                    Elaboró / Área de Nutrición
                    <span class="sub-firma">Nombre y firma</span>
                </div>
                <div class="linea-firma">
                    Tutor o Responsable del Beneficiario
                    <span class="sub-firma">Nombre y firma</span>
                </div>
                <div class="linea-firma">
                    Coordinación de Programas Alimentarios
                    <span class="sub-firma">Vo. Bo. y sello institucional</span>
                </div>
            </div>
        </div>

        <div class="acciones-pie">
            <button onclick="window.print()" class="btn-print">🖨️ IMPRIMIR REPORTE OFICIAL</button>
        </div>
    </div>

</body>
</html>
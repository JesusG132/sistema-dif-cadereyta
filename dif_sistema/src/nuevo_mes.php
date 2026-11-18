<?php
session_start();
include 'conexion.php'; 

date_default_timezone_set('America/Mexico_City');

if (!isset($_SESSION['usuario'])) { header("Location: index.html"); exit(); }

// --- FUNCIÓN DE DIAGNÓSTICO INTELIGENTE ---
function obtener_diagnostico_nuevo_mes($peso, $talla_m, $sexo, $conexion) {
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

// Cargar datos del beneficiario anterior
$id_anterior = isset($_GET['id']) ? intval($_GET['id']) : 0;
$datos = null;

if ($id_anterior > 0) {
    $stmt = $conexion->prepare("SELECT * FROM nutricion WHERE id = ?");
    $stmt->bind_param("i", $id_anterior);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
}

// PROCESAR EL GUARDADO
if (isset($_POST['guardar_evaluacion'])) {
    $peso = floatval($_POST['peso']); 
    $talla = floatval($_POST['talla']);
    if($talla > 3) $talla = $talla / 100; 

    $imc = ($talla > 0) ? round($peso / ($talla * $talla), 2) : 0;
    
    $diagnostico_auto = obtener_diagnostico_nuevo_mes($peso, $talla, $_POST['sexo'], $conexion);
    $edad_guardar = isset($_POST['edad_txt']) ? $_POST['edad_txt'] : "No calculada";

    $sql = "INSERT INTO nutricion (folio, nombre, sexo, fecha_nacimiento, edad, peso, talla, imc, imc_normal, diagnostico, municipio, localidad, tipo_localidad, centro_salud, grupo_vulnerable, domicilio, telefono, mes, tutor, id_usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Cadereyta', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
    $stmt = $conexion->prepare($sql);
    
    $stmt->bind_param("sssssddsssssssssssi", 
        $_POST['folio'], $_POST['nombre'], $_POST['sexo'], $_POST['fecha_nacimiento'], $edad_guardar, 
        $peso, $talla, $imc, $diagnostico_auto, $_POST['diagnostico'], 
        $_POST['localidad'], $_POST['tipo_localidad'], $_POST['centro_salud'], $_POST['grupo_vulnerable'], 
        $_POST['domicilio'], $_POST['telefono'], $_POST['mes'], $_POST['tutor'], $_SESSION['id_usuario']
    );
    
    if ($stmt->execute()) {
        $_SESSION['msg'] = "Seguimiento guardado. Estado: " . $diagnostico_auto;
        header("Location: nutricion.php"); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Mes - DIF</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6d1b2b;       /* Vino */
            --secondary: #2c3e50;     /* Gris Oscuro */
            --bg-color: #f0f2f5;
            --white: #ffffff;
            --text-color: #1a1a1a;
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
        .header-title span { font-size: 0.85em; opacity: 0.9; }
        
        .btn-back {
            background: rgba(255,255,255,0.15); color: white; text-decoration: none; padding: 10px 25px;
            border-radius: 30px; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s; font-size: 0.85em;
        }
        .btn-back:hover { background: white; color: var(--primary); font-weight: bold; }

        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        .card-evaluacion {
            background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden; border-top: 5px solid #3498db; 
        }
        .card-header {
            background: #f8f9fa; padding: 20px 30px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 15px;
        }
        .card-header h2 { margin: 0; color: #3498db; font-size: 1.3em; text-transform: uppercase; letter-spacing: 0.5px; }
        .icon-header { background: #3498db; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        .card-body { padding: 30px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }

        .form-group label { 
            display: block; font-size: 0.85em; font-weight: 700; color: var(--primary); margin-bottom: 8px; 
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;
            background: #fff; font-size: 0.95em; color: #000; box-sizing: border-box; transition: 0.3s; font-weight: 500;
        }
        
        .input-readonly { background-color: #f2f4f6 !important; color: #333 !important; border-color: #ddd; pointer-events: none; font-weight: 600; }
        
        .input-highlight { border: 2px solid #aed6f1; background-color: #f4faff; color: #000; }
        .input-highlight:focus { border-color: #3498db; box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1); outline: none; }

        .section-title {
            grid-column: 1 / -1; font-size: 0.9em; color: #555; text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 2px solid #eee; margin: 20px 0 10px 0; padding-bottom: 5px; font-weight: 800;
        }

        .btn-save {
            grid-column: 1 / -1; background: #27ae60; color: white; border: none; padding: 16px; border-radius: 8px;
            font-size: 1.1em; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 20px;
            box-shadow: 0 4px 10px rgba(39, 174, 96, 0.2); text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-save:hover { background: #219150; transform: translateY(-2px); }

        /* --- CORRECCIÓN DE COLOR PARA LA EDAD --- */
        .edad-tag {
            background: #e1f5fe !important;  /* Azul muy claro */
            color: #01579b !important;      /* Azul Oscuro Fuerte */
            border: 1px solid #81d4fa !important;
            padding: 12px; 
            border-radius: 8px; 
            text-align: center; 
            font-weight: 800; 
            display: block;
            font-size: 1em;
        }
    </style>
</head>
<body onload="calcularEdad()">

    <nav class="header-principal">
        <div class="header-brand">
            <img src="logodiff.png" alt="Logo" class="logo-img">
            <div class="header-title">
                <h1>Área de Nutrición</h1>
                <span>Seguimiento Mensual</span>
            </div>
        </div>
        <a href="nutricion.php" class="btn-back">Cancelar</a>
    </nav>

    <div class="container">
        <div class="card-evaluacion">
            <div class="card-header">
                <div class="icon-header">+</div>
                <h2>Nueva Evaluación</h2>
            </div>
            
            <form method="POST" class="card-body">
                <div class="form-grid">
                    
                    <div class="section-title">1. Datos del Beneficiario</div>
                    
                    <div class="form-group">
                        <label>Folio</label>
                        <input type="text" name="folio" value="<?php echo $datos['folio']; ?>" class="input-readonly" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" class="input-readonly" readonly>
                    </div>
                    
                    <div class="form-group" style="display:none;">
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo $datos['fecha_nacimiento']; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Edad Actual (Calculada)</label>
                        <input type="text" name="edad_txt" id="edad_texto" class="edad-tag" readonly>
                    </div>

                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="sexo" class="input-readonly">
                            <option value="Masculino" <?php if($datos['sexo']=='Masculino') echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if($datos['sexo']=='Femenino') echo 'selected'; ?>>Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Grupo Vulnerable</label>
                        <select name="grupo_vulnerable" class="input-readonly">
                            <option value="<?php echo $datos['grupo_vulnerable']; ?>" selected><?php echo $datos['grupo_vulnerable']; ?></option>
                        </select>
                    </div>

                    <div class="section-title">2. Ubicación y Contacto</div>
                    
                    <div class="form-group">
                        <label>Localidad</label>
                        <input type="text" name="localidad" value="<?php echo $datos['localidad']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Tipo Localidad</label>
                        <select name="tipo_localidad">
                            <option value="Rural" <?php if($datos['tipo_localidad']=='Rural') echo 'selected'; ?>>Rural</option>
                            <option value="Urbana" <?php if($datos['tipo_localidad']=='Urbana') echo 'selected'; ?>>Urbana</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Centro de Salud</label>
                        <input type="text" name="centro_salud" value="<?php echo $datos['centro_salud']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Domicilio</label>
                        <input type="text" name="domicilio" value="<?php echo $datos['domicilio']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" value="<?php echo $datos['telefono']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Tutor</label>
                        <input type="text" name="tutor" value="<?php echo $datos['tutor']; ?>">
                    </div>

                    <div class="section-title" style="color:#2980b9; border-color:#2980b9;">3. Nueva Medición</div>

                    <div class="form-group">
                        <label style="color:#2980b9;">Peso Actual (kg)</label>
                        <input type="number" name="peso" step="0.001" class="input-highlight" required autofocus>
                    </div>
                    <div class="form-group">
                        <label style="color:#2980b9;">Talla Actual (m)</label>
                        <input type="number" name="talla" step="0.01" class="input-highlight" required>
                    </div>
                    
                    <div class="form-group">
                        <label style="color:#2980b9;">Mes de Evaluación</label>
                        <select name="mes" class="input-highlight" required>
                            <?php 
                            $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                            $actual = date('n'); $anio = date('Y');
                            foreach($meses as $k => $m) {
                                $val = "$m $anio";
                                $sel = ($k+1 == $actual) ? "selected" : "";
                                echo "<option value='$val' $sel>$val</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Observaciones del Mes</label>
                        <textarea name="diagnostico" rows="2"></textarea>
                    </div>

                    <button type="submit" name="guardar_evaluacion" class="btn-save">Guardar Seguimiento</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function calcularEdad() {
        const fechaInput = document.getElementById('fecha_nacimiento').value;
        if (!fechaInput) return;
        
        const fechaNac = new Date(fechaInput);
        const hoy = new Date();
        
        let años = hoy.getFullYear() - fechaNac.getFullYear();
        let meses = hoy.getMonth() - fechaNac.getMonth();
        let dias = hoy.getDate() - fechaNac.getDate();

        if (meses < 0 || (meses === 0 && dias < 0)) {
            años--;
            meses += 12;
        }
        if (dias < 0) {
            meses--;
            dias += 30;
        }

        let texto = "";
        if (años > 0) {
            texto = años + " años, " + meses + " meses";
        } else {
            texto = meses + " meses, " + dias + " días";
        }
        
        document.getElementById('edad_texto').value = texto;
    }
    </script>
</body>
</html>
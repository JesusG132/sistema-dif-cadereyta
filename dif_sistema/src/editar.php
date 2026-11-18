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

// 2. Obtener datos del registro a editar
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conexion->prepare("SELECT * FROM nutricion WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $f = $stmt->get_result()->fetch_assoc();

    if (!$f) {
        header("Location: nutricion.php"); exit();
    }
} else {
    header("Location: nutricion.php"); exit();
}

// 3. Procesar la Actualización
if (isset($_POST['actualizar'])) {
    $id_act = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $fecha_nac = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $tutor = $_POST['tutor'];
    
    $localidad = $_POST['localidad'];
    $tipo_loc = $_POST['tipo_localidad'];
    $domicilio = $_POST['domicilio'];
    $telefono = $_POST['telefono'];
    $grupo = $_POST['grupo_vulnerable'];
    $centro = $_POST['centro_salud'];
    
    $peso = floatval($_POST['peso']);
    $talla = floatval($_POST['talla']);
    // Corrección automática de cm a metros si es necesario
    if($talla > 3) $talla = $talla / 100; 
    
    // Recalcular IMC por si cambiaron peso/talla
    $imc = ($talla > 0) ? round($peso / ($talla * $talla), 2) : 0;
    
    $observaciones = $_POST['diagnostico'];

    $sql = "UPDATE nutricion SET 
            nombre=?, fecha_nacimiento=?, sexo=?, tutor=?, 
            localidad=?, tipo_localidad=?, domicilio=?, telefono=?, grupo_vulnerable=?, centro_salud=?, 
            peso=?, talla=?, imc=?, diagnostico=? 
            WHERE id=?";
            
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssssssdddsi", 
        $nombre, $fecha_nac, $sexo, $tutor,
        $localidad, $tipo_loc, $domicilio, $telefono, $grupo, $centro,
        $peso, $talla, $imc, $observaciones, $id_act
    );

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Registro actualizado correctamente.";
        header("Location: nutricion.php");
        exit();
    } else {
        $error = "Error al actualizar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Registro - DIF</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6d1b2b;       /* Vino */
            --secondary: #2c3e50;     /* Gris Oscuro */
            --bg-color: #f0f2f5;
            --white: #ffffff;
            --text-color: #1a1a1a;
        }

        body { font-family: 'Montserrat', sans-serif; background-color: var(--bg-color); margin: 0; padding: 0; color: var(--text-color); }

        /* HEADER */
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

        /* CONTAINER & CARD */
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        .card-form {
            background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden; border-top: 5px solid #f39c12; /* Naranja para indicar Edición */
        }
        .card-header {
            background: #fffcf5; padding: 20px 30px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 15px;
        }
        .card-header h2 { margin: 0; color: #e67e22; font-size: 1.3em; text-transform: uppercase; letter-spacing: 0.5px; }
        .icon-header { background: #f39c12; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        .card-body { padding: 30px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }

        /* FORM FIELDS */
        .form-group label { 
            display: block; font-size: 0.85em; font-weight: 700; color: var(--primary); margin-bottom: 8px; 
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;
            background: #fff; font-size: 0.95em; color: #000; box-sizing: border-box; transition: 0.3s; font-weight: 500;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
             border-color: #f39c12; box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1); outline: none;
        }

        /* Campos de solo lectura (Folio, Mes) */
        .input-readonly { 
            background-color: #e9ecef !important; color: #555 !important; border-color: #ddd; 
            pointer-events: none; font-weight: 600; cursor: not-allowed;
        }

        .section-title {
            grid-column: 1 / -1; font-size: 0.9em; color: #7f8c8d; text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 2px solid #eee; margin: 25px 0 15px 0; padding-bottom: 5px; font-weight: 800;
        }

        .btn-save {
            grid-column: 1 / -1; background: #27ae60; color: white; border: none; padding: 16px; border-radius: 8px;
            font-size: 1.1em; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 30px;
            box-shadow: 0 4px 10px rgba(39, 174, 96, 0.2); text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-save:hover { background: #219150; transform: translateY(-2px); }
    </style>
</head>
<body>

    <nav class="header-principal">
        <div class="header-brand">
            <img src="logodiff.png" alt="Logo" class="logo-img">
            <div class="header-title">
                <h1>Área de Nutrición</h1>
                <span>Modificar Registro</span>
            </div>
        </div>
        <a href="nutricion.php" class="btn-back">Cancelar</a>
    </nav>

    <div class="container">
        
        <?php if(isset($error)): ?>
            <div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="card-form">
            <div class="card-header">
                <div class="icon-header">✎</div>
                <h2>Editar Datos del Beneficiario</h2>
            </div>
            
            <form method="POST" class="card-body">
                <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                
                <div class="form-grid">
                    
                    <div class="section-title" style="margin-top: 0;">1. Identidad y Registro (Solo Lectura)</div>
                    
                    <div class="form-group">
                        <label>Folio</label>
                        <input type="text" value="<?php echo $f['folio']; ?>" class="input-readonly" readonly>
                    </div>
                    <div class="form-group">
                        <label>Mes de Registro Original</label>
                        <input type="text" value="<?php echo $f['mes']; ?>" class="input-readonly" readonly>
                    </div>

                    <div class="section-title">2. Datos Personales (Editables)</div>

                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($f['nombre']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="<?php echo $f['fecha_nacimiento']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="sexo" required>
                            <option value="Masculino" <?php if($f['sexo'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if($f['sexo'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tutor</label>
                        <input type="text" name="tutor" value="<?php echo htmlspecialchars($f['tutor']); ?>">
                    </div>

                    <div class="section-title">3. Ubicación y Contacto</div>

                    <div class="form-group">
                        <label>Localidad</label>
                        <input type="text" name="localidad" value="<?php echo htmlspecialchars($f['localidad']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo Localidad</label>
                        <select name="tipo_localidad" required>
                            <option value="Rural" <?php if($f['tipo_localidad'] == 'Rural') echo 'selected'; ?>>Rural</option>
                            <option value="Urbana" <?php if($f['tipo_localidad'] == 'Urbana') echo 'selected'; ?>>Urbana</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Domicilio</label>
                        <input type="text" name="domicilio" value="<?php echo htmlspecialchars($f['domicilio']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" value="<?php echo $f['telefono']; ?>" maxlength="10">
                    </div>

                    <div class="section-title">4. Datos Médicos del Registro</div>

                    <div class="form-group">
                        <label>Centro de Salud</label>
                        <input type="text" name="centro_salud" value="<?php echo htmlspecialchars($f['centro_salud']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Grupo Vulnerable</label>
                        <select name="grupo_vulnerable">
                            <?php $gv = $f['grupo_vulnerable']; ?>
                            <option value="6 a 24 meses" <?php if($gv=='6 a 24 meses') echo 'selected'; ?>>6 a 24 meses</option>
                            <option value="2 a 5 años" <?php if($gv=='2 a 5 años') echo 'selected'; ?>>2 a 5 años</option>
                            <option value="6 a 17 años" <?php if($gv=='6 a 17 años') echo 'selected'; ?>>6 a 17 años</option>
                            <option value="Embarazadas lactarias" <?php if($gv=='Embarazadas lactarias') echo 'selected'; ?>>Embarazadas lactarias</option>
                            <option value="Discapacidad" <?php if($gv=='Discapacidad') echo 'selected'; ?>>Discapacidad</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="background:#fff3cd; padding:10px; border-radius:8px; border:1px solid #ffeeba;">
                        <label style="color:#856404;">Peso (kg)</label>
                        <input type="number" name="peso" step="0.001" value="<?php echo $f['peso']; ?>" required>
                    </div>
                    <div class="form-group" style="background:#fff3cd; padding:10px; border-radius:8px; border:1px solid #ffeeba;">
                        <label style="color:#856404;">Talla (metros)</label>
                        <input type="number" name="talla" step="0.01" value="<?php echo $f['talla']; ?>" required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Observaciones / Diagnóstico</label>
                        <textarea name="diagnostico" rows="3"><?php echo htmlspecialchars($f['diagnostico']); ?></textarea>
                    </div>

                    <button type="submit" name="actualizar" class="btn-save">GUARDAR CAMBIOS</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
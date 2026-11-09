<?php
//JSON
header("Content-Type: application/json");

// Recibimos el peso y la talla que mandó la página principal
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
$talla = isset($_POST['talla']) ? floatval($_POST['talla']) : 0;

if ($peso > 0 && $talla > 0) {
    // 1. Hacemos el cálculo del IMC
    $imc = $peso / ($talla * $talla);
    $imc_redondeado = round($imc, 2);

    // 2. Determinamos el diagnóstico básico
    $diagnostico = "Normal";
    if ($imc_redondeado < 18.5) {
        $diagnostico = "Bajo peso";
    } elseif ($imc_redondeado >= 25) {
        $diagnostico = "Sobrepeso";
    }

    // 3. Devolvemos la respuesta limpia en JSON
    echo json_encode([
        "estado" => "exito",
        "imc" => $imc_redondeado,
        "diagnostico" => $diagnostico
    ]);
} else {
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Faltan datos de peso o talla"
    ]);
}
?>
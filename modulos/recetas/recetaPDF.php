<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Receta inválida");
}

$sql = "SELECT re.*,
               p.nombre AS paciente,
               m.nombre AS medico
        FROM recetas re
        INNER JOIN usuarios p ON p.id = re.id_paciente
        INNER JOIN usuarios m ON m.id = re.id_medico
        WHERE re.id = $id AND re.status = 1
        LIMIT 1";

$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    die("Receta no encontrada");
}

$data = mysqli_fetch_assoc($res);

/* PDF SIMPLE CON HTML */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Médica</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            margin: 30px;
            color: #222;
        }
        .header{
            text-align:center;
            margin-bottom:30px;
        }
        .header h1{
            margin:0;
            color:#2f5fb3;
        }
        .header h3{
            margin:5px 0 0;
            color:#78b24b;
        }
        .bloque{
            margin-bottom:20px;
        }
        .label{
            font-weight:bold;
        }
        .box{
            border:1px solid #ccc;
            padding:12px;
            border-radius:8px;
            background:#f9f9f9;
            white-space: pre-line;
        }
        .firma{
            margin-top:50px;
            text-align:right;
        }
        .firma-linea{
            margin-top:50px;
            border-top:1px solid #333;
            display:inline-block;
            min-width:260px;
            text-align:center;
            padding-top:8px;
        }
        .btn-print{
            margin-bottom:20px;
        }
        @media print{
            .btn-print{
                display:none;
            }
        }
    </style>
</head>
<body>

<button class="btn-print" onclick="window.print()">Descargar / Imprimir PDF</button>

<div class="header">
    <h1>NEXUS CARE</h1>
    <h3>RECETA MÉDICA</h3>
</div>

<div class="bloque">
    <span class="label">Paciente:</span>
    <?php echo htmlspecialchars($data['paciente']); ?>
</div>

<div class="bloque">
    <span class="label">Médico:</span>
    <?php echo htmlspecialchars($data['medico']); ?>
</div>

<div class="bloque">
    <span class="label">Cédula profesional:</span>
    <?php echo htmlspecialchars($data['cedula_profesional']); ?>
</div>

<div class="bloque">
    <span class="label">Fecha:</span>
    <?php echo htmlspecialchars($data['fecha_receta']); ?>
</div>

<div class="bloque">
    <div class="label mb-1">Diagnóstico</div>
    <div class="box"><?php echo nl2br(htmlspecialchars($data['diagnostico'])); ?></div>
</div>

<div class="bloque">
    <div class="label mb-1">Medicamentos / tratamiento</div>
    <div class="box"><?php echo nl2br(htmlspecialchars($data['medicamentos'])); ?></div>
</div>

<div class="bloque">
    <div class="label mb-1">Indicaciones</div>
    <div class="box"><?php echo nl2br(htmlspecialchars($data['indicaciones'])); ?></div>
</div>

<div class="firma">
    <div class="firma-linea">
        <?php echo htmlspecialchars($data['medico']); ?><br>
        Cédula profesional: <?php echo htmlspecialchars($data['cedula_profesional']); ?>
    </div>
</div>

</body>
</html>
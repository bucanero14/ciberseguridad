<?php
require_once 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_completo'] ?? '');
    $fecha = trim($_POST['fecha_nacimiento'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $trabajo = trim($_POST['trabajo'] ?? '');

    if (empty($nombre) || empty($fecha) || empty($direccion) || empty($telefono) || empty($trabajo)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO registros (nombre_completo, fecha_nacimiento, direccion, telefono, trabajo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $fecha, $direccion, $telefono, $trabajo);

        if ($stmt->execute()) {
            $success = "Registro guardado exitosamente.";
        } else {
            $error = "Error al guardar: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletos - Cinepolis</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; color: #111; }

        .header {
            background: #080F2A;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .header-text h1 { color: #fff; font-size: 22px; font-weight: bold; }
        .header-text h2 { color: #ff8c00; font-size: 13px; font-weight: normal; }
        .header-right { display: flex; align-items: center; gap: 20px; }
        .nav-links { display: flex; gap: 18px; list-style: none; }
        .nav-links a { color: #fff; text-decoration: none; font-size: 13px; }
        .nav-links a:hover { color: #ff8c00; }
        .user-icon { width: 28px; height: 28px; fill: #fff; }

        .page-header { padding: 1.5rem 1.5rem 0.5rem; max-width: 680px; margin: 0 auto; }
        .back { font-size: 20px; color: #111; cursor: pointer; display: inline-block; margin-bottom: 8px; text-decoration: none; }
        .page-title { font-size: 26px; font-weight: 800; color: #111; text-transform: uppercase; letter-spacing: 1px; line-height: 1.2; }
        .page-subtitle { font-size: 14px; color: #555; margin-top: 6px; line-height: 1.6; }

        .form-card {
            background: #fff;
            border: 0.5px solid #e0e0e0;
            border-radius: 8px;
            max-width: 680px;
            margin: 1rem auto 2rem;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #15274D;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 1rem;
            padding-bottom: 6px;
            border-bottom: 2px solid #15274D;
        }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
        .grid-1 { display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 12px; }
        .field { display: flex; flex-direction: column; gap: 4px; }
        label { font-size: 12px; font-weight: 500; color: #555; text-transform: uppercase; letter-spacing: 0.5px; }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 0.5px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
            color: #111;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #A52A2A;
            box-shadow: 0 0 0 2px rgba(165,42,42,0.1);
        }

        .btn-row { display: flex; gap: 10px; justify-content: flex-end; margin-top: 1.5rem; }
        button {
            padding: 10px 22px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            border: 0.5px solid #ccc;
            background: #fff;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        button:hover { background: #f5f5f5; }
        button.submit {
            background: #080F2A;
            color: #fff;
            border-color: #080F2A;
            padding: 10px 28px;
        }
        button.submit:hover { background: #8B2500; }

        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .legal { font-size: 11px; color: #999; text-align: center; margin-top: 12px; }

        .page-header { padding: 1.5rem 1.5rem 0.5rem; max-width: 680px; margin: 0 auto; }
        .page-title { font-size: 26px; font-weight: 800; color: #111; text-transform: uppercase; letter-spacing: 1px; line-height: 1.2; }
        .page-subtitle { font-size: 14px; color: #555; margin-top: 6px; line-height: 1.6; }

        .promo-banner {
            max-width: 680px;
            margin: 1rem auto;
            background: #080F2A;
            border-radius: var(--border-radius-lg);
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .promo-badge { background: #FFD700; color: #111; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: 1px; display: inline-block; width: fit-content; }
        .promo-title { font-size: 22px; font-weight: 800; color: #fff; line-height: 1.2; text-transform: uppercase; }
        .promo-desc { font-size: 13px; color: rgba(255,255,255,0.9); line-height: 1.6; }
        .promo-tags { display: flex; gap: 8px; flex-wrap: wrap; }
        .promo-tag { background: rgba(255,255,255,0.15); border-radius: var(--border-radius-md); padding: 6px 12px; font-size: 12px; color: #fff; }

        .urgency {
            max-width: 680px;
            margin: 0 auto 0.5rem;
            background: #FFD700;
            padding: 9px 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
            border-radius: var(--border-radius-md);
        }
        .urgency-dot { width: 8px; height: 8px; border-radius: 50%; background: #E30613; animation: pulse 1.2s infinite; flex-shrink: 0; }
        @keyframes pulse { 0%,100%{opacity:1}50%{opacity:0.3} }
        .urgency span { font-size: 12px; font-weight: 600; color: #111; }

        @media (max-width: 600px) {
            .grid-2 { grid-template-columns: 1fr; }
            .header { flex-direction: column; gap: 12px; }
            .header-right { flex-wrap: wrap; justify-content: center; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header-left">
        <img src="img/cinepolis.svg" alt="Cinepolis" style="height: 32px;">
    </div>
</header>

<div class="page-header">
  <div class="page-title">¡Gana un pase doble!</div>
  <div class="page-subtitle">Completa tu registro y participa para ganar 2 boletos gratis en cualquier función de tu elección.</div>
</div>

<div class="promo-banner">
  <div class="promo-badge">PROMOCIÓN EXCLUSIVA</div>
  <div class="promo-title">2 boletos gratis<br>solo por registrarte</div>
  <div class="promo-desc">Así de fácil — llena el formulario, <strong>participa automáticamente</strong> y espera el correo con tus boletos ganadores.</div>
  <div class="promo-tags">
    <div class="promo-tag">🎟️ Pase doble incluido</div>
    <div class="promo-tag">⚡ Registro en 2 minutos</div>
    <div class="promo-tag">🏆 Ganadores cada semana</div>
  </div>
</div>

<div class="urgency">
  <div class="urgency-dot"></div>
  <span>¡Pocos lugares disponibles esta semana! Regístrate ahora.</span>
</div>

<div class="page-header">
    <div class="page-title">Formulario de Registro</div>
    <div class="page-subtitle">Complete el formulario con sus datos personales.</div>
</div>

<form method="POST" class="form-card">
    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <p class="section-title">Información Personal</p>
    <div class="grid-1">
        <div class="field">
            <label for="nombre_completo">Nombre completo</label>
            <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ingrese su nombre completo" required>
        </div>
    </div>
    <div class="grid-2">
        <div class="field">
            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>
        <div class="field">
            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" placeholder="Ingrese su teléfono" required>
        </div>
    </div>

    <p class="section-title" style="margin-top: 1.5rem;">Dirección y Ocupación</p>
    <div class="grid-1">
        <div class="field">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" name="direccion" placeholder="Ingrese su dirección" required>
        </div>
    </div>
    <div class="grid-1">
        <div class="field">
            <label for="trabajo">¿En qué trabajas?</label>
            <input type="text" id="trabajo" name="trabajo" placeholder="Ingrese su ocupación" required>
        </div>
    </div>

    <div class="btn-row">
        <button type="submit" class="submit">Quiero mi pase doble</button>
    </div>
    <p class="legal">Al registrarte aceptas los <a href="terminos.php" target="_blank" style="color: inherit;">términos y condiciones</a> de la promoción. Aplican restricciones. Válido solo en México.</p>
</form>

</body>
</html>

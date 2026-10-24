<?php
// ARCHIVO DE TRABAJOS
$archivo_trabajos = "trabajos.json";

// TU NÚMERO DE WHATSAPP REAL (Cambia estos números)
$mi_whatsapp = "573116249398";

// LA CLAVE QUE TÚ DEFINES PARA DARLE A LOS QUE TE PAGUEN
$clave_secreta = "barrio2026"; 

$error = "";
$acceso_concedido = false;

// Verificamos si ingresó la clave correcta
if (isset($_POST['ingresar'])) {
    if ($_POST['clave'] === $clave_secreta) {
        $acceso_concedido = true;
    } else {
        $error = "❌ Clave incorrecta. Paga tu suscripción para recibir el código del día.";
    }
}

// Procesar publicación de trabajo si tiene la clave correcta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publicar'])) {
    $acceso_concedido = true; // Mantener acceso
    $cliente = htmlspecialchars($_POST['cliente']);
    $servicio = htmlspecialchars($_POST['servicio']);
    $pago = htmlspecialchars($_POST['pago']);
    $whatsapp = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);

    if (!empty($cliente) && !empty($servicio) && !empty($pago) && !empty($whatsapp)) {
        $nuevo_trabajo = [
            'id' => time(),
            'cliente' => $cliente,
            'servicio' => $servicio,
            'pago' => $pago,
            'whatsapp' => $whatsapp,
            'fecha' => date("d/m/Y - h:i A")
        ];

        $trabajos = file_exists($archivo_trabajos) ? json_decode(file_get_contents($archivo_trabajos), true) : [];
        array_unshift($trabajos, $nuevo_trabajo);
        file_put_contents($archivo_trabajos, json_encode($trabajos, JSON_PRETTY_PRINT));
    }
}

$lista_trabajos = file_exists($archivo_trabajos) ? json_decode(file_get_contents($archivo_trabajos), true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servipueblo Premium</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --primary: #10b981;
            --accent: #38bdf8;
            --admin-border: #6366f1;
            --text: #f8fafc;
            --subtext: #94a3b8;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: var(--bg-color);
            color: var(--text);
            margin: 0;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container { max-width: 480px; width: 100%; }

        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: var(--primary); margin: 0; font-size: 1.8rem; }
        .header p { color: var(--subtext); font-size: 0.9rem; margin-top: 5px; }

        .card {
            background-color: var(--card-bg);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .card-admin {
            background: linear-gradient(135deg, #1e1b4b, #1e293b);
            border: 2px solid var(--admin-border);
        }

        form label { display: block; font-size: 0.85rem; color: var(--subtext); margin-top: 10px; }
        form input, form textarea {
            width: 100%; padding: 10px; margin-top: 5px; border-radius: 8px;
            border: 1px solid #334155; background: #0f172a; color: white;
            box-sizing: border-box; font-size: 0.95rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white; border: none; padding: 12px; font-size: 1rem;
            font-weight: bold; border-radius: 10px; width: 100%; margin-top: 15px; cursor: pointer;
        }

        .btn-ws {
            display: block; background: #25d366; color: white; text-decoration: none;
            text-align: center; padding: 10px; border-radius: 8px; font-weight: bold; font-size: 0.9rem;
        }

        .error { color: #f43f5e; font-weight: bold; font-size: 0.9rem; text-align: center; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>🛠️ ServiPueblo</h1>
            <p>Plataforma Exclusiva de Mandados y Servicios</p>
        </div>

        <!-- PANTALLA DE BLOQUEO DE PAGO -->
        <?php if (!$acceso_concedido): ?>
            <div class="card card-admin">
                <h2 style="color: #818cf8; margin-top:0;">🔒 Acceso Protegido</h2>
                <p style="font-size:0.9rem; color: var(--subtext);">
                    Para publicar o ver las ofertas de trabajo debes tener la clave de suscripción.
                </p>

                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST">
                    <label>Ingresa el Código de Acceso:</label>
                    <input type="password" name="clave" placeholder="****" required>
                    <button type="submit" name="ingresar" class="btn-submit">🔓 ENTRAR A LA PLATAFORMA</button>
                </form>

                <hr style="border:0; border-top:1px solid rgba(255,255,255,0.1); margin: 20px 0;">

                <p style="font-size:0.85rem; text-align:center; color: var(--subtext);">¿No tienes el código? Solicítalo pagando la cuota ($2.000 COP) por Nequi:</p>
                <a href="https://wa.me/<?php echo $mi_whatsapp; ?>?text=Hola,%20quiero%20pagar%20mi%20suscripci%C3%B3n%20para%20obtener%20el%20c%C3%B3digo%20de%20acceso%20a%20ServiBarrio." 
                   target="_blank" class="btn-ws">
                   💬 Pedir Código por WhatsApp
                </a>
            </div>

        <!-- PANTALLA PRINCIPAL (Solo si tiene clave) -->
        <?php else: ?>

            <div class="card card-admin">
                <h2 style="color: #818cf8; margin-top:0;">⚡ Servicio Express Oficial</h2>
                <p style="font-size: 0.9rem; color: var(--subtext);">Contacta directo con el Administrador para mandados o soporte.</p>
                <a href="https://wa.me/<?php echo $mi_whatsapp; ?>?text=Hola,%20necesito%20un%20servicio%20express." target="_blank" class="btn-ws">💬 Hablar con el Admin</a>
            </div>

            <div class="card">
                <h2>📢 Publicar un Trabajo</h2>
                <form method="POST">
                    <input type="hidden" name="clave" value="<?php echo $clave_secreta; ?>">
                    <label>Tu Nombre:</label>
                    <input type="text" name="cliente" required>
                    <label>¿Qué necesitas?:</label>
                    <textarea name="servicio" rows="2" required></textarea>
                    <label>¿Cuánto pagas? ($):</label>
                    <input type="text" name="pago" required>
                    <label>Tu WhatsApp:</label>
                    <input type="number" name="whatsapp" required>
                    <button type="submit" name="publicar" class="btn-submit">🚀 PUBLICAR</button>
                </form>
            </div>

            <div class="card">
                <h2>💼 Trabajos de la Comunidad</h2>
                <?php foreach ($lista_trabajos as $trabajo): ?>
                    <div style="background:#0f172a; border:1px solid #334155; border-radius:10px; padding:12px; margin-bottom:10px;">
                        <strong><?php echo $trabajo['servicio']; ?></strong><br>
                        <span style="color:var(--primary); font-weight:bold;">💰 <?php echo $trabajo['pago']; ?></span><br>
                        <small style="color:var(--subtext);">De: <?php echo $trabajo['cliente']; ?></small><br><br>
                        <a href="https://wa.me/<?php echo $trabajo['whatsapp']; ?>" target="_blank" class="btn-ws">💬 Contactar</a>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

</body>
</html>

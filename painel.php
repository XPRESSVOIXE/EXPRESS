<?php
session_start();
$senha = "doido1010";

if (!isset($_SESSION['logado'])) {
    if (isset($_POST['senha']) && $_POST['senha'] === $senha) {
        $_SESSION['logado'] = true;
    } else {
        echo '<form method="POST" style="margin:200px auto;width:300px;text-align:center;">
        <h3>Painel Seguro</h3>
        <input type="password" name="senha" placeholder="Senha" style="padding:10px;width:100%;"><br><br>
        <button type="submit" style="padding:10px;width:100%;">Entrar</button>
        </form>';
        exit;
    }
}

$file = 'dados.json';
$lista = json_decode(file_get_contents($file), true);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Painel</title>
<style>
body{background:#0f1115;color:white;font-family:Arial;}
table{width:100%;border-collapse:collapse;}
th,td{padding:10px;border-bottom:1px solid #333;}
button{padding:5px 10px;border:none;border-radius:5px;cursor:pointer;}
.liberar{background:green;color:white;}
.negar{background:red;color:white;}
.del{background:#444;color:white;}
</style>
</head>
<body>

<h2>Painel de Alertas</h2>
<a href="limpar.php"><button class="del">Deletar Todos</button></a>

<table>
<tr>
<th>Data</th>
<th>IP</th>
<th>País</th>
<th>Cidade</th>
<th>Provedor</th>
<th>Status</th>
<th>Ações</th>
</tr>

<?php foreach($lista as $item): ?>
<tr>
<td><?= $item['data'] ?></td>
<td><?= $item['ip'] ?></td>
<td><?= $item['pais'] ?></td>
<td><?= $item['cidade'] ?></td>
<td><?= $item['provedor'] ?></td>
<td><?= $item['status'] ?></td>
<td>
<a href="liberar.php?id=<?= $item['id'] ?>"><button class="liberar">Liberar</button></a>
<a href="negar.php?id=<?= $item['id'] ?>"><button class="negar">Negar</button></a>
<a href="deletar.php?id=<?= $item['id'] ?>"><button class="del">Deletar</button></a>
</td>
</tr>
<?php endforeach; ?>
</table>

<script>
setInterval(function(){
location.reload();
},5000);
</script>

</body>
</html>
<?php
$file='dados.json';
$lista=json_decode(file_get_contents($file),true);

foreach($lista as &$item){
if($item['id']==$_GET['id']){
$item['status']="negado";
}
}
file_put_contents($file,json_encode($lista,JSON_PRETTY_PRINT));
header("Location:painel.php");
<?php
$file='dados.json';
$lista=json_decode(file_get_contents($file),true);

$lista=array_filter($lista,function($item){
return $item['id']!=$_GET['id'];
});

file_put_contents($file,json_encode(array_values($lista),JSON_PRETTY_PRINT));
header("Location:painel.php");
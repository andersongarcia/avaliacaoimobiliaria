<?php
$db = mysql_connect('localhost','rogeriog_aval','F^&AT=FmMik]') or die("Database error"); 
mysql_select_db('rogeriog_avaliacao', $db); 

$query = "SELECT * FROM imobiliarias";

$result = mysql_query($query);
if (!$result) {
    echo 'Não foi possível executar a consulta: ' . mysql_error();
    exit;
}

while($row=mysql_fetch_array($result)) {
    //echo utf8_decode($row["nome"]);
    //mysql_query("UPDATE imobiliarias SET endereco = '" . utf8_decode($row["endereco"]) . "'" .
    //                " WHERE id = " . $row['id']);
 }
?>

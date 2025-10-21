<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function fecha_letras($date)
{
   // Formato: día del mes (número, sin ceros) /
   //          mes del año (número, sin ceros) /
   //          año (cuatro dígitos)
   // Ejemplo: 7/11/2002

$d=substr($date,8,8);
$m=substr($date,4,4);
$a=substr($date,0,4); 
$mes="";
if($m=='-01-'){
$mes="Enero";
}
if($m=='-02-'){
$mes="Febrero";
}
if($m=='-03-'){
$mes="Marzo";
}
if($m=='-04-'){
$mes="Abril";
}
if($m=='-05-'){
$mes="Mayo";
}
if($m=='-06-'){
$mes="Junio";
}
if($m=='-07-'){
$mes="Julio";
}
if($m=='-08-'){
$mes="Agosto";
}
if($m=='-09-'){
$mes="Septiembre";
}
if($m=='-10-'){
$mes="Octubre";
}
if($m=='-11-'){
$mes="Noviembre";
}
if($m=='-12-'){
$mes="Diciembre";
}

$string=' '.$d.' de '.$mes.' de '.$a;

 
 return ($string);
}


 ?>

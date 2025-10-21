<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="css/adm/tablas.css" />

</head>

<body>
<?php
//print(base_url()."css/adm/style.css");
$i=1;
	$ingresoTotal=0;
	$egresoTotal=0;
	$margenTotal=0;

	function convertirMayuscula($cadena)
	{
		$cadena=strtoupper($cadena);
		return($cadena);
	}
?>
<div style="padding-left:50px; padding-top:30px">
<div >
<!--img src="img/redisoft.png" style="width:500px; height:60px"/-->
</div>
<table style="width:99%">
    <tr>
    	<td align="center" style="padding-top:4px">
        <?php
		$img='img/logos/logotipo.png';
		
		#if(file_exists())
		#{
			if('img/logos/'.$empresa->id.'_'.$empresa->logotipo)
			{
				$img='img/logos/'.$empresa->id.'_'.$empresa->logotipo;
			}
		#}
		#echo $img;
        ?>
        <img src="<?php echo base_url().$img?>" style="width:136px; height:79px"/> 
        &nbsp;&nbsp;
   		</td>
   	    </tr>
        <tr>
        <td style="padding-left:60px; color:#000;"colspan="2" class="tdTextos">
         <?php echo $empresa->nombre?>
        </td>
        </tr>
</table>
</div>

<div align="right" style="padding-right:60px; color:#000" class="leyendas">
PUEBLA, PUE, <?php print(date('Y-m-d'));?><br />
</div>

<div  style="padding-left:60px; color:#000" class="leyendas">
<?php  echo convertirMayuscula($compra->empresa)?>
<br />
<?php  echo convertirMayuscula($compra->domicilio) ?>
<br />
<?php  echo convertirMayuscula($compra->pais).", ". convertirMayuscula($compra->estado)?>
</div>

<div align="right" style="padding-right:60px" class="leyendas">
<?php
//echo $cotizacion->presente;
?>
</div>

<br />

	<div align="center" style="padding-left:30px; padding-right:30px">
	<table class="admintable" style="width:99%">
    <tr>
    <th style="color:#000" colspan="4">Detalle de compra</th>
    </tr>
    
    <tr>
    <th style="color:#000">Descripcion</th>
    <th style="color:#000">Cantidad</th>
    <th style="color:#000" align="right">Precio unitario</th>
    <th style="color:#000" align="right">Total</th>
    </tr>
    
    <?php
	$suma=0;
	foreach($productos as $producto)
	{
		$suma+=$producto->total;
		?>
         <tr>
    		<td><?php echo $producto->nombre?></td>
            <td><?php echo $producto->cantidad?></td>
            <td align="right">$ <?php echo number_format($producto->precio,2)?></td>
            <td align="right">$ <?php echo number_format($producto->total,2)?></td>
    	 </tr>
    
        <?php
	}
	
	$descuento=$suma-$compra->total;
    ?>

    <tr>
        <td colspan="2" style="border:none"></td>
        <td  align="right">SUB-TOTAL</td>
        <td align="right">$ <?php echo number_format($suma,2)?></td>
    </tr>
    
    <tr>
        <td colspan="2" style="border:none"></td>
        <td  align="right">Descuento</td>
        <td align="right">$ <?php echo number_format($descuento,2)?></td>
    </tr>
    
    <tr>
        <td colspan="2" style="border:none"></td>
        <td  align="right">Total</td>
        <td align="right">$ <?php echo number_format($compra->total,2)?></td>
    </tr>
    
    <!--tr>
    <td colspan="2" style="border:none"></td>
    <td  align="right">IVA <?php #echo $totales->iva*100?>%</td>
    <td align="right">$ <?php #echo number_format($compra->subtotal*$totales->iva,2)?></td>
    </tr>
    
     <tr>
     <td colspan="2" style="border:none"></td>
    <td  align="right">TOTAL</td>
    <td align="right">$ <?php #echo number_format($compra->precioventa,2)?></td>
    </tr-->
    
     <tr>
    <td  colspan="4" align="right">(<?php  echo convertirMayuscula($cantidadLetra)?> M.N.)</td>
    </tr>
    </table>
</div>
<br />
<br />

<div align="left" style="padding-left:30px; font-size:10px; color:#000" >

 <?php #echo convertirMayuscula($totales->condiciones_pago)?>
<br />
<br />


<?php #echo convertirMayuscula($totales->comentarios)?>
</div>
</body>
</html>


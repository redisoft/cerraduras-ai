<?php
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

<div>
<table style="width:100%">
        <tr>
        <td style="padding-left:60px; font-size:20px"colspan="2" >
         <?php echo $empresa->nombre?>
        </td>
        </tr>
</table>
</div>

<div align="right" style="padding-right:60px; color:#000;" class="leyendas">
PUEBLA, PUE, <?php print($remision->fechaCompra);?><br />
<?php echo 'Orden de Venta:'. $remision->ordenCompra?>
</div>

<div  style="padding-left:60px; color:#000;" class="leyendas">
<?php  
	echo convertirMayuscula($cliente->empresa);
?>
<br />
<?php  
	echo convertirMayuscula($cliente->direccion.' '. $cliente->no_direccion); 
?>
<br />
<?php  
	echo 
	convertirMayuscula($cliente->colonia). ', '.
	convertirMayuscula($cliente->ciudad). ', '.
	convertirMayuscula($cliente->municipio). ', '.
	convertirMayuscula($cliente->estado);
?>
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
    	<th >Tipo</th>
        <th >Producto</th>
        <th >Cantidad</th>
        <th align="right">Precio unitario</th>
        <th align="right">Total</th>
        </tr>
    
    <?php
	foreach($productos as $row)
	{
		$producto	=strlen($row->producto)>0?$row->producto:$row->descripcion;
		
		?>
         <tr>
         	<td ><?php echo $row->servicio==1?'Servicio ('.$row->periodo.')':'Producto'?></td>
    		<td ><?php echo $producto?></td>
            <td align="right" ><?php echo $row->cantidad?></td>
            <td  align="right">$ <?php echo number_format($row->precio,4)?></td>
            <td align="right">$ <?php echo number_format($row->importe,4)?></td>
    	 </tr>
    
        <?php
	}
	
	$subTotal		=$remision->subTotal;
	$descuento		=($subTotal*$remision->descuento)/100;
	$totalDescuento	=$subTotal-$descuento;
	$iva			=$totalDescuento*$remision->iva;
	$total			=$totalDescuento+$iva;
    ?>
    <tr>
    <td colspan="3" style="border:none"></td>
    <td style="color:#000; border-bottom:1px solid #f2f2f2"  align="right">SUB-TOTAL</td>
    <td  style="color:#000; border-bottom:1px solid #f2f2f2" align="right">$ <?php echo number_format($subTotal,4)?></td>
    </tr>
    
    <tr>
    <td colspan="3" style="border:none"></td>
    <td style="color:#000; border-bottom:1px solid #f2f2f2"  align="right">
    DESCUENTO: <?php echo number_format($remision->descuento,2)?>%</td>
    <td  style="color:#000; border-bottom:1px solid #f2f2f2" align="right">$ <?php echo number_format($descuento,4)?></td>
    </tr>
    
    <tr>
    <td colspan="3" style="border:none"></td>
    <td  style="color:#000; border-bottom:1px solid #f2f2f2" align="right">IVA: <?php echo number_format($remision->iva*100,2)?>%</td>
    <td style="color:#000; border-bottom:1px solid #f2f2f2" align="right">$ <?php echo number_format($iva,4)?></td>
    </tr>
    
     <tr>
     <td colspan="3" style="border:none"></td>
    <td style="color:#000; border-bottom:1px solid #f2f2f2"  align="right">TOTAL</td>
    <td style="color:#000; border-bottom:1px solid #f2f2f2" align="right">$ <?php echo number_format($total,4)?></td>
    </tr>
    
     <tr>
    <td style="border:none"  colspan="5" align="right">(<?php  echo convertirMayuscula($cantidadLetra)?> <?php echo $remision->clave?>)</td>
    </tr>
    </table>
</div>
<br />
<br />

<div align="left" style="padding-left:30px; font-size:10px; color:#000;" >

COMENTARIOS: <?php echo convertirMayuscula($remision->condiciones)?>
<br />
<?php echo convertirMayuscula($remision->comentarios)?>
</div>



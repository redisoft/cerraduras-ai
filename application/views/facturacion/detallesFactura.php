<script type="text/javascript" src="<?php echo base_url()?>js/ocultar.js"></script>
<div style="width:1000px; height:600px">
<table class="admintabla" width="99%">
<tr>
	<th colspan="4">DATOS DE LA EMPRESA</th>
</tr>
<tr class="arriba">
	<td  class="etiquetas">Nombre:</td>
    <td>
    	<?php echo $factura->nombre?>
    </td>
	<td  class="etiquetas">RFC:</td>
    <td>
    	<?php echo $factura->rfc?>
    </td>
</tr>
<tr class="abajo">
	<td  class="etiquetas">Dirección:</td>
    <td>
    	<?php echo $factura->direccion?>
    </td>
	<td  class="etiquetas">Número:</td>
    <td>
    	<?php echo $factura->numero?>
    </td>
</tr>
<tr class="arriba">
	<td  class="etiquetas">Colonia:</td>
    <td>
    	<?php echo $factura->colonia?>
    </td>
	<td  class="etiquetas">Código postal:</td>
    <td>
    	<?php echo $factura->codigoPostal?>
    </td>
</tr>
<tr class="abajo">
	<td  class="etiquetas">Ciudad:</td>
    <td>
    	<?php echo $factura->ciudad?>
    </td>
	<td  class="etiquetas">Estado:</td>
    <td>
    	<?php echo $factura->estado?>
    </td>
</tr>
<tr class="arriba">
	<td  class="etiquetas">País:</td>
    <td>
    	<?php echo $factura->pais?>
    </td>
	<td  class="etiquetas">Teléfono:</td>
    <td>
    	<?php echo $factura->telefono?>
    </td>
</tr>
<tr class="abajo">
	<td  class="etiquetas">Email:</td>
    <td>
    	<?php echo $factura->email?>
    </td>
    <td colspan="2"></td>
</tr>
</table>

<table class="admintabla" width="99%" id="tablaFactura">
<tr>
	<th colspan="5">
        &nbsp;
        SUBTOTAL: $
        <?php echo number_format($factura->subTotal,2)?>
        &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
        DESCUENTO: 
        <?php echo number_format($factura->descuento,2)?>%
        &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
        IVA: 
        <?php echo number_format($factura->iva,2)?>%
        &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
        TOTAL: $
        <?php echo number_format($factura->total,2)?>
	</th>
</tr>
<tr>
	<th width="3%">#</th>
    <th width="30%">Descripción</th>
    <th width="10%">Cantidad</th>
    <th width="15%">Precio unitario</th>
    <th width="15%">Importe</th>
</tr>
<?php
$i=1;
foreach($conceptos as $row)
{
	$estilo="class=' abajo'";
	
	if($i%2>0)
	{
		$estilo="class=' arriba'";
	}
	
	echo'
	<tr '.$estilo.'>
		<td>'.$i.'</td>
		<td>'.$row->descripcion.'</td>
		<td>'.$row->cantidad.'</td>
		<td>$ '.number_format($row->precio,2).'</td>
		<td>$ '.number_format($row->importe,2).'</td>
	</tr>
	';
	
	$i++;
}
?>
</table>
</div>
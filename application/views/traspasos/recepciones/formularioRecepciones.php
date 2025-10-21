<script>
$(document).ready(function()
{
	$('#txtFechaRecepcion').timepicker();
	$('#txtComentarios').focus()
});
</script>
<form id="frmRecepciones">
<table class="admintable" width="100%">
	<tr>
        <td class="key">Folio:</td>
        <td><?php echo $folio?></td>
        <td class="key">Traspaso:</td>
        <td><?php echo $traspaso->folio?></td>
     </tr>
    <tr>
        <td class="key">Fecha:</td>
        <td>
            <input type="text" class="cajas"  id="txtFechaRecepcion"  name="txtFechaRecepcion" style="width:120px"  value="<?php echo date('Y-m-d H:i')?>"/>
            <input type="hidden" id="txtIdTraspaso" 				  name="txtIdTraspaso" 			 value="<?php echo $traspaso->idTraspaso?>"/>
            <input type="hidden" id="txtNumeroProductos" 			  name="txtNumeroProductos"  value="<?php echo count($detalles)?>"/>
        </td>
        
        <td class="key">Comentarios:</td>
        <td>
            <textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="width:250px; height:50px"></textarea>
        </td>
    </tr>
</table>
    
<?php
if($detalles!=null)
{
	echo '
	<table class="admintable" width="100%">	
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Código interno</th>
			<th class="encabezadoPrincipal">Producto</th>
			<th class="encabezadoPrincipal">Línea</th>
			<th class="encabezadoPrincipal">Cantidad</th>
			<th class="encabezadoPrincipal">Recibidos</th>
			<th class="encabezadoPrincipal">Recibir</th>
		</tr>';
	$i	= 0;
	foreach($detalles as $row)	
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="right">'.($i+1).'</td>
			<td align="center">'.$row->codigoInterno.'</td>
			<td>'.$row->producto.'</td>
			<td>'.$row->linea.'</td>
			<td align="center">'.number_format($row->cantidad,decimales).'</td>
			<td align="center">'.number_format($row->recibidos,decimales).'</td>
			
			<td class="vinculos" align="center">
				<input type="text" class="cajas" id="txtCantidadRecibir'.$i.'" 	name="txtCantidadRecibir'.$i.'" 	style="width:60px" onkeypress="return soloDecimales(event)" />		
				<input type="hidden" id="txtCantidadPendiente'.$i.'"  			name="txtCantidadPendiente'.$i.'"	value="'.round($row->cantidad-$row->recibidos,decimales).'"/>
				<input type="hidden" id="txtIdProducto'.$i.'" 		 			name="txtIdProducto'.$i.'"			value="'.$row->idProducto.'"/>
				<input type="hidden" id="txtIdDetalle'.$i.'" 		 			name="txtIdDetalle'.$i.'"			value="'.$row->idDetalle.'"/>
			</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de productos</div>';
}
?>
</form>
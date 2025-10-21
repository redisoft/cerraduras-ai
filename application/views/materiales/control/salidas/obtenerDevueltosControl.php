<form id="frmDevueltosControl">
<?php
if($idRol!=5)
{
	?>
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">
				Detalles de salida
			</th>
		</tr>
		<tr>
			<td class="key">Control:</td>
			<td><?php echo salidas.$salida->folio?></td>
		</tr>
		
		<tr>
			<td class="key">Fecha salida:</td>
			<td>
				<?php echo obtenerFechaMesCorto($salida->fechaSalida)?>
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha devolución:</td>
			<td>
				<?php echo obtenerFechaMesCorto($salida->fechaDevolucion)?>
			</td>
		</tr>
	
		<tr>
			<td class="key">Comentarios:</td>
			<td>
				<?php echo $salida->comentarios?>
			</td>
		</tr>
	</table>

	<?php
	}
?>

<table class="admintable" width="100%" id="tablaSalidasControl">
	<tr>
    	<th colspan="6" class="encabezadoPrincipal">
        	Devueltos
            <input type="hidden"  name="txtNumeroMateriales" id="txtNumeroMateriales" value="<?php echo count($materiales)?>"/>
            <input type="hidden"  name="txtIdSalida" id="txtIdSalida" value="<?php echo $salida->idSalida?>"/>
        </th>
    </tr>
	<tr>
    	<th width="3%">-</th>
        <th width="15%">Código interno</th>
        <th width="45%">Materia prima</th>
        <th width="15%">Unidad</th>
        <?php
		if($idRol!=5)
		{
			echo '
			<th>Cantidad salida</th>
        	';
		}
		?>
        
        <th>Devuelto</th>
        
    </tr>
    
    <?php
	$i=1;
    foreach($materiales as $row)
	{
		echo '
		<tr id="filaSalidaControl'.$i.'" '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td>'.$i.'</td>
			<td>'.$row->codigoInterno.'</td>
			<td>'.$row->material.'</td>
			<td>'.$row->unidad.'</td>
			'.($idRol!=5?'<td align="center">'.number_format($row->cantidad,decimales).'</td>':'').'
			<td align="center"> <input type="text"  name="txtCantidadDevuelto'.$row->idDetalle.'" 	id="txtCantidadDevuelto'.$i.'" class="cajas" style="width:100px;" value="" onkeypress="return soloDecimales(event)"/></td>
				<input type="hidden"  				name="txtIdDetalle'.$i.'"		 				id="txtIdDetalle'.$i.'" value="'.$row->idMaterial.'" />
				<input type="hidden"  				name="txtCantidadSalida'.$i.'" 					id="txtCantidadSalida'.$i.'" value="'.$row->cantidad.'" />
		</tr>';
		
		$i++;
	}
	?>
</table>
</form>
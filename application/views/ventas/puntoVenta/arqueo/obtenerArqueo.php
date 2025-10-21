<script>
$("#tableDenominaciones tr:even").addClass("sombreado");
$("#tableDenominaciones tr:odd").addClass("sinSombra");  

$("#tablaArqueo tr:even").addClass("sombreado");
$("#tablaArqueo tr:odd").addClass("sinSombra");  

</script>
<?php

$totalDenominaciones=0;

echo '
<div style="float: left; width:48%">
	<table class="admintable" style="width:100%; " id="tableDenominaciones">
		<tr>
			<th colspan="3">Denominaciones</th>
		</tr>';
	if($denominaciones!=null)
	{
		$i=1;
		foreach($denominaciones as $row)	
		{
			$totalDenominaciones	+= $row->valor*$row->cantidad;
			
			echo '
			<tr id="filaDenominacion'.$i.'" >
				<td  align="center" >$'.$row->valor.'</td>
				
				<td  width="10%">
					<input maxlength="200" type="text" class="cajas" onchange="registrarDenominacion('.$i.')" id="txtCantidadArqueo'.$i.'" name="txtCantidadArqueo'.$i.'" value="'.($row->cantidad>0?$row->cantidad:'').'" style="width:80px" onkeypress="return soloNumerico(event)" />
				</td>
				
				<td  align="right" width="25%" >$ '.number_format($row->valor*$row->cantidad,decimales).'</td>
				
				
				<input type="hidden" id="txtIdRelacion'.$i.'" value="'.$row->idRelacion.'" />
				<input type="hidden" id="txtIdDenominacion'.$i.'" value="'.$row->idDenominacion.'" />
				<input type="hidden" id="txtCantidadDenominacion'.$i.'" value="'.$row->cantidad.'" />
				<input type="hidden" id="txtValorDenominacion'.$i.'" value="'.$row->valor.'" />
				
			</tr>';
			
			$i++;
		}
	}
	
	echo '
		<tr>
			<td align="right" class="totales" colspan="2">Total</td>
			<td align="right" class="totales" >'.number_format($totalDenominaciones,decimales).'</td>
		</tr>
	</table>

</div>';


$total=$totalDenominaciones-$efectivo;
echo '
<div style="float: right; width:48%" id="obtenerArqueoDetalles">
	<table class="admintable" style="width:100%" id="tablaArqueo">
		<tr>
			<th colspan="2">Arqueo</th>
		</tr>
		<tr>
			<td >Fondo de caja:</td>
			<td  align="right">$ '.number_format($fondoCaja,decimales).'</td>
		</tr>
		<tr>
			<td >Efectivo:</td>
			<td  align="right">$ '.number_format($efectivo,decimales).'</td>
		</tr>
		<tr>
			<td >Diferencia:</td>
			<td  align="right" '.($total>0?'style="color: red"':'').'>$ '.number_format($total,decimales).'</td>
		</tr>
	</table>
</div>';



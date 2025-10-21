<?php
$this->load->view('reportes/cajaChica/encabezado');

if($cajaChica!=null)
{
	echo'
	<script>
		$("#tablaEgresos tr:even").addClass("sombreado");
		$("#tablaEgresos tr:odd").addClass("sinSombra");
	</script>
	<table class="admintable" id="tablaEgresos" width="100%">
		<tr>
			<th style="text-align:right" colspan="10">Suma caja chica: $'.number_format($sumaCaja,2).'</th>
		</tr>';
	
	$i=1;
	
	foreach($cajaChica as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
			<td width="2%" class="vinculos">'.$i.'</td>
			<td width="8%" class="vinculos">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="15%" class="vinculos" class="vinculos">';
				$producto	=$this->configuracion->obtenerProducto($row->idProducto);
				echo $producto!=null?$producto->nombre:'';
			echo'</td>
			<td width="10%" class="vinculos" align="right">$'.number_format($row->pago,2).'</td>
			<td width="10%" class="vinculos">'.$row->formaPago.'</td>
			<td width="10%" class="vinculos" align="center">'.$row->cheque.$row->transferencia.'</td>
			<td width="10%" class="vinculos">';
				$nombre	=$this->administracion->obtenerNombre($row->idNombre);
				echo $nombre!=null?$nombre->nombre:'';
			echo'</td>
			<td width="10%" class="vinculos">';
				$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
				echo $departamento!=null?$departamento->nombre:'';
			echo'</td>
			<td width="10%" class="vinculos">';
			echo $row->producto;
			
			echo'
			</td>
			<td width="15%" class="vinculos">';
				$gasto	=$this->configuracion->obtenerGasto($row->idGasto);
				echo $gasto!=null?$gasto->nombre:'';
			echo'</td>
		</tr>';
		
		$cajas	=$this->administracion->obtenerCajaChica($row->idEgreso);
		
		foreach($cajas as $caja)
		{
			echo'
			<tr>
				<td class="vinculos"></td>
				<td class="vinculos">'.obtenerFechaMesCorto($caja->fecha).'</td>
				<td class="vinculos" class="vinculos">'.$caja->concepto.'</td>
				<td class="vinculos" align="right">$'.number_format($caja->importe,2).'</td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
			</tr>';
		}
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	 echo'
	 <div class="erroresGeneral" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		Sin registros de caja chica.
	 </div>';
}

?>
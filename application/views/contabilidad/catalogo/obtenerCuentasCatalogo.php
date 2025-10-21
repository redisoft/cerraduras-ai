<?php
$i=$limite;
if($cuentas!=null)
{
	echo '
	<div id="procesandoInformacion"></div>
	
	<div align="center">
		<ul id="pagination-digg" class="ajax-pagCuentas">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaCuentasCatalogo tr:even").addClass("sinSombra");
		$("#tablaCuentasCatalogo tr:odd").addClass("sombreado");  
	});
	</script>
	
	<table class="admintable" id="tablaCuentasCatalogo" style="width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="9">Detalles de cuentas</th>
		</tr>
		<tr>
			<th>No.</th>
			<th width="7%">Fecha</th>
			<th>Cuenta SAT</th>
			<th>Referencia contable</th>
			<th>Descripción</th>
			<!--<th>Subcuenta</th>-->
			<th>Nivel</th>
			<th>Naturaleza</th>
			<th>Saldo</th>
			<th width="13%">Acciones</th>
		</tr>';
		
	foreach($cuentas as $row)
	{
		$saldo	= $row->saldo;
		$debe	= $row->debe;
		$haber	= $row->haber;
	
		if($row->cuentasHijo>0)
		{
			$saldos	= $this->contabilidad->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo);
			$saldo	= $saldos[0];
			$debe	= $saldos[1];
			$haber	= $saldos[2];
		}
		
		#'.$i.'
		echo'
		<tr id="filaCuenta'.$row->idCuentaCatalogo.'">
			<td class="numeral" align="center">►</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="left">'.$row->cuenta.' ('.$row->codigoAgrupador.')</td>
			<td align="left">'.$row->numeroCuenta.'</td>
			<td align="left">'.$row->descripcion.'</td>
			<!--<td align="left">'.$row->subCuenta.'</td>-->
			<td align="center">'.$row->nivel.'</td>
			<td align="center">'.($row->naturaleza=='A'?'Acreedora':'Deudora').'</td>
			<td align="right">$'.number_format($saldo+$debe-$haber,decimales).'</td>
			<td class="vinculos">
				<img src="'.base_url().'img/editar.png" title="Editar cuenta" onclick="obtenerCuenta('.$row->idCuentaCatalogo.')" />
				
				&nbsp;
				<img src="'.base_url().'img/add.png" title="Agregar cuenta" onclick="formularioAgregarCuenta('.$row->idCuentaCatalogo.')" />
				
				&nbsp;&nbsp;
				<img src="'.base_url().'img/borrar.png" title="Borrar cuenta" onclick="borrarCuenta('.$row->idCuentaCatalogo.')" />
				
				<br />
				Editar
				Cuenta
				Borrar
			</td>
		</tr>';
		
		if($row->cuentasHijo>0)
		{
			$this->contabilidad->obtenerCuentasCatalogoDetalleVista($row->idCuentaCatalogo,2);
		}
		
		$i++;
	}
	
	echo '</table>

	<div align="center">
		<ul id="pagination-digg" class="ajax-pagCuentas">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de cuentas</div>';
}
?>
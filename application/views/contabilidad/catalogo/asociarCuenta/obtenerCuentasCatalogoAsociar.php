<?php
if($cuentas!=null)
{
	
	#$this->load->library('../controllers/contabilidad');
	
	foreach($cuentas as $row)
	{
		echo '
		<div class="cuentaCatalogoAsociar" onclick="agregarCuentaCatalogoAsociar('.$row->idCuentaCatalogo.',\''.$row->descripcion.' ('.$row->numeroCuenta.')\')">
			'.$row->descripcion.' ('.$row->numeroCuenta.')
		</div>';
		
		/*echo'
		<tr id="filaCuenta'.$row->idCuentaCatalogo.'">
			<td class="numeral" align="center">►</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="left">'.$row->cuenta.' ('.$row->codigoAgrupador.')</td>
			<td align="left">'.$row->numeroCuenta.'</td>
			<td align="left">'.$row->descripcion.'</td>
			<!--<td align="left">'.$row->subCuenta.'</td>-->
			<td align="center">'.$row->nivel.'</td>
			<td align="center">'.($row->naturaleza=='A'?'Acreedora':'Deudora').'</td>
			<td align="right">$'.number_format($row->saldo,decimales).'</td>
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
		</tr>';*/
		
		/*if($row->cuentasHijo>0)
		{
			$this->contabilidad->obtenerCuentasCatalogoDetalle($row->idCuentaCatalogo,2);
		}*/

	}
	
	
}
else
{
	#echo '<div class="Error_validar">Sin registro de cuentas</div>';
}
?>
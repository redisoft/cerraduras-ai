<?php

echo '
<div id="procesandoTransacciones"></div>
<form id="frmTransacciones" name="frmTransacciones">
	<table class="tablaFormularios">
		<tr>
			<th colspan="2">Detalles de póliza</th>
		</tr>
		<tr>
			<td class="etiquetas">Tipo de póliza</td>
			<td>'.obtenerTipoPoliza($concepto->tipo).'</td>
			<input type="hidden" id="txtIdConcepto" name="txtIdConcepto" value="'.$concepto->idConcepto.'" />
			<input type="hidden" id="txtNumeroCuentas" name="txtNumeroCuentas" value="'.count($transacciones).'" />
		</tr>
		<tr>
			<td class="etiquetas">Número</td>
			<td>'.$concepto->numero.'</td>
		</tr>
		<tr>
			<td class="etiquetas">Fecha</td>
			<td>'.obtenerFechaMesCorto($concepto->fecha).'</td>
		</tr>
		<tr>
			<td class="etiquetas">Concepto</td>
			<td>'.$concepto->concepto.'</td>
		</tr>
	</table>';
	
		echo'
		<script>
		$(document).ready(function()
		{
			$("#tablaTransacciones tr:even").addClass("arriba");
			$("#tablaTransacciones tr:odd").addClass("abajo");  
		});
		</script>
		
		<table class="tablaDatos" id="tablaTransacciones">
			<tr>
				<th class="titulos" colspan="8">Lista de transacciones</th>
			</tr>
			<tr>
				<th>No.</th>
				<th>Número de cuenta</th>
				<th>Concepto</th>
				<th>Debe</th>
				<th>Haber</th>
				<th>Moneda</th>
				<th>Tipo de cambio</th>
				<th width="28%">Operaciones</th>
			</tr>';
		
		$i=1;
		foreach($transacciones as $row)
		{
			echo'
			<tr id="filaTransaccion'.$row->idTransaccion.'">
				<td class="numeral	">'.$i.'</td>
				<td class="letraChica" align="center">
					
					<select class="selectTextos" id="selectCuentasTransaccion'.$i.'" name="selectCuentasTransaccion'.$i.'" style="width:250px">
						<option value="0">Seleccione</option>';
					
					$idCatalogo = 0;
					$c			=1;
					foreach($cuentasCatalogo as $cat)
					{
						$idCatalogo	= $c==1?$cat->idCatalogo:$idCatalogo;
						
						if($idCatalogo!=$cat->idCatalogo)
						{
							break;
						}
						
						#echo'<option '.($cat->idCuentaCatalogo==$row->idCuentaCatalogo?'selected="selected"':'').' value="'.$cat->idCuentaCatalogo.'">'.$cat->numeroCuenta.'</option>';
						echo'<option title="'.$cat->numeroCuenta.'('.($cat->naturaleza=='A'?'Acreedora':'Deudora').', '.$cat->descripcion.')" '.($cat->idCuentaCatalogo==$row->idCuentaCatalogo?'selected="selected"':'').' value="'.$cat->idCuentaCatalogo.'">'.$cat->numeroCuenta.'('.($cat->naturaleza=='A'?'Acreedora':'Deudora').', '.$cat->descripcion.')</option>';
						
						$c++;
					}
					
				echo'
					
				</td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtConcepto'.$i.'" name="txtConcepto'.$i.'" value="'.$row->concepto.'" placeholder="Concepto" maxlength="300" /></td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'.$i.'" name="txtDebe'.$i.'" value="'.$row->debe.'" placeholder="Debe" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'.$i.'" name="txtHaber'.$i.'" value="'.$row->haber.'" placeholder="Haber" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)"/></td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtMoneda'.$i.'" name="txtMoneda'.$i.'" value="'.$row->moneda.'" placeholder="Moneda" maxlength="4" /></td>
				<td class="letraChica" align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidadesChicas" id="txtTipoCambio'.$i.'" name="txtTipoCambio'.$i.'" value="'.round($row->tipoCambio,2).'" placeholder="Tipo de cambio" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" />
				<input type="hidden" id="txtIdTransaccion'.$i.'" name="txtIdTransaccion'.$i.'" value="'.$row->idTransaccion.'" />
				</td>
				
				<td class="vinculos" style="text-align:left">
					<!--&nbsp;
					<img src="'.base_url().'img/borrar.png" title="Borrar transacción" onclick="borrarTransaccion('.$row->idTransaccion.')" />-->
					&nbsp;
					<img src="'.base_url().'img/cheques.png" title="Cheques" onclick="obtenerCheques('.$row->idTransaccion.')" />
					&nbsp;
					<img src="'.base_url().'img/transferencias.png" title="Transferencias" onclick="obtenerTransferencias('.$row->idTransaccion.')" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/comprobantes.png" title="Comprobantes" onclick="obtenerComprobantes('.$row->idTransaccion.')" />';
					
					if($row->numeroConceptos>0)
					{
						echo'
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="'.base_url().'img/cuentas.png" title="Conceptos" onclick="obtenerConceptosTransaccion('.$row->idTransaccion.')" />';
					}
					
					echo'
					<br />
					<!--Borrar-->
					Cheq.
					Trans.
					Compro.';
					
					if($row->numeroConceptos>0)
					{
						echo' Concep.';
					}
					
				echo'
				</td>
			</tr>';
			
			$i++;
		}
	
	echo '</table>
</form>';
?>
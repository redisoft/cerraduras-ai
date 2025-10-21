<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtBuscarCodigoAgrupador").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerCodigoAgrupador",
		select: function(event,ui)
		{
			$("#txtIdCuenta").val(ui.item.idCuenta);
			$("#txtNivel").val(ui.item.nivel);
			$("#txtCodigoAgrupador").val(ui.item.codigoAgrupador);
		}
	});
	
	$("#txtFechaCuenta").datepicker();
	
	sugerirCuentaNivel();

});
</script>

<div id="registrandoInformacion"></div>
<form id="frmCuentas" name="frmCuentas">

	<input type="hidden" id="txtIdCuenta" name="txtIdCuenta" value="0" />
	<input type="hidden" id="txtIdSubCuenta" name="txtIdSubCuenta" value="0" />
	<input type="hidden" id="txtNivel" name="txtNivel" value="'.(isset($cuenta->nivel)?($cuenta->nivel+1):0).'" />
	<input type="hidden" id="txtCodigoAgrupador" name="txtCodigoAgrupador" value="0" />
	<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="'.($idCuentaCatalogo>0?($idCuentaCatalogo):0).'" />

	<table class="admintable" id="tablaFormularioCuenta" width="100%">
		<!--<tr>
			<td class="key">Código agrupador:</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarCodigoAgrupador" name="txtBuscarCodigoAgrupador" placeholder="Seleccione" style="width:500px"  />
				
			</td>
		</tr>-->
		
		<tr>
			<td class="key">Cuenta SAT:</td>
			<td>
				<select id="selectCuenta" name="selectCuenta" class="cajas" style="width:500px" onchange="sugerirCuentaNivel()">	
					'.($idCuentaCatalogo==0?'<option value="0|0|0">Seleccione cuenta</option>':'').'';
					
					foreach($cuentas as $row)
					{
						$seleccionado="";
						
						if($idCuentaCatalogo>0)
						{
							if($cuenta->nivel==1 and $row->idCuenta==$cuenta->idCuenta) $seleccionado='selected="selected"';
						}
						
						echo '<option '.$seleccionado.' value="'.$row->idCuenta.'|'.$row->codigo.'|1|0">'.$row->codigo.' '.$row->nombre.' ('.$row->cuenta.')</option>';
						
						$subCuentas	= $this->contabilidad->obtenerSubCuentas($row->idCuenta);
						
						foreach($subCuentas as $sub)
						{
							$seleccionado="";
							
							if($idCuentaCatalogo>0)
							{
								if($cuenta->nivel>1 and $cuenta->idCuenta==$sub->idSubCuenta) $seleccionado='selected="selected"';
							}
							
							echo '<option '.$seleccionado.' value="'.$sub->idSubCuenta.'|'.$sub->codigo.'|2|'.$sub->idSubCuenta.'">&nbsp;&nbsp;'.$sub->codigo.' '.$sub->nombre.' ('.$row->cuenta.')</option>';
						}
					}
					
				echo'
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="key">Naturaleza</td>
			<td>';
				
				if($idCuentaCatalogo==0)
				{
					echo'
					<select id="selectNaturaleza" name="selectNaturaleza" class="cajas">	
						<option value="A">Acreedora</option>
						<option value="D">Deudora</option>
					</select>';
				}
				else
				{
					echo '<input type="hidden" id="selectNaturaleza" name="selectNaturaleza" value="'.$cuenta->naturaleza.'" />';
					echo $cuenta->naturaleza=='D'?'Deudora':'Acreedora';
				}
			
			echo'
			</td>
		</tr>
		
		<tr>
			<td class="key">Referencia contable:</td>
			<td>
				<input type="text" class="cajas" id="txtNumeroCuenta" name="txtNumeroCuenta" maxlength="100" />
			</td>
		</tr>

		<tr>
			<td class="key">Descripción:</td>
			<td>
				<input type="text" class="cajas" id="txtDescripcion" name="txtDescripcion" style="width:500px" maxlength="400" />
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Subcuenta:</td>
			<td>
				<input type="text" class="cajas" id="txtSubCuenta" name="txtSubCuenta" maxlength="100" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaCuenta" name="txtFechaCuenta" value="'.date('Y-m-d').'" readonly="readonly" style="width:100px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Saldo:</td>
			<td>
				<input type="text" class="cajas" id="txtSaldoCuenta" name="txtSaldoCuenta" maxlength="20" onkeypress="return soloDecimales(event)" value="0" style="width:100px" />
			</td>
		</tr>
		
		
	</table>
</form>';

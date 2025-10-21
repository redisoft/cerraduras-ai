<?php
$i=$limite;

if($polizas!=null)
{
	echo '
	<div id="procesandoInformacion"></div>
	
	<div align="center">
		<ul id="pagination-digg" class="ajax-pagPolizas">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaPolizas tr:even").addClass("sinSombra");
		$("#tablaPolizas tr:odd").addClass("sombreado");  
	});
	</script>
	
	<table class="admintable" id="tablaPolizas" style="width:100%">
		<tr>
			<th colspan="7" class="encabezadoPrincipal">
				Lista de pólizas
				<!--<img src="'.base_url().'img/zip.png" title="Zip" onclick="zipearPolizas()" width="22" />-->
			</th>
		</tr>
		<tr>
			<th>Fecha</th>
			<th>Tipo de poliza</th>
			<th>Folio</th>
			<th>Concepto</th>
			<th>Total</th>
			<th>Diferencia</th>
			<th width="17%">Acciones</th>
		</tr>';
	
	foreach($polizas as $row)
	{
		echo'
		<tr id="filaPoliza'.$row->idPoliza.'">
			<td class="numeral" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.obtenerTipoPoliza($row->tipo).''.($row->cancelada=='1'?'<i>(Cancelada)</i>':'').'</td>
			<td align="center">'.$row->numero.'</td>
			<td align="center">'.$row->concepto.'</td>
			<td align="right">$'.number_format($row->debe,decimales).'</td>
			<td align="right">$'.number_format($row->debe-$row->haber,decimales).'</td>
			<td class="vinculos">
				<img id="a-btnVerPoliza'.$i.'" src="'.base_url().'img/ver.png" title="Ver" onclick="verPolizaConcepto('.$row->idConcepto.')" />
				&nbsp;
				<img id="btnEditarPoliza'.$i.'" src="'.base_url().'img/editar.png" title="Editar" onclick="obtenerPolizaConcepto('.$row->idConcepto.')" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnCancelarPoliza'.$i.'" src="'.base_url().'img/cancelar.png" title="Cancelar" onclick="cancelarPolizaConcepto('.$row->idConcepto.')" />
				&nbsp;&nbsp;&nbsp;
				<img id="btnBorrarPoliza'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar" onclick="borrarPolizaConcepto('.$row->idConcepto.')" />
				
				<br />
				&nbsp;&nbsp;
				<a id="a-btnVerPoliza'.$i.'">Ver</a>
				<a id="a-btnEditarPoliza'.$i.'">Editar</a>
				<a id="a-btnCancelarPoliza'.$i.'">Cancelar</a>
				<a id="a-btnBorrarPoliza'.$i.'">Borrar</a>';
				
				if($row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEditarPoliza'.$i.'\');
						desactivarBotonSistema(\'btnCancelarPoliza'.$i.'\');
						desactivarBotonSistema(\'btnBorrarPoliza'.$i.'\');
					</script>';
				}
				
			
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>

	<div align="center">
		<ul id="pagination-digg" class="ajax-pagPolizas">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Aun no se han registrado pólizas</div>';
}
?>
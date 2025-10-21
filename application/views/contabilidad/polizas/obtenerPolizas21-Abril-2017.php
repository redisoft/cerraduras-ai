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
			<th colspan="5" class="titulos">
				Lista de pólizas
				<img src="'.base_url().'img/zip.png" title="Zip" onclick="zipearPolizas()" width="22" />
			</th>
		</tr>
		<tr>
			<th>No.</th>
			<th>Versión</th>
			<th>RFC</th>
			<th>Fecha</th>
			<th width="17%">Operaciones</th>
		</tr>';
	
	foreach($polizas as $row)
	{
		echo'
		<tr id="filaPoliza'.$row->idPoliza.'">
			<td class="numeral	">'.$i.'</td>
			<td align="center">'.$row->version.'</td>
			<td align="center">'.$row->rfc.'</td>
			<td align="center">'.obtenerMesAnio($row->fecha).'</td>
			<td class="vinculos">
				<img src="'.base_url().'img/editar.png" title="Editar balanza" onclick="obtenerPoliza('.$row->idPoliza.')" />
				&nbsp;&nbsp;
				<img src="'.base_url().'img/borrar.png" title="Borrar balanza" onclick="borrarPoliza('.$row->idPoliza.')" />
				&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/cuentas.png" title="Pólizas" onclick="conceptosPoliza('.$row->idPoliza.')" />';
				
				if($row->numeroConceptos>0)
				{
					echo' &nbsp;<img src="'.base_url().'img/xml.png" title="Generar xml" onclick="window.location.href=\''.base_url().'contabilidad/xmlPoliza/'.$row->idPoliza.'\'" />';
					
					echo' &nbsp;<img src="'.base_url().'img/excel.png" title="Generar excel" onclick="excelPolizas('.$row->idPoliza.')" />';
				}
			echo'
				<br />
				Editar
				Borrar
				Pólizas';
				
				if($row->numeroConceptos>0)
				{
					echo ' XML';
					echo ' Excel';
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
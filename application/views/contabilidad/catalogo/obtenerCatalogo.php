<?php
$i=$limite;

if($catalogo!=null)
{
	echo '
	
	<div id="procesandoInformacion"></div>
	
	<div align="center">
		<ul id="pagination-digg" class="ajax-pagCatalogo">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaCatalogo tr:even").addClass("sinSombra");
		$("#tablaCatalogo tr:odd").addClass("sombreado");  
	});
	</script>
	
	<table class="admintable" id="tablaCatalogo" width="100%">
		<tr>
			<th colspan="6" class="encabezadoPrincipal">
				Lista de catálogos
				<!--<img src="'.base_url().'img/zip.png" title="Zip" onclick="zipearCatalogo()" />-->
			</th>
		</tr>
		<tr>
			<th>No.</th>
			<th>Versión</th>
			<th>RFC</th>
			<th>Fecha</th>
			<th>Número de cuentas</th>
			<th width="16%">Operaciones</th>
		</tr>';
	
	foreach($catalogo as $row)
	{
		echo'
		<tr id="filaCatalogo'.$row->idCatalogo.'">
			<td class="numeral	">'.$i.'</td>
			<td align="center">'.$row->version.'</td>
			<td align="center">'.$row->rfc.'</td>
			<td align="center">'.obtenerMesAnio($row->fecha).'</td>
			<td align="center">'.$row->numeroCuentas.'</td>
			<td class="vinculos">
				<img src="'.base_url().'img/editar.png" title="Editar catálogo" onclick="obtenerCatalogoEditar('.$row->idCatalogo.')" />
				&nbsp;
				<img src="'.base_url().'img/borrar.png" title="Borrar catálogo" onclick="borrarCatalogo('.$row->idCatalogo.')" />
				&nbsp;
				<img src="'.base_url().'img/cuentas.png" title="Cuentas" onclick="cuentasCatalogo('.$row->idCatalogo.')" />
				&nbsp;';
				
				if($row->numeroCuentas>0)
				{
					echo'<img src="'.base_url().'img/xml.png" title="Generar xml" onclick="window.location.href=\''.base_url().'contabilidad/xmlCatalogo/'.$row->idCatalogo.'\'" />';
				}
				
			echo'
				<br />
				Editar
				Borrar
				Cuentas';
				
				if($row->numeroCuentas>0)
				{
					echo ' XML';
				}
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>

	<div align="center">
		<ul id="pagination-digg" class="ajax-pagCatalogo">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Aun no se han registrado cátalogos de cuentas</div>';
}
?>
<?php
if($traspasos!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaEnvios tr:even").addClass("sombreado");
		$("#tablaEnvios tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagTraspasos">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%" id="tablaEnvios">	
		<tr>
			<th colspan="12" class="encabezadoPrincipal">
				Detalles de traspasos
				<!--<img src="'.base_url().'img/pdf.png" width="22" height="20" onclick="reporteEnvios()" />
				<img src="'.base_url().'img/excel.png" width="22" height="22" onclick="excelEnvios()" />-->
			</th>
		</tr>
		<tr>
			<th class="">#</th>
			<th class="">Fecha</th>
			<th class="">Folio</th>
			<th class="">Tienda salida</th>
			<th class="">Tienda entrada</th>
			<th class="">Comentarios</th>
			<th class="" width="10%">Acciones</th>
		</tr>';
	
	$i=$limite;
	foreach($traspasos as $row)	
	{
		echo '
		<tr>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaTraspaso).'</td>
			<td align="center">'.$row->folio.'</td>
			<td>'.($row->origen).'</td>
			<td>'.($row->destino).'</td>
			<td>'.nl2br($row->comentarios).'</td>
			<td align="center">
				<img id="btnRecepciones'.$i.'" src="'.base_url().'img/truck.png" width="22" onclick="formularioRecepciones('.$row->idTraspaso.')" />
				&nbsp;&nbsp;
				<img id="btnBorrarTraspaso'.$i.'" src="'.base_url().'img/borrar.png" width="22" onclick="accesoBorrarTraspaso('.$row->idTraspaso.')" /><br />
				<a id="a-btnRecepciones'.$i.'">Recibir</a>&nbsp;
				<a id="a-btnBorrarTraspaso'.$i.'">Borrar</a>';
				
				
				if($row->idLicenciaOrigen==$idLicencia)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRecepciones'.$i.'\');
					</script>';
				}
				
				if($row->idLicencia!=$idLicencia or $row->idCotizacion>0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarTraspaso'.$i.'\');
					</script>';
				}
				
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagTraspasos">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de traspasos</div>';
}
	
	
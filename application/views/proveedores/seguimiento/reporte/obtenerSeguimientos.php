<?php
/*if($seguimientos!=null)
{*/
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagSeguimientos">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Folio</th>
			<th class="encabezadoPrincipal">Fecha</th>
			<th class="encabezadoPrincipal">
				<select class="cajas" id="selectStatusBusqueda" name="selectStatusBusqueda" style="width:120px" onchange="obtenerSeguimientos()">
            		<option value="0">CRM</option>';
					foreach($status as $row)
					{
						echo '<option '.($row->idStatus==$idStatus?'selected="selected"':'').' value="'.$row->idStatus.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</th>
			<th class="encabezadoPrincipal">
				<select class="cajas" id="selectServiciosBusqueda" name="selectStatusBusqueda" style="width:120px" onchange="obtenerSeguimientos()">
            		<option value="0">Servicio</option>';
					foreach($servicios as $row)
					{
						echo '<option '.($row->idServicio==$idServicio?'selected="selected"':'').' value="'.$row->idServicio.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</th>
			<!--<th class="encabezadoPrincipal">Empresa</th>-->
			<th class="encabezadoPrincipal">Contacto</th>
			<th class="encabezadoPrincipal">Teléfono</th>
			<th class="encabezadoPrincipal">Email</th>
			<th class="encabezadoPrincipal">Responsable</th>
			<th class="encabezadoPrincipal">Lugar</th>
			<th class="encabezadoPrincipal">Seguimiento</th>
			<th class="encabezadoPrincipal">Comentarios</th>
			<th class="encabezadoPrincipal">Archivos</th>
		</tr>';
	
	$i=$limite;
	foreach($seguimientos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFolioSeguimiento($row->folio).'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >
				<div style="background-color: '.$row->color.'" class="circuloStatus"></div>
				'.$row->status.'
			</td>
			<td align="center">';
				
				if($row->idStatusIgual!=3 and $row->idStatusIgual!=4)
				{
					echo $row->servicio.'<br /><a>'.$row->compra;
				}
				
				echo'
				</a>
			</td>
			<!--<td align="left">'.$row->empresa.'</td>-->
			<td align="left">'.$row->contacto.'</td>
			<td align="left">'.$row->telefono.'</td>
			<td align="left">'.$row->email.'</td>
			<td align="left">'.$row->responsable.'</td>
			<td align="left">'.$row->lugar.'</td>
			<td align="center">';
			
			if($row->idStatusIgual!=3 and $row->idStatusIgual!=4)
			{
				echo obtenerFechaMesCortoHora($row->fechaCierre);
			}
			
			echo'
			</td>
			<td align="left">'.$row->comentarios.'</td>
			<td align="center">';

			echo '
			<img id="btnArchivosSeguimiento'.$i.'" src="'.base_url().'img/subir.png" title="Archivos" width="22" onclick="obtenerArchivosSeguimiento('.$row->idSeguimiento.')" /><br />
			<a id="a-btnArchivosSeguimiento'.$i.'">Archivos</a>';
			
			if($permiso[1]->activo==0)
			{ 
				echo '
				<script>
					desactivarBotonSistema(\'btnArchivosSeguimiento'.$i.'\');
				</script>';
			}
			
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagSeguimientos">'.$this->pagination->create_links().'</ul>
	 </div>';
/*}
else
{
	echo '<div class="Error_validar">Sin registro de seguimiento</div>';
}*/
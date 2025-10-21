<?php
/*if($llamadas!=null)
{*/
	echo '
	
	<input type="hidden" id="txtNumeroTotalProspectos" value="'.count($llamadas).'" />
	
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagLlamadas">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		
		<tr>
			<th class="encabezadoPrincipal">#<br />'.$registros.'</th>
			<!--<th class="encabezadoPrincipal">Folio</th>-->
			<th class="encabezadoPrincipal">Fecha</th>
			<th class="encabezadoPrincipal">
			
				<select class="cajas" id="selectUsuarios" name="selectUsuarios" style="width:90px" onchange="obtenerLlamadas()">
            		<option value="0">Usuario</option>';
					foreach($promotores as $row)
					{
						echo '<option '.($row->idResponsable==$idUsuarioRegistro?'selected="selected"':'').' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			
			</th>
			
			<th class="encabezadoPrincipal">
			
				<select class="cajas" id="selectEstatusBuscar" name="selectEstatusBuscar" style="width:90px" onchange="obtenerLlamadas()">
            		<option value="0">Estatus</option>
					
					 ';
					foreach($estatus as $row)
					{
						echo '<option '.($row->idEstatus==$idEstatus?'selected="selected"':'').' value="'.$row->idEstatus.'">'.$row->nombre.'</option>';
					}
				echo'
				
				<option '.($idEstatus==500?'selected="selected"':'').' value="500">Baja</option>
				</select>
			
			</th>
			
			
			<th class="encabezadoPrincipal" >
				<select class="cajas" id="selectStatusBusqueda" name="selectStatusBusqueda" style="width:90px" onchange="obtenerLlamadas()">
            		<option value="0">CRM</option>';
					foreach($status as $row)
					{
						echo '<option '.($row->idStatus==$idStatus?'selected="selected"':'').' value="'.$row->idStatus.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</th>
			
			<th class="encabezadoPrincipal" style="display:none">
				<select class="cajas" id="selectServiciosBusqueda" name="selectStatusBusqueda" style="width:120px" onchange="obtenerLlamadas()">
            		<option value="0">Servicio</option>';
					foreach($servicios as $row)
					{
						echo '<option '.($row->idServicio==$idServicio?'selected="selected"':'').' value="'.$row->idServicio.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</th>
			
			<!--<th class="encabezadoPrincipal">Empresa</th>-->
			
			<th class="encabezadoPrincipal" width="8%">Alumno</th>
			<!--<th class="encabezadoPrincipal">Teléfono</th>-->
			<th class="encabezadoPrincipal" style="display:none">Email</th>
			<th class="encabezadoPrincipal">
			
				<select class="cajas" id="selectResponsables" name="selectResponsables" style="width:100px" onchange="obtenerLlamadas()">
            		<option value="0">Responsable</option>';
					foreach($responsables as $row)
					{
						echo '<option '.($row->idResponsable==$idResponsable?'selected="selected"':'').' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			
			</th>
			<th class="encabezadoPrincipal" style="display:none">Lugar</th>
			
			<th class="encabezadoPrincipal">
				<select class="cajas" id="selectProgramasBuscar" name="selectProgramasBuscar" style="width:100px" onchange="obtenerLlamadas()">
            		<option value="0">Programa</option>';
					foreach($programas as $row)
					{
						echo '<option '.($row->idPrograma==$idPrograma?'selected="selected"':'').' value="'.$row->idPrograma.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			
			</th>
			<th class="encabezadoPrincipal">Comentarios</th>
			<th class="encabezadoPrincipal" width="23%">Acciones</th>
		</tr>';
	
	$i=$limite;
	foreach($llamadas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right" onclick="detallesSeguimiento('.$row->idSeguimiento.')" > '.$i.'</td>
			<!--<td align="center" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >'.obtenerFolioSeguimiento($row->folio).'</td>-->
			<td align="center" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="left" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >'.$row->usuarioRegistro.'</td>
			
			<td align="center" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >
				<div style="background-color: '.($row->idZona!=2?$row->estatusColor:'black').'" class="circuloStatus"></div>
				'.($row->idZona!=2?$row->estatus:'Baja').'
			</td>
			
			
			<td align="center" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >
				<div style="background-color: '.$row->color.'" class="circuloStatus"></div>
				'.$row->status.'
			</td>
			<td align="center" style="display:none">';
				
				if($row->idStatusIgual!=3 and $row->idStatusIgual!=4)
				{
					echo $row->servicio.'<br /><a>'.$row->cotizacion.$row->venta;
				}
				
				echo'
				</a>
			</td>
			
			<!--<td align="left">'.$row->empresa.'</td>-->
			<td align="left" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >
			'.$row->alumno.($row->idZona==2?'<br /><i> (Baja)</i>':'').' <br /><i>'.$row->matricula.'</i>
			'.$row->telefono.'
			</td>
			<td align="left" style="display:none">'.$row->email.'</td>
			<td align="left" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >'.$row->responsable.'</td>
			<td align="left" style="display:none">'.$row->lugar.'</td>
			
			<td align="left" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >';
			
			echo $row->programa;
			/*if($row->idStatusIgual!=3 and $row->idStatusIgual!=4)
			{
				echo obtenerFechaMesCortoHora($row->fechaCierre);
			}*/
			
			echo'
			</td>
			
			<td align="left" onclick="detallesSeguimiento('.$row->idSeguimiento.')" >'.substr($row->comentarios.$row->bitacora,0,30).'</td>
			<td align="left">
				&nbsp;
				
				<img id="btnEditarSeguimiento'.$i.'" src="'.base_url().'img/editar.png" title="Editar" width="22" onclick="accesoEditarSeguimientoCliente('.$row->idSeguimiento.')" />
				&nbsp;
				<img id="btnEmail'.$i.'" src="'.base_url().'img/correo.png" title="Archivos" width="22" onclick="obtenerPlantillasEnviadas('.$row->idCliente.')" />
				&nbsp;&nbsp;&nbsp;
				<img id="btnArchivos'.$i.'" src="'.base_url().'img/subir.png" title="Archivos" width="22" onclick="obtenerArchivosSeguimiento('.$row->idSeguimiento.')" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnEstatus'.$i.'" src="'.base_url().'img/engranes.png" title="Archivos" width="22" onclick="accesoEditarEstatusSeguimiento('.$row->idSeguimiento.')" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnResponsable'.$i.'" src="'.base_url().'img/contactos.png" title="Responsable" width="22" onclick="formularioEditarResponsable('.$row->idSeguimiento.')" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnSeguimiento'.$i.'" src="'.base_url().'img/crm.png" title="Seguimiento" width="22" onclick="formularioSeguimientoDetalle('.$row->idSeguimiento.')" />
				&nbsp;
				<img id="btnBorrarSeguimiento'.$i.'" src="'.base_url().'img/borrar.png" title="Seguimiento" width="22" onclick="accesoBorrarSeguimientoCliente('.$row->idSeguimiento.')" />
				&nbsp;&nbsp;
				<img id="btnBaja'.$i.'" src="'.base_url().'img/baja.png" title="Seguimiento" width="22" onclick="formularioBajas('.$row->idCliente.')" />
				<br />
				
				<a id="a-btnEditarSeguimiento'.$i.'">Editar</a>
				<a id="a-btnEmail'.$i.'">Email</a>
				<a id="a-btnArchivos'.$i.'">Archivos</a>
				<a id="a-btnEstatus'.$i.'">Estatus</a>
				<a id="a-btnResponsable'.$i.'">Respons.</a>
				<a id="a-btnSeguimiento'.$i.'">CRM</a>
				<a id="a-btnBorrarSeguimiento'.$i.'">Borrar</a>
				<a id="a-btnBaja'.$i.'">Baja</a>';
				
				if($permiso[1]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnArchivos'.$i.'\');
					</script>';
				}
			
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagLlamadas">'.$this->pagination->create_links().'</ul>
	 </div>';
/*}
else
{
	echo '<div class="Error_validar">Sin registro de llamadas</div>';
}*/
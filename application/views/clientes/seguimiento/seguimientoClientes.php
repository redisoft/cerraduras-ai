<?php
/*$status			=$this->configuracion->obtenerStatus();
$servicios		=$this->configuracion->obtenerServicios();*/

echo'
<input type="hidden" id="txtIdCliente" value="'.$idCliente.'" />
<input type="hidden"  name="txtRegistrarCrm" id="txtRegistrarCrm" value="'.$permiso[1]->activo.'"/>';

/*echo 
'<div align="right" style="margin-left:60%; padding-right:20px">
Llamada<img src="'.base_url().'img/morada.png" width="25" />
Cita<img src="'.base_url().'img/verde.png" width="25" />
Seguimiento<img src="'.base_url().'img/amarilla.png" width="25" />
Bitácora<img src="'.base_url().'img/naranja.png" width="25" />';
echo'</div>'; 
*/

if(!empty($seguimientos))
{
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagSeguimiento">'.$this->pagination->create_links().'</ul>
	</div>
	<table class="admintable" width="100%;" >
	<tr>
		<th class="encabezadoPrincipal">#</th>
		<th class="encabezadoPrincipal">Folio</th>
		<th class="encabezadoPrincipal">Fecha</th>
		<th class="encabezadoPrincipal">Responsable</th>
		<th class="encabezadoPrincipal">Servicio</th>
		<th class="encabezadoPrincipal">Seguimiento</th>
		<th class="encabezadoPrincipal">Comentarios</th>
		<th class="encabezadoPrincipal">CRM</th>
		<th class="encabezadoPrincipal">Estatus</th>
		<th class="encabezadoPrincipal" width="23%">Acciones</th>
	</tr>';
	  
	$i=1;
	$fecha=date('Y-m-d');
	
	foreach ($seguimientos as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		print'
		<tr '.$estilo.'>
		<td align="center" valign="middle">'.$i.'</td>
		<td align="center" valign="middle">'.obtenerFolioSeguimiento($row->folio).'</td>
		<td align="center" valign="middle">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="center" valign="middle">'.$row->responsable.'</td>
		<td align="center" valign="middle">';
			
			if($row->idStatusIgual!=4 and $row->idStatusIgual!=3)
			{
				echo $row->servicio.'<br />'.($row->cotizacion.' '.$row->venta);
			}
			
		echo'</td>
		
		<td align="center" valign="middle">';
			if($row->idStatusIgual!=3 and $row->idStatusIgual!=3)
			{
				echo obtenerFechaMesCortoHora($row->fechaCierre);
			}
			
		echo'</td>
		<td align="center" valign="middle" onclick="detallesSeguimiento('.$row->idSeguimiento.')" title="Click para ver detalle">
			<a  style="cursor:pointer" >'.
			substr($row->comentarios,0,50).substr($row->bitacora,0,50).'</a>
			<input type="hidden" value="'.$row->idSeguimiento.'" id="idSeguimiento'.$i.'" />
		</td>
		<td align="center" valign="middle" onclick="detallesSeguimiento('.$row->idSeguimiento.')" title="Click para ver detalle">
			<div style="background-color: '.$row->color.'" class="circuloStatus"></div>
			'.$row->status.'
		</td>
		
		<td align="center" valign="middle" onclick="detallesSeguimiento('.$row->idSeguimiento.')" title="Click para ver detalle">
			<div style="background-color: '.$row->estatusColor.'" class="circuloStatus"></div>
			'.$row->estatus.'
		</td>
		
		<td align="center" valign="middle" id="tdSeguimiendo'.$i.'">';
			
			echo '
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnSeguimientoDetalles'.$i.'" src="'.base_url().'img/crm.png" title="Seguimiento" width="22" onclick="formularioSeguimientoDetalle('.$row->idSeguimiento.')" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
			<img id="btnArchivosSeguimiento'.$i.'" src="'.base_url().'img/subir.png" title="Archivos" width="22" onclick="obtenerArchivosSeguimiento('.$row->idSeguimiento.')" />';
			
			 echo '
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnEditarSeguimiento'.$i.'" src="'.base_url().'img/editar.png" title="Editar" width="22" onclick="accesoEditarSeguimientoCliente('.$row->idSeguimiento.')" />';
			
			echo '
			&nbsp;
			<img id="btnBorrarSeguimiento'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar" width="22" onclick="accesoBorrarSeguimientoCliente('.$row->idSeguimiento.')" />';

			echo '<br />
			
			<a id="a-btnSeguimientoDetalles'.$i.'">Seguimiento</a>
			
			<a id="a-btnArchivosSeguimiento'.$i.'">Archivos</a>
			<a id="a-btnEditarSeguimiento'.$i.'">Editar</a>
			<a id="a-btnBorrarSeguimiento'.$i.'">Borrar</a>';
			
			if($permiso[1]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnArchivosSeguimiento'.$i.'\');
					desactivarBotonSistema(\'btnSeguimientoDetalles'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnEditarSeguimiento'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnBorrarSeguimiento'.$i.'\');
				</script>';
			}
		
			
		echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo'</table>
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagSeguimiento">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:96%; margin-left:2px; margin-bottom: 5px;">
	No hay registro de seguimientos.</div>';
}
<?php
echo '<div id="procesandoDetallesSeguimiento"></div>';
if($seguimiento!=null)
{
	echo '
	<input type="hidden" id="txtIdClienteSeguimiento" value="'.$seguimiento->idCliente.'" />
	<input type="hidden" id="txtIdSeguimiento" value="'.$seguimiento->idSeguimiento.'" />
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2">Seguimiento del cliente '.$seguimiento->empresa.'</th>
		</tr>
		
		<tr>
			<td class="key">Folio:</td>
			<td>'.obtenerFolioSeguimiento($seguimiento->folio).'</td>
		</tr>
		
		<tr>
			<td class="key">Fecha:</td>
			<td>'.obtenerFechaMesCortoHora($seguimiento->fecha).'</td>
		</tr>';
		
		if(sistemaActivo=='IEXE')
		{
			echo'
			<tr>
				<td class="key">Promotor:</td>
				<td>
					'.$seguimiento->usuarioRegistro.'
				</td>
			</tr>';
		}
		
		echo'
		<tr>
			<td class="key">CRM:</td>
			<td>
				'.$seguimiento->status.'
			</td>
		</tr>
		
		<tr>
			<td class="key">Estatus:</td>
			<td>
				'.$seguimiento->estatus.'
			</td>
		</tr';
		
		if($seguimiento->idStatusIgual!=3)
		{
			echo '
			<tr>
				<td class="key">Próximo contacto:</td>
				<td>
					'.obtenerFechaMesCorto($seguimiento->fechaCierre).'
					<br />Entre '.substr($seguimiento->fechaCierre,10,6).' y '.substr($seguimiento->horaCierreFin,0,5).'
				</td>
			</tr>
			<tr>
				<td class="key">Alerta:</td>
				<td>'.($seguimiento->alerta=='1'?'Si':'No').'</td>
			</tr>
			<tr>
				<td class="key">Recordatorio:</td>
				<td>'.$seguimiento->tiempo.'</td>
			</tr>';
		}
		
		echo'
		<tr>
			<td class="key">'.($seguimiento->idStatusIgual==3?'Bitácora':'Comentarios').'</td>
			<td>'.($seguimiento->idStatusIgual==3?$seguimiento->bitacora:$seguimiento->comentarios).'</td>
		</tr>';

		
	echo'
	<table>';
	
	if($archivos!=null)
	{
		$i=1;
		
		echo'
		<table class="admintable" width="100%">
			<tr>
				<th colspan="4">Lista de archivos</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Fecha</th>
				<th width="45%">Nombre</th>
				<th>Tamaño</th>
			</tr>';
		
		foreach($archivos as $row)
		{
			$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
			
			echo '
			<tr '.$estilo.'>
				<td align="right">'.$i.'</td>
				<td align="center">'.$row->fecha.'</td>
				<td>
					<a title="Descargar '.$row->nombre.'" href="'.base_url().'clientes/descargarArchivoSeguimiento/'.$row->idArchivo.'">'.$row->nombre.'</a>
				</td>
				<td align="center">'.number_format($row->tamano/1024,1).' KB</td>
			</tr>';	
			
			$i++;
		}
		
		echo '</table>';
	}
}
else
{
	echo '<div class="Error_validar">El seguimiento no esta disponible</div>';
}

if($detalles!=null)
{
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5" class="encabezadoPrincipal">Seguimientos</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha/Hora</th>
			<th>Responsable</th>
			<th>Seguimiento</th>';
			
			if($idUsuario==$seguimiento->idUsuarioRegistro or $idRol==1)
			{
				echo'<th width="16%">Acciones</th>';
			}
			
		
		echo'
		</tr>';
	
	$i=1;
	foreach($detalles as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td>'.$i.'</td>
			<td>'.obtenerFechaMesCortoHora($row->fecha.' '.$row->hora).'</td>
			<td>'.$row->usuario.'</td>
			<td>'.nl2br($row->observaciones).'</td>';
			
			if($idUsuario==$seguimiento->idUsuarioRegistro or $idRol==1)
			{
				echo'
				<td align="center">
					<img src="'.base_url().'img/borrar.png" width="22" height="22" onclick="accesoBorrarDetallesSeguimiento('.$row->idDetalle.')" />
					<br />
					<a>Borrar</a>
				</td>';
			}
		
		echo'
		</tr>';
		
		$i++;
	}
		
		
	echo'
	</table>';
}
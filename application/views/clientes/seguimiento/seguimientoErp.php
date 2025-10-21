<?php
/*$status			=$this->configuracion->obtenerStatus();
$servicios		=$this->configuracion->obtenerServicios();*/

echo'<input type="hidden" id="txtIdCliente" value="'.$idCliente.'" />';

echo 
'<div align="right" style="margin-left:60%; padding-right:20px">
 Detalle<img src="'.base_url().'img/roja.png" width="25" />
 Corregido<img src="'.base_url().'img/amarilla.png" width="25" />
 Revisado<img src="'.base_url().'img/verde.png" width="25" />';
 
echo'</div>'; 

if(!empty($seguimientos))
{
	echo'
	<table class="admintable" width="97%;" style=" margin-left:1.5%;">
	<tr>
		<th class="encabezadoPrincipal">#</th>
		<th class="encabezadoPrincipal">Fecha</th>
		<th class="encabezadoPrincipal">Responsable</th>
		<th class="encabezadoPrincipal">Cliente</th>
		<th class="encabezadoPrincipal">Comentarios</th>
		<th class="encabezadoPrincipal">Observaciones</th>
		<th class="encabezadoPrincipal">Status</th>
		<th class="encabezadoPrincipal">Acciones</th>
	</tr>';
	  
	$i=1;
	$fecha=date('Y-m-d');
	
	foreach ($seguimientos as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		print'
		<tr '.$estilo.'>
		<td align="center" valign="middle">'.$i.'</td>
		<td align="center" valign="middle">'.$row->fecha.'</td>
		<td align="center" valign="middle">'.$row->responsable.'</td>
		<td align="center" valign="middle">'.$row->cliente.'</td>
		<td align="center" valign="middle">
		<a onclick="detallesSeguimiento('.$row->idSeguimiento.')" style="cursor:pointer" id="lblSeguimiento'.$i.'">'.
		substr($row->comentarios,0,50).'</a>
		<input type="hidden" value="'.$row->idSeguimiento.'" id="idSeguimiento'.$i.'" />
		</td>
		<td align="center" valign="middle">'.$row->comentariosExtra.'</td>
		<td align="center" valign="middle">';
		
			if ($row->idStatus==4)
			{
				print('<img src="'.base_url().'img/roja.png" width="25" />');
			}
			
			if ($row->idStatus==5)
			{
				print('<img src="'.base_url().'img/amarilla.png" width="25" />');
			}
			
			if ($row->idStatus==6)
			{
				print('<img src="'.base_url().'img/verde.png" width="25" />');
			}
		
		echo'</td>
		<td align="center" valign="middle" >
			<img id="btnEditarErp'.$i.'" src="'.base_url().'img/editar.png" title="Editar" width="22" onclick="obtenerSeguimientoErp('.$row->idSeguimiento.')" />
			&nbsp;&nbsp;&nbsp;
			<img src="'.base_url().'img/borrar.png" title="Borrar" width="22" onclick="borrarSeguimientoErp('.$row->idSeguimiento.')" />
			
			<br />
			<label>Editar</label>
			<label>Borrar</label>
		</td>
		</tr>';
		
		echo'<script>
		$("#lblSeguimiento'.$i.'").click(function(e)
		{
			$("#ventanaDetallesSeguimiento").dialog("open");
		});
		
		$("#btnEditarErp'.$i.'").click(function(e)
		{
			$("#ventanaEditarErp").dialog("open");
		});
		
		</script>';
		
		$i++;
	}
	
	echo'</table>';
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:96%; margin-left:2px; margin-bottom: 5px;">
	No hay registro de seguimientos de ERP</div>';
}
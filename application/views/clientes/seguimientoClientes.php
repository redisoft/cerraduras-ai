<?php
/*$status			=$this->configuracion->obtenerStatus();
$servicios		=$this->configuracion->obtenerServicios();*/

echo'<input type="hidden" id="txtIdCliente" value="'.$idCliente.'" />';

echo 
'<div align="right" style="margin-left:60%; padding-right:20px">
Llamada<img src="'.base_url().'img/morada.png" width="25" />
Cita<img src="'.base_url().'img/verde.png" width="25" />
Seguimiento<img src="'.base_url().'img/amarilla.png" width="25" />
Cliente<img src="'.base_url().'img/naranja.png" width="25" />';
 
echo'</div>'; 

if(!empty($seguimientos))
{
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagSeguimiento">'.$this->pagination->create_links().'</ul>
	</div>
	<table class="admintable" width="100%;" >
	<tr>
		<th class="encabezadoPrincipal">#</th>
		<th class="encabezadoPrincipal">Fecha</th>
		<th class="encabezadoPrincipal">Responsable</th>
		<th class="encabezadoPrincipal">Servicio</th>
		<th class="encabezadoPrincipal">Monto</th>
		<th class="encabezadoPrincipal">Fecha cierre</th>
		<th class="encabezadoPrincipal">Comentarios</th>
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
		<td align="center" valign="middle">'.obtenerFechaMesCortoHora($row->fecha).'</td>
		<td align="center" valign="middle">'.$row->responsable.'</td>
		<td align="center" valign="middle">';
			echo $row->idStatus==11?'':$row->servicio;
		echo'</td>
		<td align="center" valign="middle">';
			echo $row->idStatus==11?'':'$ '.number_format($row->monto,2);
		echo'</td>
		<td align="center" valign="middle">';
			echo $row->idStatus==11?'':$row->fechaCierre;
		echo'</td>
		<td align="center" valign="middle">
		<a onclick="detallesSeguimiento('.$row->idSeguimiento.')" style="cursor:pointer" id="lblSeguimiento'.$i.'">'.
		substr($row->comentarios,0,50).'</a>
		<input type="hidden" value="'.$row->idSeguimiento.'" id="idSeguimiento'.$i.'" />
		</td>
		<td align="center" valign="middle">';
		
			if ($row->idStatus==3)
			{
				print('<img src="'.base_url().'img/naranja.png" width="25" />');
			}
			
			if ($row->idStatus==2)
			{
				print('<img src="'.base_url().'img/amarilla.png" width="25" />');
			}
			
			if ($row->idStatus==1)
			{
				print('<img src="'.base_url().'img/verde.png" width="25" />');
			}
			
			if ($row->idStatus==11)
			{
				print('<img src="'.base_url().'img/morada.png" width="25" />');
			}
		
		echo'</td>
		<td align="center" valign="middle" id="tdSeguimiendo'.$i.'">';
		
		if($permiso->escribir=='1')
		{ 
			
			echo '
			<img id="btnEditarSeguimiento'.$i.'" src="'.base_url().'img/editar.png" title="Editar" width="22" onclick="obtenerSeguimientoEditar('.$row->idSeguimiento.')" />
			&nbsp;&nbsp;&nbsp;
			<img src="'.base_url().'img/borrar.png" title="Borrar" width="22" onclick="borrarSeguimientoCrm('.$row->idSeguimiento.')" />
			
			<br />
			<label>Editar</label>
			<label>Borrar</label>
			</td>
			</tr>';
			
			echo'<script>
			
			$("#btnEditarSeguimiento'.$i.'").click(function(e)
			{
				$("#ventanaEditarSeguimiento").dialog("open");
			});
			
			$("#lblSeguimiento'.$i.'").click(function(e)
			{
				$("#ventanaDetallesSeguimiento").dialog("open");
			});
			</script>';
		}
		echo'</td>
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
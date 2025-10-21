<?php
echo'<input type="hidden" id="txtIdCliente" value="'.$idCliente.'" />';
		
echo '
<div class="ui-state-error" ></div>
<div align="right" style="margin-left:50%; padding-right:20px">
 En proceso<img src="'.base_url().'img/success.png" width="25" />
 Terminado<img src="'.base_url().'img/feliz.png" width="25" />
 No concluido<img src="'.base_url().'img/triste.png" width="25" />';
 
 
echo'</div>'; 

if(!empty($proyectos))
{
	echo'
	<table class="admintable" width="97%;" style=" margin-left:1.5%;">
	<tr>
		<th class="encabezadoPrincipal">#</th>
		<th class="encabezadoPrincipal">Fecha</th>
		<th class="encabezadoPrincipal">Responsable</th>
		<th class="encabezadoPrincipal">Comentarios</th>
		<th class="encabezadoPrincipal">Proyecto</th>
		<th class="encabezadoPrincipal">Meta</th>
		<th class="encabezadoPrincipal">Avance</th>
		<th class="encabezadoPrincipal">Status</th>
		<th class="encabezadoPrincipal">Acciones</th>
	</tr>';
	  
	$i=1;
	$fecha=date('Y-m-d');
	
	foreach ($proyectos as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		print'
		<tr '.$estilo.'>
		<td align="center" valign="middle">'.$i.'</td>
		<td align="center" valign="middle">'.$row->fecha.'</td>
		<td align="center" valign="middle">'.$row->responsable.'</td>
		<td align="center" valign="middle">
		<a onclick="detallesSeguimiento('.$row->idSeguimiento.')" style="cursor:pointer" id="lblSeguimiento'.$i.'">'.
		substr($row->comentarios,0,50).'</a>
		<input type="hidden" value="'.$row->idSeguimiento.'" id="idSeguimiento'.$i.'" />
		</td>
		<td align="center" valign="middle">'.$row->proyecto.'</td>
		<td align="center" valign="middle">'.$row->meta.'</td>
		<td align="center" valign="middle">'.$row->avance.'</td>
		<td align="center" valign="middle">';
		
			if ($row->idStatus==12)
			{
				print('<img src="'.base_url().'img/success.png" width="25" />');
			}
			
			if ($row->idStatus==13)
			{
				print('<img src="'.base_url().'img/feliz.png" width="25" />');
			}
			
			if ($row->idStatus==14)
			{
				print('<img src="'.base_url().'img/triste.png" width="25" />');
			}

		echo'</td>
		<td align="center" valign="middle" >
			<img src="'.base_url().'img/editar.png" title="Editar" width="22" onclick="obtenerProyecto('.$row->idSeguimiento.')" />
			&nbsp;&nbsp;&nbsp;
			<img src="'.base_url().'img/borrar.png" title="Borrar" width="22" onclick="borrarProyecto('.$row->idSeguimiento.')" />
			
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

		</script>';
		
		$i++;
	}
	
	echo'</table>';
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:96%; margin-left:2px; margin-bottom: 5px;">
	No hay registro de proyectos</div>';
}
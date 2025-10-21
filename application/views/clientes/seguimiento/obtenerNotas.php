<?php
echo'<input type="hidden" id="txtIdCliente" value="'.$idCliente.'" />';

if(!empty($notas))
{
	echo'
	<table class="admintable" width="97%;" style=" margin-left:1.5%;">
	<tr>
		<th class="encabezadoPrincipal">#</th>
		<th class="encabezadoPrincipal">Fecha</th>
		<th class="encabezadoPrincipal">Responsable</th>
		<th class="encabezadoPrincipal">Comentarios</th>
		<th class="encabezadoPrincipal">Acciones</th>
	</tr>';
	  
	$i=1;
	$fecha=date('Y-m-d');
	
	foreach ($notas as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		print'
		<tr '.$estilo.'>
		<td align="center" valign="middle">'.$i.'</td>
		<td align="center" valign="middle">'.$row->fecha.'</td>
		<td align="center" valign="middle">'.$row->responsable.'</td>
		<td align="center" valign="middle">'.
		substr($row->comentarios,0,200).'</a>
		<input type="hidden" value="'.$row->idNota.'" id="txtIdNota'.$i.'" />
		</td>

		<td align="center" valign="middle" >
			<img id="btnEditarNota'.$i.'" src="'.base_url().'img/editar.png" title="Editar" width="22" onclick="obtenerNota('.$row->idNota.')" />
			&nbsp;&nbsp;&nbsp;
			<img src="'.base_url().'img/borrar.png" title="Borrar nota" width="22" onclick="borrarNota('.$row->idNota.')" />
			
			<br />
			<label>Editar</label>
			<label>Borrar</label>
		</td>
		</tr>';
		
		echo'<script>

		
		$("#btnEditarNota'.$i.'").click(function(e)
		{
			$("#ventanaEditarNota").dialog("open");
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
	No hay registro de notas</div>';
}
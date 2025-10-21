<?php
echo '
<input type="hidden" id="txtIdClienteSeguimiento" value="'.$seguimiento->idCliente.'" />
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Seguimiento del cliente '.$seguimiento->empresa.'</th>
	</tr>
	<tr>
		<td class="key">Contacto:</td>
		<td><label>'.$seguimiento->contacto.'</label></td>
	</tr>
	<tr>
		<td class="key">Teléfono:</td>
		<td><label>'.$seguimiento->telefono.'</label></td>
	</tr>
	
	<tr>
		<td class="key">Fecha</td>
		<td><label>'.$seguimiento->fecha.'</label></td>
	</tr>';
	
	if($seguimiento->idServicio!=0)
	{
		$servicio=$this->clientes->obtenerServicio($seguimiento->idServicio);
		echo'
		<tr>
			<td class="key">Servicio</td>
			<td><label>'.$servicio->nombre.'</label></td>
		</tr>';
	}
	
	echo'
	<tr>
		<td class="key">Status</td>
		<td>
			<label>'.$seguimiento->status.'</label>
		</td>
	</tr>
	
	<tr>
		<td class="key">Responsable</td>
		<td><label>'.$seguimiento->responsable.'</label></td>
	</tr>
	<tr>
		<td class="key">Comentarios</td>
		<td><label>'.$seguimiento->comentarios.'</label></td>
	</tr>
	<tr>
		<td class="key">Lugar:</td>
		<td><label>'.$seguimiento->lugar.'</label></td>
	</tr>
	
	<tr>
		<td class="key">Observaciones</td>
		<td><label>'.$seguimiento->comentariosExtra.'</label>';
		if(strlen($seguimiento->comentariosExtra)==0)
		{
			echo '<label>--Ninguna--</label>';
		}
		echo'</td>
	</tr>
<table>';
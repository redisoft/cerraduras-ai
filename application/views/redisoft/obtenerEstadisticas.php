
<?php
//MENSAJE
echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="2" class="encabezadoPrincipal" > 
			Mensaje para la cuenta
		</th>
	</tr>
	
	<tr>
		<td class="key">Mensaje:</td>
		<td>
			<textarea class="TextArea" id="txtMensaje" name="txtMensaje" style="height:50px; width:300px">'.$mensaje->mensaje.'</textarea>
			&nbsp;&nbsp;
			<img src="'.base_url().'img/editar.png" width="25" height="25" onclick="editarMensaje('.$mensaje->id.')" title="Editar">
		</td>
	</tr>
</table>';

//USUARIOS
if($usuarios!=null)
{	
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5" class="encabezadoPrincipal" > 
				Lista de usuarios
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>Usuario</th>			
			<th>Email</th>
			<th>Último acceso</th>
		</tr>';
	
	$i =1;
	foreach($usuarios as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="left">'.$row->nombre.'</td>
			<td align="left">'.$row->usuario.'</td>
			<td align="center">'.$row->correo.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaAcceso).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';

}
else
{
	echo '<div class="Error_validar">Sin registro de usuarios</div>';
}

//EMISORES DE FACTURACIÓN
if($emisores!=null)
{	
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="8" class="encabezadoPrincipal" > 
				Lista de emisores
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>RFC</th>			
			<th>Serie</th>
			<th>Folio inicial</th>
			<th>Folio final</th>
			<th>Folios usados</th>
			<th>Folios disponibles</th>
		</tr>';
	
	$i =1;
	foreach($emisores as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="left">'.$row->nombre.'</td>
			<td align="center">'.$row->rfc.'</td>
			<td align="center">'.$row->serie.'</td>
			<td align="center">'.$row->folioInicial.'</td>
			<td align="center">'.$row->folioFinal.'</td>
			<td align="center">'.$row->foliosUsados.'</td>
			<td align="center">'.($row->folioFinal-$row->foliosUsados).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';

}

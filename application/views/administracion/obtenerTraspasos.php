<?php
#echo '<input type="hidden" id="txtIdProducto" value="'.$idProducto.'">';
echo '<div class="ui-state-error" ></div>';
if($traspasos!=null)
{
	echo'
	<div style="width:90%; padding-left:2%; margin-top:1%; margin-bottom:2%; text-align:center;" align="center">
	 	<ul id="pagination-digg" class="ajax-pagTra">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo'
	<table class="admintable" width="100%">
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Cuenta origen</th>
		<th>Cuenta destino</th>
		<th>Monto</th>
		<th>Acciones</th>
	</tr>';
	
	$i=1;
	
	foreach($traspasos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$cuenta=$this->administracion->obtenerCuentaDestino($row->idCuentaDestino);
		
		echo'
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td align="center">'.substr($row->fecha,0,16).'</td>
			<td>'.$row->cuenta.', '.$row->banco.'</td>
			<td>'.$cuenta->cuenta.', '.$cuenta->banco.'</td>
			<td align="right">$'.number_format($row->monto,2).'</td>
			<td align="center">
				<img id="btnBorrar'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" title="Borrar traspaso" onclick="borrarTraspaso('.$row->idTraspaso.')" />
				<br />
				<a id="a-btnBorrar'.$i.'">Borrar</a>
			</td>
		</tr>';
		
		if($permiso[3]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnBorrar'.$i.'\');
			</script>';
		}
		
		$i++;
	}
	
	echo '
	</table>';
}
else
{
	 echo'
	 <div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		Sin registros de traspasos.
	 </div>';
}

echo '<input type="hidden" id="txtPermisoRegistro" value="'.$permiso[1]->activo.'" />';
<?php
echo '<input type="hidden" id="txtIdEgreso" value="'.$idEgreso.'">';

echo '<div class="ui-state-error" ></div>';

if($egresos!=null)
{
	echo'
	<table class="admintable" width="100%">
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Concepto</th>
		<th>Monto</th>
		<th width="14%">Acciones</th>
	</tr>';
	
	$i=1;
	
	foreach($egresos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td>'.$row->fecha.'</td>
			<td>'.$row->concepto.'</td>
			<td align="right">$'.number_format($row->importe,2).'</td>
			<td align="center">
				<img src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerCajaChicaEditar('.$row->idCaja.')" 
					id="btnEditarCajaChica'.$i.'" onclick="" />
				&nbsp;
				<img src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarCajaChica('.$row->idCaja.')" />
				<br />
				<a>Editar</a>
				<a>Borrar</a>
			</td>
		</tr>';
		
		echo'
		<script>
			$("#btnEditarCajaChica'.$i.'").click(function(e)
			{
				$("#ventanaEditarCajaChica").dialog("open");
			});
		</script>';
		$i++;
	}
	
	echo '</table>';
}
else
{
	 echo'
	 <div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		Sin registros de caja chica.
	 </div>';
}
<?php
echo
'<table class="admintable" width="100%;">
	<tr>
	<th>Fecha</th>
	<th>Cantidad</th>
	<th>Entrego</th>
	<th>Acciones</th>
</tr>';

if($entregas!=null)
{
	$i=1;
	foreach ($entregas as $row)
	{
		$cantidad	= $row->cantidad;
		
		if(sistemaActivo=='olyess')
		{
			if($row->rebanadas>0)
			{
				$cantidad		= $row->cantidad*$row->rebanadasPastel;
			}
		}
		
		echo'
		<tr '.($i%2==0?'class="sombreado"':'class="sinSombra"').'>
			<td align="center"> '.obtenerFechaMesCorto($row->fecha).' </td>
			<td align="center"> '.round($cantidad,decimales).' </td>
			<td align="center"> '.$row->entrego.' </td>
			<td align="center">
				<img  src="'.base_url().'img/editar.png" width="20" height="20" title="Editar" onclick="formularioEditarEntrega('.$row->idEntrega.');" style="cursor:pointer;"/><br>
				<a>Editar</a>
			</td>
		</tr>';
		
		$i++;
	}
}

echo '</table>';
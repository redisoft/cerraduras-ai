<?php
$modulo=$licenciatura=='1'?'Licenciaturas':'Maestrías';
if(strlen($licenciatura)==0) $modulo='';

echo'
<div class="table-responsive">
<table class="table table-striped">
	<tr>
		<th colspan="5" class="encabezadoPrincipal">
			'.(strlen($modulo)>0?$modulo:'Matrícula').(strlen($cuatrimestre)>0?', Cuatrimestre: '.$cuatrimestre:'').'
		</th>
	</tr>
	<tr>
		<th width="40%">Programa</th>
		<th width="20%">Alumnos inscritos</th>
		<th width="20%">Alumnos actuales</th>
		<th width="20%">Resultados</th>
	</tr>';

$i=1;

$ingresos			= 0;
$actual				= 0;
$desercionTotal		= 0;

foreach($matriculas as $row)
{
	$ingresos					+= $row->ingresos;
	$actual						+= $row->actual;
	$desercion					= (1-($row->actual/$row->ingresos))*100;
	$desercionTotal				+= $desercion;

	echo'
	<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
		<td align="left">'.$row->programa.'</td>
		<td align="center">'.$row->ingresos.'</td>
		<td align="center">'.$row->actual.'</td>
		<td align="center">'.round($desercion,decimales).'%</td>
	</tr>';

	$i++;
}

echo '
	<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
		<td class="totales" align="center">Total</td>
		<td class="totales" align="center">'.$ingresos.'</td>
		<td class="totales" align="center">'.$actual.'</td>
		<td class="totales" align="center">'.round($desercionTotal/count($matriculas),decimales).'%</td>
	</tr>
</table></div>';




<?php
$this->load->view('reportes/ventasContadora/encabezado');
$cantidad	= 0;
$importe	= 0;

foreach($ventas as $row)
{
	$cantidad	+=$row->cantidad;
	$importe	+=$row->importe;
}

echo  '
<div>
	
</div>';
?>

<table  width="100%">
	<tr>
    	<td align="center" width="33%" style="font-size:14px"><?php echo 'Fecha: '.obtenerFechaMesCorto(date('Y-m-d'))?></td>
        <td align="center" width="33%" style="font-size:14px"><?php echo 'Fecha inicial: '.obtenerFechaMesCorto($inicio)?></td>
        <td align="center" width="33%" style="font-size:14px"><?php echo 'Fecha final: '.obtenerFechaMesCorto($fin)?></td>
    </tr>
    
    <tr>
    	<td align="center" colspan="3" style="font-size:20px"><?php echo $configuracion->nombre?></td>
    </tr>
    <tr>
    	<td align="center" colspan="3" style="font-size:20px"><?php echo 'Ventas por departamento'?></td>
    </tr>
</table>
	

<table class="admintable" width="100%">

	
    <tr>
        <th align="center">Departamento</th>
        <th align="center">Cantidad <br /> Total: <?=number_format($cantidad,decimales)?></th>
        <th align="center">Total <br /> Total: $<?=number_format($importe,decimales)?></th>
    </tr>
	<?php
	$i=1;
    foreach($ventas as $row)
    {
        echo'
        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
            <td align="left">'.$row->departamento.'</td>
            <td align="right">'.round($row->cantidad,decimales).'</td>
            <td align="right">$'.number_format($row->importe,2).'</td>
        </tr>';
        
        $i++;
    }
    ?>
</table>


<?php 
$this->load->view('ventas/ventasServicio/encabezado');

echo '<table class="admintable" width="100%">';

$i=1;
foreach ($ventas as $row)
{
    echo'
    <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
		<td align="center" 	width="4%">'.$i.'</td>
		<td align="left" 	width="18%">'.$row->cliente.'</td>
		<td align="center" 	width="10%">';
		
			echo $row->ordenCompra; 
			if($row->cancelada==1)
			echo ' (Venta cancelada)';
			echo $row->idTienda>0?'('.$row->tienda.')':'';
		 
		 echo'
		 </td>
		 <td align="center" width="10%">'.obtenerFechaMesCortoHora($row->fechaCompra).'</td>
		 <td align="left" 	width="18%">'.$row->producto.'</td>
		 
		 <td align="center" width="10%">'.number_format($row->cantidad,decimales).'</td>
		 <td align="right" 	width="10%">'."$".number_format($row->precio,decimales).'</td>
		 <td align="right" 	width="10%">'."$".number_format($row->descuento,decimales).'</td>
		 <td align="right" 	width="10%">'."$".number_format($row->importe,decimales).'</td>
     </tr>';
    $i++;
}

echo '</table>';
?>
	


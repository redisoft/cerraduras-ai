<?php
if($ventas!=null)
{
	$this->load->view('reportes/ventas/encabezado');
	
	echo '<table class="admintable" width="100%">';
	
	$i=1;
	foreach($ventas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cancelada	=0;
			
		if($row->idFactura!=0)
		{
			$cancelada	=$this->reportes->obtenerFacturaCancelada($row->idFactura);
		}
		
		if($cancelada==0)
		{
			$total		+=$row->total;

			echo '
			<tr '.$estilo.'>
				<td width="3%" align="right">'.$i.'</td>
				<td width="8%" align="center">'.obtenerFechaMesCorto($row->fechaCompra).'</td>
				<td width="20%" align="center">'.$row->empresa.'</td>
				<td width="11%" align="left">'.$row->ordenCompra.' '.($row->idTienda>0?'('.$row->tienda.')':'').'</td>
				<!--<td width="8%" align="center">'.$folio.'</td>-->
				<td width="10%" align="left">'.$row->identificador.'</td>
				<td width="12%" align="left">'.$row->usuario.'</td>';
				
				$seguimiento	= null;
				if(strlen($row->idSeguimiento)>0)
				{
					$seguimiento	= $this->crm->obtenerUltimoSeguimientoVenta($row->idCotizacion);
				}
				echo'
				<td align="center" width="8%" >';
					
					if($seguimiento!=null)
					{
						echo'
						<span >
							<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
							<i style="font-weight:100" style="color: '.$seguimiento->color.'">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
						</span>';
					}
					
				echo'
				</td>';
				
				echo'
				<td width="7%" align="right">$'.number_format($row->subTotal,2).'</td>
				<td width="7%" align="right">$'.number_format($row->descuento,2).'</td>
				<td width="7%" align="right">$'.number_format($row->iva,2).'</td>
				<td width="7%" align="right">$'.number_format($row->total,2).'</td>
			</tr>'; 
		}
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de ventas</div>';
}
?>
<?php

if($registros!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagReporte">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Estación</th>
			<th>Fecha</th>
			<th>Hora</th>
			<th>Importe</th>
			<th>Tipo</th>
			<th>Descripción</th>
			<th>Imprimir</th>
		</tr>';
		
		$i=$limite;
		foreach($registros as $row)
		{
			echo '
			<tr '.($i%2==0?'class="sombreado"':'class="sinSombra"').'>
				<td align="right">'.$i.'</td>
				<td align="center">'.$row->estacion.'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td align="center">'.obtenerHora($row->fecha).'</td>
				<td align="right">$'.number_format($row->pago,2).'</td>
				<td align="center">'.($row->tipoRegistro<3?($row->tipoRegistro=='1'?'Retiro':'Vale'):'Saldo inicial').'</td>
				<td align="center">'.$row->producto.'</td>
                <td align="center">';   
                
                if($row->tipoRegistro<3)
                {
                    echo '<a href="'.base_url().'reportes/imprimirTicketVales/'.$row->id.'" target="_blank"><img src="'.base_url().'img/print.png" width="22" />
                    <br>
                    Reimprimir
                    </a>';
                }
                
                echo'</td>
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagReporte">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de retiros</div>';
}

?>

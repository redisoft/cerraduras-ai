<?php
if($pedidos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagPedidos">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="18">
				Reporte de pedidos  
			</th>
		</tr>
		<tr>
			<th>No.</th>
			<th>Fecha</th>
			<th align="center">Hora</th>
			<th align="center">Tamaño y descripción</th>
			<th align="center">Cliente y teléfono</th>
			<th align="center">Referencia</th>
			<th align="center">Sucursal</th>
			<th align="center">Total</th>
			<th align="center">Abono</th>
			<th align="center">Debe</th>
			<th align="center">Tipo</th>
			<th align="center">Estado</th>
			<th align="center">Cambiar estado</th>
			<th align="center">Modifica pago</th>
			<th align="center">Repartidor</th>
			<th align="center">Finalizar</th>
			<th align="center">Ticket</th>
			<th align="center">PDF</th>
		</tr>';

		$i		= 1;
		$p		= 0;
		$total	= 0;
		
		foreach($pedidos as $row)
		{
			#$productos	= $this->reportes->obtenerProductosVentas($row->idCotizacion);

			$cancelada	= 0;

			echo '
			<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
				<td>'.$row->folio.'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fechaEntrega).'</td>
				<td align="center">'.obtenerFormatoHora($row->hora).'</td>
				<td title="'.($row->sabor.', '.$row->cobertura.', '.$row->relleno.', '.$row->forma.', '.$row->decoracion).'">'.substr($row->sabor.', '.$row->cobertura.', '.$row->relleno.', '.$row->forma.', '.$row->decoracion,0,50).'</td>
				<td>'.$row->empresa.' '.$row->telefono.'</td>
				<td>'.$row->referencia.'</td>
				<td>'.$empresa->nombre.'</td>
				<td align="right">$'.number_format($row->total,decimales).'</td>
				<td align="right">$'.number_format($row->pagado,decimales).'</td>
				<td align="right">$'.number_format($row->total-$row->pagado,decimales).'</td>
				<td>'.($row->idDireccion==0?'Recolección en sucursal':'Servicio a domicilio').'</td>
				<td>'.$row->estado.'</td>
				<td align="center">
					<img src="'.base_url().'img/semaforo.png" width="22" height="22" onclick="cambiarEstado('.$row->idPedido.','.($row->idEstado=='1'?2:1).')" title="Cambiar estado" />
					<br />
					Estado
				</td>
				<td align="center">
					<img src="'.base_url().'img/pagos.png" width="22" height="22" onclick="obtenerPagosClientes('.$row->idCotizacion.')" title="Pagos" />
					<br />
					Pago
				</td>
				<td align="center">
					<img src="'.base_url().'img/clientes.png" width="22" height="22" onclick="formularioRepartidores('.$row->idCotizacion.')" title="Repartidor" />
					<br />
					Repartidor
				</td>
				<td></td>
				<td align="center">
					<a href="'.base_url().'reportes/pedidoTicket/'.$row->idCotizacion.'" target="_blank" title="Ticket">
						<img src="'.base_url().'img/print.png" width="22" height="22" />
					</a>
					<br />
					Ticket
				</td>
				<td align="center">
					<a href="'.base_url().'reportes/pedidosReporte/'.$row->idCotizacion.'" target="_blank" title="Pedido">
						<img src="'.base_url().'img/printer.png" width="22" height="22" />
					</a>
					<br />
					PDF
				</td>
			</tr>';
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagPedidos">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de pedidos</div>';
}



?>

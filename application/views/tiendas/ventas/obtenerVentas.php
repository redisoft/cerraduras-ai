<?php
if($ventas!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaListaVentas tr:even").addClass("sombreado");
		$("#tablaListaVentas tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagVentas">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%" id="tablaListaVentas">	
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Fecha</th>
			<th class="encabezadoPrincipal">Orden</th>
			<th  width="30%" class="encabezadoPrincipal">Cliente</th>
			<th class="encabezadoPrincipal">Total</th>
			<th class="encabezadoPrincipal">Pagado</th>
			<th class="encabezadoPrincipal">Saldo</th>
			<th class="encabezadoPrincipal" width="20%">Acciones</th>
		</tr>';
	
	$i=$limite;
	foreach($ventas as $row)	
	{
		echo '
		<tr>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaCompra).'</td>
			<td>'.$row->ordenCompra.'</td>
			<td>'.$row->cliente.'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="right">$'.number_format(0,2).'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td class="vinculos" align="center">
				<a title="Imprimir venta" href="'.base_url().'pdf/nuevaVenta/'.$row->idCotizacion.'/1" target="_black">
					<img src="'.base_url().'img/printer.png" width="22px" height="22px" />
				</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a title="Imprimir ticket" href="'.base_url().'clientes/imprimirTicket/'.$row->idCotizacion.'" target="_black">
					<img src="'.base_url().'img/printer.png" width="22px" height="22px" />	
				</a>
				&nbsp;&nbsp;&nbsp;
				<img id="btnFacturacion'.$i.'" title="Facturación" src="'.base_url().'img/pdf.png" style="width:20px; cursor:pointer" onclick="obtenerDatosFactura('.$row->idCotizacion.')" />
				&nbsp;&nbsp;&nbsp;
				<img id="btnBorrarVenta'.$i.'" src="'.base_url().'img/borrar.png" onclick="accesoCancelarVenta('.$row->idCotizacion.')" title="Borrar venta" />
				<br />
				<a>Imprimir</a>
				<a>Ticket</a>
				<a id="a-btnFacturacion'.$i.'">Factura</a>
				<a id="a-btnBorrarVenta'.$i.'">Cancelar</a>';
				
				if($row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnFacturacion'.$i.'\');
						desactivarBotonSistema(\'btnBorrarVenta'.$i.'\');
					</script>';
				}
				
			
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	
	echo '</table>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagVentas">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de ventas</div>';
}
	
	
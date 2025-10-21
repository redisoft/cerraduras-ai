<?php
if($cotizaciones!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagCotizaciones">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Número cotización</th>
			<th>Cliente</th>
			<th>Concepto</th>
			<th>Subtotal</th>
			<th>Descuento</th>
			<th>IVA</th>
			<th>Total</th>
			<th>
				<select id="selectMotivos" onchange="obtenerCotizacionesAsignadas()" class="cajas" style="width:150px">
					<option value="0">Motivos</option>';
				
				foreach($motivos as $row)
				{
					echo '<option '.($row->idMotivo==$idMotivo?'selected="selected"':'').' value="'.$row->idMotivo.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</th>
		</tr>';
	
	$i=$limite;
	foreach($cotizaciones as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.' onclick="obtenerCotizacionInformacion('.$row->idCotizacion.')" title="Dar click para ver detalles">
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->serie.'</td>
			<td align="left">'.$row->empresa.'</td>
			<td align="left" >'.substr($row->producto,0,10).'...</td>
			<td align="right">$'.number_format($row->subTotal,2).'</td>
			<td align="right">$'.number_format($row->descuento,2).'</td>
			<td align="right">$'.number_format($row->iva,2).'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td>'.$row->motivos.'</td>';
				
				/*#if($row->cancelada==0)
				#{
					echo'
					&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/printer.png" title="Imprimir" width="22" style="cursor:pointer" onclick="formularioMargenCotizacion('.$row->idCotizacion.')"/>
					&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/ventas.png" title="Convertir cotización venta" width="22" style="cursor:pointer" onclick="obtenerDetallesCotizacion('.$row->idCotizacion.')"/>
					&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/editar.png" title="Editar cotización" width="22" style="cursor:pointer" onclick="obtenerCotizacion('.$row->idCotizacion.')"/>
					
					 &nbsp;&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/correo.png" width="20" height="20" title="Enviar correo" onclick="formularioCorreo('.$row->idCotizacion.');" style="cursor:pointer;"/>
					&nbsp;&nbsp;
					<img src="'.base_url().'img/remision.png" width="20" height="20" onclick="obtenerCotizacionInformacion('.$row->idCotizacion.');" style="cursor:pointer;"/>
					
					&nbsp;&nbsp;
					<img src="'.base_url().'img/borrar.png" title="Borrar cotización" width="22" style="cursor:pointer" onclick="borrarCotizacionCliente('.$row->idCotizacion.',\'¿Realmente desea borrar la cotización?\')"/>';
				#}
                    
				echo'<br />
				<a>Imprimir</a>&nbsp;
				<a>Venta</a>&nbsp;
				<a>Editar</a>&nbsp;
				<a>Enviar</a>&nbsp;
                <a>Ver</a>&nbsp;
				<a>Borrar</a>';

			echo'
			</td>*/
		
		echo'
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagCotizaciones">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de cotizaciones</div>';
}
<?php
if($cotizaciones!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagProcesadas">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cliente</th>
			<th>Cotización</th>
			<th>Venta</th>
			<th>Concepto</th>
			<th>Subtotal</th>
			<th>Descuento</th>
			<th>IVA</th>
			<th>Total</th>
			<th style="display:none" width="10%">Acciones</th>
		</tr>';
	
	$i=$limite;
	foreach($cotizaciones as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$onclick	= 'onclick="obtenerVentaInformacion('.$row->idCotizacion.')" title="Dar click para ver detalles" ';
		
		echo '
		<tr '.$estilo.'>
			<td align="right" '.$onclick.'>'.$i.'</td>
			<td align="center" '.$onclick.'>'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="left" '.$onclick.'>'.$row->empresa.'</td>
			
			<td align="left" '.$onclick.'>'.$row->serie.'</td>
			<td align="left" '.$onclick.'>'.$row->ordenCompra.'</td>
			
			<td align="left" '.$onclick.'>'.substr($row->producto,0,10).'...</td>
			<td align="right" '.$onclick.'>$'.number_format($row->subTotal,2).'</td>
			<td align="right" '.$onclick.'>$'.number_format($row->descuento,2).'</td>
			<td align="right" '.$onclick.'>$'.number_format($row->iva,2).'</td>
			<td align="right" '.$onclick.'>$'.number_format($row->total,2).'</td>
			<td align="center" style="display:none">
			
				<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" title="Editar cotización" width="22" style="cursor:pointer" onclick="accesoReutilizarVenta('.$row->idCotizacion.')"/>
				
				<br />
			
				<a id="a-btnEditar'.$i.'">Reutilizar</a>';
				
				if($permiso[2]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEditar'.$i.'\');
					</script>';
				}
				
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagProcesadas">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de procesadas</div>';
}
<?php
#if($cotizaciones!=null)
{
	echo '
	
	<table class="admintable" width="100%">
		<tr >
			<th colspan="12">
				<div style="width:90%">
				<ul id="pagination-digg" class="ajax-pagCotizaciones">'.$this->pagination->create_links().'</ul>
			 </div>
			</th>
		<tr>
		<tr>
			<th>#</th>
			<th>Fecha
				<img src="'.base_url().'img/'.($orden=='asc'?'ocultar':'mostrar').'.png" onclick="ordenCotizaciones(\''.($orden=='asc'?'desc':'asc').'\')" width="18" />
			</th>
			<th style="width:82px">';
				
				if($permiso[5]->activo=='1')
				{
					echo'
					<select class="cajas" id="selectEstaciones" name="selectEstaciones" style="width:80px" onchange="obtenerCotizaciones()">
						<option value="0">Estación</option>';

						foreach($estaciones as $row)
						{
							echo '<option '.($row->idEstacion==$idEstacion?'selected="selected"':'').' value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
						}

					echo '
					</select>';
				}
				else
				{
					echo '<input type="hidden"  	name="selectEstaciones" id="selectEstaciones" value="0"/> Estación';
				}
				
		
			echo'
			</th>
			<th>Serie</th>
			<th>Cliente</th>
			<th>Concepto</th>
			'.($desglose=='0'?'<th>Subtotal</th>
			<th>Descuento</th>
			<th>IVA</th>':'').'
			<th>Total</th>
			<th>CRM</th>
			<th width="35%">Acciones</th>
		</tr>';
	
	$i=$limite;
	foreach($cotizaciones as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$onclick	= 'onclick="obtenerCotizacionInformacion('.$row->idCotizacion.')" title="Dar click para ver detalles"';
		
		echo '
		<tr '.$estilo.' id="filaCotizacion'.$row->idCotizacion.'">
			<td align="right" '.$onclick.'>'.$i.'</td>
			<td align="center" '.$onclick.')">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center"  '.$onclick.'>'.$row->estacion.'</td>
			<td align="center" '.$onclick.'>'.$row->serie.' '.($row->cancelada=='1'?'(Cancelada)':'').'</td>
			<td align="left"  '.$onclick.'>'.$row->empresa.'</td>
			<td align="left"  '.$onclick.')">'.substr($row->producto,0,10).'...</td>
			'.($desglose==0?'
			<td align="right" '.$onclick.')">$'.number_format($row->subTotal,2).'</td>
			<td align="right" '.$onclick.')">$'.number_format($row->descuento,2).'</td>
			<td align="right" '.$onclick.')">$'.number_format($row->iva,2).'</td>':'').'
			<td align="right" '.$onclick.'>$'.number_format($row->total,2).'</td>';
			
			$seguimiento	= null;
			if(strlen($row->idSeguimiento)>0)
			{
				$seguimiento	= $this->crm->obtenerUltimoSeguimientoCotizacion($row->idCotizacion,$permisoCrm[4]->activo);
			}
			
			$mostrarSeguimiento=false;
			
			if($permisoCrm[0]->activo==1)
			{
				$mostrarSeguimiento=true;
			}
			
			echo'
			<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.',0)"'):'').' >';
				
				if($mostrarSeguimiento and $seguimiento!=null)
				{
					echo'
					<span >
						<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
						<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
					</span>';
				}
				
				if($mostrarSeguimiento and $seguimiento==null)
				{
					echo '<img src="'.base_url().'img/crm.png" width="22" height="22" />';
				}
				
			echo'
			</td>
			
			<td>
			
				&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/pdf.png" title="Imprimir" width="22" style="cursor:pointer" onclick="window.open(\''.base_url().'pdf/cotizacionPdf/'.$row->idCotizacion.'/0/'.$desglose.'\')"/>
				
				&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/printer.png" title="Imprimir" width="22" style="cursor:pointer" onclick="window.open(\''.base_url().'clientes/imprimirTicket/'.$row->idCotizacion.'/1\')"/>
				
				&nbsp;&nbsp;&nbsp;
				<img id="btnConvertirVenta'.$i.'" src="'.base_url().'img/ventas.png" title="Convertir cotización venta" width="22" style="cursor:pointer" onclick="accesoConvertirVenta('.$row->idCotizacion.')"/>
				
				&nbsp;&nbsp;&nbsp;
				<img id="btnEditarCotizacion'.$i.'" src="'.base_url().'img/editar.png" title="Editar cotización" width="22" style="cursor:pointer" onclick="accesoEditarCotizacion('.$row->idCotizacion.')"/>
				
				&nbsp;&nbsp;&nbsp;
				<img id="btnEnviarCotizacion'.$i.'" src="'.base_url().'img/correo.png" width="20" height="20" title="Enviar correo" onclick="formularioCorreo('.$row->idCotizacion.');" style="cursor:pointer;"/>
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnDesasignarCotizacion'.$i.'" src="'.base_url().'img/desasignado.png" width="24" height="20" onclick="obtenerCotizacionAsignada('.$row->idCotizacion.');" style="cursor:pointer;"/>
				&nbsp;&nbsp;&nbsp;
				
				&nbsp;
				<img src="'.base_url().'img/buscar.png" width="20" height="20" onclick="obtenerCotizacionInformacion('.$row->idCotizacion.');" style="cursor:pointer;"/>
				
				&nbsp;&nbsp;
				<img id="btnCancelarCotizacion'.$i.'" src="'.base_url().'img/cancelame.png" 	title="Cancelar cotización" width="22" style="cursor:pointer" onclick="accesoCancelarCotizacion('.$row->idCotizacion.')"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnBorrarCotizacion'.$i.'" src="'.base_url().'img/borrar.png" 	title="Borrar cotización" 	width="22" style="cursor:pointer" onclick="borrarCotizacionCliente('.$row->idCotizacion.',\'¿Realmente desea borrar la cotización?\')"/>
				
				<br />
				<a>Imprimir</a>
				<a>Ticket</a>
				<a id="a-btnConvertirVenta'.$i.'">Venta</a>
				<a id="a-btnEditarCotizacion'.$i.'">Editar</a>
				<a id="a-btnEnviarCotizacion'.$i.'">Enviar</a>
				<a id="a-btnDesasignarCotizacion'.$i.'">No asignada</a>
				<a>Ver</a>
				<a id="a-btnCancelarCotizacion'.$i.'">Cancelar</a>
				<a id="a-btnBorrarCotizacion'.$i.'">Borrar</a>';
					
				if($permiso[1]->activo==0 or $row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnConvertirVenta'.$i.'\');
						desactivarBotonSistema(\'btnDesasignarCotizacion'.$i.'\');
					</script>';
				}
				
				if($row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEnviarCotizacion'.$i.'\');
					</script>';
				}
				
				if($permiso[2]->activo==0 or $row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEditarCotizacion'.$i.'\');
					</script>';
				}

				if($permiso[3]->activo==0 or $row->cancelada=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnCancelarCotizacion'.$i.'\');
						desactivarBotonSistema(\'btnBorrarCotizacion'.$i.'\');
					</script>';
				}
	
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<!--<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagCotizaciones">'.$this->pagination->create_links().'</ul>
	 </div>-->';
}
/*else
{
	echo '<div class="Error_validar">Sin registro de cotizaciones</div>';
}*/

<?php
echo'
<form id="frmTraslado">
<input type="hidden" id="txtIdCotizacion" name="txtIdCotizacion" value="'.$cotizacion->idCotizacion.'" />
<table class="admintable" width="100%;">
	<tr>
	  <td class="key">Cliente:</td>
	  <td>'.$cliente->empresa.'</td>
	</tr>	
	
	<!--<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" style="width:120px" class="cajas" id="txtFechaFactura" name="txtFechaFactura" value="'.date('Y-m-d H:i').'" />
		</td>
	</tr>	-->

	<tr>
		<td class="key">Dirección: </td>

		<td id="obtenerDireccionesCfdi">
			<select style="width:500px" id="selectDireccionesCfdi" name="selectDireccionesCfdi" class="cajas" >
				<option value="0">Seleccione</option>';

				foreach($direcciones as $row)
				{
					echo '<option value="'.$row->idDireccion.'">'.$row->razonSocial.', '.$row->calle.' '.$row->numero.' '.$row->colonia.'</option>';
				}

			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Emisor:</td>
		<td>
			
			<select style="width:500px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
				<option value="0">Seleccione</option>';
			
				foreach($emisores as $row)
				{
					$seleccionado='';#$row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
					echo '<option '.($row->idEmisor==$cotizacion->idEmisor?'selected="selected"':'').' value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
				}
			
		echo'
		</td>
	</tr>
	
	<tr>
		<td class="key">Folio:</td>
		<td id="obtenerFolio" colspan="2">
			Seleccionar emisor
		</td>
	</tr>
	<tr>
		<td class="key">Uso del CFDI:</td>
		<td>
			<select id="selectUsoCfdi" name="selectUsoCfdi" class="cajas" style="width:500px">';
			
			foreach($usos as $row)	
			{
				echo '<option '.($row->idUso==$cotizacion->idUso?'selected="selected"':'').' value="'.$row->clave.'">'.$row->clave.', '.$row->descripcion.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	<tr>
		<td class="key">Observaciones:</td>
		<td>
			<textarea class="TextArea" id="txtObservaciones" name="txtObservaciones" style="width:500px; height:60px">'.$cotizacion->observaciones.'</textarea>
		</td>
	</tr>
</table>

<table class="admintable" width="100%;">
	<tr>
		<th>#</th>
		<th>Código</th>
		<th width="500px">Producto</th>
		<th>Unidad</th>
		<th>Cantidad</th>
		<th>Precio unitario</th>
		<th>Importe</th>
		<th width="26%">Pedimento</th>
	</tr>';

$i=1;

echo '<input type="hidden" id="txtNumeroProductosFactura" value="'.count($productos).'" />';

foreach($productos as $row)	
{
	echo '
	<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
		<td>'.$i.'</td>
		<td>'.$row->codigoInterno.'</td>
		<td>'.$row->nombre.'</td>
		<td align="center">'.$row->unidad.'</td>
		<td align="center" >'.number_format($row->cantidad,2).'</td>
		<td align="right">$'.number_format($row->precio,2).'</td>
		<td align="right">$'.number_format($row->importe,2).'</td>
		<td>
			<input type="text" name="txtPedimento1'.$i.'" id="txtPedimento1'.$i.'" value="'.$row->anio.'" class="cajas" placeholder="Año validación, (Últimos 2 dígitos)" maxlength="2"  onkeypress="return soloNumerico(event)" style="width:97%"/> 
			  
			<input type="text" name="txtPedimento2'.$i.'" id="txtPedimento2'.$i.'" value="'.$row->aduana.'" class="cajas" placeholder="Aduana despacho, (2 dígitos)" maxlength="2" onkeypress="return soloNumerico(event)" style="width:97%"/> 
			  
			<input type="text" name="txtPedimento3'.$i.'" id="txtPedimento3'.$i.'" value="'.$row->patente.'" class="cajas" placeholder="Número patente, (4 dígitos)" maxlength="4" onkeypress="return soloNumerico(event)" style="width:97%"/> 
			  
			<input type="text" name="txtPedimento4'.$i.'" id="txtPedimento4'.$i.'" value="'.$row->digitos.'" class="cajas" placeholder="1 Dígito año en curso + 6 dígitos numeración progresiva" maxlength="7" onkeypress="return soloNumerico(event)" style="width:97%"/> 
				
			<input type="text" class="cajas" name="txtFecha'.$i.'" value="'.$row->fecha.'" id="txtFecha'.$i.'" style="width:30%"/>
		</td>
	</tr>
	<script>
		$("#txtFecha'.$i.'").datepicker();
	</script>';
	
	$i++;
}
	
echo '
	</table>
</form>';


//PARA LAS FACTURAS PARCIALES
if($parciales!=null)
{
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="8">Facturas parciales</th>
		</tr>
		<tr>
			<th width="3%">#</th>
			<th>Fecha</th>
			<th>Folio</th>
			<th width="15%">Acciones</th>
		</tr>';
	
	$i=1;
	foreach($parciales as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cancelada	=$row->cancelada==1?'<i>(Cancelada)</i>':'';
		
		echo '
		<tr '.$estilo.'>
			<td align="center">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha,2).'</td>
			<td align="center">'.$row->serie.$row->folio.$cancelada.'</td>
			<td align="center">
				<img title="Factura" onclick="window.open(\''.base_url().'pdf/crearFactura/'.$row->idFactura.'\')" src="'.base_url().'img/pdf.png" width="20" />
				<img title="XML" onclick="window.location.href=\''.base_url().'facturacion/descargarXML/'.$row->idFactura.'\'" src="'.base_url().'img/xml.png" width="20" />
				<br />
				<a>PDF</a>
				<a>XML</a>
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}

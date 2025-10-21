<?php $this->load->view('formatos/cotizacion/cabecera');?>

<table class="tablaSucursales">
	<tr>
		<td width="30%" align="center">
			<?php
			if(strlen($this->session->userdata('logotipo'))>3 and file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width:120px; max-height:120px;" >';
			}
			?>
			
		</td>
		<?php
		#foreach($sucursales as $row)
		{
			echo '
			<td style="font-size: 16px">
				'.$empresa->nombre.' <br>'.
				$empresa->direccion.' '. $empresa->numeroExterior.' '.$empresa->numero.' <br />
				'.$empresa->colonia.' C.P. '.$empresa->codigoPostal.' '.$empresa->municipio.' '.$empresa->estado.' 
				<br />
				TEL: '.$empresa->telefono.'
				
			</td>';
		}
		?>
	</tr>
</table>

<div style="width:50%; float: left; font-weight: bold; font-size: 14px">
	Estimado cliente <?=$cliente->empresa?> <br /><br />
	Por medio de la presente ponemos a su<br />
	consideración los siguiente producto, esperando<br />
	vernos favorecidos por su preferencia.<br />
</div>

<div style="width:49%; float: left">
	<table class="tablaEncabezados">
		<tr>
			<th colspan="2" class="pleca">COTIZACION-PRE CFDI</th>
		</tr>
		<tr>
			<td class="normal">Número de Cliente</td>
			<td class="normal">Su Vendedor</td>
		</tr>
		<tr>
			<td align="center"><?=$cliente->alias?></td>
			<td align="center"><?=$cotizacion->usuario?></td>
		</tr>
	</table>
</div>

<table class="tablaVarios">
	<tr>
		<th colspan="10" class="plecaNaranja">Datos para emision de CFDI</th>
	</tr>
	<tr>
		<td class="etiqueta">RFC:</td>
		<td><?=$cliente->rfc?></td>
		<td class="etiqueta">C.P.:</td>
		<td><?=$direccion->codigoPostal?></td>
		<td class="etiqueta">Forma de pago:</td>
		<td><?=$cotizacion->formaPagoSat?></td>
		<td class="etiqueta">Uso CFDI:</td>
		<td><?=$cotizacion->usoCfdi?></td>
		<td class="etiqueta">Método de pago:</td>
		<td><?=$cotizacion->metodoPago?></td>
	</tr>
</table>

<table class="tablaVarios">
	<tr>
		<th class="plecaNaranja">Dirección de entrega</th>
	</tr>
	<tr>
		<td class="normal" align="center">
			<?php  
			if($direccion!=null)
			{
				echo $direccion->calle.' '. $direccion->numero.' '.$direccion->colonia.' 
				'.$direccion->localidad.' '.$direccion->municipio.' '.$direccion->estado.', C.P.'.$direccion->codigoPostal;
			}
			
			
			?>
		</td>
	</tr>
</table>



<!--<div style="border-radius: 6px; border: solid 1px #000; width:100%; margin-top:15px ">-->
	<table class="tablaCotizaciones">
    	<tr>
        	<th></th>
            <th width="55%">Descripción</th>
        	<th width="12%">Cantidad</th>
            <th width="14%">Precio</th>
            <th width="14%">Total</th>
        </tr>
        
        <?php
		$subTotal			= $cotizacion->subTotal;
		$descuento			= ($subTotal*$cotizacion->descuentoPorcentaje)/100;
		$totalDescuento		= $subTotal-$descuento;
		$iva				= $totalDescuento*$cotizacion->iva;

		$totalDescuento		=0;
		$i=1;
		foreach($productos as $row)
		{
			$producto			=strlen($row->producto)>0?$row->producto:$row->descripcion;
			$totalDescuento		+=$row->descuento;

			$precio			= $row->precio;
			$importe		= $row->importe;

			if($desglose=='1')
			{
				$precio		= round($row->precio*(1+($cotizacion->ivaPorcentaje/100)),2);
				$importe	= $row->cantidad*$precio;

			}
			
			echo '
			<tr>
				<td class="numeral">'.obtenerNumeral($i).'</td>
				<td>
					'.$producto.' <br />
					Item code: '.$row->codigoInterno.'
				</td>
				<td align="center">'.number_format($row->cantidad,2).' '.$row->unidad.'</td>
				<td align="right">$'.number_format($precio,2).'</td>
				<td align="right">$'.number_format($importe,2).'</td>
			</tr>';

			$i++;
		}
        ?>
    </table>
	
<!--</div>-->

<div class="filaPlazos">
	<table class="tablaCotizaciones">
		<tr>
			<td class="normal pleca plecaIzquierda">Plazo de pago</td>
			<td class="normal pleca" align="right">Contado</td>
		</tr>
	</table>

	Los precios aquí estipulados podrán sufrir cambios sin previo aviso.

	<br /><br />
	<div class="filaComentariosTotales"></div>
</div>

<div style="width: 5%; float: left;">&nbsp;</div>

<div class="filaTotales">
	<table class="tablaCotizaciones">
		<tr>
			<td class="normal">Subtotal</td>
			<td class="normal" align="right">$<?=number_format($cotizacion->subTotal,2)?></td>
		</tr>
		<tr>
			<td class="normal">IVA</td>
			<td class="normal" align="right">$<?=number_format($cotizacion->iva,2)?></td>
		</tr>
		<tr>
			<td class="normal pleca plecaIzquierda">Total</td>
			<td class="normal pleca" align="right">$<?=number_format($cotizacion->total,2)?></td>
		</tr>
	</table>
</div>







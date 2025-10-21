<?php #$this->load->view('formatos/cotizacion/cabecera');?>

<table class="tablaSucursales">
	<tr>
		<td width="30%">
			<?php
			if(strlen($this->session->userdata('logotipo'))>3 and file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width:150px; max-height:150px;" >';
			}
			?>
			
		</td>
		<?php
		echo '
		<td width="40%" class="empresa">
			'.$empresa->nombre.' <br>
			'.$empresa->rfc.' <br>'.
			$empresa->direccion.' '. $empresa->numeroExterior.' '.$empresa->numero.'  <br>
			'.$empresa->colonia.' C.P. '.$empresa->codigoPostal.' <br> 
			'.$empresa->municipio.' '.$empresa->estado.' 
			<br />
			Tel: '.$empresa->telefono.'
			<br />
			eMail: '.$empresa->correo.'
				
		</td>';
		?>
	</tr>

		<td width="40	%">
			<table class="tablaSucursales">
				<tr>
					<td class="resaltado">Cotización</td>
				</tr>
				<tr>
					<td class="informacion" style="color: red"><?=$cotizacion->folioCotizacion?></td>
				</tr>
				<tr>
					<td class="resaltado">Fecha</td>
				</tr>
				<tr>
					<td class="informacion"><?=obtenerFechaMesCorto($cotizacion->fecha)?></td>
				</tr>
				<tr>
					<td class="resaltado">Moneda: MXN</td>
				</tr>
			</table>
			
		</td>
</table>

<div class="division"></div>

<table class="tablaSucursales">
	<tr>
		<td class="clienteEtiquetas">Nombre:</td>
		<td class="cliente" width="50%"><?=$cliente->empresa?></td>
		<td class="clienteEtiquetas">R.F.C.:</td>
		<td class="cliente" width="20%"><?=$cliente->rfc?></td>
	</tr>
	<tr>
		<td class="clienteEtiquetas">eMail:</td>
		<td class="cliente"><?=$cliente->email?></td>
		<td class="clienteEtiquetas">Tel:</td>
		<td class="cliente"><?=$cliente->telefono?></td>
	</tr>

	<tr>
		<td class="clienteEtiquetas">Colonia:</td>
		<td colspan="3" class="cliente"><?=$direccion->colonia?></td>
	</tr>
	<tr>
		<td class="clienteEtiquetas">Ciudad:</td>
		<td colspan="3" class="cliente" ><?=$direccion->municipio?></td>
	</tr>
</table>

<div class="division"></div>




<!--<div style="border-radius: 6px; border: solid 1px #000; width:100%; margin-top:15px ">-->
	<table class="tablaCotizaciones">
    	<tr>
        	<th width="14%">IMG / CLAVE</th>
			<th width="10%">CANT</th>
			<th width="12%">UNIDAD</th>
            <th width="40%">DESCRIPCIÓN</th>
            <th width="12%">P. UNIT.</th>
            <th width="12%">IMPORTE</th>
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
				<td>'.$row->codigoInterno.'</td>
				<td align="center">'.number_format($row->cantidad,2).' </td>
				<td align="center">'.$row->unidad.'</td>
				<td>'.$producto.'</td>
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







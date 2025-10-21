<?php

$this->load->view('facturacion/efactura');
?>

	<div>
    	<div style="float:left; width:30%">

         <?php
        if(file_exists(carpetaCfdi.$factura->idLicencia.'_facturacion/cfdi/'.$configuracion->rfc.'/'.$configuracion->logotipo) and strlen($configuracion->logotipo)>3)
		{
			echo '<img src="'.base_url().carpetaCfdi.$factura->idLicencia.'_facturacion/cfdi/'.$configuracion->rfc.'/'.$configuracion->logotipo.'" style="width:200px; height:90px;" />';
		}
		
		if(file_exists(carpetaCfdi.$factura->idLicencia.'_facturacion/cfdi/'.$configuracion->rfc.'/'.$configuracion->logotipo2) and strlen($configuracion->logotipo2)>3)
		{
			echo '<img src="'.base_url().carpetaCfdi.$factura->idLicencia.'_facturacion/cfdi/'.$configuracion->rfc.'/'.$configuracion->logotipo2.'" style="width:200px; height:90px;" />';
		}
		?>
        </div>
        <div style="float:right; width:70%">
        <table width="100%" class="tablaFactura">
        	<tr>
            	<th colspan="2" style="background-color:#FFF; border-left-color:#FFF; border-top-color:#FFF; border-right-color:#FFF; font-size:14px; "><?php echo $factura->documento?></th>
            </tr>
        	<tr>
            	<th>UUID</th>
                <th>USO CFDI</th>
            </tr>
            <tr>
                <td align="center" ><?php echo $factura->UUID?></td>
                <td align="center" ><?php echo $factura->usoCfdi?></td>
            </tr>
           
            <tr>
                <th>SERIE Y FOLIO INTERNO</th>
                <th>LUGAR DE EXPEDICIÓN</th>
            </tr>
            <tr>
                <td align="center"><?php echo $factura->serie.$factura->folio?></td>
                <td align="center"><?php echo $configuracion->lugarExpedicion?></td>
            </tr>

            <tr>
                <th colspan="2">FECHA</th>
            </tr>
            <tr>
                <td colspan="2" align="center"><?php echo $factura->fecha ?></td>
            </tr>
        </table>
        </div>
    </div>
	
    <div style="margin-top:3px">
    	<div style="width:49%; float:left">
        <table width="100%" class="tablaFactura">
        	<tr>
            	<th colspan="">EMISOR</th>
            </tr>
            <tr>
            	<td>
                <?php
				echo 'R.F.C. '.$configuracion->rfc.'<br />';
				echo $configuracion->nombre.'<br />';
				echo $configuracion->direccion.' '. $configuracion->numero.' '.$configuracion->colonia.'<br />';
				echo $configuracion->localidad.', '. $configuracion->municipio.', '.
				$configuracion->estado.', '.$configuracion->pais.', C.P '.$configuracion->codigoPostal.', 
				<br />Regimen Fiscal: '.$configuracion->claveRegimen.' - '.$configuracion->regimenFiscal;
				if(strlen($configuracion->numeroCuenta)>0)
				{
					echo ', No. Cuenta: '.$configuracion->numeroCuenta;
				}
                ?>
                </td>
            </tr>
        </table>
        </div>
        <div style="width:49%; float:right">
         <table width="100%" class="tablaFactura">
        	<tr>
            	<th colspan="">RECEPTOR</th>
            </tr>
            <tr>
            	<td>
                 <?php
				echo 'R.F.C. '.$factura->rfc.'<br />';
				echo $factura->empresa.'<br />';
				echo strlen($factura->direccion)>0?$factura->direccion:'';
				echo strlen($factura->numero)>0?' '.$factura->numero:'';
				echo strlen($factura->colonia)>0?', '.$factura->colonia:'';
				echo '<br />';
				
				echo strlen($factura->ciudad)>0?$factura->ciudad:'';
				
				echo strlen($factura->municipio)>0?', '.$factura->municipio:'';
				
				if(strlen($factura->municipio)==0)
				{
					echo ', '.$cliente->municipio;	
				}
				
				echo strlen($factura->estado)>0?', '.$factura->estado:'';
				echo ', '.$factura->pais;
				echo strlen($factura->codigoPostal)>0?', '.$factura->codigoPostal:'';
                ?>
                </td>
            </tr>
        </table>
        </div>
    </div>
    
	<div style="padding-top:3px">
    <table class="tablaFactura" style="width:100%;">
        <tr>
            <th width="15%" style="color:#000">CÓDIGO</th>
            <th width="10%" style="color:#000">CANTIDAD</th>
            <th width="10%" style="color:#000">U.M</th>
            <th width="10%" style="color:#000">CLAVE PRODUCTO</th>
            <th width="25%" style="color:#000">DESCRIPCIÓN</th>
            <th width="10%" style="color:#000" align="right">PRECIO UNITARIO</th>
            <th width="10%" style="color:#000" align="right">DESC</th>
            <th width="10%" style="color:#000" align="right">IMPUESTO</th>
            <th width="10%" style="color:#000" align="right">IMPORTE</th>
        </tr>
    
    <?php
	$descuentos	=0;
	$importes	=0;
	foreach($productos as $row)
	{
		/*$cantidad	=$row->cantidad;
		$precio		=$row->precio;
		$importe	=$row->importe;
		$descuentos	+=$row->descuento;
		$importes	+=$row->importe;*/
		
		$cantidad	=$row->cantidad;
		$precio		=$row->precio;
		$importe	=$row->precio*$row->cantidad; #$importe	=$row->importe; 
		
		$descuentos	+=$row->descuento;
		#$importes	+=$row->importe;
		
		$importes	+=$row->precio*$cantidad;

		$marca	=strlen($row->marcaP)>1?$row->marcaP:$row->marca;
		$ds		=strlen($row->dsP)>1?$row->dsP:$row->ds;
		$medida	=strlen($row->medidaP)>1?$row->medidaP:$row->medida;
		?>
         <tr>
         	<td width="15%" align="center" style="border-bottom:none; border-top:none"><?php echo $row->codigoInterno?></td>
            <td width="10%" style="border-bottom:none; border-top:none" align="center"><?php echo number_format($row->cantidad,2)?></td>
            <td width="10%" align="center" style="border-bottom:none; border-top:none"><?php echo $row->unidad?></td>
            <td width="10%" align="center" style="border-bottom:none; border-top:none"><?php echo $row->claveProducto?></td>
    		<td width="25%" style="border-bottom:none; border-top:none"><?php echo $marca.' '.$medida.' '.$row->nombre.' '.$ds?></td>
            <td width="10%" style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($precio,2)?></td>
            <td width="10%" align="right" style="border-bottom:none; border-top:none">$<?php echo number_format($row->descuento,2)?></td>
            <td width="10%" style="border-bottom:none; border-top:none" align="right">$<?php echo number_format($row->iva,2)?></td>
            <td width="10%" style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($importe-$row->descuento,2)?></td>
    	 </tr>
    
        <?php
	}
	
	$descuento	=$factura->subTotal*($factura->descuentoPorcentaje/100);
	$suma		=$factura->subTotal-$descuento;
	$iva		=$suma*$factura->iva;
	$total		=$suma+$iva;
    ?>

    <tr>
        <td colspan="7" style="border-bottom:none" ></td>
        <td class="totales" style="border-bottom:none" align="right">SUBTOTAL</td>
        <td style="border-bottom:none" align="right">$ <?php echo number_format($factura->subTotal,2)?></td>
    </tr>
    <tr>
        <td colspan="7" style="border: none"></td>
        <td class="totales" style="border-bottom:none; border-top:none" align="right">IVA <?php echo number_format($factura->iva*100,2)?>%</td>
        <td style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($factura->ivaTotal,2)?></td>
    </tr>

    <tr>
        <td colspan="7" style="border: none" align="right">
        <?php  echo $cantidadLetra.' '. $factura->claveDivisa?>
        </td>
        <td class="totales" style="border-bottom:none; border-top:none"  align="right">TOTAL</td>
        <td style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($factura->total,2)?></td>
        </tr>
    </table>
    
    
    </div>
    
    
    <?php
if(strlen($configuracion->calleSucursal)>3 and strlen($configuracion->coloniaSucursal)>3)
{
	echo '<div style="border: solid 0.1px #333; font-size:11px;">Emitido en sucursal: '.$configuracion->calleSucursal.' '.$configuracion->numeroExteriorSucursal.' '.$configuracion->coloniaSucursal
	.', '.$configuracion->localidadSucursal.', '.$configuracion->municipioSucursal.', '.$configuracion->estadoSucursal.', '.$configuracion->paisSucursal
	.' CP.'.$configuracion->codigoPostalSucursal.', Teléfono '.$configuracion->telefonoSucursal.'</div>';
}


?>

<table class="tablaFactura" style="width:100%; margin-top:3px">
	<tr>
    	<th colspan="3" class="sinBordes" style="borde-color: #FFF !important;">Complemento Recepción de Pagos</th>
    </tr>
    <tr>
    	<th colspan="3">Información Cliente-Proveedor</th>
    </tr>
    
    <tr>
        <td>
            Emisor cuenta ordenante:<br />
            <?php echo $pago->rfcOrdenante?><br />
        </td>
        <td>
            Emisor cuenta Beneficiario:<br />
            <?php echo $pago->rfcBeneficiario?>
        </td>
        <td>
            Nombre banco ordenante:<br />
            <?php echo $pago->nombreBanco?>
        </td>
    </tr>
    <tr>
        <td>
            Cuenta ordenante:<br />
            <?php echo $pago->cuentaOrdenante?>
        </td>
        <td>
            Cuenta benficiario:<br />
            <?php echo $pago->cuentaBeneficiario?>
        </td>
        <td>
           
        </td>
    </tr>
    <tr>
    	<th colspan="3" class="sinBordes" style="borde-color: #FFF !important;">Información del deposito</th>
    </tr>
    
    <tr>
        <td>
            Fecha de pago:  <?php echo $pago->fechaPago?><br />
            
            Monto: <?php echo number_format($pago->importe,2)?>
        </td>
        <td>
            Tipo de cambio: <?php echo number_format($factura->tipoCambio,2)?> <br />
            
             Moneda: <?php echo $factura->claveDivisa?>
        </td>
        <td>
            Forma de pago: <?php echo $factura->formaPago?><br />
            Número de operación: <?php echo $pago->numeroOperacion?>
        </td>
    </tr>
</table>

<table class="tablaFactura" style="width:100%; margin-top:3px">
	<tr>
    	<th colspan="9">Documento relacionado</th>
    </tr>
    <tr>
    	<th>ID Documento</th>
        <th>Serie y folio</th>
        <th>Moneda DR</th>
        <th>Tipo de cambio</th>
        <th>Método de pago</th>
        <th>Número parcialidad</th>
        <th>Saldo anterior</th>
        <th>Pago</th>
        <th>Saldo insoluto</th>
    </tr>
    
    <?php
    echo '
	<tr>
		<td>'.$relacion->UUID.'</td>
		<td align="center">'.$relacion->serie.$relacion->folio.'</td>
		<td align="center">'.$relacion->claveDivisa.'</td>
		<td align="center">'.number_format($relacion->tipoCambio,2).'</td>
		<td>'.$relacion->metodoPago.'</td>
		<td align="center">'.$pago->numeroParcialidad.'</td>
		<td align="right">$'.number_format($pago->importeAnterior,2).'</td>
		<td align="right">$'.number_format($pago->importe,2).'</td>
		<td align="right">$'.number_format($pago->saldoInsoluto,2).'</td>
	</tr>';
	?>
</table>
	


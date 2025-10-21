
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet"  href="<?php echo base_url();?>css/adm/tablas.css" />
</head>
<body>

<?php

#$this->load->view('facturacion/efactura');
?>

	<div>
    	<div style="float:left; width:30%">
        <?php
		if(strlen($configuracion->logotipo)>2)
		{
			if(file_exists("media/fel/".$configuracion->rfc.'/'.$configuracion->logotipo))
			{
				$imagen='<img src="'.base_url().'media/fel/'.$configuracion->rfc.'/'.$configuracion->logotipo.'" style="height:100px; width:180px" />';
				echo $imagen;
			}
		}
        ?>
        </div>
        <div style="float:right; width:70%">
        <table width="100%" class="tablaFactura">
        	<tr>
            	<th colspan="2" style="background-color:#FFF; border-color:#FFF; font-size:14px; "><?php echo $factura->documento?></th>
            </tr>
        	<tr>
            	<th colspan="2">UUID</th>
            </tr>
            <tr>
                <td align="center" colspan="2"><?php echo $factura->UUID?></td>
            </tr>
            <tr>
            	<th>FORMA DE PAGO</th>
                <th>SERIE Y FOLIO INTERNO</th>
            </tr>
            <tr>
            	<td align="center"><?php echo $factura->formaPago ?></td>
                <td align="center"><?php echo $factura->serie.$factura->folio?></td>
            </tr>
            
            <tr>
            	<th>CONDICIONES DE PAGO</th>
                <th>LUGAR DE EXPEDICIÓN</th>
            </tr>
            <tr>
            	<td align="center"><?php echo $factura->condicionesPago?></td>
                <td align="center"><?php echo 'PUEBLA' ?></td>
            </tr>
            
            <tr>
            	<th>METODO DE PAGO</th>
                <th>FECHA</th>
            </tr>
            <tr>
            	<td align="center"><?php echo $factura->metodoPago?></td>
                <td align="center"><?php echo $factura->fecha ?></td>
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
				echo $configuracion->calle.' '. $configuracion->numeroExterior.' '.$configuracion->colonia.'<br />';
				echo $configuracion->localidad.', '. $configuracion->municipio.', '.
				$configuracion->estado.', '.$configuracion->pais.', C.P '.$configuracion->codigoPostal.', 
				<br />Regimen Fiscal: '.$configuracion->regimenFiscal;
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
				echo 'R.F.C. '.$cliente->rfc.'<br />';
				echo $cliente->empresa.'<br />';
				echo $cliente->calle.' '. $cliente->numero.' '.$cliente->colonia.'<br />';
				echo $cliente->localidad.', '. $cliente->municipio.', '.
				$cliente->estado.', '.$cliente->pais.', C.P '.$cliente->codigoPostal.'<br />';
                ?>
                </td>
            </tr>
        </table>
        </div>
    </div>
    
	<div style="padding-top:3px">
    <table class="tablaFactura" style="width:100%;">
    <tr>
    	<th style="color:#000">CÓDIGO</th>
        <th style="color:#000">CANTIDAD</th>
        <th style="color:#000">UNIDAD</th>
        <th width="40%" style="color:#000">DESCRIPCIÓN</th>
        <th style="color:#000" align="right">PRECIO UNITARIO</th>
        <th style="color:#000" align="right">DESCUENTO</th>
        <th style="color:#000" align="right">IMPORTE</th>
    </tr>
    
    <?php
	foreach($productos as $row)
	{
		?>
         <tr>
         	<td align="center"><?php echo $row->codigoInterno?></td>
            <td align="center"><?php echo number_format($row->cantidad,2)?></td>
            <td align="center"><?php echo $row->unidad?></td>
    		<td><?php echo $row->nombre?></td>
            <td align="right">$<?php echo number_format($row->precio,2)?></td>
             <td align="right">$<?php echo number_format($row->descuento,2)?></td>
            <td align="right">$<?php echo number_format($row->importe,2)?></td>
    	 </tr>
    
        <?php
	}
    ?>

    <tr>
        <td colspan="5"  ></td>
        <td class="totales" align="right">SUBTOTAL</td>
        <td align="right">$<?php echo number_format($factura->subTotal,2)?></td>
    </tr>
    
    <tr>
        <td colspan="5" ></td>
        <td class="totales"  align="right">DESCUENTO <?php echo number_format($factura->descuentoPorcentaje,2)?>%</td>
        <td  align="right">$<?php echo number_format($factura->descuento,2)?></td>
    </tr>
    
    <tr>
        <td colspan="5" ></td>
        <td class="totales"  align="right">IVA <?php echo number_format($factura->ivaPorcentaje,2)?>%</td>
        <td  align="right">$<?php echo number_format($factura->iva,2)?></td>
    </tr>
    
    <?php
	if($retencion!=null)
	{
		?>
    	<tr>
            <td colspan="5" ></td>
            <td class="totales" align="right"><?php echo $retencion->retencion.' '. number_format($retencion->tasa,2)?>%</td>
            <td  align="right">$<?php echo number_format($retencion->importe,2)?></td>
   	 	</tr>
    	<?php
	}
    ?>
    
         <tr>
             <td colspan="5" align="right">
             <?php  echo $cantidadLetra.' '. $factura->claveDivisa?>
             </td>
            <td class="totales" align="right">TOTAL</td>
            <td align="right">$<?php echo number_format($factura->total,2)?></td>
        </tr>
    </table>
    <?php
	if(strlen($factura->observaciones))
	{
		echo '<span style="font-size:13px">Observaciones: <br />'.sustituirSaltos($factura->observaciones).'</span>';
	}
	?>

    </div>

	<img src="<?php echo base_url()?>img/sinValor.png" width="300px" style="position:absolute; margin-left:30%; margin-top:-3%" />

</body>
</html>




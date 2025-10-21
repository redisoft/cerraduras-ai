<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet"  href="<?php echo base_url();?>css/reportes.css" />
</head>
<body>
	
    <div>
    
    <div style="border:none; float:none; width:100%" class="divDatosNomina">
    	<strong style="font-size:24px">CFDI</strong> <span style="font-size:13px">Comprobante Fiscal Digital a través de Internet</span>
    </div>
    
    <div style="float:left; width:47%; height:142px; margin-top:5px" class="divDatosNominaResaltado">
        <table width="100%" class="tablaNomina">
            <tr>
                <td class="negrita">Folio Fiscal</td>
                <td align="right"><?php echo $factura->UUID?></td>
            </tr>
            <tr>
                <td class="negrita">Certificado SAT</td>
                <td align="right"><?php echo $factura->certificadoSat?></td>
            </tr>
            <tr>
                <td class="negrita">Certificado del emisor</td>
                <td align="right"><?php echo $configuracion->numeroCertificado?></td>
            </tr>
            
            <tr>
                <td class="negrita">Fecha y hora de certificación</td>
                <td align="right"><?php echo $factura->fechaTimbrado?></td>
            </tr>
            <tr>
                <td class="negrita">Régimen fiscal</td>
                <td align="right"><?php echo $configuracion->regimenFiscal?></td>
            </tr>
            <tr>
                <td class="negrita">Lugar de expedición</td>
                <td align="right"><?php echo $configuracion->municipio.', '.$configuracion->estado?></td>
            </tr>
        </table>
    </div>
    
    <div style="float:left; width:270px; margin-left:3px" class="divDatosNomina">
        <table width="100%" class="tablaNomina" style="background-color:none; border:none">
            <tr>
                <td class="negrita">Tipo de comprobante</td>
                <td align="right"><?php echo $factura->documento?></td>
            </tr>
            <tr>
                <td class="negrita">Folio/Serie</td>
                <td align="right"><?php echo $factura->serie.$factura->folio?></td>
            </tr>
            <tr>
                <td class="negrita">Fecha y hora de emisión</td>
                <td align="right"><?php echo $factura->fecha?></td>
            </tr>
            
            <tr>
                <td class="negrita">Condiciones de pago</td>
                <td align="right"><?php echo $factura->condicionesPago?></td>
            </tr>
            <tr>
                <td class="negrita">Método de pago</td>
                <td align="right"><?php echo $factura->metodoPago?></td>
            </tr>
            <tr>
                <td class="negrita">No. de cuenta de pago</td>
                <td align="right"><?php echo $configuracion->numeroCuenta?></td>
            </tr>
            <tr>
                <td class="negrita">Moneda</td>
                <td align="right"><?php echo $factura->claveDivisa?></td>
            </tr>
            <tr>
                <td class="negrita">Tipo de cambio</td>
                <td align="right"><?php echo number_format($factura->tipoCambio,2)?></td>
            </tr>
        </table>
    </div>
    <div style="float:left">
    	<img src="<?php echo base_url().'media/fel/'.$configuracion->rfc.'/folio'.$factura->serie.$factura->folio.'/codigo'.$factura->folio.'.png'?>"/>
    </div>
    
    <div style="float:left; width:47%" class="divDatosNomina">
   
		<?php
		echo '<strong>Emisor</strong> <br /><br />';
        echo '<strong>'.$configuracion->rfc.'</strong> '.$configuracion->nombre.'<br /><br />';
        echo $configuracion->calle.' '. $configuracion->numeroExterior.' <br />
		COL. '.$configuracion->colonia.' '.$configuracion->localidad.'C.P'.$configuracion->codigoPostal.'<br />
		'. $configuracion->municipio.', '.$configuracion->estado.', '.$configuracion->pais.'<br /><br />';
		
		echo '<strong>Expedido en</strong> <br />';
		echo $configuracion->calle.' '. $configuracion->numeroExterior.' <br />
		COL. '.$configuracion->colonia.' '.$configuracion->localidad.'C.P'.$configuracion->codigoPostal.'<br />
		'. $configuracion->municipio.', '.$configuracion->estado.', '.$configuracion->pais;
        ?>
         
    </div>
    
    <div style="float:left; margin-left:3px; width:49%;" class="divDatosNomina">
		<?php
        echo '<strong>Receptor</strong> <br /><br />
		
		<strong>'.$factura->rfc.'</strong> '.$factura->empresa.'<br />';
		echo $factura->calle.' '. $factura->numeroExterior.' <br />
		COL. '.$factura->colonia.' '.$factura->localidad.'C.P'.$factura->codigoPostal.'<br />
		'. $factura->municipio.', '.$factura->estado.', '.$factura->pais.'<br /><br />';
		
        ?>
    </div>

</div>
    
	<div style="padding-top:3px">
    <table class="tablaNomina" style="width:100%; background-color:none; border:none">
        <tr>
            <th class="titulosTablas" width="15%" style="color:#000">Código</th>
            <th class="titulosTablas" width="30%" style="color:#000">Descripción del producto</th>
            <th class="titulosTablas" style="color:#000">Cantidad</th>
            <th class="titulosTablas" style="color:#000">Unidad de medida</th>
           
            <th class="titulosTablas" style="color:#000" align="right">Precio</th>
            <th class="titulosTablas" style="color:#000" align="right">Importe</th>
        </tr>
    
    <?php
	foreach($productos as $row)
	{
		$cantidad	=$row->cantidad;
		$precio		=$row->precio;
		$importe	=$row->importe;

		?>
         <tr>
         	
         	<td style="border-bottom:none; border-top:none" align="center"><?php echo $row->codigoInterno?></td>
            <td style="border-bottom:none; border-top:none"><?php echo $row->nombre?></td>
            <td style="border-bottom:none; border-top:none" align="center"><?php echo number_format($row->cantidad,2)?></td>
            <td style="border-bottom:none; border-top:none" align="center"><?php echo $row->unidad?></td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($precio,2)?></td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($importe,2)?></td>
    	 </tr>
    
        <?php
	}
	
	$descuento	=$factura->subTotal*($factura->descuento/100);
	$suma		=$factura->subTotal-$descuento;
	$iva		=$suma*$factura->iva;
	$total		=$suma+$iva;
    ?>

    <tr>
        <td class="negritaBorde" colspan="4">
        	Importe con letra &nbsp;&nbsp; &nbsp; *** <?php  echo ($cantidadLetra).' '. $factura->claveDivisa?> ***
        </td>
        <td class="negritaBorde" style="border-bottom:none" align="right">Subtotal</td>
        <td class="negritaBorde" align="right">$ <?php echo number_format($factura->subTotal,2)?></td>
    </tr>
    
    <tr>
        <td class="negrita" colspan="4" style="border: none" >
        
        Desglose de impuestos trasladados &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Desglose de impuestos retenidos
        </td>
        <td class="negrita" style="border-bottom:none; border-top:none" align="right">Descuentos <?php echo number_format($factura->descuento,2)?>%</td>
        <td class="negrita" align="right">$ <?php echo number_format($descuento,2)?></td>
    </tr>
    
    <tr>
        <td class="negrita" colspan="4" style="border: none">
        	IVA <?php echo number_format($factura->iva*100,2)?>%  &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; $ <?php echo number_format($iva,2)?>
        </td>
        <td class="negrita" style="border-bottom:none; border-top:none" align="right">Impuestos trasladados </td>
        <td class="negrita" align="right">$ <?php echo number_format($iva,2)?></td>
    </tr>
    
    <tr>
        <td class="negrita" colspan="4" style="border: none">
        </td>
        <td class="negrita" style="border-bottom:none; border-top:none" align="right">Impuestos retenidos</td>
        <td class="negrita" align="right">$ <?php echo number_format(0,2)?></td>
    </tr>
    
    <?php
	if($retencion!=null)
	{
		?>
    	<tr>
            <td class="negrita" style="border: none"></td>
            <td class="negrita" style="border-bottom:none; border-top:none" align="right"><?php echo $retencion->retencion.' '. number_format($retencion->tasa,2)?>%</td>
            <td class="negrita" align="right">$ <?php echo number_format($retencion->importe,2)?></td>
   	 	</tr>
    	<?php
	}
    ?>
    
    <tr>
        <td colspan="4" style="border: none" align="right">
        </td>
        <td class="negrita" style="font-size:14px" align="right">TOTAL</td>
        <td class="negrita" style="font-size:14px" align="right">$ <?php echo number_format($factura->total,2)?></td>
    </tr>
    </table>
    
   
</div>

<div style="padding-top:3px">
    <table width="100%" class="tablaNomina" style="background-color:none; border:none">
    	<tr>
        	<th align="left" >Forma de pago</th>
        </tr>
        <tr>
            <td align="left">
				<?php echo $factura->formaPago ?>
            </td>
        </tr>
        <tr>
        	<th align="left">Sello Digital del Emisor</th>
        </tr>
        <tr>
            <td align="left">
				<?php 
                $tamano	=strlen($factura->selloDigital);
                $n		=$tamano/140;
                
                if($tamano%140>0)
                {
               		$n++;
                }
                
                $inicio=0;
                
				for($i=1;$i<$n;$i++)
                {
					echo substr($factura->selloDigital,$inicio,140).'<br />';
					$inicio=$inicio+140;
                }
                ?>
            </td>
        </tr>
        <tr>
        	<th align="left" >Sello del SAT</th>
        </tr>
        <tr>
            <td align="left">
            <?php 
				#echo $factura->selloSat
				$tamano	=strlen($factura->selloSat);
				$n		=$tamano/140;
				
				if($tamano%140>0)
				{
					$n++;
				}
				
				$inicio=0;
				for($i=1;$i<$n;$i++)
				{
					echo substr($factura->selloSat,$inicio,140).'<br />';
					$inicio=$inicio+140;
            }
            ?>
            
            </td>
        </tr>
        
        <tr>
            <th align="left">Cadena Original del complemento de certificación digital del SAT</th>
        </tr>
        <tr>
            <td align="left" style="padding:2px;2px;2px:2px;">
            <?php 
            $tamano	=strlen($factura->cadenaTimbre);
            $n		=$tamano/140;
            
            if($tamano%140>0)
            {
                $n++;
            }
            
            $inicio=0;
            
            for($i=1;$i<$n;$i++)
            {
                echo substr($factura->cadenaTimbre,$inicio,140).'<br />';
                $inicio=$inicio+140;
            }
            ?>
            </td>
        </tr>
        
          <?php
		if(strlen($factura->observaciones))
		{
			echo '
			<tr>
				<th align="left">Observaciones</th>
			</tr>
			<tr>
            	<td align="left" style="padding:2px;2px;2px:2px;">
					'.sustituirSaltos($factura->observaciones).'
				</td>
			</tr>';
		}
		?>
		
    </table>
    
    
        
    </div>
</div>

<?php
if($factura->cancelada==1)
{
	echo '  <div class="alertasGeneral" align="center">El CFDI se encuentra cancelado</div>';
}
?>


</body>
</html>




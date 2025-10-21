<div>
    <div style="float:left; width:37%">
    <?php
    if(strlen($configuracion->logotipo)>2)
    {
        if(file_exists("media/fel/".$configuracion->rfc.'/'.$configuracion->logotipo))
        {
            $imagen='<img src="'.base_url().'media/fel/'.$configuracion->rfc.'/'.$configuracion->logotipo.'" style="max-height:100px; max-width:300px; margin-top: 30px" />';
            echo $imagen;
        }
    }
    ?>
    </div>
    <div style="float:right; width:63%">
    <table width="100%" class="tablaFactura">
        <tr>
            <th colspan="2" style="background-color:#FFF; border-left-color:#FFF; border-top-color:#FFF; border-right-color:#FFF; font-size:14px; "><?php echo $factura->documento?></th>
        </tr>
        <?php
		if($factura->documento!='TRASLADO')
		{
			echo '
			<tr>
				<th width="50%">UUID</th>
				<th width="50%">USO CFDI</th>
			</tr>
			<tr>
				<td align="center">'.$factura->UUID.'</td>
				<td align="center" >'.$factura->usoCfdi.'</td>
			</tr>
			<tr>
				<th>FORMA DE PAGO</th>
				<th>SERIE Y FOLIO INTERNO</th>
			</tr>
			<tr>
				<td align="center">'.$factura->formaPago.'</td>
				<td align="center">'.$factura->serie.$factura->folio.'</td>
			</tr>

			<tr>
				<th>CONDICIONES DE PAGO</th>
				<th>LUGAR DE EXPEDICIÓN</th>
			</tr>
			<tr>
				<td align="center">'.$factura->condicionesPago.'</td>
				<td align="center">'.$configuracion->codigoPostal.'</td>
			</tr>

			<tr>
				<th>METODO DE PAGO</th>
				<th>FECHA</th>
			</tr>
			<tr>
				<td align="center">'.$factura->metodoPago.'</td>
				<td align="center">'.$factura->fecha.'</td>
			</tr>

			<tr>
				<th>TIPO DE COMPROBANTE</th>
				<th>MONEDA Y TIPO DE CAMBIO</th>
			</tr>
			<tr>
				<td align="center">'.obtenerTipoComprobante($factura->tipoComprobante).'</td>
				<td align="center">MXN PESO MEXICANO</td>
			</tr>';
		}
		else
		{
			echo '
			<tr>
				<th width="50%">UUID</th>
				<th width="50%">USO CFDI</th>
			</tr>
			<tr>
				<td align="center">'.$factura->UUID.'</td>
				<td align="center" >'.$factura->usoCfdi.'</td>
			</tr>
			<tr>
				<th>LUGAR DE EXPEDICIÓN</th>
				<th>SERIE Y FOLIO INTERNO</th>
			</tr>
			<tr>
				<td align="center">'.$configuracion->codigoPostal.'</td>
				<td align="center">'.$factura->serie.$factura->folio.'</td>
			</tr>
			<tr>
				<th>TIPO DE COMPROBANTE</th>
				<th>FECHA</th>
			</tr>
			<tr>
				<td align="center">'.obtenerTipoComprobante($factura->tipoComprobante).'</td>
				<td align="center">'.$factura->fecha.'</td>
			</tr>';
		}
		?>
		
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
            <td style="height: 60px">
            <?php
            echo 'R.F.C. '.$configuracion->rfc.'<br />';
            echo $configuracion->nombre.'<br />';
            echo $configuracion->calle.' '. $configuracion->numeroExterior.' '.$configuracion->colonia.'<br />';
            echo $configuracion->localidad.', '. $configuracion->municipio.', '.
            $configuracion->estado.', '.$configuracion->pais.', C.P '.$configuracion->codigoPostal.', 
            <br />Regimen Fiscal: '.$configuracion->claveRegimen.' - '.$configuracion->regimen;
            
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
            <td style="height: 60px">
             <?php
            echo 'R.F.C. '.$factura->rfc.'<br />';
            echo $factura->empresa.'<br />';
            
			#if($factura->rfc!='XAXX010101000')
			{
				echo $factura->calle.' '. $factura->numeroExterior.' '.$factura->colonia.'<br />';
				echo $factura->localidad.', '. $factura->municipio.', '.
				$factura->estado.', '.$factura->pais.', 
				C.P '.$factura->codigoPostal.', 
				# Cliente: '.$cliente->alias;
			}
				
			if($factura->versionCfdi=='4.0')
            {
                echo '<br> Régimen fiscal: '.$factura->regimenFiscalCliente;
            }
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
        <th width="10%" style="color:#000">CLAVE SAT</th>
        <th width="32%" style="color:#000">DESCRIPCIÓN</th>
        <th style="color:#000" align="center">PRECIO UNITARIO</th>
		<?=$factura->versionCfdi=='4.0'?'<th  style="color:#000" align="center">OBJETO IMPUESTO</th>':''?>
        <th  style="color:#000" align="center">IMPUESTO<?=$factura->documento!='TRASLADO'?' 16%':''?></th>
        <th width="10%" style="color:#000" align="center">IMPORTE</th>
    </tr>

<?php
foreach($productos as $row)
{
    ?>
     <tr>
        <td align="center"><?php echo $row->codigoInterno?></td>
        <td align="center"><?php echo number_format($row->cantidad,2)?></td>
		<td align="center"><?php echo $row->claveUnidad?></td>
        <td align="center"><?php echo $row->claveProducto?></td>
        <td ><?php 
			echo nl2br($row->nombre);
			
			if(strlen($row->pedimento)>1)
			{
				echo '<br />Pedimento: '.$row->pedimento;
			}
		?>
		</td>
        <td align="right">$ <?php echo number_format($row->precio,2)?></td>
		<?=$factura->versionCfdi=='4.0'?'<td align="center">'.$row->claveObjetoImpuesto.'</td>':''?>
        <td align="right">
        <?php 
        if($row->tasa>0)
        {
			#echo $row->nombreImpuesto.' '.$row->tasa.', $'.number_format($row->importeImpuesto,decimales);
			echo '$'.number_format($row->importeImpuesto,decimales);
        }
        else
        {
			if($row->exento=='0')
			{
				#echo $row->nombreImpuesto.' '.$row->tasa.', $'.number_format($row->importeImpuesto,decimales);
				echo '$'.number_format($row->importeImpuesto,decimales);
			}
			else
			{
				echo 'IVA Exento';
			}
            
        }
        ?>
        </td>
        <td align="right">$ <?php echo number_format($row->importe,2)?></td>
     </tr>

    <?php
}
		
$columna=$factura->versionCfdi=='4.0'?7:6;

?>

<tr>
    <td rowspan="3" colspan="<?=$columna?>" >
	<?php
	if($factura->versionCfdi=='4.0' and $factura->global=='1')	
	{
		echo 'Año: '.$factura->anio;
		echo '<br>Mes: '.$factura->mes;
		echo '<br>Periocididad: '.$factura->periodicidad;
	}
	?>
	</td>
    <td class="totales" align="right">SUBTOTAL</td>
    <td class="totales" align="right">$ <?php echo number_format($factura->subTotal,decimales)?></td>
</tr>

<tr>
    <td class="totales" align="right">DESCUENTO</td>
    <td class="totales" align="right">$ <?php echo number_format($factura->descuento,2)?></td>
</tr>

 <?php
if($retencion!=null)
{
    ?>
    <tr>
        <td colspan="<?=$columna?>" style="border: none" ></td>
        <td class="totales" align="right">IEPS</td>
        <td class="totales" align="right">$ <?php echo number_format($factura->ieps,decimales)?></td>
    </tr>
    <?php
}
?>

<tr>

    <td class="totales" align="right">IMPUESTOS</td>
    <td class="totales" align="right">$ <?php echo number_format($factura->iva,2)?></td>
</tr>

<?php
if($retencion!=null)
{
    foreach($retencion as $row)
    {
        ?>
        <tr>
            <td colspan="7" ></td>
            <td class="totales" align="right"><?php echo $row->retencion?></td>
            <td class="totales" align="right">$ <?php echo number_format($row->importe,decimales)?></td>
        </tr>
        <?php
    }
}
?>

    <tr>
        <td colspan="<?=$columna?>" align="right">
        <?php  echo ($cantidadLetra).' '. $factura->claveDivisa?>
        </td>
        <td class="totales" align="right">TOTAL</td>
        <td class="totales" align="right">$ <?php echo number_format($factura->total,2)?></td>
    </tr>
</table>

 <?php
if(strlen($factura->observaciones))
{
    echo '<span style="font-size:11px">Observaciones: <br />'.sustituirSaltos($factura->observaciones).'</span>';
}
?>


</div>


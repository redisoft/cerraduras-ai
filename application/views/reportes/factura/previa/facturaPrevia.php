<div style="float:left; width:37%">
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
    <div style="float:right; width:63%">
    <table width="100%" class="tablaFactura">
        <tr>
            <th colspan="2" style="background-color:#FFF; border-left-color:#FFF; border-top-color:#FFF; border-right-color:#FFF; font-size:14px; ">FACTURA</th>
        </tr>
        <tr>
            <th width="50%">UUID</th>
            <th width="50%">USO CFDI</th>
        </tr>
       <tr>
            <td align="center"></td>
            <td align="center" ><?php echo $this->input->post('usoCfdiTexto') ?></td>
        </tr>
        
        <tr>
            <th>FORMA DE PAGO</th>
            <th>SERIE Y FOLIO INTERNO</th>
        </tr>
        <tr>
            <td align="center"><?php echo $this->input->post('formaPagoTexto').' '.$this->input->post('txtCuentaPago') ?></td>
            <td align="center"><?php echo $this->input->post('folioActual')?></td>
        </tr>
        
        <tr>
            <th>CONDICIONES DE PAGO</th>
            <th>LUGAR DE EXPEDICIÓN</th>
        </tr>
        <tr>
            <td align="center"><?php echo $this->input->post('txtCondiciones')?></td>
            <td align="center"><?php echo $configuracion->codigoPostal ?></td>
        </tr>
        
        <tr>
            <th>METODO DE PAGO</th>
            <th>FECHA</th>
        </tr>
        <tr>
            <td align="center"><?php echo $this->input->post('metodoPagoTexto')?></td>
            <td align="center"><?php echo $this->input->post('txtFechaFactura') ?></td>
        </tr>
		
		<tr>
            <th colspan="2">TIPO DE COMPROBANTE</th>
        </tr>
        <tr>
            <td colspan="2" align="center">I - Ingreso</td>
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
           <br />Regimen Fiscal: '.$configuracion->claveRegimen.' - '.$configuracion->regimen;
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
            echo $cliente->razonSocial.'<br />';
            echo $cliente->calle.' '. $cliente->numero.' '.$cliente->colonia.'<br />';
            echo $cliente->localidad.', '. $cliente->municipio.', '.
            $cliente->estado.', '.$cliente->pais.', C.P '.$cliente->codigoPostal.', # Cliente: '.$cliente->alias;
				
			echo '<br> Régimen fiscal: '.$cliente->claveRegimen.' '.$cliente->regimenFiscal;
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
    <th style="color:#000" align="center">OBJETO IMPUESTO</th>
    <th  style="color:#000" align="center">IMPUESTO 16%</th>
    <th width="10%" style="color:#000" align="center">IMPORTE</th>
</tr>

<?php

$productos	= $this->input->post('productos');
$i=1;

foreach($detalles as $row)
{
	$cantidad			= $row->cantidad-$row->devueltos;
	
	if($cantidad>0)
	{
		$importe		= $cantidad*$row->precio;

		$pedimento1		= trim($this->input->post('txtPedimento1'.$i));
		$pedimento2		= trim($this->input->post('txtPedimento2'.$i));
		$pedimento3		= trim($this->input->post('txtPedimento3'.$i));
		$pedimento4		= trim($this->input->post('txtPedimento4'.$i));
		$fecha			= trim($this->input->post('txtFecha'.$i));

		$pedimento		= $pedimento1.'  '.$pedimento2.'  '.$pedimento3.'  '.$pedimento4;
		
		?>
		 <tr>
			<td align="center"><?php echo $row->codigoInterno?></td>
			<td align="center"><?php echo number_format($cantidad,2)?></td>
			<!--<td align="center"><?php echo $row->claveUnidad.', '.$row->unidad?></td>
			<td align="center"><?php echo $row->claveProducto.', '.$row->claveDescripcion?></td>-->

			 <td align="center"><?php echo $row->claveUnidad?></td>
			<td align="center"><?php echo $row->claveProducto?></td>
			<td>
			<?php 
			echo $this->input->post('txtDescripcionProductoFactura'.$i);
			
			if(strlen($pedimento1)==2 and strlen($pedimento2)==2 and strlen($pedimento3)==4 and strlen($pedimento4)==7 and strlen($fecha)==10)
			{
				echo '<br />Pedimento: '.$pedimento;
			}
			?>
			</td>

			<td align="right">$ <?php echo number_format($row->precio,2)?></td>
			<td align="center">02</td>

			<td align="right">
			<?php 
		   /* if($row->tasa>0)
			{
				echo $row->nombreImpuesto.' '.$row->tasa.', $'.number_format($row->importeImpuesto,decimales);
			}
			else
			{
				if($row->exento=='0')
				{
					echo $row->nombreImpuesto.' '.$row->tasa.', $'.number_format($row->importeImpuesto,decimales);
				}
				else
				{
					echo 'IVA Exento';
				}
			}*/

			 if($row->tasa>0)
			{
				echo '$'.number_format($row->importeImpuesto,decimales);
			}
			else
			{
				if($row->exento=='0')
				{
					echo '$'.number_format($row->importeImpuesto,decimales);
				}
				else
				{
					echo 'IVA Exento';
				}
			}
			?>
			</td>

			<td align="right">$ <?php echo number_format($importe,2)?></td>
		 </tr>

		<?php

		$i++;
	}
}

?>

<tr>
    <td colspan="7" ></td>
    <td class="totales" align="right">SUBTOTAL</td>
    <td align="right">$ <?php echo number_format($this->input->post('txtSubTotal'),decimales)?></td>
</tr>

<tr>
    <td colspan="7" style="border: none" ></td>
    <td class="totales" align="right">DESCUENTO</td>
    <td align="right">$ <?php echo number_format($this->input->post('txtDescuento'),2)?></td>
</tr>


<tr>
    <td colspan="7" ></td>
    <td class="totales" align="right">IMPUESTOS 16%</td>
    <td align="right">$ <?php echo number_format($this->input->post('txtIvaCfdi'),2)?></td>
</tr>


    <tr>
        <td colspan="7" align="right">
        <?php  echo ($cantidadLetra).' '. $divisa->clave?>
        </td>
        <td class="totales" align="right">TOTAL</td>
        <td align="right">$ <?php echo number_format($this->input->post('txtTotalCfdi'),2)?></td>
    </tr>
</table>

<?php
if(strlen($this->input->post('txtObservaciones'))>0)
{
    echo '<span style="font-size:11px">Observaciones: <br />'.sustituirSaltos($this->input->post('txtObservaciones')).'</span>';
}
?>

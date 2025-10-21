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
            <th colspan="2" style="background-color:#FFF; border-color:#FFF; font-size:14px; ">Factura</th>
        </tr>
        <tr>
            <th colspan="2">UUID</th>
        </tr>
        <tr>
            <td align="center" colspan="2"></td>
        </tr>
        <tr>
            <th>FORMA DE PAGO</th>
            <th>SERIE Y FOLIO INTERNO</th>
        </tr>
        <tr>
            <td align="center"><?php echo $this->input->post('txtFormaPago') ?></td>
            <td align="center"><?php echo $this->input->post('txtFolioActual')?></td>
        </tr>
        
        <tr>
            <th>CONDICIONES DE PAGO</th>
            <th>LUGAR DE EXPEDICIÓN</th>
        </tr>
        <tr>
            <td align="center"><?php echo $this->input->post('txtCondicionesPago')?></td>
            <td align="center"><?php echo $configuracion->codigoPostal ?></td>
        </tr>
        
        <tr>
            <th>METODO DE PAGO</th>
            <th>FECHA</th>
        </tr>
        <tr>
            <td align="center"><?php echo $this->input->post('metodoPagoTexto')?></td>
            <td align="center"><?php echo date('Y-m-d H:i:s') ?></td>
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
            echo $cliente->empresa.'<br />';
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
    <th width="10%" style="color:#000">UNIDAD</th>
    <th width="32%" style="color:#000">DESCRIPCIÓN</th>
    <th style="color:#000" align="right">PRECIO UNITARIO</th>
    <th style="color:#000" align="right">OBJETO IMPUESTO</th>
    <th style="color:#000" align="right">IMPORTE</th>
</tr>

<?php
for($i=1;$i<$this->input->post('txtNumeroProductos');$i++)
{
	$impuestos			= $this->input->post('txtTotalImpuesto'.$i);
	$impuesto			= $impuestos/$this->input->post('txtCantidadProducto'.$i);
    ?>
     <tr>
        <td align="center"><?php echo $this->input->post('txtCodigoInterno'.$i)?></td>
        <td align="center"><?php echo number_format($this->input->post('txtCantidadProducto'.$i),decimales)?></td>
        <td align="center"><?php echo $this->input->post('txtUnidadProducto'.$i)?></td>
        <td ><?php echo $this->input->post('txtNombreProducto'.$i)?></td>
        
        <td align="right">$ <?php echo number_format($this->input->post('txtPrecioProducto'.$i)-$impuesto,decimales)?></td>
        <!--<td align="right">$ <?php echo number_format($this->input->post('txtDescuentoProducto'.$i),decimales)?></td>-->
		 <td align="center">02</td>
        <td align="right">$ <?php echo number_format($this->input->post('txtTotalProducto'.$i)-$impuestos,decimales)?></td>
     </tr>

    <?php
}

?>

<tr>
    <td colspan="5" ></td>
    <td class="totales" align="right">SUBTOTAL</td>
    <td align="right">$ <?php echo number_format($this->input->post('txtSubTotal')-$this->input->post('txtDescuentoTotal'),decimales)?></td>
</tr>


<tr>
    <td colspan="5" ></td>
    <td class="totales" align="right">IMPUESTOS</td>
    <td align="right">$ <?php echo number_format($this->input->post('txtIvaTotal'),decimales)?></td>
</tr>


    <tr>
        <td colspan="5" align="right">
        <?php  echo ($cantidadLetra).' '. $divisa->claveDivisa?>
        </td>
        <td class="totales" align="right">TOTAL</td>
        <td align="right">$ <?php echo number_format($this->input->post('txtTotal'),decimales)?></td>
    </tr>
</table>

<?php
if(strlen($factura->observaciones))
{
    echo '<span style="font-size:13px">Observaciones: <br />'.sustituirSaltos($this->input->post('txtObservacionesVenta')).'</span>';
}
?>

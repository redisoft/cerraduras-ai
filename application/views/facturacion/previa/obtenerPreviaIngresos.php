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
            <th colspan="2" style="background-color:#FFF; border-left-color:#FFF; border-top-color:#FFF; border-right-color:#FFF; font-size:14px; ">Factura</th>
        </tr>
        <tr>
            <th>UUID</th>
            <th>USO CFDI</th>
        </tr>
        <tr>
            <td align="center"></td>
            <td align="center" ><?php echo $this->input->post('usoCfdiTexto')?></td>
        </tr>
        <tr>
            <th>FORMA DE PAGO</th>
            <th>SERIE Y FOLIO INTERNO</th>
        </tr>
        <tr>
            <td align="center"><?php echo $this->input->post('formaPagoTexto')?></td>
            <td align="center"><?php echo $configuracion->serie.$folio?></td>
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
            <td align="center"><?php echo date('Y-m-d H:i:s') ?></td>
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
            echo 'R.F.C. '.$cliente->rfc.'<br />';
            echo $cliente->razonSocial.'<br />';
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
    <th width="10%" style="color:#000">CLAVE PRODUCTO</th>
    <th width="25%" style="color:#000">DESCRIPCIÓN</th>
    <th style="color:#000" align="right">PRECIO UNITARIO</th>
    <th style="color:#000" align="right">DESCUENTO</th>
    <th  style="color:#000" align="right">IMPUESTO</th>
    <th width="11%" style="color:#000" align="right">IMPORTE</th>
</tr>

<tr>
    <td align="center"></td>
    <td align="center">1</td>
    <td align="center"><?php echo $this->input->post('txtUnidad')?></td>
    <td align="center"><?php echo $this->input->post('txtClaveProductoServicio')?></td>
    <td ><?php echo $this->input->post('txtConcepto')?></td>
    <td align="right">$ <?php echo number_format($this->input->post('txtSubTotal'),2)?></td>
    <td align="right">$ <?php echo number_format(0,2)?></td>
    <td align="right">
    <?php 
    if($this->input->post('txtIvaPorcentaje')==0)
    {
        echo 'IVA 0.000000';
    }
    else
    {
        echo 'IVA 0.160000, '.number_format($this->input->post('txtIva'),decimales);
    }
    ?></td>
    <td align="right">$ <?php echo number_format($this->input->post('txtSubTotal'),decimales)?></td>
 </tr>
 
    <tr>
        <td colspan="7" ></td>
        <td class="totales" align="right">SUBTOTAL</td>
        <td align="right">$ <?php echo number_format($this->input->post('txtSubTotal'),decimales)?></td>
    </tr>
    
    <tr>
        <td colspan="7" ></td>
        <td class="totales" align="right">IMPUESTOS</td>
        <td align="right">$ <?php echo number_format($this->input->post('txtIva'),decimales)?></td>
    </tr>

    <tr>
        <td colspan="7" align="right">
        <?php  echo ($cantidadLetra).' MXN'?>
        </td>
        <td class="totales" align="right">TOTAL</td>
        <td align="right">$ <?php echo number_format($this->input->post('txtTotal'),2)?></td>
    </tr>
</table>

 <?php
if(strlen($this->input->post('txtObservaciones'))>0)
{
    echo '<span style="font-size:13px">Observaciones: <br />'.sustituirSaltos($this->input->post('txtObservaciones')).'</span>';
}
?>


</div>


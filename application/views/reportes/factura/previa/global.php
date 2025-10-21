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
            <td align="center"><?php echo $this->input->post('txtFolioActual')?></td>
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
            <th>TIPO DE COMPROBANTE</th>
			<th>MONEDA Y TIPO DE CAMBIO</th>
        </tr>
        <tr>
            <td align="center">I - Ingreso</td>
			<td align="center">MXN PESO MEXICANO</td>
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
            <td style="height: 60px">
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
            <td style="height: 60px">
             <?php
            echo 'R.F.C. '.$cliente->rfc.'<br />';
            echo $cliente->razonSocial.'<br />';
           
			#if($cliente->rfc!='XAXX010101000')
			{
				echo $cliente->calle.' '. $cliente->numero.' '.$cliente->colonia.'<br />';
            	echo $cliente->localidad.', '. $cliente->municipio.', '.
				$cliente->estado.', '.$cliente->pais.', C.P '.$cliente->codigoPostal.', # Cliente: '.$cliente->alias;
				
				echo '<br> Régimen fiscal: '.$cliente->claveRegimen.' '.$cliente->regimenFiscal;
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
    <th style="color:#000" align="center">OBJETO IMPUESTO</th>
    <th  style="color:#000" align="center">IMPUESTO 16%</th>
    <th width="10%" style="color:#000" align="center">IMPORTE</th>
</tr>

<?php

$i=1;

$subTotal	= 0;
$total		= 0;
$iva		= 0;
$ivas		= 0;
$ieps		= 0;
$descuentos	= 0;

foreach($ventas as $row)
{
	$importe		= $row->subTotalIva;
	$importe		= round($importe,decimales);

	$importeSinIva	= $row->subTotalSinIva;
	$importeSinIva	= round($importeSinIva,decimales);

	$impuesto		= $importe*($row->ivaPorcentaje/100);
	$impuesto		= round($impuesto,decimales);

	$subTotal		+=$importe+$importeSinIva;
	$ivas			+=$impuesto;
	
	$producto		= 'VENTA '.obtenerFechaFormato($row->fechaCompra).' '.$row->folio.'('.$row->estacion.')';

    ?>
     <tr>
        <td align="center"></td>
        <td align="center"><?php echo number_format(1,2)?></td>		 
		<td align="center">ACT</td>
        <td align="center">01010101</td>
        <td ><?php echo $producto;?></td>
        <td align="right">$ <?php echo number_format($importe+$importeSinIva,2)?></td>
        <td align="center">02</td>
        <td align="right"><?php echo '$'.number_format($impuesto,decimales);?></td>
        <td align="right">$ <?php echo number_format($importe+$importeSinIva,2)?></td>
     </tr>

    <?php
	
	$i++;
}
	
$total			= $subTotal+$ivas;
$total			= round($total,decimales);

?>

<tr>
    <td rowspan="3" colspan="7" >
		Año:  <?=$this->input->post('selectAnio')?>
		<br>Mes:  <?=$this->input->post('mes')?>
		<br>Periodicidad:  <?=$this->input->post('periodicidad')?>
	</td>
    <td class="totales" align="right">SUBTOTAL</td>
    <td class="totales" align="right">$ <?php echo number_format($subTotal,decimales)?></td>
</tr>

<tr>

    <td class="totales" align="right">DESCUENTO</td>
    <td class="totales" align="right">$ <?php echo number_format(0,2)?></td>
</tr>


<tr>

    <td class="totales" align="right">IMPUESTOS 16%</td>
    <td class="totales" align="right">$ <?php echo number_format($ivas,2)?></td>
</tr>


    <tr>
        <td colspan="7" align="right">
        <?php  echo ($cantidadLetra).' MXN'?>
        </td>
        <td class="totales" align="right">TOTAL</td>
        <td  class="totales" align="right">$ <?php echo number_format($total,2)?></td>
    </tr>
</table>


<?php
$i=1;
	$ingresoTotal=0;
	$egresoTotal=0;
	$margenTotal=0;

	function convertirMayuscula($cadena)
	{
		#$cadena=strtoupper($cadena);
		return($cadena);
	}
?>

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
		if(file_exists("img/logos/".$configuracion->idLicencia.'_'.$configuracion->logotipo))
		{
			$imagen='<img src="'.base_url().'img/logos/'.$configuracion->idLicencia.'_'.$configuracion->logotipo.'" style="height:100px; width:180px" />';
			echo $imagen;
		}
        ?>
        </div>
        <div style="float:right; width:70%">
        <table width="100%" class="tablaFactura">
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
            	<td align="center"><?php echo 'EN UNA SOLA EXIBICIÓN' ?></td>
                <td align="center"><?php echo $factura->folio?></td>
            </tr>
            
            <tr>
            	<th>CONDICIONES DE PAGO</th>
                <th>LUGAR DE EXPEDICIÓN</th>
            </tr>
            <tr>
            	<td align="center"><?php echo 'CONTADO'?></td>
                <td align="center"><?php echo 'PUEBLA' ?></td>
            </tr>
            
            <tr>
            	<th>METODO DE PAGO</th>
                <th>FECHA Y HORA DE EXPEDICIÓN</th>
            </tr>
            <tr>
            	<td align="center"><?php echo 'EFECTIVO'?></td>
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
				echo $configuracion->direccion.' '. $configuracion->numero.' '.$configuracion->colonia.'<br />';
				echo $configuracion->localidad.', '. $configuracion->municipio.', '.
				$configuracion->estado.', '.$configuracion->pais.', C.P '.$configuracion->codigoPostal.'<br />';
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
				echo $cliente->direccion.' '. $cliente->no_direccion.' '.$cliente->colonia.'<br />';
				echo $cliente->ciudad.', '. $cliente->municipio.', '.
				$cliente->estado.', '.$cliente->pais.', C.P '.$cliente->cp.'<br />';
                ?>
                </td>
            </tr>
        </table>
        </div>
    </div>
    
	<div style="padding-top:3px">
    <table class="tablaFactura" style="width:100%;">
    <tr>
    <th width="40%" style="color:#000">DESCRIPCIÓN</th>
    <th style="color:#000">CANTIDAD</th>
    <th style="color:#000" align="right">PRECIO UNITARIO</th>
    <th style="color:#000" align="right">IMPORTE</th>
    </tr>
    
    <?php
	foreach($productos as $row)
	{
		?>
         <tr>
    		<td style="border-bottom:none; border-top:none"><?php echo $row->nombre?></td>
            <td style="border-bottom:none; border-top:none" align="center"><?php echo number_format($row->cantidad,2)?></td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($row->precio,2)?></td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($row->importe,2)?></td>
    	 </tr>
    
        <?php
	}
    ?>

    <tr>
        <td colspan="2" style="border-bottom:none" ></td>
        <td align="right">SUB-TOTAL</td>
        <td align="right">$ <?php echo number_format($factura->subTotal,2)?></td>
    </tr>
    
    <tr>
        <td colspan="2" style="border: none" ></td>
        <td  align="right">DESCUENTO</td>
        <td align="right">$ <?php echo number_format(($factura->descuento/100)*$factura->subTotal,2)?></td>
    </tr>
    
    <tr>
        <td colspan="2" style="border: none"></td>
        <td  align="right">IVA <?php echo $factura->iva?>%</td>
        <td align="right">$ <?php echo number_format(($factura->subTotal*$factura->iva)/100,2)?></td>
    </tr>
    
     <tr>
     <td colspan="2" style="border: none" align="right">
     (<?php  echo convertirMayuscula($cantidadLetra)?> M.N.)
     </td>
    <td  align="right">TOTAL</td>
    <td align="right">$ <?php echo number_format($factura->total,2)?></td>
    </tr>
    </table>
    </div>
    
<div align="left" style="padding-left:30px; font-size:10px; color:#000" >
</div>
</body>
</html>




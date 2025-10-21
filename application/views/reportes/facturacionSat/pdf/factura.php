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
		if(file_exists("media/fel/".$configuracion->rfc.'/'.$configuracion->logotipo))
		{
			/*$imagen='<img src="'.base_url().'media/fel/'.$configuracion->rfc.'/'.$configuracion->logotipo.'" style="height:100px; width:180px" />';
			echo $imagen;*/
		}
        ?>
        </div>
        <div style="float:right; width:70%">
        <table width="100%" class="tablaFactura">
        	<tr>
            	<th colspan="2" style="background-color:#FFF; border-color:#FFF; font-size:14px; "><?php #echo $factura->documento?></th>
            </tr>
        	<tr>
            	<th colspan="2">UUID</th>
            </tr>
            <tr>
                <td align="center" colspan="2"><?php echo $factura->uuid?></td>
            </tr>
            <tr>
            	<th>FORMA DE PAGO</th>
                <th>SERIE Y FOLIO INTERNO</th>
            </tr>
            <tr>
            	<td align="center"><?php echo $xml[7] ?></td>
                <td align="center"><?php echo $xml[11].$xml[12]?></td>
            </tr>
            
            <tr>
            	<th>CONDICIONES DE PAGO</th>
                <th>LUGAR DE EXPEDICIÓN</th>
            </tr>
            <tr>
            	<td align="center"><?php echo $xml[46]?></td>
                <td align="center"><?php echo $xml[47]?></td>
            </tr>
            
            <tr>
            	<th>MÉTODO DE PAGO</th>
                <th>FECHA</th>
            </tr>
            <tr>
            	<td align="center"><?php echo $xml[10]?></td>
                <td align="center"><?php echo $xml[39] ?></td>
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
            	<td style="height:95px">
                <?php
				echo 'R.F.C. '.$xml[15].'<br />';
				echo $xml[16].'<br />';
				echo $xml[18].' '. $xml[22].' '.$xml[20].'<br />';
				echo $xml[48].', '. $xml[21].', '.
				$xml[19].', '.$xml[17].', C.P '.$xml[23].', 
				<br />Regimen Fiscal: '.$xml[49];
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
            	<td style="height:95px">
                 <?php
				echo 'R.F.C. '.$xml[24].'<br />';
				echo $xml[25].'<br />';
				echo $xml[27].' '. $xml[31].' '.$xml[29].'<br />';
				echo $xml[32].', '. $xml[30].', '.
				$xml[28].', '.$xml[26].', C.P '.$xml[33].'<br />';
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
        <th style="color:#000">UNIDAD</th>
        <th style="color:#000">CANTIDAD</th>
        <th style="color:#000" align="right">PRECIO UNITARIO</th>
        <th style="color:#000" align="right">IMPORTE</th>
    </tr>
    
    <?php
	foreach($xml[34] as $row)
	{
		?>
         <tr>
            <td style="border-bottom:none; border-top:none"><?php echo sustituirSaltos($row[2])?></td>
            <td style="border-bottom:none; border-top:none" align="center"><?php echo $row[1]?></td>
            <td style="border-bottom:none; border-top:none" align="center"><?php echo ($row[0]);?>
            
            </td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo ($row[3])?></td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo ($row[4])?></td>
    	 </tr>
    
        <?php
	}

    ?>

    <tr>
        <td colspan="3" style="border-bottom:none" ></td>
        <td class="totales" style="border-bottom:none" align="right">SUBTOTAL</td>
        <td style="border-bottom:none" align="right">$ <?php echo $xml[5]?></td>
    </tr>
    
    <?php
    if(strlen($xml[44])>0)
	{
		?>
        <tr>
            <td colspan="3" style="border: none" ></td>
            <td class="totales" style="border-bottom:none; border-top:none" align="right">DESCUENTO</td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo $xml[44]?></td>
        </tr>
        <?php
	}
	?>
    
    
    <tr>
        <td colspan="3" style="border: none"></td>
        <td class="totales" style="border-bottom:none; border-top:none" align="right">IVA <?php echo $xml[35]?>%</td>
        <td style="border-bottom:none; border-top:none" align="right">$ <?php echo $xml[36]?></td>
    </tr>
    
    <?php
	/*if($retencion!=null)
	{
		?>
    	<tr>
            <td colspan="3" style="border: none"></td>
            <td class="totales" style="border-bottom:none; border-top:none" align="right"><?php echo $retencion->retencion.' '. number_format($retencion->tasa,2)?>%</td>
            <td style="border-bottom:none; border-top:none" align="right">$ <?php echo number_format($retencion->importe,2)?></td>
   	 	</tr>
    	<?php
	}*/
    ?>
    
     <tr>
     <td colspan="3" style="border: none" align="right">
     <?php  echo $cantidadLetra.' '?>
     </td>
    <td class="totales" style="border-bottom:none; border-top:none"  align="right">TOTAL</td>
    <td style="border-bottom:none; border-top:none" align="right">$ <?php echo $xml[4]?></td>
    </tr>
    
     
    </table>
</div>



</body>
</html>




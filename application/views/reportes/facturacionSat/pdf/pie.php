	
    <div style="padding-top:3px">
 <table width="100%" class="tablaFactura">
     <tr>
   		<th colspan="4">Sello Digital del Emisor</th>
    </tr>
     <tr>
   	 	<td align="left" colspan="4">
       <?php 
	   	$tamano	= strlen($xml[38]);
		$n		= $tamano/110;
		
		if($tamano%110>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($xml[38],$inicio,110).'<br />';
			$inicio=$inicio+110;
		}
	   ?>
        </td>
    </tr>
     <tr>
   		<th colspan="4">Sello del SAT</th>
    </tr>
     <tr>
   	 	<td align="left" colspan="4">
       <?php 
	   #echo $factura->selloSat
	   	$tamano	= strlen($xml[43]);
		$n		= $tamano/110;
		
		if($tamano%110>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($xml[43],$inicio,110).'<br />';
			$inicio=$inicio+110;
		}
	   ?>
	   
        </td>
    </tr>
    </table>
    
    <div style="padding-top:3px">
    <div style="width:17%; float:left">
    <table class="tablaFactura" width="100%" style="border:none">
    <tr>
    <td style="border:none">
    	<?php
		if(file_exists('media/sat/'.$xml[15].'_'.obtenerFechaMesCorto($factura->fecha).'_'.$xml[11].$xml[12].'.png'))
		{
      		echo '<img src="'.base_url().'media/sat/'.$xml[15].'_'.obtenerFechaMesCorto($factura->fecha).'_'.$xml[11].$xml[12].'.png"/>';
		}
        ?>
    </td>
    </tr>
    </table>
    </div>
    <div style="width:80%; float:right">
    <table class="tablaFactura" width="100%">
     <tr>
   		<th>Cadena Original del complemento de certificación digital del SAT</th>
    </tr>
    <tr>
   	 <td align="left" style="padding:2px;2px;2px:2px;">
		<?php #echo $factura->cadenaTimbre
		
		$cadena='||'.$xml[42].'|'.$factura->uuid.'|'.$xml[39].'|'.$xml[38].'|'.$xml[41].'||';
		
		$tamano	= strlen($cadena);
		$n		= $tamano/90;
		
		if($tamano%90>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($cadena,$inicio,90).'<br />';
			$inicio=$inicio+90;
		}
		?>
     </td>
    </tr>
     <tr>
   		<th>No de Serie del Certificado del Emisor: <?php echo $xml[8]?></th>
    </tr>
      <tr>
   		<th>No de Serie del Certificado del SAT: <?php echo $xml[41]?></th>
    </tr>
      <tr>
   		<th>Fecha y hora de certificación: <?php echo $xml[39]?></th>
    </tr>
    </table>
   </div>
   </div>
   </div>
    <?php
	/*if($factura->cancelada==1)
	{
		echo '  <div class="alertasGeneral" align="center">El CFDI se encuentra cancelado</div>';
	}*/

    ?>
    
     <hr />
    <div align="center" style="font-size:12px" >
    	Este documento es una representación impresa de un CFDI 
    </div>
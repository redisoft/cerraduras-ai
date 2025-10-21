 <table width="100%" class="tablaFactura">
     <tr>
   		<th colspan="4">Sello Digital del Emisor</th>
    </tr>
     <tr>
   	 	<td align="left" colspan="4">
       <?php 
	   $tamano=strlen($factura->selloDigital);
		$n=$tamano/110;
		
		if($tamano%110>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($factura->selloDigital,$inicio,110).'<br />';
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
	   $tamano=strlen($factura->selloSat);
		$n=$tamano/110;
		
		if($tamano%110>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($factura->selloSat,$inicio,110).'<br />';
			$inicio=$inicio+110;
		}
	   ?>
	   
        </td>
    </tr>
    </table>
    
    <div style="padding-top:3px">
    <table class="tablaFactura" width="100%" style="border:none">
    <tr>
    <td style="border:none">
    <img src="<?php echo base_url().'media/fel/'.$this->session->userdata('idLicencia').'_facturacion'.'/cfdi/folio'.$factura->folio.'/codigo'.$factura->folio.'.png'?>"/>
    </td>
    	<td style="border:none">
      <table class="tablaFactura" width="100%">
     <tr>
   		<th>Cadena Original del complemento de certificación digital del SAT</th>
    </tr>
    <tr>
   	 <td align="left" style="padding:2px;2px;2px:2px;">
		<?php #echo $factura->cadenaTimbre
		
		 $tamano=strlen($factura->cadenaTimbre);
		$n=$tamano/90;
		
		if($tamano%90>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($factura->cadenaTimbre,$inicio,90).'<br />';
			$inicio=$inicio+90;
		}
		?>
     </td>
    </tr>
      <tr>
   		<th>No de Serie del Certificado del SAT: <?php echo $factura->certificadoSat?></th>
    </tr>
      <tr>
   		<th>Fecha y hora de certificación: <?php echo $factura->fechaTimbrado?></th>
    </tr>
    </table>
    </td>
    </tr>
    </table>
   
   </div>
    <?php
	if($factura->cancelada==1)
	{
		echo '  <div class="alertasGeneral" align="center">La factura se encuentra cancelada </div>';
	}
    ?>
     <hr />
    <div align="center" >
    Este documento es una representación impresa de un CFDI 
    </div>
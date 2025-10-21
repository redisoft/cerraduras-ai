	
<div style="padding-top:3px; font-size:9px">
	
    
	
	<?php
	echo 'Tipo de comprobante: ';
	echo 'P - Pago';

	#echo nl2br('<div style="font-size:10px">"Be Evergreen, no se hace responsable por robos o extravíos que ocurran en el transcurso de la entrega, quienes deberán de responder en caso de que suceda algún siniestro sera la fletera que resulte responsable, la cual es totalmente independiente a nuestra compañía."</div>');
    /*if($cuentas!=null)
	{
		echo '
		<div style="font-size:10px">';
		
		
			echo '<section style="width:130px; float:left; font-size:10px">
			<table style="font-size:10px; width:70px;">
				<tr>';
					
					foreach($cuentas as $row)
					{
						echo'
						<td>';
						
						echo 'Banco:'.$row->banco.'<br />';
						echo 'Cuenta:'.$row->cuenta.'<br />';
						echo 'Clabe:'.$row->clabe.'';
						
						echo '
						</td>';
					}
				
				echo'
				</tr>
			</table>
			</section>';
		
		
		echo'
		</div>';
	}*/
		?>

 <table width="100%" class="tablaFactura" >
     <tr>
   		<th colspan="4" >Sello Digital del Emisor</th>
    </tr>
     <tr>
   	 	<td align="left" colspan="4">
       <?php 
	   $tamano=strlen($factura->selloDigital);
		$n=$tamano/130;
		
		if($tamano%130>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($factura->selloDigital,$inicio,130).'<br />';
			$inicio=$inicio+130;
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
		$n=$tamano/130;
		
		if($tamano%130>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($factura->selloSat,$inicio,130).'<br />';
			$inicio=$inicio+130;
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
		if(file_exists('media/fel/'.$configuracion->rfc.'/folio'.$factura->serie.$factura->folio.'/codigo'.$factura->folio.'.png'))
		{
      		?>
    		<img src="<?php echo base_url().'media/fel/'.$configuracion->rfc.'/folio'.$factura->serie.$factura->folio.'/codigo'.$factura->folio.'.png'?>"/>
        	<?php
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
		
		$tamano=strlen($factura->cadenaTimbre);
		$n=$tamano/112;
		
		if($tamano%112>0)
		{
			$n++;
		}
		
		$inicio=0;
		for($i=1;$i<$n;$i++)
		{
			echo substr($factura->cadenaTimbre,$inicio,112).'<br />';
			$inicio=$inicio+112;
		}
		?>
     </td>
    </tr>
     	<tr>
   			<th>No de Serie del Certificado del Emisor: <?php echo $factura->verionCfdi!='4.0'?$configuracion->numeroCertificado:$factura->certificadoEmisor?></th>
        </tr>
        <tr>
        	<th>No de Serie del Certificado del SAT: <?php echo $factura->certificadoSat?></th>
        </tr>
      	<tr>
   			<th>Fecha y hora de certificación: <?php echo $factura->fechaTimbrado?></th>
    	</tr>
    </table>
   </div>
   </div>
   </div>
    <?php
	if($factura->cancelada==1)
	{
		echo '  <div class="alertasGeneral" align="center">El CFDI se encuentra cancelado</div>';
	}
    ?>

     <hr />
    <div align="center" style="font-size:10px" >
        Este documento es una representación impresa de un CFDI <?=$factura->versionCfdi?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        Este comprobante fue generado por Redisoftsystems
        
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        <?php echo '<label>Página '.'{PAGENO}/{nb}'.'</label>';?>
    </div>
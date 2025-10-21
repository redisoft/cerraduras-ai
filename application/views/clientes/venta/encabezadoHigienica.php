
<htmlpageheader name="myHTMLHeader1">
<div style="width:100%">
	<div style="width:33%; float:left">
    	<img src="<?php echo base_url()?>img/logo.png" style="width:330px; height:90px;" />
    
    	<img src="<?php echo base_url()?>img/logos/1_Firestone1.png" style="width:180px; height:100px;" />

        <img src="<?php echo base_url()?>img/fire.png" style="width:60px; height:60px; margin-bottom:15px" />
    </div>
    
	<div style="float:left; width:36%; font-size:14px; color:#000">
    	 <table style="font-size:14px; width:100%; color:#000 ">
            <tr>
                <td width="65%">
                <?php
				echo $empresa->direccion.' '.$empresa->numero.'<br />';
				echo 'Col.'.$empresa->colonia.' C.P.'.$empresa->codigoPostal.'<br />';
				echo $empresa->municipio.', '.$empresa->estado.'<br />';

                ?>
               
                </td>
                <td style="font-size:12px">
                Tel. (271) 71.6.24.20
                </td>
            </tr>
         </table>
      	
 		
        <div style="margin-left:50px">
            <span style="font-size:16px">VIVE LA EXPERIENCIA FIRESTONE</span>
            <table style="font-size:14px; line-height:11px; margin-left:20px; color:#000">
                <tr>
                    <td>-Cambio de Aceite</td>
                    <td>-Suspensión</td>
                </tr>
                <tr>
                    <td>-Alineación y balanceo</td>
                    <td>-Llantas</td>
                </tr>
                <tr>
                    <td>-Afinación</td>
                    <td>-Diagnóstico</td>
                </tr>
                <tr>
                    <td>-Frenos</td>
                    <td>-Amortiguadores</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div style="border-radius: 10px; border: solid 2px #000; width:180px; height:100px; float:right;">
   		<div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:20px">REMISIÓN</div>
        <div align="center" style="height:25px; color:#000; font-size:20px">
        	<?php echo $cotizacion->folio?>
        </div>
        <div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:20px">FECHA</div>
        <div align="center" style="height:25px; color:#000; font-size:20px">
        	<?php echo obtenerFechaMesCorto(substr($cotizacion->fechaCompra,0,10))?>
        </div>
    </div>
</div>


</htmlpageheader>

<htmlpageheader name="myHTMLHeader1Even">

<div style="width:100%">
	<div style="width:33%; float:left">
    	<img src="<?php echo base_url()?>img/logo.png" style="width:330px; height:90px;" />
    
    	<img src="<?php echo base_url()?>img/logos/1_Firestone1.png" style="width:180px; height:100px;" />

        <img src="<?php echo base_url()?>img/fire.png" style="width:60px; height:60px; margin-bottom:15px" />
    </div>
    
	<div style="float:left; width:36%; font-size:14px; color:#000">
    	 <table style="font-size:14px; width:100%; color:#000 ">
            <tr>
                <td width="65%">
                <?php
				echo $empresa->direccion.' '.$empresa->numero.'<br />';
				echo 'Col.'.$empresa->colonia.' C.P.'.$empresa->codigoPostal.'<br />';
				echo $empresa->municipio.', '.$empresa->estado.'<br />';

                ?>
               
                </td>
                <td style="font-size:12px">
                Tel. (271) 71.6.24.20
                </td>
            </tr>
         </table>
      	
 		
        <div style="margin-left:50px">
            <span style="font-size:16px">VIVE LA EXPERIENCIA FIRESTONE</span>
            <table style="font-size:14px; line-height:11px; margin-left:20px; color:#000">
                <tr>
                    <td>-Cambio de Aceite</td>
                    <td>-Suspensión</td>
                </tr>
                <tr>
                    <td>-Alineación y balanceo</td>
                    <td>-Llantas</td>
                </tr>
                <tr>
                    <td>-Afinación</td>
                    <td>-Diagnóstico</td>
                </tr>
                <tr>
                    <td>-Frenos</td>
                    <td>-Amortiguadores</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div style="border-radius: 10px; border: solid 2px #000; width:180px; height:100px; float:right;">
   		<div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:20px">REMISIÓN</div>
        <div align="center" style="height:25px; color:#000; font-size:20px">
        	<?php echo $cotizacion->folio?>
        </div>
        <div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:20px">FECHA</div>
        <div align="center" style="height:25px; color:#000; font-size:20px">
        	<?php echo obtenerFechaMesCorto(substr($cotizacion->fechaCompra,0,10))?>
        </div>
    </div>
</div>

<div style="border-radius: 6px; border: solid 2px #000; width:100%; margin-top:15px; border-bottom-left-radius:none; border-bottom-right-radius:none ">
<table width="100%" style="border-collapse:collapse; font-size:12px; color:#000 ">
    <tr>
        <tr>
        	<th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px; height:30px">CÓDIGO</th>
        	<th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px; height:30px">CANT.</th>
            <th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">U.M</th>
            <th width="25%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">DESCRIPCIÓN</th>
            <th width="15%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">P. UNITARIO</th>
            <th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">%DESC</th>
            <th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">DESC</th>
            <th width="10%" align="right"  style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">IMPORTE</th>
        </tr>
    </tr>
</table>
</div>
 </htmlpageheader>

    
mpdf-->
<!-- set the headers/footers - they will occur from here on in the document -->
<!--mpdf
<sethtmlpageheader name="myHTMLHeader1" page="O" value="on" show-this-page="1" />
<sethtmlpageheader name="myHTMLHeader1Even" page="E" value="on" />
mpdf-->
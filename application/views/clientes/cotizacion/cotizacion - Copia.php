<?php $this->load->view('clientes/cotizacion/encabezado');?>


<!--div style="width:100%">
	<div style="width:33%; float:left">
    	<img src="<? echo base_url()?>img/logos/<?php echo $this->session->userdata('logotipo')?>" style="width:230px; height:100px;" />
        
        <img src="<? echo base_url()?>img/fire.png" style="width:60px; height:60px; margin-bottom:15px" />
    </div>
    
	<div style="float:left; width:36%; font-size:14px; color:#000">
    	 <table style="font-size:14px; width:100%; color:#000 ">
            <tr>
                <td width="70%">
                Av.11 No.2215 entre calles 22 y 24<br />
            Col. De los Maestros C.P.94550<br />
            Córdoba, Ver.
                </td>
                <td style="font-size:20px">
                Tel. (271)<br />
                71.6.24.20
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
   		<div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:20px">PRESUPUESTO</div>
        <div align="center" style="height:25px; color:#000; font-size:20px">
        	<?php echo $cotizacion->folio?>
        </div>
        <div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:20px">FECHA</div>
        <div align="center" style="height:25px; color:#000; font-size:20px">
        	<?php echo obtenerFechaMesCorto(substr($cotizacion->fecha,0,10))?>
        </div>
    </div>
</div-->

<div style="border-radius: 10px; border: solid 2px #000; width:100%; margin-top:10px ">
	<div align="left" style="height:25px; color:#000; border:none; font-size:16px; padding-left:15px">
    NOMBRE: <?php  echo $cliente->empresa?>
    </div>
    
    <div align="left" style="height:25px; color:#000; border-top: solid 2px #000; font-size:16px; padding-left:15px">
    DIRECCIÓN: <?php  echo $cliente->calle.' '. $cliente->numero.' '.$cliente->colonia ?>
    </div>
    
    <div align="left" style="height:25px; color:#000; border-top: solid 2px #000; font-size:16px; padding-left:15px">
    CIUDAD: <?php  echo $cliente->localidad?>
    </div>
    
    <div align="left" style="height:25px; color:#000; border-top: solid 2px #000; font-size:16px; padding-left:15px">
    VEHÍCULO: 
    </div>
</div>


<div style="border-radius: 6px; border: solid 2px #000; width:100%; margin-top:15px ">
	<table width="100%" style="border-collapse:collapse; font-size:16px; color:#000 ">
    	<tr>
        	<th width="10%" style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px; height:30px">CÓDIGO</th>
        	<th width="10%" style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px; height:30px">CANT.</th>
            <th width="10%" style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px">U.M</th>
            <th width="25%" style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px">DESCRIPCIÓN</th>
            <th width="15%" style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px">P. UNITARIO</th>
            <th width="10%" style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px">%DESC</th>
            <th width="10%" style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px">DESC</th>
            <th width="10%" align="right"  style="background-color:#000; color:#FFF; font-size:20px; border-top-left-radius:6px">IMPORTE</th>
        </tr>
        
        <?php
		$subTotal			=$cotizacion->subTotal;
		$descuento			=($subTotal*$cotizacion->descuentoPorcentaje)/100;
		$totalDescuento		=$subTotal-$descuento;
		$iva				=$totalDescuento*$cotizacion->iva;
		
		$totalMano			=0;
		$totalProductos		=0;
		$totalDescuento		=0;
		
		foreach($productos as $row)
		{
			$producto	=strlen($row->producto)>0?$row->producto:$row->descripcion;
			
			#$totalMano		+=$row->servicio==1?$row->importe-$row->descuento:0;
			#$totalProductos	+=$row->servicio==0?$row->importe-$row->descuento:0;
			
			$totalMano		+=$row->servicio==1?$row->importe:0;
			$totalProductos	+=$row->servicio==0?$row->importe:0;
			
			$totalDescuento	+=$row->descuento;
			
			echo '
			<tr>
				<td style="border-right: solid 2px #000" align="center">'.$row->codigoInterno.'</td>
				<td style="border-right: solid 2px #000" align="center">'.number_format($row->cantidad,2).'</td>
				<td style="border-right: solid 2px #000" align="center">'.$row->unidad.'</td>
				<td style="border-right: solid 2px #000">'.$row->medida.' '.$producto.'</td>
				<td style="border-right: solid 2px #000" align="center">$'.number_format($row->precio,2).'</td>
				<td style="border-right: solid 2px #000" align="center">%'.number_format($row->descuentoPorcentaje,2).'</td>
				<td style="border-right: solid 2px #000" align="center">$'.number_format($row->descuento,2).'</td>
				<td align="right">$'.number_format($row->importe-$row->descuento,2).'</td>
			</tr>';
		}
        ?>
    </table>
	
</div>

<div style="width:100%; margin-top:10px">
	<div align="center" style="border-radius: 10px; border: solid 2px #000; width:68%; float:left; font-size:14px; color:#000">
    	OBSERVACIONES<br />
		<?php  echo $cantidadLetra. ' '. $cotizacion->clave?>
    </div>
    
    <div align="center" style="border-radius: 10px; border: solid 2px #000; width:30%; float:right; font-size:15px; color: #000">
    	<div style="width:45%; float:left; text-align:right">
        PRODUCTOS $<br />
		MANO DE OBRA $<br />
        DESCUENTO $<br />
		I.V.A $<br />
		TOTAL $
        </div>
        <div style="width:52%; float:right; text-align:right; margin-left:2px; font-size:15px">
        <?php echo ''.number_format($totalProductos,2)?>
        <br />
         <?php echo ''.number_format($totalMano,2)?>
         <br />
        <?php echo ''.number_format($descuento+$totalDescuento,2)?>
		<br />
        <?php echo ''.number_format($iva,2)?>
		<br />
		<?php echo ''.number_format($cotizacion->total,2)?>
        </div>
    </div>
    
</div>

<div align="center" style="font-size:13px; width:80%">
	<u>PRECIOS SUJETOS A CAMBIOS SIN PREVIO AVISO</u>
</div>

<!--div align="left" style="padding-left:30px; font-size:10px; color:#000;" >

COMENTARIOS: <?php echo $cotizacion->condiciones?>
<br />
<br />


<?php echo $cotizacion->comentarios?>
</div-->






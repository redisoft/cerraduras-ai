<?php #$this->load->view('clientes/cotizacion/encabezado');?>

<div style="width:100%">

	<div style="width:33%; float:left">
    	<img src="<?php echo base_url()?>img/logos/<?php echo $this->session->userdata('logotipo')?>" style="max-width:200px; max-height:120px;" />
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
                Tel. <?php echo $empresa->telefono?>
                </td>
            </tr>
         </table>
    </div>
    
    <div style="border-radius: 10px; border: solid 1px #000; width:180px; height:100px; float:right;">
   		<div align="center" style="height:20px; background-color:#000; color:#FFF; font-size:16px; border-top-left-radius: 10px; border-top-right-radius: 10px;">COTIZACIÓN</div>
        <div align="center" style="height:20px; color:#000; font-size:16px">
        	<?php echo $cotizacion->folioCotizacion?>
        </div>
        
        <div align="center" style="height:20px; background-color:#000; color:#FFF; font-size:16px">FECHA</div>
        <div align="center" style="height:20px; color:#000; font-size:16px">
        	<?php echo obtenerFechaMesCorto(substr($cotizacion->fecha,0,10))?>
        </div>
    </div>
</div>

<div style="color: #000; font-size:16px">
	<?php echo $contacto!=null?$contacto->nombre:''?> <br />
    <?php echo $cliente!=null?$cliente->empresa:''?> <br />
    
    <!--PRESENTE <br /><br />-->

    
    <?php #echo $cotizacion->asunto?> <br /><br />
    
    <?php #echo nl2br($cotizacion->presentacion)?>
    
</div>

<!--<div style="border-radius: 10px; border: solid 1px #000; width:100%; margin-top:0px ">
	<div align="left" style="height:15px; color:#000; border:none; font-size:12px; padding-left:15px">
    NOMBRE: <?php  echo $cliente->empresa?>
    </div>
    
    <div align="left" style="height:15px; color:#000; border-top: solid 1px #000; font-size:12px; padding-left:15px">
    DIRECCIÓN: <?php  echo $cliente->calle.' '. $cliente->numero.' '.$cliente->colonia.', '.$cliente->localidad ?>
    </div>
    
    <div align="left" style="height:15px; color:#000; border-top: solid 1px #000; font-size:12px; padding-left:15px">
    CIUDAD: 
	<?php  
		echo $cliente->municipio.'';
		echo strlen($cliente->estado)>0?', '.$cliente->estado:'';
		echo strlen($cliente->pais)>0?', '.$cliente->pais:'';
	?>
    </div>
</div>-->


<!--<div style="border-radius: 6px; border: solid 1px #000; width:100%; margin-top:15px ">-->
	<table width="100%" style="border-collapse:collapse; font-size:12px; color:#000 ">
    	<tr>
        	<th style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:11px; border-top-left-radius:6px; height:25px">CÓDIGO</th>
            <th style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:11px; border-top-left-radius:6px">DESCRIPCIÓN</th>
            <th style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:11px; border-top-left-radius:6px">U.M</th>
        	<th style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:11px; border-top-left-radius:6px; height:25px">CANT.</th>
            <th style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:11px; border-top-left-radius:6px">P.U</th>
            <th style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:11px; border-top-left-radius:6px">Desc</th>
            <th align="right"  style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:11px; border-top-left-radius:6px">IMPORTE</th>
        </tr>
        
        <?php
		$subTotal			= $cotizacion->subTotal;
		$descuento			= ($subTotal*$cotizacion->descuentoPorcentaje)/100;
		$totalDescuento		= $subTotal-$descuento;
		$iva				= $totalDescuento*$cotizacion->iva;

		$totalDescuento		=0;
		
		foreach($productos as $row)
		{
			$producto			=strlen($row->producto)>0?$row->producto:$row->descripcion;
			$totalDescuento		+=$row->descuento;

			$precio			= $row->precio;
			$importe		= $row->importe;

			if($desglose=='1')
			{
				$precio		= round($row->precio*(1+($cotizacion->ivaPorcentaje/100)),2);
				$importe	= $row->cantidad*$precio;

			}
			
			echo '
			<tr>
				<td style="border: solid 1px #000" align="center">'.$row->codigoInterno.'</td>
				<td style="border: solid 1px #000">'.$producto.'</td>
				<td style="border: solid 1px #000" align="center">'.$row->unidad.'</td>
				<td style="border: solid 1px #000" align="center">'.number_format($row->cantidad,2).'</td>
				<td style="border: solid 1px #000" align="center">$'.number_format($precio,2).'</td>
				<td style="border: solid 1px #000" align="center">$'.number_format($row->descuento,2).'</td>
				<td style="border: solid 1px #000" align="right">$'.number_format($importe,2).'</td>
			</tr>';
		}
        ?>
    </table>
	
<!--</div>-->

<div style="width:100%; margin-top:10px">
	<div align="center" style="border-radius: 10px; border: solid 1px #000; width:68%; float:left; font-size:14px; color:#000">
		<?php  echo $cantidadLetra. ' '. $cotizacion->clave?>
    </div>
    
    <div align="center" style="border-radius: 10px; border: solid 1px #000; width:30%; float:right; font-size:15px; color: #000">
    	<div style="width:47%; float:left; text-align:right">
       
        <?php echo $desglose=='0'?'SUBTOTAL $<br />
        DESC $<br />
		IMPUESTOS $<br />':''?>
		
		TOTAL $
        </div>
        <div style="width:50%; float:right; text-align:right; margin-left:2px; font-size:15px">
        
		<?php echo $desglose=='0'?'
		'.number_format($cotizacion->subTotal,2).'
		<br />
        '.number_format($cotizacion->descuento,2).'
		<br />
        '.number_format($cotizacion->iva,2).'
		<br />
		':''?>
		
		
		
		<?php echo ''.number_format($cotizacion->total,2)?>
        </div>
    </div>
    
    <?php
    if(strlen($cotizacion->observaciones)>2)
	{
		echo '
		<div align="left" style="border-radius: 10px; border: solid 1px #000; width:100%;font-size:14px; color:#000; margin-top:2px">
			OBSERVACIONES<br />
			'.sustituirSaltos($cotizacion->observaciones).'
		</div>';
	}
	?>
</div>

<div align="center" style="font-size:13px; width:80%; color: #000">
	<u>PRECIOS SUJETOS A CAMBIOS SIN PREVIO AVISO</u>
</div>

<!--div align="left" style="padding-left:30px; font-size:10px; color:#000;" >

COMENTARIOS: <?php echo $cotizacion->condiciones?>
<br />
<br />


<?php echo $cotizacion->comentarios?>
</div-->






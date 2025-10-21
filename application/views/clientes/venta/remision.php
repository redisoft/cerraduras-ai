
<?php #$this->load->view('clientes/venta/encabezado');?>

<div style="width:100%">
	<div style="width:33%; float:left">
    	<img src="<?php echo base_url()?>img/logos/<?php echo $this->session->userdata('logotipo')?>" style="width:200px; height:120px;" />
    </div>
    
	<div style="float:left; width:36%; font-size:14px; color:#000">
    	 <table style="font-size:14px; width:100%; color:#000 ">
            <tr>
                <td width="65%">
                <?php
				echo ($tienda!=null?$tienda->calle:$empresa->direccion).' '.($tienda!=null?$tienda->numero:$empresa->numero).'<br />';
				echo 'Col.'.($tienda!=null?$tienda->colonia:$empresa->colonia).' C.P.'.($tienda!=null?$tienda->codigoPostal:$empresa->codigoPostal).'<br />';
				echo ($tienda!=null?$tienda->municipio:$empresa->municipio).', '.($tienda!=null?$tienda->estado:$empresa->estado).'<br />';

                ?>
               
                </td>
                <td style="font-size:12px">
                Tel. <?php echo ($tienda!=null?$tienda->telefono:$empresa->telefono)?>
                </td>
            </tr>
         </table>
      	</div>
    
    <div style="border-radius: 10px; border: solid 1px #000; width:180px; height:100px; float:right;">
   		<div align="center" style="height:20px; background-color:#000; color:#FFF; font-size:16px">REMISIÓN</div>
        <div align="center" style="height:20px; color:#000; font-size:16px">
        	<?php echo $cotizacion->folio?>
        </div>
        <div align="center" style="height:20px; background-color:#000; color:#FFF; font-size:16px">FECHA</div>
        <div align="center" style="height:20px; color:#000; font-size:16px">
        	<?php echo obtenerFechaMesCorto(substr($cotizacion->fechaCompra,0,10))?>
        </div>
    </div>
</div>

<div style="border-radius:10px; border:solid 1px #000; width:100%; margin-top:0px ">
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
    
    <!--div align="left" style="height:15px; color:#000; border-top: solid 1px #000; font-size:12px; padding-left:15px">
    VEHÍCULO: 
    </div-->
</div>


<!--<div style="border-radius: 6px; border: solid 1px #000; width:100%; margin-top:15px ">-->
	<table width="100%" style="border-collapse:collapse; font-size:12px; color:#000; margin-top:5px ">
    	<tr>
        	<th width="10%" style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px; height:30px">CÓDIGO</th>
            <th width="25%" style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">DESCRIPCIÓN</th>
            <th width="10%" style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">U.M</th>
        	<th width="10%" style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px; height:30px">CANT.</th>
            <th width="15%" style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">P.U</th>
            <th width="10%" style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">DESC</th>
            <th width="10%" align="right"  style="border: solid 1px #000; background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">IMPORTE</th>
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
			$totalProductos	+=$row->servicio==0?$row->importe:0;
			$totalDescuento	+=$row->descuento;
			
			if($row->servicio==1)
			{
				$totalMano		+=$row->manoObra;
				$totalProductos	+=$row->refacciones;
			}
			
			echo '
			<tr>
				<td style="border: solid 1px #000" align="center">'.$row->codigoInterno.'</td>
				<td style="border: solid 1px #000">'.$row->medida.' '.$producto.'</td>
				<td style="border: solid 1px #000" align="center">'.$row->unidad.'</td>
				<td style="border: solid 1px #000" align="center">'.number_format($row->cantidad,2).'</td>
				<td style="border: solid 1px #000" align="right">$'.number_format($row->precio,2).'</td>
				<td style="border: solid 1px #000" align="right">$'.number_format($row->descuento,2).'</td>
				<td style="border: solid 1px #000" align="right">$'.number_format($row->importe,2).'</td>
			</tr>';
		}
        ?>
    </table>
	
<!--</div>-->

<div style="width:100%; margin-top:10px">
	<div align="center" style="border-radius: 10px; border: solid 1px #000; width:65%; float:left; font-size:14px; color:#000">
		<?php  echo $cantidadLetra. ' '. $cotizacion->clave?>
    </div>
    
    <div align="center" style="border-radius: 10px; border: solid 1px #000; width:33%; float:right; font-size:15px; color: #000">
    	<div style="width:47%; float:left; text-align:right">
        <!--PRODUCTOS $<br />
		MANO DE OBRA $<br />
        DESCUENTO $<br /-->
        SUBTOTAL $<br />
       <!-- DESCUENTO $<br /> -->
		IMPUESTOS $<br />
		TOTAL $
        </div>
        <div style="width:50%; float:right; text-align:right; margin-left:2px; font-size:15px">
      
      
        <?php echo ''.number_format($cotizacion->subTotal,2)?>
        <!--<br />-->
         <?php #echo ''.number_format($cotizacion->descuento,2)?>
        <br />
        <?php echo ''.number_format($cotizacion->iva,2)?>
		<br />
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

<!--div align="left" style="padding-left:30px; font-size:10px; color:#000;" >

COMENTARIOS: <?php echo $cotizacion->condiciones?>
<br />
<br />


<?php echo $cotizacion->comentarios?>
</div-->






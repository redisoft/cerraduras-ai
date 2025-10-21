
<div style="width:100%; height:170.055px">
	<div style="width:70%; float:left; text-align:center; font-size:11px">
    	<img src="<?php echo base_url()?>img/logoRemision.png" style="width:330px; height:100px;" />
        
		<?php
			echo '<strong>Fabricado y Distribuido por Esponjas La Higiénica </strong> <br />';
			echo ($tienda!=null?$tienda->calle:$empresa->direccion).' '.($tienda!=null?$tienda->numero:$empresa->numero).' ';
			echo 'Col.'.($tienda!=null?$tienda->colonia:$empresa->colonia).', ';
			echo ($tienda!=null?$tienda->municipio:$empresa->municipio).'<br />';
			echo 'Tel./Fax: '.($tienda!=null?$tienda->telefono:$empresa->telefono).' '.($tienda!=null?$tienda->email:$empresa->correo).'<br /> lahigienicafibrasyesponjas.com';
        ?>
        
    </div>

    <div style="border-radius: 10px; border: solid 2px #054990; width:28%; height:70px; float:right;">
   		<div align="center" style="height:18px; background-color:#FFF; color:#054990; font-size:13px; border-top-left-radius:6px; border-top-right-radius:6px; border-bottom: solid 1px #054990">NOTA DE VENTA</div>
        <div align="center" style="height:22px; color:#054990; font-size:30px; line-height:40px; color:#F00">
        	<?php echo $cotizacion->folio?>
        </div>
    </div>
    
    <div style="border-radius: 10px; border: solid 2px #054990; width:28%; height:70px; float:right; margin-top:10px">
        <div align="center" style="height:18px; background-color:#FFF; color:#054990; font-size:13px; border-top-left-radius:6px; border-top-right-radius:6px; border-bottom: solid 1px #054990">FECHA</div>
        <div align="center" style="height:65px;  font-size:20px; ">
        	<div style="border-right:solid 1px #054990; width:33%; height:51px; float:left; font-size:13px" align="center">DIA <br /> <?='<span style="color:red">'.substr($cotizacion->fechaCompra,8,2).'</span>'?></div>
          	<div style="border-right:solid 1px #054990; width:33%; height:51px; float:left; font-size:13px" align="center">MES <br /><?='<span style="color:red">'.substr($cotizacion->fechaCompra,5,2).'</span>'?></div>
            <div style="width:32%; height:51px; float:left; font-size:13px" align="center">AÑO <br /> <?='<span style="color:red">'.substr($cotizacion->fechaCompra,0,4).'</span>'?></div>
        </div>
    </div>
    
</div>

<div style="border-radius:10px; border:solid 2px #054990; width:100%; margin-top:0px; line-height:20px; height:65.58px;  width:514.06px; padding:5px; ">
	
    <strong>NOMBRE:</strong> <u><?php  echo $cliente->empresa?></u><br />
    <strong>DIRECCIÓN:</strong> <u><?php  echo $cliente->calle.' '. $cliente->numero.' '.$cliente->colonia.', '.$cliente->localidad ?></u><br />    
    <strong>POBLACIÓN:</strong> <u>
	<?php  
		echo $cliente->municipio.'';
		echo strlen($cliente->estado)>0?', '.$cliente->estado:'';
		echo strlen($cliente->pais)>0?', '.$cliente->pais:'';
	?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   </u>
   
   <strong>TEL:</strong> <u><?=$cliente->telefono?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
</div>


<div style="border-radius: 6px; border: solid 2px #054990; width:100%; margin-top:10px; height:400.574px !important; max-height:400.574px !important ">
	<table width="100%" style="border-collapse:collapse; font-size:12px; color:#054990 ">
    	<tr>
        	<th width="71.80px" style="background-color:#FFF; color:#054990; font-size:10px; border-top-left-radius:6px; height:20px; border-bottom: solid 2px #054990; border-right: solid 2px #054990">CANTIDAD</th>
            <th width="234.298px" style="background-color:#FFF; color:#054990; font-size:10px; border-top-left-radius:6px; border-bottom: solid 2px #054990; border-right: solid 2px #054990">DESCRIPCIÓN</th>
            <th width="75.58px" style="background-color:#FFF; color:#054990; font-size:10px; border-top-left-radius:6px; border-bottom: solid 2px #054990; border-right: solid 2px #054990">P. UNITARIO</th>
            <th width="75.58px" align="center"  style="background-color:#FFF; color:#054990; font-size:10px; border-top-left-radius:6px; border-bottom: solid 2px #054990">IMPORTE</th>
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
				<td style=" color: #054990" align="center">'.number_format($row->cantidad,2).'</td>
				<td style=" solid 2px #054990; color: #054990">'.$row->medida.' '.$producto.'</td>
				<td style=" solid 2px #054990; color: #054990" align="center">$'.number_format($row->precio,2).'</td>
				<td style="color: #054990" align="right">$'.number_format($row->importe-$row->descuento,2).'</td>
			</tr>';
		}
        ?>
    </table>
	
</div>

<div style="width:100%; margin-top:10px">
	<strong>
    DEBEMOS Y PAGAREMOS LA CANTIDAD DE: <br />
    $ ______________________________ <span style="font-size:20px">TOTAL $</span> </strong>
    
	<div align="left" style="border-radius: 10px; border: solid 2px #054990; width:350px; float:left; font-size:14px;  padding:2px; font-style:italic; height:68.022px; line-height:22px">
    	Cantidad con letra<br />
		<?php  echo $cantidadLetra. ' '. $cotizacion->clave?>
    </div>
    
    <div align="center" style="border-radius: 10px; border: solid 2px #054990; width:155px; float:right; font-size:24px;  height:41.569px; margin-top:-37px; line-height:37px">
		<?php echo '$'.number_format($cotizacion->total,2)?>
    </div>
    
    <div align="center" style="width:30%; float:right; font-size:20px; height:35px; font-family: Tahoma, Geneva, sans-serif">
    	Gracias por su preferencia
    </div>
</div>

<?php #$this->load->view('compras/encabezado');?>
<div style="width:100%">
	<div style="width:33%; float:left">
    	<?php
        if(strlen($empresa->logotipo)>0 and file_exists('img/logos/'.$empresa->id.'_'.$empresa->logotipo))
		{
			echo '<img src="'.base_url().'img/logos/'.$empresa->id.'_'.$empresa->logotipo.'" style="max-width:230px; max-height:100px;" />';
		}
		?>
    	
    </div>
    
	<div style="float:left; width:36%; font-size:14px; color:#000">
    	 <table style="font-size:14px; width:100%; color:#000 ">
            <tr>
                <td width="70%">
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
   		<div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:16px; border-top-left-radius: 10px; border-top-right-radius: 10px;">ORDEN DE COMPRA</div>
        <div align="center" style="height:25px; color:#000; font-size:16px">
        	<?php echo $compra->nombre?>
        </div>
        <div align="center" style="height:25px; background-color:#000; color:#FFF; font-size:16px">FECHA</div>
        <div align="center" style="height:25px; color:#000; font-size:16px">
        	<?php echo obtenerFechaMesCorto(substr($compra->fechaCompra,0,10))?>
        </div>
    </div>
</div>

<div style="border-radius: 10px; border: solid 1px #000; width:100%; margin-top:10px ">
	<div align="left" style="height:25px; color:#000; border:none; font-size:16px; padding-left:15px">
    PROVEEDOR: <?php  echo $compra->empresa?>
    </div>
    
    <div align="left" style="height:25px; color:#000; border-top: solid 1px #000; font-size:16px; padding-left:15px">
    DIRECCIÓN: <?php  echo $compra->domicilio.' '. $compra->numero.' '.$compra->colonia ?>
    </div>
    
    <div align="left" style="height:25px; color:#000; border-top: solid 1px #000; font-size:16px; padding-left:15px">
    CIUDAD: <?php  echo $compra->municipio?>
    </div>
    
</div>


<div style="border-radius: 6px; border: solid 1px #000; width:100%; margin-top:15px ">
	<table width="100%" style="border-collapse:collapse; font-size:16px; color:#000 ">
    	<tr>
        	<th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px; height:25px">CÓDIGO</th>
            <th width="25%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">DESCRIPCIÓN</th>
            <th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">U.M</th>
        	<th width="10%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px; height:25px">CANT.</th>
            <th width="20%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">P.U</th>
            <th width="20%" style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">DESCUENTO UNITARIO</th>
            <th width="15%" align="right"  style="background-color:#000; color:#FFF; font-size:12px; border-top-left-radius:6px">IMPORTE</th>
        </tr>
        
        <?php

		foreach($productos as $row)
		{
			echo '
			<tr>
				<td style="border-right: solid 1px #000" align="center">'.$row->codigoInterno.'</td>
				<td style="border-right: solid 1px #000">'.$row->nombre.'</td>
				<td style="border-right: solid 1px #000" align="center">'.$row->unidad.'</td>
				<td style="border-right: solid 1px #000" align="center">'.number_format($row->cantidad,2).'</td>
				<td style="border-right: solid 1px #000" align="center">$'.number_format($row->precio,2).'</td>
				<td style="border-right: solid 1px #000" align="center">$'.number_format($row->descuento,2).'</td>
				<td align="right">$'.number_format($row->total,2).'</td>
			</tr>';
		}
        ?>
    </table>
	
</div>

<div style="width:100%; margin-top:10px">
	<div align="center" style="border-radius: 10px; border: solid 1px #000; width:65%; float:left; font-size:14px; color:#000">
    	OBSERVACIONES<br />
		<?php  echo $cantidadLetra?>
    </div>
    
    <div align="center" style="border-radius: 10px; border: solid 1px #000; width:33%; float:right; font-size:15px; color: #000">
    	<div style="width:49%; float:left; text-align:right">
        SUBTOTAL $<br />
        DESCUENTO GLOBAL$<br />
		I.V.A $<br />
		TOTAL $
        </div>
        <div style="width:50%; float:right; text-align:right; margin-left:2px; font-size:15px">
        <?php echo ''.number_format($compra->subTotal,2)?>
        <br /><br />
        <?php echo ''.number_format($compra->descuento,2)?>
		<br />
        <?php echo ''.number_format($compra->iva,2)?>
		<br />
		<?php echo ''.number_format($compra->total,2)?>
        </div>
    </div>
    
</div>

<div align="left" style="font-size:13px; width:80%">
	<u>Términos y condiciones</u><br />
    
    <?php echo nl2br($compra->terminos)?>
</div>







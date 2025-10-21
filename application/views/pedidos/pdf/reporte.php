
<table class="admintable" width="100%">
    <tr>
        <th colspan="2" class="encabezadoPrincipal">
            Detalles de pedido
        </th>
    </tr>
    <tr>
        <td class="key">Fecha:</td>
        <td>
            <?php echo obtenerFechaMesCorto($pedido->fechaPedido)?>
        </td>
    </tr>
    
    <tr>
        <td class="key">Pedido:</td>
        <td><?php 
		if($pedido->idLinea==2) echo frances;
		if($pedido->idLinea==3) echo bizcocho;
		echo $pedido->folio;?></td>
    </tr>
    
    <tr>
        <td class="key">Línea:</td>
       <td><?php echo $pedido->linea?></td>
    </tr>

    <tr>
        <td class="key">Usuario:</td>
        <td><?php echo $pedido->usuario?></td>
    </tr>
    
    <tr>
        <td class="key">Total:</td>
        <td>$<?php echo number_format($total+$impuestos,decimales)?></td>
    </tr>
    
    <tr>
        <td class="key">Mano de obra:</td>
        <td>
            <?php echo $report!=null?round($report->manoObra,decimales):10?>%
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label id="lblManoObra">$<?php echo number_format($report->manoTotal,decimales)?></label>
            
             <!--&nbsp;&nbsp;&nbsp;&nbsp;
            <label>Maestro:</label>
            
            $<?php echo $reporte!=null?number_format($report->maestro,decimales):0?>
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label>Oficial</label>
            
           $<?php echo $reporte!=null?number_format($report->oficial,decimales):0?>-->
        </td>
    </tr>
    
    <tr>
        <td class="key">Cuota sindical:</td>
        <td>
            <?php echo $report!=null?round($report->cuotaSindical,decimales):4?>%
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label id="lblCuotaSindical">$<?php echo number_format($report->cuotaTotal,decimales)?></label>
            
           
        </td>
    </tr>
    
    <?php
    $dominical	= 25;
    $domingo	= obtenerDiaActual($pedido->fechaPedido);
    
    if($domingo!='domingo')
    {
        $dominical=0;
    }
    ?>
    
    <tr <?php echo $domingo!='domingo'?'style="display:none"':''?>>
        <td class="key">Prima dominical:</td>
        <td>
            <?php echo $report!=null?round($report->primaDominical,decimales):$dominical?>%
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label id="lblPrimaDominical">$<?php echo number_format($report->primaTotal,decimales)?></label>
        </td>
    </tr>
    
    <tr>
        <td class="key">Pago total:</td>
        <td>
            <label id="lblPagoTotal">$<?php echo number_format($report->manoTotal+$report->primaTotal-$report->cuotaTotal,decimales)?></label>
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label>Maestro: </label>
            <input type="text" class="cajas" style="width:120px" />
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <label>Oficial: </label>
             <input type="text" class="cajas" style="width:120px" />
             
            <br />
			<br />
			&nbsp;
            
        </td>
    </tr>
</table>


<table class="admintable" width="100%" id="tablaPedidos">
	<tr>
    	<th colspan="<?php echo $idRol!=5?'6':'1'?>" class="encabezadoPrincipal">
        	Detalles de productos
        </th>
    </tr>
	<tr>
        <th width="40%">Producto</th>
        <?php
        if($idRol!=5)
		{
		 	echo '
			<th>Cantidad pedido</th>
			<th>Cantidad producido</th>
			<th>Diferencia</th>
			<th>PU</th>
			<th>Importe</th>';
		}
		?>
    </tr>
    
    <?php
	$i=0;
	$cantidad	= 0;
	$importes	= 0;
	$producido	= 0;
    foreach($productos as $row)
	{
		$impuesto	= $row->precioA*($row->impuesto/100);
		$precio		= $impuesto+$row->precioA;
		$importe	= $precio*$row->producido;
		
		echo '
		<input type="hidden"  name="txtIdDetalle'.$i.'" id="txtIdDetalle'.$i.'" value="'.$row->idDetalle.'" />
		
		<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td>'.$row->producto.'</td>
			'.($idRol!=5?'<td align="center">'.number_format($row->cantidad,decimales).'</td>
			<td align="center">'.number_format($row->producido,decimales).'</td>
			<td align="center">'.number_format($row->producido-$row->cantidad,decimales).'</td>
			<td align="right">$'.number_format($precio,decimales).'</td>
			<td align="right">$'.number_format($importe,decimales).'</td>':'').'
		</tr>';
		
		$cantidad	+=$row->cantidad;
		$importes	+=$importe;
		$producido	+=$row->producido;
		
		$i++;
	}
	
	if($idRol!=5)
	{
		echo '
		<tr>
			<td class="totales">Totales</td>
			<td align="center" class="totales">'.number_format($cantidad,decimales).'</td>
			<td align="center" class="totales">'.number_format($producido,decimales).'</td>
			<td align="center"></td>
			<td align="right"></td>
			<td align="right" class="totales">$'.number_format($importes,decimales).'</td>
		</tr>';
	}
	?>
</table>

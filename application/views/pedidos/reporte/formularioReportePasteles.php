<form id="frmReporte">
    <input type="hidden" id="txtIdPedidoReporte" 	name="txtIdPedidoReporte" 	value="<?php echo $pedido->idPedido?>" />
    <input type="hidden" id="txtIdReporte" 			name="txtIdReporte" 		value="<?php echo $reporte!=null?$reporte->idReporte:0?>" />
    <input type="hidden" id="txtTotalReporte" 		name="txtTotalReporte" 		value="<?php echo 0?>" />
    <input type="hidden" id="txtTotalPeso" 			name="txtTotalPeso" 		value="<?php echo $pedido->totalPeso?>" />
    <input type="hidden" id="txtPagoMaestro" 		name="txtPagoMaestro" 		value="<?php echo $reporte!=null?$reporte->maestro:0?>" />
    
    <input type="hidden" id="txtTipoRerporte" 		name="txtTipoRerporte" 		value="pasteles" />
    
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">
				Detalles de orden
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
			<td id="lblTotal">$<?php echo number_format($pedido->totalPeso*($reporte!=null?$reporte->costoKg:costoKg),decimales)?></td>
		</tr>
        
        <tr>
			<td class="key">Peso en kg:</td>
			<td><?php echo number_format($pedido->totalPeso,decimales)?></td>
		</tr>
        
        <tr>
			<td class="key">Costo por kg:</td>
			<td>
            	<input type="text" class="cajas" onchange="calcularPagosKilos()" id="txtCostoKg" name="txtCostoKg" value="<?php echo $reporte!=null?round($reporte->costoKg,decimales):costoKg?>" style="width:50px" maxlength="8" onkeypress="return soloDecimales(event)" />
            </td>
		</tr>
        
        <tr>
			<td class="key">Maestro pago por kg:</td>
			<td>
            	<input type="text" class="cajas" onchange="calcularPagosKilos()" id="txtPagoKg" name="txtPagoKg" value="<?php echo $reporte!=null?round($reporte->pagoKg,decimales):0?>" style="width:50px"  maxlength="8" onkeypress="return soloDecimales(event)"/>
            </td>
		</tr>
        
       <tr>
			<td class="key">Pago maestro:</td>
			<td id="lblMaestro">$<?php echo number_format($reporte!=null?$reporte->maestro:0,decimales)?></td>
		</tr>
		
	</table>

</form>
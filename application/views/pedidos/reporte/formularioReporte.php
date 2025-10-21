<form id="frmReporte">

    
    <input type="hidden" id="txtIdPedidoReporte" 	name="txtIdPedidoReporte" 	value="<?php echo $pedido->idPedido?>" />
    <input type="hidden" id="txtIdReporte" 			name="txtIdReporte" 		value="<?php echo $reporte!=null?$reporte->idReporte:0?>" />
    <input type="hidden" id="txtTotalReporte" 		name="txtTotalReporte" 		value="<?php echo $total+$impuestos?>" />
    
    <input type="hidden" id="txtManoTotal" 			name="txtManoTotal" 		value="0" />
    <input type="hidden" id="txtCuotaTotal" 		name="txtCuotaTotal" 		value="0" />
    <input type="hidden" id="txtPrimaTotal" 		name="txtPrimaTotal" 		value="0" />
    
    <input type="hidden" id="txtTipoRerporte" 		name="txtTipoRerporte" 		value="panes" />
    
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
			<td>$<?php echo number_format($total+$impuestos,decimales)?></td>
		</tr>
        
        <tr>
			<td class="key">Mano de obra:</td>
			<td>
            	<input type="text" class="cajas" onchange="calcularImportesReporte()" id="txtManoObra" name="txtManoObra" value="<?php echo $reporte!=null?round($reporte->manoObra,decimales):manoObra?>" style="width:50px" maxlength="8" onkeypress="return soloDecimales(event)" />%
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label id="lblManoObra">$0.00</label>
                
                
            </td>
		</tr>
        
        <tr>
			<td class="key">Cuota sindical:</td>
			<td>
            	<input type="text" class="cajas" onchange="calcularImportesReporte()" id="txtCuotaSindical" name="txtCuotaSindical" value="<?php echo $reporte!=null?round($reporte->cuotaSindical,decimales):cuotaSindical?>" style="width:50px"  maxlength="8" onkeypress="return soloDecimales(event)"/>%
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label id="lblCuotaSindical">$0.00</label>
                
                
            </td>
		</tr>
        
        <?php
        $dominical	= primaDominical;
		$domingo	= obtenerDiaActual($pedido->fechaPedido);
		
		if($domingo!='domingo')
		{
			$dominical=0;
		}
		?>
        
        <tr <?php echo $domingo!='domingo'?'style="display:none"':''?>>
			<td class="key">Prima dominical:</td>
			<td>
            	<input type="text" class="cajas" onchange="calcularImportesReporte()" id="txtPrimaDominical" name="txtPrimaDominical" value="<?php echo $reporte!=null?round($reporte->primaDominical,decimales):$dominical?>" style="width:50px" maxlength="8" onkeypress="return soloDecimales(event)" />%
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label id="lblPrimaDominical">$0.00</label>
            </td>
		</tr>
        
        <tr>
			<td class="key">Pago total:</td>
			<td>
                <label id="lblPagoTotal">$0.00</label>
                
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>Maestro:</label>
                
                $<input type="text" class="cajas" onchange="calcularImportesReporte()" id="txtMaestro" name="txtMaestro" value="<?php echo $reporte!=null?round($reporte->maestro,decimales):0?>" style="width:50px"  maxlength="8" onkeypress="return soloDecimales(event)"/>
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>Oficial:</label>
                
               $<input type="text" class="cajas" 	id="txtOficial" name="txtOficial" value="<?php echo $reporte!=null?round($reporte->oficial,decimales):0?>" style="width:50px"  maxlength="8" onkeypress="return soloDecimales(event)"/>
                
                 &nbsp;&nbsp;&nbsp;&nbsp;
                Saldo: <label id="lblSaldo">$0.00</label>
                
            </td>
		</tr>
		
	</table>

</form>
<form id="frmRetiroEfectivo" name="frmRetiroEfectivo">
<table class="admintable" width="100%">
	<tr>
    	<td align="center">Efectivo disponible: $<?php echo number_format($efectivo+$fondoCaja-$retiros,decimales)?></td>
    </tr>
    
	<tr>
    	<td align="center">Ingrese retiro de efectivo</td>
    </tr>
    <tr>
    	<td align="center">
        	<input type="text" class="cajas" id="txtRetiroEfectivo" name="txtRetiroEfectivo" />
            <input type="hidden"  id="txtIdEgreso" name="txtIdEgreso" value="0" />
            <input type="hidden"  id="txtEfectivoDisponible" name="txtEfectivoDisponible" value="<?php echo number_format($efectivo+$fondoCaja-$retiros,decimales)?>" />
        </td>
    </tr>
    
    <tr>
    	<td align="center">Motivo</td>
    </tr>
    <tr>
    	<td align="center">
        	<textarea class="TextArea" id="txtMotivoRetiro" name="txtMotivoRetiro" style="width:300px; height:50px"></textarea>
        </td>
    </tr>
</table>
</form>
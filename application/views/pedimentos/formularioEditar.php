<script>
$(document).ready(function()
{
   $('#txtFechaPedimento').datepicker({changeYear: true});    
});
</script>
<?php
echo'
<form id="frmRegistroPedimentos" name="frmRegistroPedimentos" action="javascript:editarFormularioPedimentos()">
	<input type="hidden" id="txtIdPedimento" name="txtIdPedimento" value="'.$registro->idPedimento.'"/>
	<table class="admintable" width="100%">
        <tr>
			<td class="key">Año validación(Últimos 2 dígitos):</td>
			<td>
				<input type="text" id="txtAnio" name="txtAnio" class="cajas" value="'.$registro->anio.'" style="width: 120px" required="true" maxlength="2" />
			</td> 
		</tr>
       
        <tr>
			<td class="key">Aduana despacho(2 dígitos):</td>
			<td>
				<input type="text" id="txtAduana" name="txtAduana" value="'.$registro->aduana.'" class="cajas" style="width: 120px" required="true" maxlength="2" />
			</td> 
		</tr>
		
		<tr>
			<td class="key">Número patente(4 dígitos):</td>
			<td>
				<input type="text" id="txtPatente" name="txtPatente" value="'.$registro->patente.'" class="cajas" style="width: 120px" required="true"  maxlength="4"/>
			</td> 
		</tr>

		<tr>
			<td class="key">1 dígito año en curso + 6 dígitos numeración progresiva:</td>
			<td>
				<input type="text" id="txtDigitos" name="txtDigitos" value="'.$registro->digitos.'" class="cajas" style="width: 120px" required="true" maxlength="7"/>
			</td> 
		</tr>
        <tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" id="txtFechaPedimento" name="txtFechaPedimento" value="'.$registro->fecha.'" class="cajas" style="width: 120px" required="true" />
			</td> 
		</tr>
       
	</table>
</form>';

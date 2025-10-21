<?php
echo'
<script>
$("#txtFechaPedimento").datepicker({changeYear: true});
</script>
<form id="frmRegistroPedimentos" name="frmRegistroPedimentos" action="javascript:registrarFormularioPedimentos()">
	<table class="admintable" width="100%">
        
        
        <tr>
			<td class="key">Año validación(Últimos 2 dígitos):</td>
			<td>
				<input type="text" id="txtAnio" name="txtAnio" class="cajas" style="width: 120px" required="true" maxlength="2" />
			</td> 
		</tr>
       
        <tr>
			<td class="key">Aduana despacho(2 dígitos):</td>
			<td>
				<input type="text" id="txtAduana" name="txtAduana" class="cajas" style="width: 120px" required="true" maxlength="2" />
			</td> 
		</tr>
		
		<tr>
			<td class="key">Número patente(4 dígitos):</td>
			<td>
				<input type="text" id="txtPatente" name="txtPatente" class="cajas" style="width: 120px" required="true"  maxlength="4"/>
			</td> 
		</tr>

		<tr>
			<td class="key">1 dígito año en curso + 6 dígitos numeración progresiva:</td>
			<td>
				<input type="text" id="txtDigitos" name="txtDigitos" class="cajas" style="width: 120px" required="true" maxlength="7"/>
			</td> 
		</tr>

		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" id="txtFechaPedimento" name="txtFechaPedimento" class="cajas" style="width: 120px" required="true" />
			</td> 
		</tr>      
	</table>
</form>';

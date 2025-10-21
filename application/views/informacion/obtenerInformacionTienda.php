<?php
echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Sucursal</td>
		<td>'.$tienda->nombre.'</td>
	</tr>
	<tr>
		<td class="key">Teléfono:</td>
		<td>'.$tienda->telefono.'</td>
	</tr>
	<tr>
		<td class="key">Dirección:</td>
		<td>'.$tienda->calle.' '.$tienda->numero.' '.$tienda->colonia.' '.$tienda->localidad.' '.$tienda->municipio.' '.$tienda->estado.', CP: '.$tienda->codigoPostal.'</td>
	</tr>
</table>';
<?php
echo'
<input type="hidden" id="txtIdProveedorFicha" value="'.$proveedor->idProveedor.'" />
<table class="admintable" width="100%;">
	<tr>
		<th colspan="4"> Datos del proveedor</th>
	</tr>
	<tr>
		<td style="width:15%" class="key">Empresa:</td>
		<td style="width:35%">'.$proveedor->empresa.'</td>
		<td style="width:15%" class="key">Alias:</td>
		<td style="width:35%">'.$proveedor->alias.'</td>
		
	</tr>
	
	<tr>
		<td class="key">Calle:</td>
		<td>'.$proveedor->domicilio.'</td>
		<td class="key">Número:</td>
		<td>'.$proveedor->numero.'</td>
	</tr>
	
	<tr>
		<td class="key">Colonia:</td>
		<td>'.$proveedor->colonia.'</td>
		<td class="key">Localidad:</td>
		<td>'.$proveedor->localidad.'</td>
	</tr>
	
	<tr>
		<td class="key">Municipio:</td>
		<td>'.$proveedor->municipio.'</td>
		<td class="key">Estado:</td>
		<td>'.$proveedor->estado.'</td>
	</tr>
	
	<tr>
		<td class="key">País:</td>
		<td>'.$proveedor->pais.'</td>
		<td class="key">Código postal:</td>
		<td>'.$proveedor->codigoPostal.'</td>
	</tr>
	
	<tr>
		<td class="key">Teléfono:</td>
		<td>'.$proveedor->telefono.'</td>
		<td class="key">Email:</td>
		<td>'.$proveedor->email.'</td>
	</tr>

	<tr>
	 <td class="key">Página:</td>
	 <td colspan="3">'.$proveedor->website.'</td>
	</tr>
</table>

<table class="admintable" width="100%;">
	<tr>
		<th colspan="5"> Contactos</th>
	</tr>
	<tr>
		<th align="center">Nombre</th>
		<th align="center">Teléfono</th>
		<th align="center">Email</th>
		<th align="center">Departamento</th>
		<th align="center">Extensión</th>
	</tr>';

foreach($contactos as $contacto)
{
	echo'
	<tr>
		<td align="left">'.$contacto->nombre.'</td>
		<td align="left">'.$contacto->telefono.'</td>
		<td align="left">'.$contacto->email.'</td>
		<td align="left">'.$contacto->departamento.'</td>
		<td align="center">'.$contacto->extension.'</td>
	</tr>';
}
echo '</table>';
	
echo'
<table class="admintable" width="100%;">
	<tr>
		<th colspan="4"> Cuentas de banco</th>
	</tr>
	<tr>
		<th align="center">Banco</th>
		<th align="center">Sucursal</th>
		<th align="center">Cuenta</th>
		<th align="center">Clabe</th>
		
	</tr>';
	
	foreach($cuentas as $row)
	{
		echo'
		<tr>
			<td align="center">'.$row->banco.'</td>
			<td align="center">'.$row->sucursal.'</td>
			<td align="center">'.$row->cuenta.'</td>
			<td align="center">'.$row->clabe.'</td>
		</tr>';
	}
	
echo '</table>';
<?php
echo'
<form id="frmSucursales" name="frmSucursales">
	<input type="hidden" name="txtIdClienteSucursal" id="txtIdClienteSucursal" value="'.$cliente->idCliente.'"/>
	<input type="hidden" name="txtNumeroSucursales" id="txtNumeroSucursales" value="'.count($registros).'"/>
	<table class="admintable" width="100%">
		<tr>
		  <td class="key">Cliente:</td>
		  <td>'.$cliente->empresa.'</td>
		</tr>

		<tr>
		  <td class="key">Sucursal:</td>
		  <td>
			<select class="cajas" id="selectSucursalesRegistro" name="selectSucursalesRegistro" style="width:400px">';
				foreach($licencias as $row)
				{
					if($row->idLicencia!=$idLicencia)
					{
						echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';	
					}
				}

			echo'
			</select>
			&nbsp;&nbsp;
			<img src="'.base_url().'img/add.png" onclick="cargarSucursalCliente()" title="Agregar" width="22" />
		  </td>
		</tr>
	</table>
	
	<table class="admintable" width="100%" id="tablaSucursales">
		<tr>
			<th colspan="3">Sucursales</th>
		</tr>
		<tr>
			<th>Sucursal</th>
			<th>Borrar</th>
		</tr>';
	
		$i=0;

		foreach($registros as $row)
		{
			echo '
			<tr '.($i%2==0?'class="sinSombra"':'class="sombreado"').' id="filaSucursal'.$i.'">
				<td width="70%">'.$row->sucursal.'</td>
				<td align="center"><img src="'.base_url().'img/borrar.png" onclick="quitarSucursal('.$i.')" title="Borrar" width="22" /><br><a>Borrar</a></td>
				
				<input type="hidden" name="txtIdLicencia'.$i.'" id="txtIdLicencia'.$i.'" value="'.$row->idSucursal.'"/>
			</tr>';
			
			$i++;
		}
	echo'
	</table>
</form>';
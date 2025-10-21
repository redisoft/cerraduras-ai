<form id="frmRegistrarUsuario">
	<ul class="menuTabsCliente">
        <li id="generales" class="cliente activado" onclick="configurarTabsCliente('generales')">Datos de usuario</li>
        <li id="sucursales" class="cliente" onclick="configurarTabsCliente('sucursales')">Sucursales</li>
    </ul>
	
	<div id="div-generales" class="divCliente visible">
		<table class="admintable" width="100%">
		  <tr>
			<td class="key">Nombre:</td>
			<td> <input type="text" name="txtNombre" id="txtNombre" class="cajas" style="width:270px" maxlength="100" /> </td>
		  </tr>
		  <tr>
			<td class="key">Apellido paterno: </td>
			<td><input type="text" name="txtPaterno" id="txtPaterno" class="cajas" style="width:270px" maxlength="100"/></td>
		  </tr>
		  <tr>
			<td class="key">Apellido materno: </td>
			<td><input type="text" name="txtMaterno" id="txtMaterno" class="cajas" style="width:270px" maxlength="100"/></td>
		  </tr>	
		  <tr>
			<td class="key">Usuario:</td>
			<td valign="middle" align="left"> <input type="text" name="txtUsuario" id="txtUsuario" class="cajas" style="width:270px" maxlength="50" /> </td>
			</tr>
		  <tr>
			<td class="key">Contraseña:</td>
			<td  valign="middle" align="left"><input type="password" name="txtPassword" id="txtPassword" class="cajas" style="width:270px" maxlength="30" placeholder="Mínimo 6 caracteres"/></td>
		  </tr>
			<tr>
				<td class="key">Confirmar contraseña:</td>
				<td valign="middle" align="left"><input type="password" name="txtRepetirPassword" id="txtRepetirPassword" class="cajas" style="width:270px" maxlength="30" placeholder="Mínimo 6 caracteres"/></td>
			</tr>

			<tr>
				<td class="key">Vendedor:</td>
				<td valign="middle" align="left"> <input type="text" name="txtVendedor" id="txtVendedor" class="cajas" style="width:270px" maxlength="30" /> </td>
			</tr>

			<tr>
				<td class="key">Correo:</td>
				<td valign="middle" align="left"><input type="text" name="txtCorreo" id="txtCorreo" class="cajas"  style="width:270px" maxlength="50"/></td>
			</tr>  
			<tr>
				<td class="key">Rol de usuario:</td>
				<td >
					<select id="selectRol" name="selectRol" class="cajas" style="width:270px" <?=sistemaActivo=='cerraduras'?'':'onchange="criterioRol()"'?>>
						<option value="0">Seleccione</option>
						<?php 
						foreach($roles as $row)
						{
							echo '<option value="'.$row->idRol.'">'.$row->nombre.'</option>';
						}
						?>
					</select>    
				</td>
			</tr>

			
			

		  <tr style="display:none" id="filaTienda">
				<td class="key">Tienda:</td>
				<td >
				<select id="selectTiendas" name="selectTiendas" class="cajas" style="width:270px" >
					<option value="0">Seleccione</option>
					<?php 
					foreach($tiendas as $row)
					{
						echo '<option value="'.$row->idTienda.'">'.$row->nombre.'</option>';
					}
					?>
				</select>    
				</td>
			</tr>
			<tr>
				<td class="key">Firma:</td>
				<td valign="middle" align="left"><textarea name="txtFirma" id="txtFirma" class="TextArea" style="width:500px; height:100px" ></textarea></td>
			</tr>

			<tr>
				<td class="key">Clave descuento:</td>
				<td valign="middle" align="left"><input type="password" name="txtClaveDescuento" id="txtClaveDescuento" class="cajas"  style="width:270px" maxlength="50" placeholder="Mínimo 6 caracteres"/></td>
			</tr>  

			<tr>
				<td class="key">Clave usuario:</td>
				<td valign="middle" align="left"><input type="password" name="txtClaveCancelacion" id="txtClaveCancelacion" class="cajas"  style="width:270px" maxlength="50" placeholder="Mínimo 6 caracteres"/></td>
			</tr>  

			<tr>
				<td class="key">IPAD:</td>
				<td valign="middle" align="left"><input type="checkbox" id="chkIpd" name="chkIpd" value="1" /></td>
			</tr>  

		</table>
	</div>
	
	<div id="div-sucursales" class="divCliente">
    	 <script>
		$(document).ready(function()
		{
			$("#tablaSucursales tr:even").addClass("sombreado");
			$("#tablaSucursales tr:odd").addClass("sinSombra");  
		});
		</script>
         <input type="hidden" name="txtNumeroLicencias" id="txtNumeroLicencias" value="<?php echo count($licencias)?>" />
        <table class="admintable" width="100%" id="tablaSucursales">
            <tr>
                <th colspan="2">Sucursales</th>
                <!--<th colspan=""><label><input type="checkbox" id="chkTodas" onchange="checarSucursales()" /> &nbsp;Todas</label> </th>-->
            </tr>
            <tr>
                <th width="60%">Nombre</th>
                <th>Acceso</th>
            </tr>
            
            <?php
            $i=1;
            foreach($licencias as $row)
            {
                echo '
                <tr>
                    <td>'.$row->nombre.'</td>
                    <td align="center">
                        <input type="checkbox" id="chkLicencia'.$i.'" name="chkLicencia'.$row->idLicencia.'" value="1" />
                    </td>
                </tr>';
                
                $i++;
            }
            ?>
            
        </table>
    </div>
</form>


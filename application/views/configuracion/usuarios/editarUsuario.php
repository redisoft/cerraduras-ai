<form id="frmEditarUsuario">
	<ul class="menuTabsCliente">
        <li id="generales" class="cliente activado" onclick="configurarTabsCliente('generales')">Datos de usuario</li>
        <li id="sucursales" class="cliente" onclick="configurarTabsCliente('sucursales')">Sucursales</li>
    </ul>
	<div id="div-generales" class="divCliente visible">
		<table class="admintable" width="100%">
		  <tr>
			<td class="key">Nombre:</td>
			<td> 
				<input type="text" value="<?php echo $usuario->nombre?>" name="txtNombre" id="txtNombre" class="cajas" style="width:270px" /> 
				<input type="hidden" value="<?php echo $usuario->idUsuario?>" name="txtIdUsuario" id="txtIdUsuario" /> 
			 </td>
		  </tr>
		  <tr>
			<td class="key">Apellido paterno: </td>
			<td><input type="text" value="<?php echo $usuario->apellidoPaterno?>" name="txtPaterno" id="txtPaterno" class="cajas" style="width:270px" /></td>
		  </tr>
		  <tr>
			<td class="key">Apellido materno: </td>
			<td><input type="text" value="<?php echo $usuario->apellidoMaterno?>" name="txtMaterno" id="txtMaterno" class="cajas" style="width:270px" /></td>
		  </tr>	
		  <tr>
			<td class="key">Usuario:</td>
			<td valign="middle" align="left"> 
				<input type="text" value="<?php echo $usuario->usuario?>" readonly="readonly" name="txtUsuario" id="txtUsuario" class="cajas" style="width:270px" /> 
			 </td>
			</tr>
		  <tr>
			<td class="key">Contraseña:</td>
			<td  valign="middle" align="left"><input type="password" name="txtPassword" id="txtPassword" class="cajas" style="width:270px" placeholder="Mínimo 6 caracteres" /></td>
		  </tr>
		  <tr>
			<td class="key">Confirmar contraseña:</td>
			<td valign="middle" align="left"><input type="password" name="txtRepetirPassword" id="txtRepetirPassword" class="cajas" style="width:270px" placeholder="Mínimo 6 caracteres" /></td>
			</tr>
		  <tr>

			<tr>
				<td class="key">Vendedor:</td>
				<td valign="middle" align="left"> <input type="text" name="txtVendedor" id="txtVendedor" class="cajas" style="width:270px" maxlength="30" value="<?php echo $usuario->vendedor?>" /> </td>
			</tr>

			<td class="key">Correo:</td>
			<td valign="middle" align="left">
				<input type="text" value="<?php echo $usuario->correo?>" name="txtCorreo" id="txtCorreo" class="cajas"  style="width:270px"/>
			 </td>
		  </tr>  
		  <tr>
			<td class="key">Rol de usuario:</td>
			<td>

				<?php
				if($usuario->superAdmin=="1")
				{
					echo 'Administrador (El usuario principal no puede cambiar de rol)<input type="hidden" id="selectRol" name="selectRol" value="'.$usuario->idRol.'" />';
				}

				if($usuario->idTienda>0)
				{
					echo 'Tienda <input type="hidden" id="selectRol" name="selectRol" value="2" />';
				}

				if($usuario->superAdmin!='1' and $usuario->idTienda==0)
				{
					?>
					<select id="selectRol" name="selectRol" class="cajas" style="width:270px" >
						<option value="0">Seleccione</option>
						<?php 
						foreach($roles as $row)
						{
							$seleccionado=$row->idRol==$usuario->idRol?'selected="selected"':'';

							echo '<option '.$seleccionado.' value="'.$row->idRol.'">'.$row->nombre.'</option>';
						}
						?>
					</select>    
					<?php
				}
				?>
				</td>
		  </tr>

		
			<tr>
				<td class="key">Firma:</td>
				<td valign="middle" align="left"><textarea name="txtFirma" id="txtFirma" class="TextArea" style="width:500px; height:100px" ><?php echo $usuario->firma?></textarea></td>
			</tr>

			<tr>
				<td class="key">Clave descuento:</td>
				<td><input type="password" name="txtClaveDescuento" id="txtClaveDescuento" class="cajas"  style="width:270px" maxlength="50" placeholder="Mínimo 6 caracteres" /></td>
			</tr>

			<tr>
				<td class="key">Clave usuario:</td>
				<td valign="middle" align="left"><input type="password" name="txtClaveCancelacion" id="txtClaveCancelacion" class="cajas"  style="width:270px" maxlength="50" placeholder="Mínimo 6 caracteres"/></td>
			</tr>  

			<tr>
				<td class="key">IPAD:</td>
				<td valign="middle" align="left"><input type="checkbox" <?=$usuario->ipad=='1'?'checked="checked"':''?>  id="chkIpd" name="chkIpd" value="1" /></td>
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
         <input type="hidden" name="txtNumeroLicencias" id="txtNumeroLicencias" value="<?php echo count($licencias)?>"/>
        <table class="admintable" width="100%" id="tablaSucursales">
            <tr>
                <th colspan="2">Sucursales</th>
                <!--<th><label><input type="checkbox" id="chkTodas" onchange="checarSucursales()" /> &nbsp;Todas</label> </th>-->
            </tr>
            <tr>
                <th width="60%">Nombre</th>
                <th>Acceso</th>
            </tr>
            
            <?php
            $i=1;
            foreach($licencias as $row)
            {
				$registro=$this->configuracion->comprobarSucursalesUsuario($usuario->idUsuario,$row->idLicencia);
				
                echo '
                <tr>
                    <td>'.$row->nombre.'</td>
                    <td align="center">
                        <input type="checkbox" id="chkLicencia'.$i.'" name="chkLicencia'.$row->idLicencia.'" '.($registro!=null?'checked="checked"':'').' value="1" />
                    </td>
                </tr>';
                
                $i++;
            }
            ?>
            
        </table>
    </div>
</form>


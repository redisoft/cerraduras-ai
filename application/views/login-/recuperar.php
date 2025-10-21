<?php
echo '<script type="text/javascript" src="'.base_url().'js/jquery-1.4.2.min.js"></script>';
echo '<script type="text/javascript" src="'.base_url().'js/jsUsuarios.js"></script>';
?>

<div class="headerRecuperar">
Solicitando nuevo password
</div>

<div id="div_login">
	<center>
	<table border="0" cellspacing="0" cellpadding="0" class="t_login">
		<tr>
		<td align="center">
           <img src="<?php echo base_url()?>img/login.png" align="Acceso al sistema" />
			<h2>Recuperar password</h2>
		</td>
		</tr>
		<tr align="center">
			<td>
            	<label for="username">Correo electronico:</label>
            </td>
		</tr>
		<tr>
			<td align="center">
            <input type="text" id="mail" name="mail" style="width:250px;"  />
			</td>
		</tr>
		<tr>
			<td align="center">
            <div id="confirmando"></div>
				<input type="reset" value="Aceptar" id="btReset" name="btReset" onclick="enviarCorreo()"/>
			</td>
		</tr>
     
	</table>
	</center>
</div>

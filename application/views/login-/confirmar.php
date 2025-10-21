<?php
echo '<script type="text/javascript" src="'.base_url().'js/jquery-1.4.2.min.js"></script>';
echo '<script type="text/javascript" src="'.base_url().'js/jsUsuarios.js"></script>';
?>
<div class="header">

</div>

		<div id="div_login">
	<center>
    <?php
	//print($cambiar->password);
	if($cambiar!=null)
	{
    ?>
        <table border="0" cellspacing="0" cellpadding="0" class="">
            <tr>
                <td colspan="2" align="center">
                <img src="<?php echo base_url()?>img/login.png" align="Acceso al sistema" />
                <h2>Recuperar password</h2>
                </td>
            </tr>
            <tr align="center">
                <td>
                <label for="username">Contraseña:</label>
                </td>
                <td>
                <input type="hidden" id="usuario" name="usuario" value="<?php echo $this->uri->segment(4)?>"  />
                <input type="password" id="password" name="password" />
                </td>
            </tr>
            <tr>
                <td align="center">
                <label for="username">Confirmar contraseña:</label>
                </td>
                <td> <input type="password" id="confirmarPassword" name="confirmarPassword" /></td>
            </tr>
           
            <tr>
                <td colspan="2" align="center">
                <div id="confirmando"></div>
                <input type="reset" value="Aceptar" id="btReset" name="btReset"/ onclick="recuperarPassword()">
            </td>
            </tr>
        </table>
        <?php
	}
	else
	{
		?>
       <table border="0" cellspacing="0" cellpadding="0" class="t_login">
        <tr>
        <td>
      	 El codigo de confirmacion no es valido o ha caducado
        </td>
        </tr>
        </table>
		<?php
	}
        ?>
	</center>
</div>

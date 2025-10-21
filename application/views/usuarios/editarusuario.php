<div class="derecha">

<div class="barra"><?php print(ucwords($Categoria)); ?>s</div>

<div class="submenu">

<table width="200%" border="0">
  <tr>
 <td width="3%" align="left" valign="bottom">
   <?php print(anchor('usuario','<img src="'.base_url().'img/arrow_left.png" width="20px" align="bottom" height="20px"/>',array('title'=>'Regresar')) );?> </td>
    <td width="97%" align="left" valign="middle">Editar usuario: <?php print($NombreUsuario); ?> </td>
  </tr>
</table>

</div>

<div class="listproyectos">

<?php
echo form_open(base_url().'usuario/guardarcambios',array('id' => 'formusuario'));
?>

<table class="admintable" width="99%;">
  <tr>
    <td class="key">Nombre:</td>
    <td  valign="middle" align="left"> 
        <input type="text" name="T1" id="T1" class="cajas" value="<?php print($Usuarios['name']); ?>"/> </td>
  </tr>
  <tr>
    <td class="key">Usuario:</td>
    <td  valign="middle" align="left"> 
        <input type="text" name="T2" id="T2" class="cajas" value="<?php print($Usuarios['username']); ?>" /> </td>
    </tr>
  <tr>
    <td class="key">Contraseña:</td>
    <td valign="middle" align="left"><input type="password" name="T3" id="T3" class="cajas" /></td>
  </tr>
  <tr>
    <td class="key" >Repetir contraseña:</td>
    <td valign="middle" align="left"><input type="password" name="T4" id="T4" class="cajas" /></td>
    </tr>
  <tr>
    <td class="key">Correo:</td>
    <td  valign="middle" align="left">
       <input type="text" name="T5" id="T5" class="cajas" value="<?php print($Usuarios['correo']); ?>" /></td>
  </tr>
  <tr>
    <td class="key">Tipo de usuario:</td>
    <td>

        <select id="T6" name="T6" class="cajas" >
        
            <?php

            switch ($Usuarios['role']){

                case 1:
                        print('
                               <option value="1" selected="selected">Administrador</option>
                               <option value="2">Usuario</option>
                               <option value="3">Vendedor</option>
                       ');
                        break;
                case 2:
                        print('
                               <option value="1">Administrador</option>
                               <option value="2" selected="selected">Usuario</option>
                               <option value="3">Vendedor</option>
                       ');
                        break;
                case 3:
                        print('
                               <option value="1">Administrador</option>
                               <option value="2">Usuario</option>
                               <option value="3" selected="selected">Vendedor</option>
                       ');
                        break;

            }//siwtch

           ?>

        </select>

    </td>
  </tr>

  <tr>
    <td colspan="2">
        <div style="width:70%;" align="center">
         <input type="hidden" name="T7" id="T7"  value="<?php print($idu); ?>"  />
         <input type="submit" name="Submit" id="gGuardar" class="btn" value="Guardar">
         <input type="reset" name="gLimpiar" id="gLimpiar" class="btn" value="Limpiar">
        </div>
     </td>
  </tr>
  
</table>

<?php print(form_close()); ?>


<!-- Termina lista-->
</div>
<!-- Termina derecha-->
</div>

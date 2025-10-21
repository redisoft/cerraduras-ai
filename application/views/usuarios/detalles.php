<div class="derecha">

<div class="barra"><?php print(ucwords($Categoria)); ?>s</div>

<div class="submenu">

<table width="200%" border="0">
  <tr>
 <td width="3%" align="left" valign="bottom">
   <?php print(anchor('usuario','<img src="'.base_url().'img/arrow_left.png" width="20px" align="bottom" height="20px"/>',array('title'=>'Regresar')) );?> </td>
    <td width="97%" align="left" valign="middle">Detalles del usuario: <?php print($NombreUsuario); ?></td>
  </tr>
</table>

</div>

<div class="listproyectos">

<table class="admintable" width="99%;" >
  <tr>
    <td class="key">Nombre:</td>
    <td>
        <input type="text" name="T1" id="T1"   disabled="disabled" class="cajas"  value="<?php print($Usuarios['name']); ?>"/>
    </td>
  </tr>
  <tr>
    <td>Usuario:</td>
    <td>
        <input type="text" name="T2" id="T2" disabled="disabled" class="cajas" value="<?php print($Usuarios['username']); ?>" />
    </td>
  </tr>

  <tr>
    <td>Correo:</td>
    <td>
       <input type="text" name="T3" id="T3"  disabled="disabled" class="cajas" value="<?php print($Usuarios['correo']); ?>" />
    </td>
  </tr>
  
  <tr>
    <td>Fecha creado: </td>
   <td>
       <input type="text" name="T4" id="T4" disabled="disabled" class="cajas" value="<?php print($Usuarios['createDate']); ?>" />
   </td>
  </tr>
  
  <tr>
    <td>Ultima modificación:</td>
    <td>
       <input type="text" name="T5" id="T5" disabled="disabled" class="cajas" value="<?php print($Usuarios['modify_by']); ?>" />
    </td>
  </tr>
  
  <tr>
    <td>Tipo de usuario:</td>
    <td>
        <?php

        $Cadena="";
        switch($Usuarios['role']){
            case 1: $Cadena="Administrador"; break;
            case 2: $Cadena="Usuario"; break;
            case 3: $Cadena="Vendedor"; break;
        }

        print('<input type="text" name="T6" id="T6" disabled="disabled" class="cajas" value="'.$Cadena.'" />');

        ?>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><?php print(anchor('usuario','Regresar<img src="'.base_url().'img/arrow_left.png" width="20px" align="bottom" height="20px"/>',array('title'=>'Regresar')) );?></td>	
  </tr>
  

</table>

<!-- Termina lista-->
</div>
<!-- Termina derecha-->
</div>

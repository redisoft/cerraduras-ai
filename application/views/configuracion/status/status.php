<script src="<?php echo base_url()?>js/configuracion/status/status.js"></script>
<script>
$(document).ready(function()
{
	base_url='<?php echo base_url()?>';
	obtenerStatus();
});
</script>

<script src="<?php echo base_url()?>js/bibliotecas/colorPicker.js"></script>

<div class="derecha">


<div class="listproyectos" >
 <table class="toolbar" width="10%">
    <tr>
      <td style="border:none" width="27%" align="center" valign="middle" class="button">
      	<a id="btnRegistrarStatus" onclick="formularioStatus()" title="Agregar CRM" style="cursor:pointer">
            <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar status" /> <br />
			Agregar
        </a>
        
       <?php
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnRegistrarStatus\');
			</script>';
		}
       ?>
       </td>
      
    </tr>
  </table>
	
    <div id="procesandoStatus"></div>
	<div id="obtenerStatus"></div>

<div id="ventanaEditarStatus" title="Editar CRM">
    <div id="editandoStatus"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerStatusEditar"></div>
</div>

<div id="ventanaStatus" title="CRM">
    <div id="registrandoStatus"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioStatus"></div>
</div>

</div>
</div>





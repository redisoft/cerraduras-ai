<script src="<?php echo base_url()?>js/configuracion/estatus/estatus.js"></script>
<script>
$(document).ready(function()
{
	base_url='<?php echo base_url()?>';
	obtenerEstatus();
});
</script>

<script src="<?php echo base_url()?>js/bibliotecas/colorPicker.js"></script>

<div class="derecha">


<div class="listproyectos" >
 <table class="toolbar" width="10%">
    <tr>
      <td style="border:none" width="27%" align="center" valign="middle" class="button">
      	<a id="btnRegistrarEstatus" onclick="formularioEstatus()" title="Agregar estatus" style="cursor:pointer">
            <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar estatus" /> <br />
			Agregar
        </a>
        
       <?php
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnRegistrarEstatus\');
			</script>';
		}
       ?>
       </td>
      
    </tr>
  </table>
	
    <div id="procesandoEstatus"></div>
	<div id="obtenerEstatus"></div>

<div id="ventanaEditarEstatus" title="Editar estatus">
    <div id="editandoEstatus"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerEstatusEditar"></div>
</div>

<div id="ventanaEstatus" title="Estatus">
    <div id="registrandoEstatus"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioEstatus"></div>
</div>

</div>
</div>





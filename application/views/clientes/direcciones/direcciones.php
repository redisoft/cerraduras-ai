<script src="<?php echo base_url()?>js/clientes/direcciones/direcciones.js?v=<?php echo(rand());?>"></script>

<input type="hidden" id="txtIClienteDirecciones" value="<?=$idCliente?>"  />
<div class="derechaa">

<div class="listproyectos" >
 <table class="toolbar" width="10%">
    <tr>
      <td style="border:none" width="27%" align="center" valign="middle" class="button">
      	<a id="btnRegistrarDirecciones" onclick="formularioDirecciones()" title="Agregar direcciones" style="cursor:pointer">
            <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Agregar direcciones" /> <br />
			Agregar
        </a>
        
       <?php
		/*if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnRegistrarDirecciones\');
			</script>';
		}*/
       ?>
       </td>
      
    </tr>
  </table>
	
    <div id="procesandoDirecciones"></div>
	<div id="obtenerDirecciones"></div>

<div id="ventanaEditarDirecciones" title="Editar direcciones">
    <div id="editandoDirecciones"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerDireccionesEditar"></div>
</div>

<div id="ventanaDirecciones" title="Direcciones">
    <div id="registrandoDirecciones"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioDirecciones"></div>
</div>

</div>
</div>





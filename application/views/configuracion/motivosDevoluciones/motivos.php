<script src="<?php echo base_url()?>js/configuracion/motivos/motivos.js"></script>

<script>
$(document).ready(function()
{
	obtenerMotivos();
});
</script>

<div class="derecha" style="min-height:300px !important">
<div class="submenu" style="height:13px !important">
<div class="toolbar" id="toolbar">
  	<div class="seccionDiv">
   		Motivos de devolución
    </div>

  </div>
</div>

<div class="listproyectos" >
<table class="toolbar" width="10%">
    <tr>
        <td style="border:none" width="27%" align="center" valign="middle" class="button">
        <?php
        
		echo '
		<a id="btnMotivosDevoluciones" onclick="formularioMotivos()"  title="Motivos" style="cursor:pointer">
			<img src="'.base_url().'img/add.png" title="Registrar motivo" /> 
			<br />
			Agregar
		</a>';
		
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnMotivosDevoluciones\');
			</script>';
		}
        ?>
        </td>
    </tr>
</table>

<div id="procesandoMotivos"></div>
<div id="obtenerMotivos"></div>

<div id="ventanaMotivos" title="Motivos de devolución">
    <div id="registrandoMotivo"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioMotivos"></div>	
</div>

<div id="ventanaEditarMotivo" title="Editar motivo de devolución">
<div id="editandoMotivo"></div>
<div class="ui-state-error" ></div>
<div id="obtenerMotivo"></div>
</div>




</div>
</div>

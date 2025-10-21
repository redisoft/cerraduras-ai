<script src="<?php echo base_url()?>js/configuracion/zonas.js"></script>
<script>
$(document).ready(function()
{
	obtenerZonasCatalogo();
});
</script>

<div class="listproyectos" >
<table style=" margin-left:1100px;" class="toolbar" width="10%">
    <tr>
        <td style="border:none" width="27%" align="center" valign="middle" class="button">
        <?php
        if($permiso->escribir=='1')
        {
			?>
			<span class="icon-option" onclick="formularioZonas()"  title="bancos" style="cursor:pointer">
			<img src="<?php print(base_url()); ?>img/add.png" alt="a" border="0" title="Añadir <?php echo $this->session->userdata('identificador')?>" /> 
			</span>Agregar
			<?php
        }
        ?>
        </td>
    </tr>
</table>

<div id="procesandoInformacion"></div>
<div id="obtenerZonasCatalogo"></div>

<div id="ventanaZonas" title="<?php echo $this->session->userdata('identificador')?>:">
    <div id="registrandoZona"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioZonas"></div>	
</div>

<div id="ventanaEditarZona" title="Editar <?php echo $this->session->userdata('identificador')?>:">
<div id="editandoZona"></div>
<div class="ui-state-error" ></div>
<div id="obtenerZona"></div>
</div>




</div>
</div>

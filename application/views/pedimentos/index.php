<script src="<?php echo base_url()?>js/pedimentos/pedimentos.js?v=<?=rand()?>"></script>

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<a onclick="formularioRegistroPedimentos()" id="btnRegistro" >
                <span class="icon-option"  title="Registrar" style="cursor:pointer">
                <img src="<?php print(base_url()); ?>img/add.png" alt="Registrar" border="0" title="Registrar" /> 
                </span>Registrar
            </a>

            <?php
            /*if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistro\');
				</script>';
			}*/
            ?>
            
        </td>
        <td align="center">
        	<input type="text"  name="txtBuscarRegistroPedimentos" id="txtBuscarRegistroPedimentos" class="busquedas" placeholder="Buscar por pedimento"  style="width:500px;"/>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="procesandoInformacionPedimentos"></div>
    <div id="obtenerRegistrosPedimentos"></div>
</div>

    
<div id="ventanaRegistroPedimentos" title="Registro">
    <div id="registrandoPedimentos"></div>
    <div id="formularioRegistroPedimentos"></div>
</div>

</div>

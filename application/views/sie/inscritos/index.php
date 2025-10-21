<script src="<?php echo base_url()?>js/sie/prospectos/prospectos.js"></script>
<div class="submenu">
     <table class="toolbar" width="100%">
     	<tr>
       	 	<td class="seccion" colspan="2">
            
            </td>
        </tr>
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
                echo'<img src="'.base_url().'img/add.png" style="cursor:pointer" onclick="formularioProspectosSie()" /> <br />
				Agregar';
                ?>
            </td>
            <td>
            	De
            	<input type="text"  name="txtInicioSieBusqueda" 	id="txtInicioSieBusqueda" 	class="cajas" style="width:100px; " value="<?=date('Y-m-d')?>" onchange="obtenerProspectosSie()" readonly="readonly"/>
                a
                <input type="text"  name="txtFinSieBusqueda" 		id="txtFinSieBusqueda" 		class="cajas" style="width:100px; " value="<?=date('Y-m-d')?>" onchange="obtenerProspectosSie()" readonly="readonly"/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="procesandoProspectosSie"></div>
<div id="obtenerProspectosSie"></div>


<div id="ventanaRegistroProspectosSie" title="Registrar">
    <div id="formularioProspectosSie"></div>
</div>

<div id="ventanaEditarProspectosSie" title="Editar">
    <div id="formularioEditarProspectosSie"></div>
</div>


</div>

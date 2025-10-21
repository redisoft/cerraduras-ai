<script src="<?php echo base_url()?>js/productos/listas/listas.js?v=<?=rand()?>"></script>

<div id="ventanaListas" title="Descuentos">
    <div class="ui-state-error" ></div>
    <div id="procesandoListas"></div>
    <table class="admintable" width="100%">
        <tr>
            <td width="14%" align="center" style="border:none">
            <?php
            echo '<img onclick="formularioListas()" src="'.base_url().'img/descuento.png" width="30px;" height="30px;" id="subirFichero" style="cursor:pointer;" title="Traspasos">
            <br />
            <a>Registrar descuentos</a>';
            ?>
            </td>
            <td align="center" style="border:none">

                
                <input type="text" class="cajas"  id="txtInicioBusqueda"		 	onchange="obtenerListas()" style="width:90px" value="<?php echo date('Y-m-01')?>" />
                <input type="text" class="cajas"  id="txtFinalBusqueda" 			onchange="obtenerListas()" style="width:90px" value="<?php echo date('Y-m-d')?>" />
                
                <input type="text" class="cajas"  id="txtBuscarLista" 		style="width:350px" 		placeholder="Buscar por nombre"  />

                </td>
        </tr>
    </table>
    <div id="obtenerListas"></div>
</div>



<div id="ventanaFormularioListas" title="Registrar descuentos">
   <div id="formularioListas"></div>
</div>

<div id="ventanaEditarLista" title="Editar lista">
	<div id="editandoLista"></div>
   <div id="obtenerLista"></div>
</div>

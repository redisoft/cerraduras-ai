<script src="<?php echo base_url()?>js/traspasos/traspasos.js?v=<?php echo(rand());?>"></script>
<script src="<?php echo base_url()?>js/traspasos/recepciones.js?v=<?php echo(rand());?>"></script>

<div id="ventanaTraspasos" title="Traspasos de productos entre tiendas">
    <div class="ui-state-error" ></div>
    <div id="procesandoTraspasos"></div>
    <table class="admintable" width="100%">
        <tr>
            <td width="14%" align="center" style="border:none">
            <?php
            echo '<img onclick="formularioTraspasos()" src="'.base_url().'img/truck.png" width="30px;" height="30px;" id="subirFichero" style="cursor:pointer;" title="Traspasos">
            <br />
            <a>Registrar traspaso</a>';
            ?>
            </td>
            <td align="center" style="border:none">

                
                <input type="text" class="cajas"  id="txtFechaInicial"		 	onchange="obtenerTraspasos()" style="width:90px" value="<?php echo date('Y-m-01')?>" />
                <input type="text" class="cajas"  id="txtFechaFinal" 			onchange="obtenerTraspasos()" style="width:90px" value="<?php echo date('Y-m-d')?>" />
                
                <input type="text" class="cajas"  id="txtBuscarTraspasos" 		style="width:350px" 		placeholder="Buscar por folio"  />

                </td>
        </tr>
    </table>
    <div id="obtenerTraspasos"></div>
</div>

<div id="ventanaFormularioTraspasos" title="Registrar traspaso">
   <div id="formularioTraspasos"></div>
</div>

<div id="ventanaRecepciones" title="Recibir traspaso">
	<div id="registrandoRecepcion"></div>
   <div id="formularioRecepciones"></div>
</div>
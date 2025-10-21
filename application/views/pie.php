<input type="hidden"  name="txtIdUsuarioSistema" id="txtIdUsuarioSistema" value="<?=$this->session->userdata('id')?>"/>
<div id="ventanaPagos" title="Pagos a proveedores">
        <div id="errorPagosProveedores" class="ui-state-error" ></div>
        <div id="cargandoPagos"></div>
        <div id="cargarPagos"></div>
    </div>
    
    <div id="ventanaPagosClientes" title="Cobros a clientes">
        <div id="cargandoPagosClientes"></div>
        <div id="cargarPagosClientes"></div>
    </div>
    
    
    <div id="ventanaFichaProveedor" title="Ficha técnica del proveedor">
        <div class="ui-state-error" ></div>
        <div id="cargarFichaProveedor"></div>
    </div>
    
    <div id="ventanaEnviarFichaProveedor" title="Enviar ficha técnica del proveedor">
    	<div id="enviandoFichaProveedor"></div>
        <div class="ui-state-error" ></div>
        <div id="formularioCorreoFicha"></div>
    </div>
    
    <div id="ventanaComprobantes" title="Comprobantes">
        <div id="registrandoComprobante"></div>
        <div id="obtenerComprobantes"></div>
    </div>

	<div id="ventanaSincronizacion" title="Sincronización">
		<input type="hidden" id="txtTipoSincronizacion" value="0" />
        <div id="procesandoSincronizacion"></div>
        <div id="formularioSincronizacion" class="textoSincronizacion">
			
		</div>
    </div>
    
    <!--VENTANAS DE CONFIRMACIÓN-->
    <div id="ventanaConfirmacion" title="Confirmar acción">
    <div id="confirmando"></div>
    <table class="admintable" width="100%">
        <tr>
            <td class="key">Código de confirmación:</td>
            <td>
                <input type="password" class="cajas" id="txtCodigoConfirmacion" style="width:200px"  />
            </td>
        </tr>
		
		<tr id="filaAccion" style="display: none">
            <td class="key" id="lblAccionGlobal">Etiqueta:</td>
            <td>
                <input type="checkbox" id="chkAplicarAccion" />
            </td>
        </tr>
		
    </table>
    </div>

	<script  src="<?=base_url()?>js/adeudos.js?v=<?=ASSET_VERSION?>"></script>

	<div id="ventanaAdeudoPendiente" title="Adeudo pendiente">
		<div style="font-size: 20px; text-align: center">
			<br /><br />
			Presenta un adeudo pendiente. Favor de enviar comprobante al WhatsApp 22 22 60 61 97. 
			<br />
			Gracias.
		</div>
	</div>

    <!-- Finaliza cuerpo-->
    </div>
    <div style="width: 100%; clear: both"></div>
    <!-- ** Finaliza main ** -->
    </div>
    
    <div class="footer">
    	Redisoftsystems 
        <?php
        /*if($permisos['leer'][9]==1 or $permisos['escribir'][9]==1)
        {
            ?>
            Config. 
            <a href="<?php echo base_url()?>configuracion">
            <img src="<?php echo base_url()?>img/configure.png" style="width:28px; height:28px;" /></a>
            <?php
        }*/
        ?>
    </div>
    
    </div>
    

<script>
$(document).ready(function()
{
	$('textarea').attr({
		'autocomplete': 'off',
		'autocorrect': 'off',
		'autocapitalize': 'off'
	}).prop('spellcheck', false);

	$('.busquedas').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			event.preventDefault();
			return;
		}
	});	
});

</script>

</body>

</html>

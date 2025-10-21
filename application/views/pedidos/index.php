<script src="<?php echo base_url()?>js/pedidos/pedidos.js"></script>
<script src="<?php echo base_url()?>js/pedidos/producido.js"></script>
<script src="<?php echo base_url()?>js/pedidos/reporte.js"></script>

<script>
$(document).ready(function()
{
	//obtenerPedidos();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%" >
    <tr>
    	<?php
		
		#echo date('l', strtotime(date('Y-m-d')));
		
		echo '
		<td align="center" valign="middle" width="25%">
			<a onclick="formularioPedidos()" id="btnControl">
				<img src="'.base_url().'img/materiales.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Registrar pedido">
				<br />
				Nuevo registro
			</a>
			
			<a onclick="obtenerReportePanaderos()">
				<img src="'.base_url().'img/reportes.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Reporte panaderos">
				<br />
				Reporte panaderos
			</a>
		</td>';
	
	
		/*if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnControl\');
			</script>';
		}*/
        ?>
			<td width="80%" align="left" valign="middle" >
                <input type="text"  	name="txtBuscarPedido" id="txtBuscarPedido" class="busquedas" placeholder="Buscar por folio, usuario, tienda" style="width:500px; "/>
                
                Filtro de 
                <input type="text"  	name="txtInicioPedido" id="txtInicioPedido" class="busquedas" value="<?php echo $fechas[0]?>" style="width:90px; " onchange="obtenerPedidos()"/>
                a 
                <input type="text"  	name="txtFinPedido" 	id="txtFinPedido" 	class="busquedas" 	value="<?php echo $fechas[1]?>" style="width:90px;" onchange="obtenerPedidos()"/>
                
                <input type="hidden"  	name="txtOrdenPedidos" 	id="txtOrdenPedidos"  value="desc"/>
                <input type="hidden"  	name="txtOrdenReporte" 	id="txtOrdenReporte"  value="desc"/>
        	</td>
        
		</tr>
 	</table>
 </div>
</div>
<div class="listproyectos">
	<div id="procesandoPedidos"></div>

	<div id="obtenerPedidos"></div>

    <div id="ventanaPedidos" title="Registrar orden de producción">
        <div id="registrandoPedido"></div>
        <div id="formularioPedidos"></div>
    </div>
    
    <div id="ventanaEditarPedido" title="Editar orden de producción">
        <div id="editandoPedido"></div>
        <div id="obtenerPedido"> </div>
    </div>
    
    <div id="ventanaProducidoPedido" title="Producido">
        <div id="registrandoProducido"></div>
        <div id="obtenerProducidoPedido"> </div>
    </div>
    
    <div id="ventanaProducidosProducto" title="Producido producto">
        <div id="procesandoProducido"></div>
        <div id="obtenerProducidosProducto"> </div>
    </div>
    
    <div id="ventanaFormularioReporte" title="Reporte">
        <div id="registrandoReporte"></div>
        <div id="formularioReporte"></div>
    </div>
    
    <div id="ventanaReportePanaderos" title="Reporte">
        <div id="generandoReporte"></div>
        
        <table class="admintable" width="100%">
        	<tr>
            	<td align="center">
                	 <input type="text" style="width:0px; height:0px" />
                    Filtro de 
                    <input type="text"  	name="txtInicioPanaderos" id="txtInicioPanaderos" 	class="busquedas" value="<?php echo $fechas[0]?>" style="width:90px; " onchange="obtenerReportePanaderos()"/>
                    a 
                    <input type="text"  	name="txtFinPanaderos" 	id="txtFinPanaderos" 		class="busquedas" 	value="<?php echo $fechas[1]?>" style="width:90px;" onchange="obtenerReportePanaderos()"/>
                    
                   
                </td>
            </tr>
        </table>
        <div id="obtenerReportePanaderos">
        	<input type="hidden" id="selectLineasPanaderos" value="0" />
        </div>
    </div>
    

</div>
</div>


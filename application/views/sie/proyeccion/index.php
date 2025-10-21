<script src="<?php echo base_url()?>js/sie/proyeccion/ingresos.js"></script>
<script src="<?php echo base_url()?>js/sie/proyeccion/egresos.js"></script>

<script>
$(document).ready(function()
{
	$('#txtInicioIngreso,#txtFinIngreso,#txtInicioEgreso,#txtFinEgreso').datepicker();
	obtenerIngresos(); 
	obtenerEgresos(); 
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
<!--<div class="seccionDiv">
	Contabilidad
</div>-->
 <table class="toolbar" width="100%" >
    <tr>
    
     <?php
	 	echo'
		<td align="center" valign="middle" style="border:none" width="5%">
			<a id="btnOtrosIngresos" onclick="formularioIngresos()" >
				<img src="'.base_url().'img/ingresos.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Ingresos" />
				<br />
				Ingresos  
			</a>
		</td>';
		
		echo'
		<td align="center" valign="middle" style="border:none" width="5%">
			<a id="btnOtrosEgresos" onclick="formularioEgresos()">
				<img src="'.base_url().'img/egresos.png" width="30px;" height="30px;" style="cursor:pointer;" title="Egresos" />
				<br />
				Egresos  
			</a>
		</td>';
		
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnOtrosIngresos\');
				desactivarBotonSistema(\'btnOtrosEgresos\');
			</script>';
		}
		?>
          <td width="52%"></td>
	</tr>
</table>

 <table class="toolbar" width="100%" >
    <tr>
    	<td width="50%">
        	De <input type="text" class="cajas" id="txtInicioIngreso" value="<?=date('Y-m-01')?>" style="width:90px" onchange="obtenerIngresos()"/>
            a <input type="text" class="cajas" id="txtFinIngreso" value="<?=date('Y-m-d')?>" style="width:90px" onchange="obtenerIngresos()"/>
            
            <input type="text" class="cajas" id="txtBuscarIngreso" style="width:300px" placeholder="Buscar concepto" />
        </td>
        <td width="50%">
        	
            <select class="cajas" id="selectTipoFecha" style="width:120px; display:none" onchange="obtenerEgresos()">
            	<option value="0">Fecha registro</option>
                <option value="1" selected="selected">Fecha de pago</option>
            </select>
            
        	De <input type="text" class="cajas" id="txtInicioEgreso" value="<?=date('Y-m-01')?>" style="width:90px" onchange="obtenerEgresos()"/>
            a <input type="text" class="cajas" id="txtFinEgreso" value="<?=date('Y-m-d')?>" style="width:90px" onchange="obtenerEgresos()"/>
            
            <input type="text" class="cajas" id="txtBuscarEgreso" style="width:300px" placeholder="Buscar concepto"  />
            
            <input type="hidden" class="cajas" id="txtPagado" 				value="0" />
            <input type="hidden" class="cajas" id="txtEscenario" 			value="0" />
            
            <input type="hidden" class="cajas" id="txtCobrado" 				value="0" />
            <input type="hidden" class="cajas" id="txtEscenarioIngreso" 	value="0" />
        </td>
    </tr>
</table>
 </div>
</div>
<div class="listproyectos">

<div id="procesandoInformacion" style="width:100%; margin-top:30px"></div>

<div id="obtenerIngresos" style="float: left; width:49%"></div>
<div id="obtenerEgresos" style="float: right; width:49%"></div>

<!-- INGRESOS-->
<div id="ventanaIngresos" title="Ingresos">
    <div id="formularioIngresos"></div>
</div>

<div id="ventanaEditarIngresos" title="Ingresos">
    <div id="obtenerIngreso"></div>
</div>

<div id="ventanaIngresoCobrado" title="Cobrado">
	<div id="editandoIngresoCobrado"></div>
    <div id="obtenerIngresoCobrado"></div>
</div>

<!-- EGRESOS-->
<div id="ventanaEgresos" title="Egresos">
    <div id="formularioEgresos"></div>
</div>

<div id="ventanaEditarEgresos" title="Egresos">
    <div id="obtenerEgreso"></div>
</div>

<div id="ventanaEgresoPagado" title="Pagado">
	<div id="editandoEgresoPagado"></div>
    <div id="obtenerEgresoPagado"></div>
</div>



</div>
</div>


<script src="<?php echo base_url()?>js/materiales/materiales.js"></script>
<script src="<?php echo base_url()?>js/materiales/importar.js"></script>
<script src="<?php echo base_url()?>js/productos/impuestos.js"></script>

<!-- CONTABILIDAD -->
<script src="<?php echo base_url()?>js/contabilidad/asociarCuentas.js"></script>

<script>
$(document).ready(function()
{
	/*$("#txtBusquedaMaterial").autocomplete(
	{
		source:base_url+'configuracion/obtenerMateriales',
		
		select:function( event, ui)
		{
			window.location.href=base_url+"materiales/index/asc/0/"+ui.item.idMaterial;
		}
	});*/
	
	obtenerMateriales();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%" >
 	<!--<tr>
    	<td class="seccion" colspan="4">
    		Materia prima
   	    </td>
    </tr>-->
    <tr>
    	<?php
		
		echo '
		<td align="center" valign="middle" width="8%">
			<a onclick="formularioMateriaPrima()" id="btnMateriales">
				<img src="'.base_url().'img/materiales.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Registrar '.(sistemaActivo=='IEXE'?'Insumos':'materia prima').' ">
				<br />
				Registrar '.(sistemaActivo=='IEXE'?'Insumos':'materia prima').'    
			</a>
		</td>';
		
		echo '
		<td class="button" width="5%">
			<a class="toolbar" onclick="accesoImportarMateriales()" id="btnImportar">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Importar" alt="Importar" /><br />
				Importar  
			</a>      
		</td>
		
		<td class="button" width="5%">
			<a class="toolbar" onclick="accesoExportarMateriales()" id="btnExportar">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar" alt="Exportar" /><br />
				Exportar  
			</a>      
		</td>
		
		<td class="button" width="5%">
			<a class="toolbar" onclick="obtenerRequisiciones()" id="btnRequisiciones">
				<img src="'.base_url().'img/requisicion.png" width="30px;" height="30px;" title="Requisiciones" /><br />
				Requisiciones  
			</a>      
		</td>';
		
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnMateriales\');
				desactivarBotonSistema(\'btnImportar\');
				desactivarBotonSistema(\'btnExportar\');
			</script>';
		}
        ?>
			<td width="77%" align="left" valign="middle" style="padding-right:80px" >
                <input type="text"  	name="txtBusquedaMaterial" id="txtBusquedaMaterial" class="busquedas" placeholder="Buscar materia prima" style="width:500px;"/>
                <input type="hidden"  	name="txtOrdenMaterial" id="txtOrdenMaterial" value="asc"/>
                <input type="hidden"  name="txtGrupoActivo" id="txtGrupoActivo" value="Activo" /> 
        	</td>
        
		</tr>
 	</table>
 </div>
</div>
<div class="listproyectos">
	<div id="exportandoDatos"></div>

	<div id="obtenerMateriales"></div>

<div id="ventanaMateriales" title="<?php echo sistemaActivo=='IEXE'?'Insumos':'Materia prima' ?>">
<div id="registrandoMateriaPrima"></div>
<div id="formularioMateriaPrima"></div>
</div>

<div id="ventanaMerma" title="Salidas de <?php echo sistemaActivo=='IEXE'?'Insumos':'Materia prima' ?>">
<div id="agregandoMermas"></div>
<div id="cargarMermas"> </div>
</div>

<div id="ventanEditarMaterial" title="Editar <?php echo sistemaActivo=='IEXE'?'Insumos':'Materia prima' ?>">
<div id="editandoMateriaPrima"></div>
<div id="editaMaterial"> </div>
</div>

<div id="ventanaAgregarProveedor" title="Agregar proveedor a <?php echo sistemaActivo=='IEXE'?'Insumos':'Materia prima' ?>">
<div id="asociandoProveedor"></div>
<div id="obtenerTodosProveedores"> </div>
</div>

<div id="ventanaImportarMateriales" title="Importar <?php echo sistemaActivo=='IEXE'?'Insumos':'Materia prima' ?>">
    <div id="importandoMateriales"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioImportarMateriales"></div>
</div>

<div id="ventanaFormularioAsociarCuenta" title="Cuentas contables">
    <div id="asociandoCuentas"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioAsociarCuenta"></div>
</div>

<?php $this->load->view('requisiciones/materiales/index')?>

</div>
</div>


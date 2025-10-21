<script src="<?php echo base_url()?>js/contabilidad/catalogo.js"></script>
<script src="<?php echo base_url()?>js/contabilidad/cuentas.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/drag/context-menu.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/drag/drag-drop-folder-tree.js"> </script>
<link rel="stylesheet" href="<?php echo base_url()?>css/drag/drag-drop-folder-tree.css" type="text/css"></link>
<link rel="stylesheet" href="<?php echo base_url()?>css/drag/context-menu.css" type="text/css"></link> 


<script>
$(document).ready(function()
{
	obtenerCuentasCatalogo();
	
	$("#txtFechaInicial,#txtFechaFinal,#txtFechaExportarCatalogo").monthpicker();
	
	$('.menuCatalogo').removeClass('activado');
	$('#catalogo'+$('#txtNumeroCuentaActiva').val()).addClass('activado');
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >

<?php
echo'

<input type="hidden" id="txtTipoCuentaActiva" class="txtTipoCuentaActiva" value="todos" />
<input type="hidden" id="txtNumeroCuentaActiva" class="txtNumeroCuentaActiva" value="0" />

	<table class="toolbar">
		<td width="10%" align="center" style="display:none">
			<img src="'.base_url().'img/excel.png" class="botonesMenu" title="Importar excel" style="margin-right:9px" id="subirExcel"  />
			<br />
			<label>Importar</label>
		</td>
		
		<td width="10%" align="center" style="display:none">
			<img src="'.base_url().'img/niveles.png" class="botonesMenu" onclick="obtenerArbol()" title="Niveles árbol" />
			<br />
			<label>Niveles</label>
		</td>
		
		<td width="10%" align="center">
			<a id="btnExportarXml" >
				<img src="'.base_url().'img/xml.png" class="botonesMenu"  title="Exportar a xml" width="30"  />
				<br />
				<label>Exportar XML</label>
			</a>
		</td>
		
		<td width="10%" align="center">
			<a onclick="formularioAgregarCuenta(0)" title="Registrar" >
			<img src="'.base_url().'img/agregar.png" class="botonesMenu"  width="30" />
			<br />
			Cuenta de mayor
			</a>
		</td>
		
		<td align="center">
			<label>Buscar por fechas</label>
			<input type="text" id="txtFechaInicial" class="cajasMes" value="'.date('Y-01').'" onchange="obtenerCuentasCatalogo()" />
			
			<input type="text" id="txtFechaFinal" class="cajasMes" value="'.date('Y-m').'" onchange="obtenerCuentasCatalogo()" />
			
			<input type="text" class="cajas" id="txtCriterio" name="txtCriterio" placeholder="Por descripción, referencia contable" style="width:400px" />
			
		</td>
		
		
	</table>
	</div>
</div>
	<br /><br />
<div class="menuCatalogo activado" onclick="definirTipoCuentaCatalogo(\'todos\',0)" id="catalogo0">
	Todos<br />
	(101-899)
</div>';
	
	$i=1;
	foreach($tiposCuenta as $row)
	{
		echo '
		<div class="menuCatalogo" onclick="definirTipoCuentaCatalogo(\''.$row->cuenta.'\','.$i.')" id="catalogo'.$i.'">
			'.$row->cuenta.'<br />
			('.$row->minimo.'-'.$row->maximo.')
		</div>';
		
		$i++;
	}

	echo'';

echo '<div id="obteniendoReporte"></div>';
$this->load->view('contabilidad/menu');

echo '
<div class="contenidoMenuDatos" id="obtenerCuentasCatalogo" style="margin-top:70px">
	
</div>

<div id="ventanaExportarCatalogoXml" title="Exportar catálogo a xml">
	<div id="exportandoCatalogoXml"></div>
	<table class="admintable" width="100%">
		<tr>
			<td style="width:40% !important" class="key">Mes: </td>
			<td>
				<input type="text" id="txtFechaExportarCatalogo" class="cajasMes" value="'.date('Y-01').'" />
			</td>
		</tr>
	</table>
</div>

<div title="Registrar catálogo" id="ventanaFormularioCatalogo">
	<div id="formularioCatalogo"></div>
</div>

<div title="Editar catálogo" id="ventanaEditarCatalogo">
	<div id="obtenerCatalogoEditar"></div>
</div>

<div title="Agregar cuentas al catálogo" id="ventanaCuentasCatalogo">
	<div id="cuentasCatalogo"></div>
</div>

<div title="Agregar cuenta" id="ventanaAgregarCuenta">
	<div id="formularioAgregarCuenta"></div>
</div>

<div title="Editar cuenta" id="ventanaEditarCuenta">
	<div id="obtenerCuenta"></div>
</div>

<div id="ventanaNiveles" title="Cuentas" >
	
	<table class="tablaFormularios">
		<td class="etiquetas">Tipo de cuenta:</td>
		<td>
			<select class="selectTextosGrandes" id="selectTipoCuentasTabla" name="selectTipoCuentasTabla" onchange="obtenerNiveles()">
				<option value="0">Seleccione</option>';
				
				/*$i=0;
				foreach($tiposCuenta as $row)
				{
					echo '<option '.($i==0?'selected="selected"':'').'>'.$row->detalle.'</option>';
					
					$i++;
				}*/
			
			echo'
			</select>
		</td>
	</table>
	
	<div id="obtenerNiveles"></div>
</div>

<div id="ventanaNivel2" title="Cuentas nivel 2" >
	<div id="obtenerNivel2"></div>
</div>

<div id="ventanaNivel3" title="Cuentas nivel 3" >
	<div id="obtenerNivel3"></div>
</div>



<div id="ventanaEditarNivel3" title="Editar nivel 3" >
	<div id="obtenerCuentaNivel3"></div>
</div>

<div id="ventanaNivel4" title="Cuentas nivel 4" >
	<div id="obtenerNivel4"></div>
</div>

<div id="ventanaEditarNivel4" title="Editar nivel 4" >
	<div id="obtenerCuentaNivel4"></div>
</div>

<div id="ventanaNivel5" title="Cuentas nivel 5" >
	<div id="obtenerNivel5"></div>
</div>

<div id="ventanaEditarNivel5" title="Editar nivel 5" >
	<div id="obtenerCuentaNivel5"></div>
</div>

<div id="ventanaNivel6" title="Cuentas nivel 6" >
	<div id="obtenerNivel6"></div>
</div>

<div id="ventanaEditarNivel6" title="Editar nivel 6" >
	<div id="obtenerCuentaNivel6"></div>
</div>


<div id="ventanaArbol" title="Cuentas" >
	<table class="admintable" width="100%">
		<td class="key">Tipo de cuenta:</td>
		<td>
			<select class="cajas" id="selectTipoCuentasArbol" name="selectTipoCuentasArbol" onchange="obtenerArbol()">
				<option value="0">Seleccione</option>';
				
				$i=0;
				foreach($tiposCuenta as $row)
				{
					echo '<option '.($i==0?'selected="selected"':'').'>'.$row->detalle.'</option>';
					
					$i++;
				}
			
			echo'
			</select>
		</td>
	</table>
	<div id="obtenerArbol"></div>
</div>


<div id="ventanaFormularioNivel3" title="Registrar nivel 3" >
	<div id="formularioNivel3"></div>
</div>

<div id="ventanaFormularioNivel4" title="Registrar nivel 4" >
	<div id="formularioNivel4"></div>
</div>

<div id="ventanaFormularioNivel5" title="Registrar nivel 5" >
	<div id="formularioNivel5"></div>
</div>

<div id="ventanaFormularioNivel6" title="Registrar nivel 6" >
	<div id="formularioNivel6"></div>
</div>

<div id="ventanaSaldoInicial" title="Saldo inicial" >
	<div id="formularioSaldoInicial"></div>
</div>';
?>
</div>


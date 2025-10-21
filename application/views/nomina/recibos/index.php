<script type="text/javascript" src="<?php echo base_url()?>js/nomina/nomina.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/facturacion/folios.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	$('#txtBuscarRecibo').val('');

	obtenerRecibos();
	
	$("#txtMes").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Recibos de nómina
</div>
--> <table class="toolbar" width="100%">
    <tr>
    	<td style="border:none" width="20%" align="center" valign="middle" class="button">
			<?php
				echo'
				<a id="btnRegistrarRecibo" onclick="formularioNomina()">
					<img src="'.base_url().'img/add.png" title="Registrar recibo"  /> <br />
					Registrar
				</a>';
			
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarRecibo\');
				</script>';
			}
        	?>
    	</td>
    	<td>
        <input title="Seleccione mes fiscal" type="text" class="busquedas" placeholder="Mes fiscal" style="width:150px; cursor:pointer" id="txtMes" onchange="obtenerRecibos()" value="<?php echo date('Y-m')?>" />
        <input type="text"  name="txtBuscarRecibo" id="txtBuscarRecibo" class="busquedas" placeholder="Buscar recibo de nómina"  style="width:500px;" onkeyup="obtenerRecibos()"/>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>
	<div id="obtenerRecibos"></div>
</div>

<div id="ventanaFormularioNomina" title="Recibos de nómina">
<div id="registrandoRecibo"></div>
<div id="formularioNomina"></div>
</div>

<div id="ventanaEmpleados" title="Lista de empleados">
<div id="listaEmpleados"></div>
</div>

<div id="ventanaPercepciones" title="Lista de percepciones">
<div id="listaPercepciones"></div>
</div>

<div id="ventanaDeducciones" title="Lista de deducciones">
<div id="listaDeducciones"></div>
</div>

</div>

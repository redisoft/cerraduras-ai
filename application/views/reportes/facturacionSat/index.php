<script src="<?php echo base_url()?>js/reportes/sat/facturacion.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	obtenerFacturas();
	
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
	Reporte de facturación Sat
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	
        	<input title="Seleccione mes fiscal" type="text" class="busquedas" placeholder="Mes fiscal" style="width:100px; cursor:pointer" id="txtMes" onchange="obtenerFacturas()" />
            <input type="text"  name="txtCriterio" id="txtCriterio" class="busquedas" placeholder="Buscar por serie y folio"  style="width:300px;"/>    
            
            <select class="cajas" id="selectRecibidas" style="width:120px" onchange="obtenerFacturas()">
            	<option value="2">Tipo de factura</option>
            	<option value="1" selected="selected">Recibidas</option>
            	<option value="0">Emitidas</option>
            </select>  
            
            <select class="cajas" id="selectEmisores" style="width:350px" onchange="obtenerFacturas()">
            	<option value="0">Seleccione tipo</option>
                <optgroup label="Empresa"></optgroup>
                
                <?php
                foreach($emisores as $row)
				{
					echo $row->recibida=='0'?'<option>'.$row->emisor.'</option>':'';
				}
				?>
                
                <optgroup label="Proveedor"></optgroup>
                
                <?php
                foreach($emisores as $row)
				{
					echo $row->recibida=='1'?'<option>'.$row->emisor.'</option>':'';
				}
				?>
            </select>
                 
        </td> 
       
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>
	<div id="obtenerFacturas"></div>
</div>

</div>

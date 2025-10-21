<script type="text/javascript">
	function busquedaCliente()
	{
		cliente=document.getElementById('selectClientes').value;
		direccion="http://"+base_url+"reportes/busquedaClienteVentas/"+cliente;
		window.location.href=direccion;
	}
	
	function busquedaFechaVenta()
	{
		direccion="http://"+base_url+"reportes/busquedaFechaVentas/"+$('#FechaDia').val();
		window.location.href=direccion;
	}
	
	$(document).ready(function()
	{
		$("#txtBuscarCliente").autocomplete(
		{
			source:base_url+'configuracion/obtenerClientes',
			
			select:function( event, ui)
			{
				//busquedaCliente(ui.item.idCliente)
				location.href=base_url+"reportes/busquedaClienteVentas/"+ui.item.idCliente;
			}
		});
		
		$("#txtBuscarZona").autocomplete(
		{
			source:base_url+'configuracion/obtenerZonas',
			
			select:function( event, ui)
			{
				location.href=base_url+"reportes/busquedaIdentificadorVentas/"+ui.item.idZona;
			}
		});
		
		$("#txtProductos").autocomplete(
		{
			source:base_url+'configuracion/obtenerProductosInventario',
			
			select:function( event, ui)
			{
				location.href=base_url+"reportes/busquedaProductosVentas/"+ui.item.idProducto;
			}
		});
	});
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >
<div class="seccionDiv">
Reporte de ventas por producto
</div>

 <table class="toolbar" width="100%">
    <tr>
    	<td>
        <input title="Ventas por fecha" type="text" class="busquedas" placeholder="Seleccionar fecha" 
        	style="width:150px; cursor:pointer" id="FechaDia" onchange="busquedaFechaVenta()" />
        </td> 
         <td align="center">
        <input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Seleccionar cliente"  style="width:300px;"/>
        </td>
        <td>
         <input type="text"  name="txtBuscarZona" id="txtBuscarZona" class="busquedas" placeholder="Seleccionar <?php echo $this->session->userdata('identificador')?>"   
        style="width:300px;"/>
          <?php
        if($this->session->userdata('idProductoVenta')!="")
        {
			echo 
			'<br />
			<a href="'.base_url().'reportes/busquedaFechaVentas/todas" class="toolbar" style="margin-left:100px" >
			<img src="'.base_url().'img/quitar.png" width="20px;" height="20px;" title="Borrar busqueda" />
			</a>';
        }
        ?>        
        
        </td>
        
       <td>
        <input type="text"  name="txtProductos" id="txtProductos" class="busquedas" placeholder="Seleccionar producto" style="width:300px;"/>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<?php
if($ventas!=null)
{
?>
<table class="admintable" width="100%">
<tr>
    <th class="encabezadoPrincipal" colspan="6" id="generandoExcel">
        Reporte de ventas por producto
        <!--img src="<?php echo base_url()?>img/excel.png" width="32" title="Generar el reporte en excel" onclick="generarExcelVentas()" /-->
    </th>
</tr>
<tr>
    <th class="encabezadoPrincipal">#</th>
    <th class="encabezadoPrincipal" align="left">Producto</th>
    <th class="encabezadoPrincipal" align="right">Total</th>
</tr>
<?php
	$i=1;
	$total=0;
	
	foreach($ventas as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		?>
        <tr <?php echo $estilo?>>
            <td><?php echo $i?></td>
            <td align="left"><?php echo $row->nombre?></td>
            <td align="right">$ <?php echo number_format($row->importe,2)?></td>
        </tr>
		<?php
		
		$i++;
	}
?>
</table>
<?php
}
else
{
	echo '<div class="Error_validar" style=" width:96%; margin-left:1.5%;">No hay registros de ventas por producto</div>';
}
?>

<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

</div>
<!-- Termina -->
</div>

<script type="text/javascript">
	function busquedaCliente()
	{
		cliente=document.getElementById('selectClientes').value;
		direccion="http://"+base_url+"reportes/busquedaClienteVentas/"+cliente;
		window.location.href=direccion;
	}
	
	function busquedaFechaVenta()
	{
		if($('#FechaDia').val()=="" || $('#FechaDia2').val()=="")
		{
			notify('Seleccione las fechas correctamente',500,4000,"error");
			return;
		}
		
		location.href=base_url+"reportes/index/"+$('#FechaDia').val()+"/"+$('#FechaDia2').val()+"/";
	}
	
	$(document).ready(function()
	{
		for(i=1;i<300;i++)
		{
			$("#trProductos"+i).hide();
		}
		
		$("#txtBuscarCliente").autocomplete(
		{
			source:base_url+'configuracion/obtenerClientes',
			
			select:function( event, ui)
			{
				//busquedaCliente(ui.item.idCliente)
				location.href=base_url+"reportes/index/fecha/fecha/"+ui.item.idCliente;
			}
		});
		
		$("#txtBuscarZona").autocomplete(
		{
			source:base_url+'configuracion/obtenerZonas',
			
			select:function( event, ui)
			{
				location.href=base_url+"reportes/index/fecha/fecha/0/"+ui.item.idZona;
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
Reporte de Ventas
</div>
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<input title="Fecha inicio" type="text" class="busquedas" placeholder="Fecha inicio" style="width:150px; cursor:pointer" id="FechaDia"  />
            <input title="Fecha fin" type="text" class="busquedas" placeholder="Fecha fin" style="width:150px; cursor:pointer" id="FechaDia2" />
            
            <input type="button" value="Buscar" onclick="busquedaFechaVenta()" class="btn" />
        </td> 
        <td align="center">
        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Seleccionar cliente"  style="width:300px;"/>
        </td>
        <td>
        <input type="text"  name="txtBuscarZona" id="txtBuscarZona" class="busquedas" placeholder="Seleccionar <?php echo $this->session->userdata('identificador')?>"   
        style="width:300px;"/>
         
         
         
        <?php
        if($idCliente!=0 or $idZona!=0 or $inicio!="fecha")
        {
			echo 
			'<br />
			<a href="'.base_url().'reportes/index" class="toolbar" style="margin-left:100px">
			<img src="'.base_url().'img/quitar.png" width="22px;" height="22px;" title="Borrar busqueda" />
			</a>';
        }
        ?>        
        
        </td>
        
       <td style="display:none">
        <input type="text"  name="txtProductos" id="txtProductos" class="busquedas" placeholder="Seleccionar producto" style="width:300px;"/>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div id="generandoExcel"></div>
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
    <th class="encabezadoPrincipal" colspan="10" id="">
        Reporte de ventas  
        <img src="<?php echo base_url()?>img/excel.png" width="32" title="Generar el reporte en excel" onclick="generarExcelVentas('<?php echo $inicio?>','<?php echo $fin?>','<?php echo $idCliente?>','<?php echo $idZona?>')" />
    </th>
</tr>
<tr>
    <th>#</th>
    <th>
    Fecha
   <!--  <?php
		  if($this->session->userdata('criterioVentas')=='a')
		  {
			echo '<a href="'.base_url().'reportes/ordenamientoVentas/z">
			<img src="'.base_url().'img/ocultar.png" width="17" /></a>';	
		  }
		  else
		  {
			  echo '<a href="'.base_url().'reportes/ordenamientoVentas/a">
			<img src="'.base_url().'img/mostrar.png" width="17" /></a>';
		  }
	  ?>-->
    </th>
    <th align="left">Cliente</th>
    <th align="left">Venta</th>
    <th align="left">Identificador (<?php echo $this->session->userdata('identificador')?>)</th>
    <th align="right">Agente de ventas</th>
    <th align="right">SubTotal</th>
    <th align="right">Descuento</th>
    <th align="right">IVA</th>
    <th align="right">Total</th>
</tr>
<?php
	$i=1;
	$p=0;
	$total=0;
	
	foreach($ventas as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';

		$sql="select nombre, apellidoPaterno
		from usuarios
		where idUsuario='$row->idUsuario'";
	
	$vendedor="";
	
	$query=$this->db->query($sql);
	
	if($query->num_rows()>0)
	{
		$vendedor=$query->row()->nombre.' '.$query->row()->apellidoPaterno;
	}
	
	$cancelada=0;
		
	if($row->idFactura!=0)
	{
		$sql="select cancelada from facturas
		where idFactura='$row->idFactura' 
		and cancelada='1'";
		
		if($this->db->query($sql)->num_rows()>0) 
		{
			$cancelada=1;
		}
	}
	
	if($cancelada==0)
	{
		$total+=$row->total;
		?>
			<tr <?php echo $estilo?> onclick="$('#trProductos<?php echo $i?>').toggle(1000)">
				<td><?php echo $i?></td>
				<td align="center"><?php echo substr($row->fechaCompra,0,10)?></td>
				<td align="left"><?php echo $row->empresa?></td>
				<td align="left"><?php echo $row->ordenCompra?></td>
				<td align="left"><?php echo $row->identificador?></td>
                <td align="left"><?php echo $row->usuario?></td>
                
                <?php
				
				$descuento=$row->descuento>0?$row->subTotal*($row->descuento/100):0;
				$iva=($row->subTotal-$descuento)*$row->iva;
                ?>
                <td align="right">$ <?php echo number_format($row->subTotal,2)?></td>
                <td align="right">$ <?php echo number_format($descuento,2).' ( '.number_format($row->descuento,2).'%)'?></td>
                <td align="right">$ <?php echo number_format($iva,2).' ( '.number_format($row->iva*100,2).'%)'?></td>
				<td align="right">$ <?php echo number_format($row->total,2)?></td>
			</tr>
		<?php
		
		echo '
		<tr id="trProductos'.$i.'" >
			<td colspan="10">
			<table class="admintable" width="100%">
				<tr>
					<th>Producto</th>
					<th>Unidad</th>
					<th>Cantidad</th>
					<th>Precio</th>
					<th>Importe</th>
					
				</tr>';
			
			$sql="select a.cantidad,a.precio, a.importe,
			b.nombre, b.unidad, b.codigoInterno
			from cotiza_productos as a
			inner join productos as b
			on b.idProducto=a.idProduct
			where a.idCotizacion='$row->idCotizacion'";
			
			foreach($this->db->query($sql)->result() as $pro)
			{
				$estilo='class="sombreado"';
		
				if($p%2>0)
				{
					$estilo="class='sinSombra'";
				}
			
				echo'
				<tr '.$estilo.'>
					<td>'.$pro->nombre.'</td>
					<td>'.$pro->unidad.'</td>
					<td align="right">'.number_format($pro->cantidad,2).'</td>
					<td align="right">$ '.number_format($pro->precio,2).'</td>
					<td align="right">$ '.number_format($pro->importe,2).'</td>
				</tr>';
				
				$p++;
			}
			
			echo'
			</table>
			</td>
		</tr>';
		
		$i++;
		
	}
}
	
	echo'
	<tr class="sombreado">
		<td class="totales" colspan="9" align="right">Total</td>
		<td class="totales" align="right">$ '.number_format($total,2).'</td>
	</tr>';
?>
</table>
<?php
}
else
{
	echo '<div class="Error_validar" style=" width:96%; margin-left:1.5%;">No hay registros de ventas</div>';
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

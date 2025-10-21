<script src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/reportes/ventas.js"></script>

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>

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
	
	
	function buscarVentaZona()
	{
		location.href=base_url+"reportes/index/fecha/fecha/0/"+$('#selectZonas').val();
	}
	
	function buscarVentaUsuario()
	{
		location.href=base_url+"reportes/index/fecha/fecha/0/0/"+$('#selectAgentes').val();
	}
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
	Reporte de Ventas
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
        	<input title="Fecha inicio" type="text" class="busquedas" placeholder="Fecha inicio" style="width:120px; cursor:pointer" id="FechaDia"  />
            <input title="Fecha fin" type="text" class="busquedas" placeholder="Fecha fin" style="width:120px; cursor:pointer" id="FechaDia2" />
            
            <input type="button" value="Buscar" onclick="busquedaFechaVenta()" class="btn" />
            
            <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="2"/>
			<input type="hidden"  name="txtIdClienteCrm" id="txtIdClienteCrm" value="0"/>
        </td> 
        <td align="center">
        	<input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Seleccionar cliente"  style="width:500px;"/>
             <?php
			if($idCliente!=0 or $idZona!=0 or $inicio!="fecha" or $idUsuario!=0)
			{
				echo '<img onclick="window.location.href=\''.base_url().'reportes/index\'" src="'.base_url().'img/quitar.png" class="borrarBusqueda" title="Borrar busqueda" />';
			}
			?>        
            
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div id="generandoExcel"></div>

<?php
if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="5" align="right" style="border-right:none">
			Reporte de ventas  
		</th>
		<th class="encabezadoPrincipal" style="border-right:none; border-left:none" colspan="2">
			<img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="reporteVentas(<?php echo $idCliente.',\''.$inicio.'\',\''.$fin.'\','.$idZona.','.$idUsuario?>)" />
			&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="generarExcelVentas('<?php echo $inicio?>','<?php echo $fin?>','<?php echo $idCliente?>','<?php echo $idZona?>','<?php echo $idUsuario?>')" />
			<br />
			<a>PDF</a>
			<a>Excel</a>
            
            <?php
            if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnExportarPdfReporte\');
					desactivarBotonSistema(\'btnExportarExcelReporte\');
				</script>';
			}
			?>
		</th>
		<th class="encabezadoPrincipal" colspan="4" style="border-left:none" align="right">
			Total: $<?php echo number_format($total,2)?>
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
		<th align="center">Cliente</th>
		<th align="center">Venta</th>
		<th align="center">
		<?php 
			
			echo '
				<select id="selectZonas" class="cajas" style="width:110px" onchange="buscarVentaZona()">
					<option value="0">'.$this->session->userdata('identificador').'</option>';
				
				foreach($zonas as $zona)
				{
					$seleccionado	=$zona['idZona']==$idZona?'selected="selected"':'';
					
					echo '<option '.$seleccionado.' value="'.$zona['idZona'].'">'.$zona['descripcion'].'</option>';
				}
					
				echo'</select>';
		?>
        </th>
		<th align="center">
        <?php 
			
			echo '
				<select id="selectAgentes" class="cajas" style="width:130px" onchange="buscarVentaUsuario()">
					<option value="0">Agente de ventas</option>';
				
				foreach($usuarios as $row)
				{
					$seleccionado	=$row->idUsuario==$idUsuario?'selected="selected"':'';
					
					echo '<option '.$seleccionado.' value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
				}
					
				echo'</select>';
		?>
        </th>
        <th align="center">CRM</th>
		<th align="center">Subtotal</th>
		<th align="center">Descuento</th>
		<th align="center">IVA</th>
		<th align="center">Total</th>
	</tr>
	<?php
		$i=1;
		$p=0;
		$total=0;
		
		foreach($ventas as $row)
		{
			$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
			$cancelada	=0;
				
			if($row->idFactura!=0)
			{
				$cancelada	=$this->reportes->obtenerFacturaCancelada($row->idFactura);
			}
			
			if($cancelada==0)
			{
				$total		+=$row->total;
				
				/*$descuento	=$row->descuento>0?$row->subTotal*($row->descuento/100):0;
				$iva		=($row->subTotal-$descuento)*$row->iva;*/
						
				?>
					<tr <?php echo $estilo?> onclick="$('#trProductos<?php echo $i?>').toggle(1)">
						<td><?php echo $i?></td>
						<td align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
						<td align="left"><?php echo $row->empresa?></td>
						<td align="left">
						<?php 
							echo $row->ordenCompra;
							echo $row->idTienda>0?'('.$row->tienda.')':'';
							echo ' <img src="'.base_url().'img/ventas.png" width="22" height="22" title="Ver detalles" onclick="obtenerVentaInformacion('.$row->idCotizacion.')" />';
						?></td>
						<td align="center">
						<?php 
						
						echo $row->identificador
						?>
                        </td>
						<td align="left"><?php echo $row->usuario?></td>
                        
                        
                        <?php
						$seguimiento	= null;
						if(strlen($row->idSeguimiento)>0)
						{
							$seguimiento	= $this->crm->obtenerUltimoSeguimientoVenta($row->idCotizacion);
						}
						
						$mostrarSeguimiento=false;
			
						if($permisoCrm[0]->activo==1)
						{
							$mostrarSeguimiento=true;
						}
						
						echo'
						<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.',0)"'):'').' >';
							
							if($mostrarSeguimiento and $seguimiento!=null)
							{
								echo'
								<span >
									<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
									<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
								</span>';
							}
							if($mostrarSeguimiento and $seguimiento==null)
							{
								echo '<img src="'.base_url().'img/crm.png" width="22" height="22" />';
							}
							
						echo'
						</td>';
					 ?>
                        
						<td align="right">$<?php echo number_format($row->subTotal,2)?></td>
						<td align="right">$<?php echo number_format($row->descuento,2).' ( '.number_format($row->descuentoPorcentaje,2).'%)'?></td>
						<td align="right">$<?php echo number_format($row->iva,2).' ( '.number_format($row->ivaPorcentaje,2).'%)'?></td>
						<td align="right">$<?php echo number_format($row->total,2)?></td>
					</tr>
				<?php
				
				echo '
				<tr id="trProductos'.$i.'" >
					<td colspan="11">
					<table class="admintable" width="100%">
						<tr>
							<th>Producto</th>
							<th>Unidad</th>
							<th>Cantidad</th>
							<th>Precio</th>
							<th>Descuento</th>
							<th>Importe</th>
						</tr>';
					
					$productos	= $this->reportes->obtenerProductosVentas($row->idCotizacion);
					
					foreach($productos as $pro)
					{
						echo'
						<tr '.($p%2>0?'class="sinSombra"':'class="sombreado"').'>
							<td>'.$pro->nombre.'</td>
							<td>'.$pro->unidad.'</td>
							<td align="right">'.number_format($pro->cantidad,2).'</td>
							<td align="right">$'.number_format($pro->precio,2).'</td>
							<td align="right">$'.number_format($pro->descuento,2).'</td>
							<td align="right">$'.number_format($pro->importe,2).'</td>
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
	?>
	</table>
	<?php
}
else
{
	echo '<div class="Error_validar" style=" width:96%; margin-left:1.5%;">No hay registros de ventas</div>';
}

echo'
<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
</div>';

?>

<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
</div>

<script src="<?php echo base_url()?>js/ventas/catalogo/ventas.js"></script>
<script src="<?php echo base_url()?>js/reportes/cobranza.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>

<script type="text/javascript">
function busquedaCliente()
{
	cliente=document.getElementById('selectClientes').value;
	
	direccion=base_url+"reportes/cobranza/fecha/fecha/"+cliente;
	
	window.location.href=direccion;
}

function busquedaZona()
{
	zona=document.getElementById('selectZonas').value;
	
	direccion=base_url+"reportes/cobranza/fecha/fecha/0/"+zona;
	
	window.location.href=direccion;
}

function busquedaFechasCobranza()
{
	if($('#FechaDia').val()==""|| $('#FechaDia2').val()=="" || $('#FechaDia').val() > $('#FechaDia2').val() )
	{
		notify('Seleccione las fechas correctamente',500,5000,'error',2,5);
		return
	}
	
	window.location.href=base_url+"reportes/cobranza/"+$('#FechaDia').val()+"/"+$('#FechaDia2').val();
}

$(document).ready(function()
{
	$("#txtBuscarCliente").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'reportes/cobranza/fecha/fecha/'+ui.item.idCliente;
		}
	});
	
	$("#txtBuscarZona").autocomplete(
	{
		source:base_url+'configuracion/obtenerZonas',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'reportes/cobranza/fecha/fecha/0/'+ui.item.idZona;
		}
	});
});

</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de cobranza
</div>
-->    <table class="toolbar" width="100%">
        <tr>
            <td>
                <input name="FechaDia" type="text" title="Inicio" style="width:150px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
                <input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:150px" class="busquedas" placeholder="Fecha fin" />
                <input type="button" class="btn" value="Buscar" onclick="busquedaFechasCobranza()"  />    
                
                
                <input type="text"  name="txtBuscarCliente" id="txtBuscarCliente" class="busquedas" placeholder="Seleccione cliente" style="width:300px;"/>
                
                <!--<input type="text"  name="txtBuscarZona" id="txtBuscarZona" class="busquedas" placeholder="Seleccione <?php echo $this->session->userdata('identificador')?>" style="width:300px;"/>-->
                
                <?php
                if($inicio!="fecha" or $idCliente!=0)
                {
                	echo '<img onclick="window.location.href=\''.base_url().'reportes/cobranza\'" src="'.base_url().'img/quitar.png" width="22px;" height="22px;" title="Borrar busqueda" />';
                }
                ?>        
            
            </td>
        </tr>
    </table>
</div>
</div>

<div class="listproyectos" style="margin-top:25px">
<div id="generandoReporte"></div>

<?php
if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	
	<table class="admintable" width="100%" >
		<tr>
			<th class="encabezadoPrincipal" colspan="5" style="border-right:none" align="right">
				Reporte de cobranza
			</th>
			<th style="border-right:none; border-left:none" class="encabezadoPrincipal">
                <img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="reporteCobranza(<?php echo '\''.$inicio.'\',\''.$fin.'\','.$idCliente?>)" />
                &nbsp;&nbsp;
                <img id="btnExportarExcelReporte" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="excelCobranza('<?php echo $inicio?>','<?php echo $fin?>',<?php echo $idCliente?>)" />
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
			
			<th class="encabezadoPrincipal" align="right" colspan="3"  style="border-left:none">
				Total: $<?php echo number_format($totalCobranza,2)?>
			</th>
		</tr>
		<tr>
			<th class="">#</th>
			<th class="">Fecha</th>
			
			<th class="" align="center">Cliente</th>
			<th align="center">Teléfono</th>
			<th class="" align="center">Venta</th>
			<th class="" align="center">Fecha de vencimiento</th>
			<th class="" align="center">Días de vencimiento</th>
			<th class="" align="center">Saldo</th>
			<th class="" align="center">Acciones</th>
		</tr>
        
    <?php
	    
	$i=1;
	$total=0;
	foreach($ventas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$dias		=0;
		
		if($row->idFactura>0)
		{
			$dias	=$this->reportes->obtenerDiasRestantes($row->fechaVencimiento);
		}

		$dias	=$dias<0?'<label style="color:red">'.($dias*-1).'</label>':$dias;
		
		?>
		<tr <?php echo $estilo?>>
			<td align="right"><?php echo $i?></td>
			<td align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
			
			<td align="left"><?php echo $row->empresa?></td>
			<td align="left"><?php echo $row->telefono?></td>
			<td align="left">
				<?php 
					echo $row->ordenCompra;
					echo ' <img src="'.base_url().'img/ventas.png" width="22" height="22" title="Ver detalles" onclick="obtenerVentaInformacion('.$row->idVenta.')" />';
				?>
			</td>
			<td align="center"><?php echo obtenerFechaMesCorto($row->fechaVencimiento)?></td>
			<td align="center"><?php echo $dias?>
			</td>
			<td align="right">$ <?php echo number_format($row->saldo,2)?></td>
			<td align="center">
			<?php
				echo '
				<img id="btnCobrosCliente'.$i.'" onclick="obtenerPagosClientes('.$row->idVenta.')" src="'.base_url().'img/pagos.png" width="20" height="20" title="Cobros a clientes"/>
				&nbsp;&nbsp;
				<img src="'.base_url().'img/correo.png" width="20" height="20" title="Enviar correo" onclick="formularioCorreo(\''.$row->ordenCompra.'\',\''.$row->email.'\','.$row->idVenta.');" />
				<br />
				<a id="a-btnCobrosCliente'.$i.'">Cobros</a>
				<a>Enviar</a>';
			
           		if($permiso[2]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnCobrosCliente'.$i.'\');
					</script>';
				}
             ?>
             
			</td>
		</tr>

		<?php
		$i++;
	}
	
	?>
    </table>
    <?php
	
	echo'
	<div style="width:90%; margin-top:4%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de cobranza</div>';
}
?>
</div>
</div>


<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

<div id="ventanaCorreo" title="Enviar orden de venta por correo">
<div id="enviandoCorreo"></div>
<div id="formularioCorreo"></div>
</div>



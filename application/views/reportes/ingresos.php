<script type="text/javascript">
	function validando()
	{
		if(document.form.FechaDia.value==""|| document.form.FechaDia2.value=="" )
		{
			alert("Debe seleccionar ambas fechas");
			return false;
		}
		else 
		if(document.form.FechaDia.value > document.form.FechaDia2.value)
		{
			alert('La fecha de inicio no puede ser mayor a la final.');
			return false;
		} 
		else
		return true;
	}

	function busquedaProveedor()
	{
		proveedor=document.getElementById('idProveedor').value;
		
		direccion="http://"+base_url+"reportes/busquedaProveedor/"+proveedor;
		
		window.location.href=direccion;
	}
	
	$(document).ready(function()
	{
		$("#borraBusqueda").click(function(e)
		{
			direccion="http://"+base_url+"reportes/busquedaIngresoFechas/";
		
			window.location.href=direccion;
		})
	});
	
	function buscarCuentas()
	{
		div = document.getElementById('selectBancos');
		idBanco=div.value;
		
		$("#cargarCuenta").load("http://"+base_url+"ficha/obtenerCuentas/"+idBanco);
	}

	
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar">
<div class="seccionDiv">
Reporte de Ingresos
</div>
<form id="form" name="form" onSubmit="return validando()" method="post" action="<?php echo base_url().'reportes/busquedaIngresoFechas/'?>">
 <table class="toolbar" width="100%">
    <tr>
        <td width="40%">
            <input name="FechaDia" type="text" title="Inicio" style="width:130px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:130px" class="busquedas" placeholder="Fecha fin" />
        </td>
        <td width="20%">
            <select class="busquedas" id="selectBancos" name="selectBancos" style="width: auto"  onchange="buscarCuentas()">
            <option value="0">Seleccione banco</option>
            <?php
            foreach($bancos as $row)
            {
                echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
            }
            ?>
            </select>  
            
             <?php
			if($this->session->userdata('ingresoInicio')!="")
			{
				echo 
				'<br />
				<a id="borraBusqueda" class="toolbar" style="margin-left:55px">
				<span class="icon-option" 
				title="Añadir cliente"><img src="'.base_url().'img/quitar.png" width="30px;" 
				height="30px;" title="Borrar busqueda" />
				</span>
				Borrar busqueda</a>';
			}
			?>        
        
        </td>
        <td width="20%">
        <div id="cargarCuenta">
         <select  id="cuentasBanco" name="cuentasBanco" class="busquedas" style="width:auto;" >
            <option value="0">Seleccione cuenta</option>
         </select>
         </div>
        </td>
        <td>
         <input type="submit" name="Submit" class="btn" value="Buscar"  />  
        </td>
</tr>
</table>
</form>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	
<?php
if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
	<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" style="width:100%">
	<tr>
	<th class="encabezadoPrincipal" colspan="8">
	Ingresos 
	<?php
	if($this->session->userdata('ingresoInicio')!="")
	{
		print('del '.$this->session->userdata('ingresoInicio')." al ".$this->session->userdata('ingresoFin'));
	}
	?>
	</th>
	</tr>
	<tr>
	<th class="encabezadoPrincipal" align="right">#</th>
	<th class="encabezadoPrincipal">Fecha</th>
	<th class="encabezadoPrincipal">Concepto</th>
	<th class="encabezadoPrincipal">Banco</th>
	<th class="encabezadoPrincipal">Cuenta</th>
	<th class="encabezadoPrincipal">Forma de pago</th>
	<th class="encabezadoPrincipal">Orden de venta</th>
	<th class="encabezadoPrincipal">Monto</th>
	</tr>
	
	<?php
	$i=1;
	$total=0;
	
	
	foreach($ingresos as $row)
	{
		$estilo='class="sombreado"';
		
		if($i%2>0)
		{
			$estilo="class='sinSombra'";
		}
		
		$total+=$row->pago;
		
		$orden 		= $this->reportes->obtenerOrdenVenta($row->idVenta);
		?>
		
        <tr <?php echo $estilo?>>
            <td align="right"><?php echo $i ?></td>
            <td align="center"><?php echo $row->fecha?></td>
            <td align="center"><?php echo $row->producto?></td>
            <td align="center"><?php echo $row->banco?></td>
            <td align="center"><?php echo $row->cuenta?></td>
            <td align="center"><?php echo $row->formaPago?></td>
            <td align="center"><?php echo $orden?></td>
            <td align="right">$ <?php echo number_format($row->pago,2)?></td>
		</tr>
		<?php
		$i++;   
	}
	
	?>
	<tr>
        <th align="right" colspan="8">
        <a onclick="window.open('<?php echo base_url()?>reportes/ingresosPDF')">
        <img src="<?php echo base_url()?>img/pdf.png" width="20" title="Generar PDF" 
        style="cursor:pointer" />
        </a>
        &nbsp; Total $ <?php echo number_format($total,2)?>
        </th>
	</tr>
	</table>
	<?php
	
	echo'
	<div style="width:90%; margin-bottom:1%;">
	<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de ingresos</div>';
}
?>
</div>
<!-- Termina -->
</div>

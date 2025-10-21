<script type="text/javascript">
function validando()
{
	if(document.form.FechaDia.value==""|| document.form.FechaDia2.value=="" )
	{
		notify('Las fechas son incorrectas',500,5000,'error',5,5);
		return false;
	}
	else 
	if(document.form.FechaDia.value > document.form.FechaDia2.value)
	{
		notify('La fecha de inicio no puede ser mayor a la final.',500,5000,'error',5,5);
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
	
	$("#txtBuscarProveedor").autocomplete(
	{
		source:base_url+'configuracion/obtenerProveedores',
		
		select:function( event, ui)
		{
			location.href=base_url+"reportes/busquedaProveedor/"+ui.item.idProveedor;
		}
	});
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar">
<div class="seccionDiv">
Reporte de compras
</div>

<form id="form" name="form" onSubmit="return validando()" method="post" action="<?php echo base_url().'reportes/busquedaFechas/'?>">
 <table class="toolbar" width="100%">
    <tr>
     <td>
        <input name="FechaDia" type="text" title="Inicio" style="width:150px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
        <input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:150px" class="busquedas" placeholder="Fecha fin" />
        <input type="submit" name="Submit" class="btn" value="Buscar"  />    
     </td>
     <td width="60%" style="padding-right:130px">
    <input type="text"  name="txtBuscarProveedor" id="txtBuscarProveedor" class="busquedas" placeholder="Seleccione proveedor"  style="width:300px;"/>
    <?php
	if($this->session->userdata('proveedorReporte')!="")
	{
		echo 
		'<br />
		<a href="'.base_url().'reportes/busquedaProveedor" class="toolbar" style="margin-left:240px">
			<img src="'.base_url().'img/quitar.png" width="22px;" height="22px;" title="Borrar busqueda" />
		</a>';
	}
        ?>        
     </td>
</tr>
</table>
</form>
</div>
</div>

<div class="listproyectos">
  
  <?php
if($compras!=null)
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
	<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
	?>
    
	<table class="admintable" style="width:100%">
        <tr>
        <th class="encabezadoPrincipal" align="right">#</th>
        <th class="encabezadoPrincipal">
        Fecha compra
        <?php
        if($this->session->userdata('criterioCompras')=='a')
        {
			echo '<a href="'.base_url().'reportes/ordenamientoCompras/z">
			<img src="'.base_url().'img/ocultar.png" width="17" /></a>';	
        }
        else
        {
			echo '<a href="'.base_url().'reportes/ordenamientoCompras/a">
			<img src="'.base_url().'img/mostrar.png" width="17" /></a>';
        }
        ?>
        </th>
        <th class="encabezadoPrincipal">Proveedor</th>
        <th class="encabezadoPrincipal">Descripcion</th>
        <th class="encabezadoPrincipal">Total</th>
	</tr>
	
	<?php
	$i=1;
	$total=0;
	
	foreach($compras as $compra)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$total+=$compra->total;
		?>
		<tr <?php echo $estilo?>>
            <td align="right"><?php echo $i ?></td>
            <td align="center"><?php echo $compra->fechaCompra?></td>
            <td align="center"><?php echo $compra->empresa?></td>
            <td align="center"><?php echo $compra->nombre?></td>
            <td align="right">$ <?php echo number_format($compra->total,2)?></td>
		</tr>
		<?php
		$i++;   
	}
	?>
        <tr>
            <th align="right" colspan="5">
            <a onclick="window.open('<?php echo base_url()?>reportes/comprasPDF')">
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
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras</div>';
}
?>
</div>
<!-- Termina -->
</div>

<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/graficas.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/flot/jquery.flot.pie.js"></script>
<script type="text/javascript">
$(function () 
{
	var data = [];
	//var series = Math.floor(Math.random()*10)+1;
	
	<?php 
	$i=0;
	if($ventas!=null)
	{
		foreach($ventas as $row)
		{
			?>
				data[<?php echo $i?>] = { label: "<?php echo $row->nombre.' ('.$row->cantidad .' unidades)'?>", data: <?php echo $row->cantidad?> }
			<?php
			$i++;
		}
	}
	else
	{
		?>
		for( var i = 0; i<5; i++)
		{
			data[i] = { label: "Producto "+(i+1), data: 1 }
		}
		<?php
	}
	?>
	
	// Ventas
    $.plot($("#graficaVentas"), data, 
	{
		series: 
		{
			pie: 
			{ 
				show: true
			}
		}
	});
});
</script>

<style>
	div.graficas
	{
		width: 600px;
		height: 320px;
	}
</style>

<div class="derecha">
<div class="toolbar" id="toolbar">
    <table class="toolbar" width="100%">
    	<tr>
        <td class="seccion">
    	Grafica de venta de unidades
   	    </td>
</tr>
</table>
</div>

<div class="listproyectos" align="center">
<h1>Ventas (Unidades) 
<img id="graficarUnidades" style="cursor:pointer" title="Graficar otros productos"  onclick="cargarGraficarUnidades()"
	src="<?php echo base_url()?>img/graficas.png" width="34" />
</h1>
<br />

<div id="graficaVentas" class="graficas"></div>


<div id="ventanaGraficarUnidades" title="Graficar otros productos">
<div style="width:99%;" id="graficandoUnidades"></div>
<div id="errorGraficandoUnidades" class="ui-state-error" ></div>
<div id="cargarGraficarUnidades"></div>
</div>
</div>

</div>
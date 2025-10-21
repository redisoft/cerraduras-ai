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


<script src="<?php echo base_url()?>js/jquery.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/barcode.js"></script>

<script>
$(document).ready(function()
{
	$("#codigoBarras").barcode("<?php echo $personal->numeroAcceso?>", "code93",{barWidth:2, barHeight:60, output:'canvas'})
	
	obtenerImagenCanvas();
});

function obtenerImagenCanvas()
{
	var canvas1 = document.getElementById("codigoBarras");        
	
	if (canvas1.getContext) 
	{
		var ctx 	= canvas1.getContext("2d");                
		var myImage = canvas1.toDataURL();   

		$('#txtCodigoImagen').val(myImage);
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
		},
		type:"POST",
		url:'<?php echo base_url()?>administracion/guardarImagenTarjeta',
		data:
		{
			tarjeta:			$('#txtCodigoImagen').val(),
			numeroEmpleado:		"<?php echo $personal->numeroAcceso?>",
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			location.href='<?php echo base_url()?>administracion/tarjetaPersonal/<?php echo $personal->idPersonal?>';
		},
		error:function(datos)
		{
		}
	});
}
</script>

<canvas id="codigoBarras" style="display:none"></canvas>
<textarea id="txtCodigoImagen" style="width:1000px; height:500px; display:none"></textarea>

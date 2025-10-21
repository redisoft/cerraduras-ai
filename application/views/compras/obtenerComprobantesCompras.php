<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUpload.js"></script>

<script type="text/javascript">
	$(document).ready(function()
	{ 
		$("#txtComprobanteCompra").pekeUpload(
		{
			theme:				'bootstrap',
			btnText:			'Seleccione',
			allowedExtensions:	"jpeg|jpg|png|gif|tif|bmp|pdf|doc|docx|xls|xlsx|txt|rar|zip|xps|oxps|xml",
			url:				base_url+'compras/subirFicherosCompra/<?php echo $idCompra?>/<?php echo $idRecibido?>',
			maxSize:			10,
			sizeError:			'El archivo es demasiado grande',
			invalidExtError:	'No se permite ese tipo de archivos',
			onFileSuccess:function(file,data)
			{
				//data	= eval(data);
				
				switch(data)
				{
					case "0":
						//alert(data[1])
					break;	
					
					case "1":
						obtenerComprobantesCompras(<?php echo $idCompra?>,<?php echo $idRecibido?>)
						notify('El comprobante se ha cargado correctamente',500,5000,'',30,5);
					break;	
				}
			}
		});
	});
</script>

<?php
echo'

<table class="admintable" width="100%">
	<tr>
		<th colspan="2" class="encabezadoPrincipal">Cargar comprobante</th>
	</tr>
	<tr>
		<td class="key">Comprobante:</td>
		<td>
			'.($cuota?'<input type="file" id="txtComprobanteCompra" name="txtComprobanteCompra" />':'<i>'.mensajeCuota.'</i>').'
		</td>
	</tr>
</table>';
	
if($ficheros!=null)
{
	$i=1;
	
	echo'
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5" class="encabezadoPrincipal">Lista de comprobantes</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th width="45%">Nombre</th>
			<th>Tamaño</th>
			<th>Acciones</th>
		</tr>';
	
	foreach($ficheros as $row)
	{
		$estilo	=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td>';
			
				if(file_exists(carpetaCompras.$row->idComprobante.'_'.$row->nombre) and strlen($row->nombre)>3)
				{	
					echo'<a title="Descargar '.$row->nombre.'" href="'.base_url().'compras/descargarFicheroCompra/'.$row->idComprobante.'">'.$row->nombre.'</a>';
				}
				else
				{
					echo '<i>'.$row->nombre.' (No se encuentra el comprobante)</i>';
				}
			
			echo'
			</td>
			<td align="center">'.number_format($row->tamano/1024,1).' KB</td>
			<td align="center">
				<img onclick="accesoBorrarComprobanteCompra('.$row->idComprobante.','.$idCompra.','.$row->idRecibido.')" src="'.base_url().'img/borrar.png" width="22" title="Borrar comprobante" /><br />
				<a>Borrar</a>
			</td>
		</tr>';	
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de comprobantes</div>';
}

if($idRecibido==0)
{
	if($pagos!=null)
	{
		$i=1;
		
		echo'
		<table class="admintable" width="100%">
			<tr>
				<th colspan="5" class="encabezadoPrincipal">Lista de comprobantes de pagos</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Fecha</th>
				<th width="55%">Nombre</th>
				<th>Tamaño</th>
			</tr>';
		
		foreach($pagos as $row)
		{
			$estilo	=$i%2>0?'class="sinSombra"':'class="sombreado"';
			
			echo '
			<tr '.$estilo.'>
				<td align="right">'.$i.'</td>
				<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
				<td>';
					if(file_exists(carpetaEgresos.$row->idComprobante.'_'.$row->nombre) and strlen($row->nombre)>3)
					{	
						echo'<a title="Descargar" href="'.base_url().'produccion/descargarFicheroEgreso/'.$row->idComprobante.'">'.$row->nombre.'</a>';
					}
					else
					{
						echo '<i>'.$row->nombre.' (No se encuentra el comprobante)</i>';
					}
				
				echo'
				</td>
				<td align="center">'.number_format($row->tamano/1024,1).' KB</td>
			</tr>';	
			
			$i++;
		}
		
		echo '</table>';
	}
}
?>
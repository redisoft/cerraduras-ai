<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUpload.js"></script>

<script type="text/javascript">
	$(document).ready(function()
	{ 
		$("#txtComprobanteIngreso").pekeUpload(
		{
			theme:				'bootstrap',
			btnText:			'Seleccione',
			allowedExtensions:	"jpeg|jpg|png|gif|tif|bmp|pdf|doc|docx|xls|xlsx|txt|rar|zip|xps|oxps|xml",
			url:				base_url+'produccion/subirFicheros/<?php echo $idIngreso?>',
			maxSize:			25,
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
						obtenerComprobantes(<?php echo $idIngreso?>)
						notify('El comprobante se ha cargado correctamente',500,5000,'',30,5);
					break;	
				}
			}
		});
	});
</script>

<?php
echo'
<input type="hidden" id="txtIngresoId" value="'.$idIngreso.'"/>
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Cargar comprobante</th>
	</tr>
	
	<tr>
		<td class="key">Comprobante:</td>
		<td>
			'.($cuota?'<input type="file" id="txtComprobanteIngreso" name="txtComprobanteIngreso" />':'<i>'.mensajeCuota.'</i>').'
			
			<a '.($ficheroXml!=null?'style="display:none"':'').'>XML, PDF contabilidad <input type="checkbox" id="chkXml" value="1" name="chkXml" title="XML contabilidad" /> </a>
		</td>
	</tr>
</table>';
	
if($ficheros!=null)
{
	$i=1;
	
	echo'
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5">Lista de comprobantes</th>
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
				
				#<a title="Descargar '.$row->nombre.'" href="'.base_url().'produccion/descargarFichero/'.$row->idComprobante.'">'.$row->nombre.'</a>
				
				if(file_exists(carpetaIngresos.$row->idComprobante.'_'.$row->nombre) and strlen($row->nombre)>3)
				{	
					echo'<a title="Descargar" href="'.base_url().'produccion/descargarFichero/'.$row->idComprobante.'">'.$row->nombre.'</a>';
					echo $row->xml=='1'?'<br /><i>XML, PDF contabilidad</i>':'';
				}
				else
				{
					echo '<i>'.$row->nombre.' (No se encuentra el comprobante)</i>';
					echo $row->xml=='1'?'<br /><i>(XML, PDF contabilidad)</i>':'';
				}
			
			echo'
			</td>
			<td align="center">'.number_format($row->tamano/1024,1).' KB</td>
			<td align="center">
				<img onclick="accesoBorrarComprobanteIngreso('.$row->idComprobante.','.$idIngreso.')" src="'.base_url().'img/borrar.png" width="22" title="Borrar comprobante" /><br />
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
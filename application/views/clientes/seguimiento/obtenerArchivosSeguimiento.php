<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUpload.js"></script>

<script type="text/javascript">
	$(document).ready(function()
	{ 
		$("#txtArchivoSeguimiento").pekeUpload(
		{
			theme:				'bootstrap',
			btnText:			'Seleccione',
			allowedExtensions:	"jpeg|jpg|png|gif|tif|bmp|pdf|doc|docx|xls|xlsx|txt|rar|zip|xps|oxps|xml",
			url:				base_url+'clientes/subirArchivosSeguimiento/<?php echo $idSeguimiento?>',
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
						obtenerArchivosSeguimiento(<?php echo $idSeguimiento?>)
						notify('El comprobante se ha cargado correctamente',500,5000,'',30,5);
					break;	
				}
			}
		});
	});
</script>

<?php
echo'
'.($permiso[1]->activo==1?'<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Cargar archivo</th>
	</tr>
	
	<tr>
		<td class="key">Archivo:</td>
		<td>
			'.($cuota?'<input type="file" id="txtArchivoSeguimiento" name="txtArchivoSeguimiento" />':'<i>'.mensajeCuota.'</i>').'
		</td>
	</tr>
</table>':'');

echo '<input type="hidden" id="txtIdSeguimiento" value="'.$idSeguimiento.'"/>';
	
if($archivos!=null)
{
	$i=1;
	
	echo'
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5">Lista de archivo</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th width="45%">Nombre</th>
			<th>Tamaño</th>
			<th>Acciones</th>
		</tr>';
	
	foreach($archivos as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td>';
				#<a title="Descargar '.$row->nombre.'" href="'.base_url().'clientes/descargarArchivoSeguimiento/'.$row->idArchivo.'">'.$row->nombre.'</a>
				
				if(file_exists(carpetaSeguimientoClientes.$row->idArchivo.'_'.$row->nombre) and strlen($row->nombre)>3)
				{	
					echo'<a title="Descargar" href="'.base_url().'clientes/descargarArchivoSeguimiento/'.$row->idArchivo.'">'.$row->nombre.'</a>';
				}
				else
				{
					echo '<i>'.$row->nombre.' (No se encuentra el archivo)</i>';
				}
				
			echo'
			</td>
			<td align="center">'.number_format($row->tamano/1024,1).' KB</td>
			<td align="center">
				<img id="btnBorrarArchivoSeguimiento'.$i.'" onclick="accesoBorrarArchivoSeguimiento('.$row->idArchivo.')" src="'.base_url().'img/borrar.png" width="22" title="Borrar fichero" /><br />
				<a id="a-btnBorrarArchivoSeguimiento'.$i.'">Borrar</a>
				'.($permiso[3]->activo==0?'
				<script>
					desactivarBotonSistema(\'btnBorrarArchivoSeguimiento'.$i.'\');
				</script>':'').'
			</td>
		</tr>';	
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de archivos</div>';
}
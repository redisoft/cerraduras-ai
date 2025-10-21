<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUpload.js"></script>

<script type="text/javascript">
	$(document).ready(function()
	{ 
		$("#txtComprobanteConcepto").pekeUpload(
		{
			theme:				'bootstrap',
			btnText:			'Seleccione',
			allowedExtensions:	"xml",
			url:				base_url+'contabilidad/subirComprobanteConcepto/<?php echo $idConcepto?>',
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
						obtenerComprobantesConcepto(<?php echo $idConcepto?>)
						notify('El comprobante se ha cargado correctamente',500,5000,'',30,5);
					break;	
				}
			}
		});
	});
</script>

<?php
echo'

<div id="procesandoComprobantesConcepto"></div>
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Cargar comprobante</th>
	</tr>
	
	<tr>
		<td class="key">Comprobante:</td>
		<td>
			'.($cuota?'<input type="file" id="txtComprobanteConcepto" name="txtComprobanteConcepto" />':'<i>'.mensajeCuota.'</i>').'
		</td>
	</tr>
</table>';
	
if($comprobantes!=null)
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
	
	foreach($comprobantes as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td>';
				if(file_exists(carpetaXml.$row->idComprobante.'_'.$row->nombre) and strlen($row->nombre)>3)
				{	
					echo'<a title="Descargar" href="'.base_url().'contabilidad/descargarComprobanteConcepto/'.$row->idComprobante.'">'.$row->nombre.'</a>';
				}
				else
				{
					echo '<i>'.$row->nombre.' (No se encuentra el comprobante)</i>';
				}
				
			echo'
			</td>
			<td align="center">'.number_format($row->tamano/1024,1).' KB</td>
			<td align="center">
				<img onclick="borrarComprobanteConcepto('.$row->idComprobante.')" src="'.base_url().'img/borrar.png" width="22" title="Borrar comprobante" /><br />
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
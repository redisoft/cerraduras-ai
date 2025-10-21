<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUpload.js"></script>

<script type="text/javascript">
	$(document).ready(function()
	{ 
		$("#txtImportarFichero").pekeUpload(
		{
			theme:				'bootstrap',
			btnText:			'Seleccione',
			allowedExtensions:	"xls",
			url:				base_url+'importar/subirArchivoMateriales',
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
						$('#ventanaImportarMateriales').dialog('close');
						obtenerMateriales();
						notify('El archivo se ha importado correctamente',500,5000,'',30,5);
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
        <td colspan="2" align="center">
            Favor de descargar el formato para que los proveedores se importen correctamente.<br />
            <a href="'.base_url().'importar/descargarFormato/materiales">Formato materia prima.xls</a>
        </td>
    </tr>
    
    <tr>
		<td class="key">Archivo:</td>
		<td>
			'.($cuota?'<input type="file" id="txtImportarFichero" name="txtImportarFichero" />':'<i>'.mensajeCuota.'</i>').'
		</td>
	</tr>
</table>';
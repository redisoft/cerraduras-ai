<?php
if($facturas!=null)
{
	$zip = new ZipArchive();
	
	#--------------------------------------------------------------------------------#
	#PARA OBTENER EL NOMBRE DEL FICHERO

	$nombre	="FACTURAS.zip";
	
	if($mes!='mes')
	{
		$nombre='FACTURAS_'.obtenerNombreMes($mes).$anio.'.zip';
	}

	#--------------------------------------------------------------------------------#
	
	$fichero 	= carpetaCfdi.$nombre;
	
	if(file_exists($fichero))
	{
		unlink($fichero);
	}

	if ($zip->open($fichero, ZIPARCHIVE::CREATE)!==TRUE) 
	{
		echo "0";
		return;
	}
		
	
	foreach($facturas as $row)
	{
		if($row->pendiente=='0')
		{
			if(!file_exists(carpetaCfdi.$row->rfc.'/folio'.$row->serie.$row->folio.'/'.$row->rfc.'_'.$row->serie.$row->folio.'.pdf'))
			{
				if($row->documento!='Recibo de Nómina')
				{
					$this->facturacion->crearFactura($row->idFactura,1);
				}
				else
				{
					$this->facturacion->reciboNomina($row->idFactura,1);
				}
			}

			if(file_exists(carpetaCfdi.$row->rfc.'/folio'.$row->serie.$row->folio.'/'.$row->rfc.'_'.$row->serie.$row->folio.'.xml'))
			{
				$zip->addFile(carpetaCfdi.$row->rfc.'/folio'.$row->serie.$row->folio.'/'.$row->rfc.'_'.$row->serie.$row->folio.'.xml', $row->rfc.'_'.$row->serie.$row->folio.'.xml');
			}

			if(file_exists(carpetaCfdi.$row->rfc.'/folio'.$row->serie.$row->folio.'/'.$row->rfc.'_'.$row->serie.$row->folio.'.pdf'))
			{
				$zip->addFile(carpetaCfdi.$row->rfc.'/folio'.$row->serie.$row->folio.'/'.$row->rfc.'_'.$row->serie.$row->folio.'.pdf', $row->rfc.'_'.$row->serie.$row->folio.'.pdf');
			}
		}
	}

	$zip->close();

	sleep(2);
	
	if(!file_exists(carpetaCfdi.$nombre))
	{
		echo "1";
	}
	else
	{
		echo $nombre;
	}
}
else
{
	echo "1";
}
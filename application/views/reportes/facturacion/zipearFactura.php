<?php
if($factura!=null)
{
	$zip = new ZipArchive();
	
	#--------------------------------------------------------------------------------#
	#PARA OBTENER EL NOMBRE DEL FICHERO

	$nombre	=$factura->rfc.'_'.$factura->serie.$factura->folio.".zip";

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

	if(!file_exists(carpetaCfdi.$factura->rfc.'/folio'.$factura->serie.$factura->folio.'/'.$factura->rfc.'_'.$factura->serie.$factura->folio.'.pdf'))
	{
		if($factura->documento!='Recibo de Nómina')
		{
			$this->facturacion->crearFactura($factura->idFactura,1);
		}
		else
		{
			$this->facturacion->reciboNomina($factura->idFactura,1);
		}
	}
	
	if(file_exists(carpetaCfdi.$factura->rfc.'/folio'.$factura->serie.$factura->folio.'/'.$factura->rfc.'_'.$factura->serie.$factura->folio.'.xml'))
	{
		$zip->addFile(carpetaCfdi.$factura->rfc.'/folio'.$factura->serie.$factura->folio.'/'.$factura->rfc.'_'.$factura->serie.$factura->folio.'.xml', $factura->rfc.'_'.$factura->serie.$factura->folio.'.xml');
	}
	
	if(file_exists(carpetaCfdi.$factura->rfc.'/folio'.$factura->serie.$factura->folio.'/'.$factura->rfc.'_'.$factura->serie.$factura->folio.'.pdf'))
	{
		$zip->addFile(carpetaCfdi.$factura->rfc.'/folio'.$factura->serie.$factura->folio.'/'.$factura->rfc.'_'.$factura->serie.$factura->folio.'.pdf', $factura->rfc.'_'.$factura->serie.$factura->folio.'.pdf');
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
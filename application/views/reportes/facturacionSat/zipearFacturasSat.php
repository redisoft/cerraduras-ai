<?php
if($facturas!=null)
{
	$zip 	= new ZipArchive();
	

	#--------------------------------------------------------------------------------#
	#PARA OBTENER EL NOMBRE DEL FICHERO

	$nombre="FACTURAS.zip";
	
	if($mes!='mes')
	{
		$nombre='FACTURAS_'.obtenerNombreMes($mes).$anio.'.zip';
	}

	#--------------------------------------------------------------------------------#
	
	$fichero 	= "media/sat/".$nombre;
	
	if(file_exists('media/sat/'.$nombre))
	{
		unlink('media/sat/'.$nombre);
	}
	
	if ($zip->open($fichero, ZIPARCHIVE::CREATE)!==TRUE) 
	{
		echo "0";
		return;
	}
	
	foreach($facturas as $row)
	{
		if(!file_exists('media/sat/'.$row->rfcEmisor.'_'.obtenerFechaMesCorto($row->fecha).'_'.$row->serie.$row->folio.'.pdf'))
		{
			$this->facturacion->crearFacturaSat($row->idFactura,1);
		}
		
		if(file_exists('media/sat/'.$row->rfcEmisor.'_'.obtenerFechaMesCorto($row->fecha).'_'.$row->serie.$row->folio.'.xml'))
		{
			$zip->addFile('media/sat/'.$row->rfcEmisor.'_'.obtenerFechaMesCorto($row->fecha).'_'.$row->serie.$row->folio.'.xml', $row->rfcEmisor.'_'.obtenerFechaMesCorto($row->fecha).'_'.$row->serie.$row->folio.'.xml');
		}
		
		#if(file_exists('media/fel/1_facturacion/cfdi/folio'.$row->folio.'/'.$row->rfc.'_'.$row->serie.$row->folio.'.pdf'))
		if(file_exists('media/sat/'.$row->rfcEmisor.'_'.obtenerFechaMesCorto($row->fecha).'_'.$row->serie.$row->folio.'.pdf'))
		{
			$zip->addFile('media/sat/'.$row->rfcEmisor.'_'.obtenerFechaMesCorto($row->fecha).'_'.$row->serie.$row->folio.'.pdf', $row->rfcEmisor.'_'.obtenerFechaMesCorto($row->fecha).'_'.$row->serie.$row->folio.'.pdf');
		}
	}

	$zip->close();

	sleep(2);
	
	if(!file_exists('media/sat/'.$nombre))
	{
		echo "0";
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
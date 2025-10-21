<?php
class Traslado_modelo extends CI_Model 
{
    protected $_fecha_actual;
    protected $_table;
    protected $_iduser;
	protected $idLicencia;
	protected $resultado;
	protected $idFactura;

    function __construct() 
	{
        parent::__construct();

		$datestring 			= "%Y-%m-%d %H:%i:%s";
        $this->_fecha_actual 	= mdate($datestring, now());
        $this->_iduser 			= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->resultado		= "1";
		$this->idFactura		= 0;

		$this->cambiarFechaActual();
		
    }
	
	public function cambiarFechaActual()
	{
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 5 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}
	
	
	public function registrarTrasldo()
	{
		$this->load->helper('sat');
		$this->load->helper('cfdi');
		
		$idCotizacion			= $this->input->post('txtIdCotizacion');
		$idEmisor				= $this->input->post('selectEmisores');
		$idDireccion			= $this->input->post('selectDireccionesCfdi');
		
		if($this->facturacion->comprobarTablasBloqueadas()>=4)
		{
			return array('0',"Hay una factura en proceso, por favor espere que termine de ejecutarse",'','','');
		}
		
		$configuracion			= $this->facturacion->obtenerEmisor($idEmisor);
		$cotizacion				= $this->facturacion->obtenerCotizacion($idCotizacion);
		$cliente				= $this->clientes->obtenerDireccionesEditar($idDireccion);
		
		$data	=array();
		
		$productos				= $this->facturacion->obtenerProductosCotizacion($cotizacion->idCotizacion);

		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->codigoPostal) !=5 )
		{
			return array('0',"El cliente no tiene los datos fiscales necesarios para crear la factura",'','','');
		}
		
		$sql=" lock table facturas write, configuracion_emisores write, facturas_errores write, configuracion write "; //Bloquear el folio, para evitar duplicidad
		$this->db->query($sql);
		
		
		$folio	= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			#$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			#$this->db->trans_complete();
			
			$sql=" unlock tables";
			$this->db->query($sql);
			
			return array('0',"Sin folios para facturación",'','','');
		}
		
		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				="";
		$certificado		="";

		$ficheroXML		= xmlTraslado($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal4.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt'); #Comentado mejor quitarlo jaja
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha256 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= xmlTraslado($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion); 
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			if($configuracion->pac=='4gFactor')
			{
				$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos);
			}
			
			if($configuracion->pac=='finkok')
			{
				$this->timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos);
			}
		}
	
		#if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		if($this->resultado!="1")
		{
			#$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			#$this->db->trans_complete();
			
			$sql=" unlock tables";
			$this->db->query($sql);
			
			$data	= array('0',$this->resultado,'','','');
		}
		else
		{
			#$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			#$this->db->trans_complete();
			
			#$this->session->set_userdata('notificacion','La factura se ha creado correctamente');
			
			
			
			$data	= array('1','La factura se ha creado correctamente',$this->idFactura,$cliente->email,strlen($cliente->email)>0?'1':'0');
		}
		
		return $data;
	}
	
	public function timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos)
	{
		$this->load->library('finkok');
		$this->load->helper('xmlcfdi');

		$timbrado 		= new Finkok();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac,$configuracion->passwordPac,$configuracion->url,$ficheroXML);

		if(!$respuesta['estatus'])
		{
			$this->resultado	= $respuesta['mensaje'];
			
			if($respuesta['codigoError']!='0')
			{
				$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['mensaje'],$configuracion->idEmisor);	
			}

			return 0;
		}
		
		if($respuesta['estatus'])
		{
			$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			$data['xml']			= $respuesta['xml'];
			$data['folio']			= $folio;
			$data['cadenaOriginal']	= $cadena;
			$data['selloDigital']	= $sello;
			$data['UUID']			= $respuesta['valores'][40];
			$data['fechaTimbrado']	= $respuesta['valores'][39];
			$data['selloSat']		= $respuesta['valores'][43];
			$data['certificado']	= $respuesta['valores'][41];
			
			$data['cadenaTimbre']	= '||1.1|'.$data['UUID'].'|'.$data['fechaTimbrado'].'|'.$respuesta['valores'][38].'|'.$data['selloDigital'].'|'.$data['certificado'].'||';
			
			$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
			
			$this->agregarFactura($data,$cotizacion,$cliente,$configuracion,$productos);
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
		}
	}
	
	
	public function agregarFactura($timbre,$cotizacion,$cliente,$configuracion,$productos)
	{
		$data=array
		(
			'rfc'					=> $cliente->rfc,
			'empresa'				=> $cliente->razonSocial,
			'calle'					=> $cliente->calle,
			'numeroExterior'		=> $cliente->numero,
			'colonia'				=> $cliente->codigoPostal,
			'localidad'				=> $cliente->localidad,
			'municipio'				=> $cliente->municipio,
			'estado'				=> $cliente->estado,
			'pais'					=> $cliente->pais,
			'codigoPostal'			=> $cliente->codigoPostal,
			'telefono'				=> $cliente->telefono,
			'email'					=> $cliente->email,
			'colonia'				=> $cliente->colonia,
			'idDireccion'			=> $cliente->idDireccion,
			'subTotal'				=> 0,
			'iva'					=> 0,
			'descuento'				=> 0,
			'total'					=> 0,
			'folio'					=> $timbre['folio'],
			'fecha'					=> $this->_fecha_actual,
			'xml'					=> $timbre['xml'],
			'cadenaOriginal'		=> $timbre['cadenaOriginal'],
			'selloSat'				=> $timbre['selloSat'],
			'selloDigital'			=> $timbre['selloDigital'],
			'UUID'					=> $timbre['UUID'],
			'certificadoSat'		=> $timbre['certificado'],
			'cadenaTimbre'			=> $timbre['cadenaTimbre'],	
			'fechaTimbrado'			=> $timbre['fechaTimbrado'],
			'idLicencia'			=> $this->idLicencia,
			'idCotizacion'			=> $cotizacion->idCotizacion,
			'idCliente'				=> $cliente->idCliente,
			'documento'				=> 'TRASLADO',
			'tipoComprobante'		=> 'traslado',
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> '',
			'parcial'				=> '0',
			'observaciones'			=> $this->input->post('txtObservaciones'),
			'divisa'				=> '',
			'claveDivisa'			=> 'XXX',
			'tipoCambio'			=> '',
			'idEmisor'				=> $this->input->post('selectEmisores'),
			'metodoPago'			=> '',
			'formaPago'				=> '',
			'usoCfdi'				=> $this->input->post('usoCfdiTexto'),
			'version'				=> '1',
			'idPac'					=> $configuracion->idPac,
			
			'versionCfdi'			=> '4.0',
			'regimenFiscalCliente'	=> $cliente->claveRegimen.', '.$cliente->regimenFiscal,
			'certificadoEmisor'		=> $configuracion->numeroCertificado,
		);
		
		$this->db->insert('facturas',$data);
		$idFactura 			= $this->db->insert_id();
		$this->idFactura	= $idFactura;
		
		
		$sql=" unlock tables";
		$this->db->query($sql);
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']		= sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS Y ASOCIAR LA FACTURA PARCIAL EN CASO DE SER NECESARIO
		#-------------------------------------------------------------------------------------#
		
		$data=array
		(
			'idCotizacion'		=> $cotizacion->idCotizacion,
			'idFactura'			=> $idFactura,
			'porcentaje'		=> 100,
		);
		
		$this->db->insert('rel_factura_cotizacion',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		
		$i=1;
		foreach($productos as $row)
		{
			$pedimento1		= trim($this->input->post('txtPedimento1'.$i));
			$pedimento2		= trim($this->input->post('txtPedimento2'.$i));
			$pedimento3		= trim($this->input->post('txtPedimento3'.$i));
			$pedimento4		= trim($this->input->post('txtPedimento4'.$i));
			$fecha			= trim($this->input->post('txtFecha'.$i));

			$pedimento		= $pedimento1.'  '.$pedimento2.'  '.$pedimento3.'  '.$pedimento4;

			$data=array
			(
				'idFactura'				=> $idFactura,
				'idProducto'			=> $row->idProducto,
				'nombre'				=> $row->nombre,
				'precio'				=> $row->precio,
				'importe'				=> $row->importe,
				'cantidad'				=> $row->cantidad,
				'descuento'				=> 0,
				'descuentoPorcentaje'	=> 0,
				'unidad'				=> $row->unidad,
				'claveUnidad'			=> $row->claveUnidad,
				'claveProducto'			=> $row->claveProducto,
				'claveDescripcion'		=> $row->claveDescripcion,
				'codigoInterno'			=> $row->codigoInterno,
				'claveObjetoImpuesto'	=> '01',
				'objetoImpuesto'		=> 'No objeto de impuesto',
			);

			if(strlen($pedimento1)==2 and strlen($pedimento2)==2 and strlen($pedimento3)==4 and strlen($pedimento4)==7 and strlen($fecha)==10)
			{
				$data['fecha']		= $fecha;
				$data['pedimento']	= $pedimento;
			}

			$this->db->insert('facturas_detalles',$data);
			$idDetalle	= $this->db->insert_id();

			$data=array
			(
				'idDetalle'				=> $idDetalle,
				'tasa'					=> 0,
				'importe'				=> 0,
				'impuesto'				=> '',
				'nombreImpuesto'		=> '',
				'base'					=> 0,
				'exento'				=> '0',
			);

			$this->db->insert('facturas_detalles_impuestos',$data);

			$i++;
		}
	
	}
}
?>

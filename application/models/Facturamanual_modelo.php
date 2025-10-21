<?php
class Facturamanual_modelo extends CI_Model 
{
    protected $_fecha_actual;
    protected $_table;
    protected $_iduser;
	protected $idLicencia;
	protected $resultado;

    function __construct() 
	{
        parent::__construct();

        $datestring 			= "%Y-%m-%d %H:%i:%s";
        $this->_fecha_actual 	= mdate($datestring, now());
        $this->_iduser 			= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->resultado		= "1";
		
		$this->cambiarFechaActual();
    }
	
	public function cambiarFechaActual()
	{
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 5 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}

	#------------------------------------------------------------------------------------------------------#
	public function registrarFacturaManual()
	{

		if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		$this->load->helper('sat');
		$this->load->helper('manual');

		$idCliente				= $this->input->post('txtIdClienteGlobal');
		$idEmisor				= $this->input->post('selectEmisores');
		
		$configuracion			= $this->configuracion->obtenerEmisor($idEmisor);
		$cliente				= $this->facturacion->obtenerCliente($idCliente);
		$divisa					= $this->facturacion->obtenerDivisa(1);

		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 )
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','El cliente no tiene los datos fiscales para registrar la factura');
		}
		
		$folio					= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','Error de servidor SAT');
		}

		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello					= "";
		$certificado			= "";

		$ficheroXML				= facturaManual($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal_3_3.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt'); #Comentado mejor quitarlo jaja
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha256 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= facturaManual($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			#$this->timbrarXML($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion);
			$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion);
		}
		
		#$this->configuracion->registrarBitacora('Registrar factura manual','Reporte ventas público',$configuracion->serie.$folio.', '.$cliente->empresa); //Registrar bitácora
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',$this->resultado);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
			
			return array('1','El registro ha sido exitoso');
		}
	}
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion)
	{
		$this->load->library('factor');
		
		$config			= $this->facturacion->obtenerConfiguracion();
		$timbrado 		= new Factor();
		$respuesta 		= $timbrado->obtenerTimbre($config->usuarioFactor, $config->passwordFactor, $ficheroXML);

		if(!$respuesta['estatus'])
		{
			if(strlen($respuesta['codigoError'])>0)
			{
				$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
			}
			
			$this->resultado=$respuesta['mensaje'];
			
			
			#$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['comentarios']);	
			
			return 0;
		}
		
		if($respuesta['estatus'])
		{
			#$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			#$timbre					= $carpetaFolio.$configuracion->rfc.'_'.$configuracion->serie.$folio.'.xml'; #Es el archivo XML Timbrado
			$timbre			= $carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero		= fopen($timbre,"w");	
			
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			$data['xml']			=$respuesta['xml'];
			$data['folio']			=$folio;
			$data['cadenaTimbre']	=$respuesta['cadenaTimbre'];
			$data['cadenaOriginal']	=$cadena;
			$data['selloDigital']	=$sello;
			$data['UUID']			=$respuesta['uuid'];
			$data['fechaTimbrado']	=$respuesta['fechaTimbrado'];
			$data['selloSat']		=$respuesta['selloSat'];
			$data['certificado']	=$respuesta['certificado'];
			
			$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
			$this->agregarFactura($data,$configuracion,$cliente);
		}
	}

	public function agregarFactura($timbre,$configuracion,$cliente)
	{
		$divisa		=$this->facturacion->obtenerDivisa(1);
		
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
			
			'subTotal'				=> $this->input->post('txtSubTotal'),
			#'iva'					=> $this->input->post('txtIvaPorcentaje'),
			#'ivaTotal'				=> $this->input->post('txtIva'),
			
			'iva'					=> $this->input->post('txtIva'),
			'ivaPorcentaje'			=> $this->input->post('txtIvaPorcentaje'),
			
			'descuento'				=> 0,
			'descuentoPorcentaje'	=> 0,
			'total'					=> $this->input->post('txtTotal'),
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
			'idCotizacion'			=> 0,
			'idCliente'				=> $cliente->idCliente,
			'documento'				=> 'Factura',
			'tipoComprobante'		=> "ingreso",
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondiciones'),
			#'metodoPago'			=> $this->input->post('txtMetodoPago'),
			'metodoPago'			=> $this->input->post('metodoPagoTexto'),
			'formaPago'				=> $this->input->post('formaPagoTexto').' '.$this->input->post('txtCuentaPago'),
			'usoCfdi'				=> $this->input->post('usoCfdiTexto'),
			'parcial'				=> 0,
			
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,
			
			#'intereses'				=> 0,
			#'porcentaje'			=> 0,
			#'notas'					=> $this->input->post('txtNotas'),
			'manual'				=> 1,
			'global'				=> 0,
			
			'idUsuario'				=> $this->_iduser,
			'idEmisor'				=> $configuracion->idEmisor,
			'observaciones'			=>  $this->input->post('txtObservaciones'),
			#'version'				=> '1',
		);
		
		$this->db->insert('facturas',$data);
		$idFactura = $this->db->insert_id();
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		
		$numeroProductos	=	$this->input->post('txtNumeroProductos');
		
		for($i=1;$i<=$numeroProductos;$i++)
		{
			if(strlen($this->input->post('txtConceptoFactura'.$i))>1)
			{
				$iva						= $this->input->post('txtImporteFactura'.$i)*$this->input->post('txtIvaPorcentaje');
				
				$data=array
				(
					'idFactura'				=> $idFactura,
					'idProducto'			=> 0,
					'nombre'				=> $this->input->post('txtConceptoFactura'.$i),
					'precio'				=> $this->input->post('txtPrecioFactura'.$i),
					'importe'				=> $this->input->post('txtImporteFactura'.$i),
					'cantidad'				=> $this->input->post('txtCantidadFactura'.$i),
					'descuento'				=> 0,
					'descuentoPorcentaje'	=> 0,
					#'iva'					=> $iva,
					#'ivaPorcentaje'			=> $this->input->post('txtIvaPorcentaje'),
					
					'unidad'				=> $this->input->post('txtUnidadFactura'.$i),
					'claveUnidad'			=> $this->input->post('txtClaveUnidad'.$i),
					
					'claveProducto'			=> $this->input->post('txtClaveProductoFactura'.$i),
					'claveDescripcion'		=> $this->input->post('txtClaveProductoDescripcion'.$i),
				);
				
				$this->db->insert('facturas_detalles',$data);
				
				$idDetalle=$this->db->insert_id();
				
				$data=array
				(
					'idDetalle'			=> $idDetalle,
					'importe'			=> $iva,
					'tasa'				=> $this->input->post('txtImporteFactura'.$i)>0?0.16:0,
					'tipoFactor'		=> 'Tasa',
					'impuesto'			=> '002',
					'base'				=> $this->input->post('txtImporteFactura'.$i),
					'nombreImpuesto'	=> 'IVA',
				);
				
				$this->db->insert('facturas_detalles_impuestos',$data);
			}
		}
		
		#-------------------------------------------------------------------------------------#

	}
	
	public function timbrarXML($archivoXML,$folio,$ruta,$selloDigital,$cadena,$cliente,$configuracion)
	{
		#$ComprobanteXML = $comprobante;
		$this->load->library('timbrado');
		$this->load->library('seguridad');
		
		#$integrador 		= "2b3a8764-d586-4543-9b7e-82834443f219";
		$integrador 		= "d8ff5d28-9fc3-4097-b683-966a1a634f75";
		$rfc 				= $configuracion->rfc;
		
		$token 			= new Seguridad();
		$transaccion 	= rand(1, 10000);
		$generaToken 	= $token->setToken($rfc, $transaccion, $integrador);
		$tokenGenerado 	= $token->getToken();

		$Timbra 		= new Timbrado();
		$timbrar 		= $Timbra->setTimbrado($archivoXML, $rfc, $transaccion, $tokenGenerado);                               
		$cfdi 			= $Timbra->getTimbrado();
		
		#echo $archivoXML;
		#echo 'cfdi: '.$cfdi;
		
		if($timbrar)
        {
			#=================================================================================#
			$timbre		=$ruta.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			#$timbre='media/timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$cfdi);
			fclose($fichero);
			
			#=================================================================================#
			$UUID			="";
			$fechaTimbrado	="";
			$sello			="";
			$certificado	="";
			$cadenaTimbre	="";
			$rutaFicheros	='media/fel/';
			$cadenaOriginal	=$ruta.'cadenaTimbre'.$folio.'.txt';
			$selloSat		=$ruta.'selloSat'.$folio.'.txt';
			
			$timbre			=$ruta.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado

			exec("xsltproc ".$rutaFicheros.'convertir.xslt'." ".$timbre." > ".$cadenaOriginal);
			$cadenaTimbre	=openFile($cadenaOriginal,"READ","");
			
			$selloSat		=$ruta.'selloSat'.$folio.'.txt';
			
			exec("xsltproc ".$rutaFicheros.'selloSat.xslt'." ".$timbre." > ".$selloSat);
			$datosSello		=openFile($selloSat,"READ","");
			$tamano			=strlen($datosSello);
	
			$b=0;
			
			for($i=0;$i<$tamano;$i++)
			{
				if($b==1)
				{
					if($datosSello[$i]!="*")
					{
						$UUID.=$datosSello[$i];
					}
				}
				
				if($b==2)
				{
					if($datosSello[$i]!="*")
					{
						$fechaTimbrado.=$datosSello[$i];
					}
				}
				
				if($b==3)
				{
					if($datosSello[$i]!="*")
					{
						$sello.=$datosSello[$i];
					}
				}
				
				if($b==4)
				{
					if($datosSello[$i]!="*")
					{
						$certificado.=$datosSello[$i];
					}
				}
				
				if($datosSello[$i]=="*")
				{
					$b++;
				}
			}
			
			$data['xml']			=$cfdi;
			$data['folio']			=$folio;
			$data['cadenaTimbre']	=$cadenaTimbre;
			$data['cadenaOriginal']	=$cadena;
			$data['selloDigital']	=$selloDigital;
			$data['UUID']			=$UUID;
			$data['fechaTimbrado']	=$fechaTimbrado;
			$data['selloSat']		=$sello;
			$data['certificado']	=$certificado;
			
			#$selloDigital=openFile($sello,"READ","");
			
			if(strlen($UUID)>3)
			{
				$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
				$this->agregarFactura($data,$configuracion,$cliente);
			}
			else
			{
				#$this->session->set_userdata('errorNotificacion',$cfdi);
				
				$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
				$this->db->trans_complete();
				
				$this->resultado=$cfdi;
			}
		}
		else
		{
			#$this->session->set_userdata('errorNotificacion',$cfdi);
			
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
				
			$this->resultado=$cfdi;
		}
	}
	
}
?>

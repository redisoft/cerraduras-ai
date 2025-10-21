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
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 10 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}

	#------------------------------------------------------------------------------------------------------#
	public function registrarFacturaManual()
	{
		/*if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return 'Los folios se han terminado, por favor consulte con el administrador';
		}*/
		
		$this->db->trans_start();
		$this->load->helper('sat');

		$idCliente				= $this->input->post('txtIdClienteGlobal');
		$idEmisor				= $this->input->post('selectEmisores');
		
		$configuracion			= $this->facturacion->obtenerEmisor($idEmisor);
		$cliente				= $this->facturacion->obtenerCliente($idCliente);
		$divisa					= $this->facturacion->obtenerDivisa(1);
		
		$retenciones['importe']	= $this->input->post('retencion');
		$retenciones['tasa']	= $this->input->post('tasa');
		$retenciones['nombre']	= $this->input->post('nombre');
		
		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->pais) <3 )
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();

			return array('0',"El cliente no tiene los datos fiscales necesarios para crear la factura");
		}
		
		$folio					= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',"Sin folios suficientes para crear el comprobante");
		}
		

		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';

		crearDirectorio($carpetaFolio);
		
		$sello					= "";
		$certificado			= "";

		$ficheroXML				= facturaManual($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal_3_2.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt'); #Comentado mejor quitarlo jaja
		
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		#echo "openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt';
		
		exec("openssl dgst -sha1 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= facturaManual($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$divisa);
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
			
			return array('1','');
		}
	}
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$divisa)
	{
		$this->load->library('factor');
		
		$timbrado 		= new Factor();
		$config			= $this->facturacion->obtenerConfiguracion();
		$respuesta 		= $timbrado->obtenerTimbre($config->usuarioFactor, $config->passwordFactor, $ficheroXML);

		if(!$respuesta['estatus'])
		{
			if(strlen($respuesta['codigoError'])>0)
			{
				$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
			}
			
			$this->resultado	=$respuesta['mensaje'];
			
			return 0;
		}
		
		if($respuesta['estatus'])
		{
			#$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
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
			$this->agregarFactura($data,$configuracion,$cliente,$divisa);
		}
	}

	public function agregarFactura($timbre,$configuracion,$cliente,$divisa)
	{
		#$divisa		=$this->facturacion->obtenerDivisa(1);
		
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
			'idCliente'				=> $cliente->idCliente,
			
			'subTotal'				=> $this->input->post('txtSubTotal'),
			'ivaPorcentaje'			=> $this->input->post('selectIva'),
			'iva'					=> $this->input->post('txtIva'),
			
			
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
			
			'documento'				=> 'FACTURA',
			'tipoComprobante'		=> "ingreso",
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondiciones'),
			'metodoPago'			=> $this->input->post('txtMetodoPagoTexto').' '.$this->input->post('txtCuentaPago'),
			'formaPago'				=> $this->input->post('txtFormaPago'),
			'parcial'				=> 0,
			'observaciones'			=> $this->input->post('txtNotas'),
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,
			'manual'				=> 1,
			'idUsuario'				=> $this->_iduser,
			'idEmisor'				=> $configuracion->idEmisor,
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
				$data=array
				(
					'idFactura'				=>$idFactura,
					'idProducto'			=>0,
					'nombre'				=>$this->input->post('txtConceptoFactura'.$i),
					'unidad'				=>$this->input->post('txtUnidadFactura'.$i),
					'precio'				=>$this->input->post('txtPrecioFactura'.$i),
					'importe'				=>$this->input->post('txtImporteFactura'.$i),
					'cantidad'				=>$this->input->post('txtCantidadFactura'.$i),
					'descuento'				=>0,
					'descuentoPorcentaje'	=>0,
				);
				
				$this->db->insert('facturas_detalles',$data);
			}
		}
		
		#-------------------------------------------------------------------------------------#

	}
	
}
?>

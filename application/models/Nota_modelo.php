<?php
class Nota_modelo extends CI_Model 
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
        #$this->config->load('datatables', TRUE);
        #$this->_table = $this->config->item('datatables');

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
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 10 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}

	#------------------------------------------------------------------------------------------------------#
	#-----------------------------------------NOTAS DE CRÉDITO --------------------------------------------#
	#------------------------------------------------------------------------------------------------------#
	
	public function obtenerProductosNota($idDevolucion)
	{
		$sql=" select a.cantidad, a.importe, b.precio, b.nombre, 
		c.codigoInterno, c.unidad, b.idProducto, a.descuento,b.descuentoPorcentaje
		from cotizaciones_devoluciones_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join productos as c
		on c.idProducto=b.idProduct
		where a.idDevolucion='$idDevolucion' ";
		
		return $this->db->query($sql)->result();
	}

	public function registrarNota($idDevolucion,$devolucion,$tipo,$orden)
	{
		if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$this->load->helper('sat');
		
		$idCotizacion			= $this->input->post('txtIdCotizacion');
		$idEmisor				= $this->input->post('selectEmisores');
		$configuracion			= $this->facturacion->obtenerEmisor($idEmisor);
		$cliente				= $this->facturacion->obtenerCliente($this->input->post('txtIdCliente'));
		$divisa					= $this->facturacion->obtenerDivisa($this->input->post('selectDivisas'));

		$data					= array();
		
		$productos				= $this->obtenerProductosNota($idDevolucion); 
		
		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->pais) <3 )
		{
			$data[0]	="0";
			$data[1]	="El cliente no tiene los datos fiscales necesarios para crear la factura";
			
			return $data;
		}
		
		$folio	= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$data[0]	="0";
			$data[1]	="Sin folios suficientes para crear el comprobante";
			
			return $data;
		}
		
		#$carpetaFel			= carpetaCfdi;
		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				="";
		$certificado		="";

		$ficheroXML	= xmlNotaCredito($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal_3_2.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt'); #Comentado mejor quitarlo jaja
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha1 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= xmlNotaCredito($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$divisa,$idDevolucion,$productos);
		}
	
		if ($this->resultado!="1")
		{
			$data[0]	= "0";
			$data[1]	= $this->resultado;
		}
		else
		{
			$data[0]	= "1";
			$data[1]	= 'La factura se ha creado correctamente';
			$data[2]	= $this->idFactura;
			
			$this->configuracion->registrarBitacora('Registrar nota de crédito por devolución','Ventas',$tipo.', Orden: '.$orden.', Devolución: '.$devolucion.', Folio nota: '.$configuracion->serie.$folio); //Registrar bitácora
		}
		
		return $data;
	}

	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$divisa,$idDevolucion,$productos)
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
			
			$this->resultado	= $respuesta['mensaje'];
			return 0;
		}
		
		if($respuesta['estatus'])
		{
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

			$this->agregarNota($data,$cliente,$configuracion,$divisa,$idDevolucion,$productos);
		}
	}

	public function agregarNota($timbre,$cliente,$configuracion,$divisa,$idDevolucion,$productos)
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
			'subTotal'				=> $this->input->post('txtSubTotal'),
			'iva'					=> $this->input->post('txtIva'),
			'ivaPorcentaje'			=> $this->input->post('txtIvaPorcentaje'),
			'descuento'				=> $this->input->post('txtDescuentoNota'),
			'descuentoPorcentaje'	=> $this->input->post('txtDescuentoPorcentaje'),
			'total'					=> $this->input->post('txtTotalNota'),
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
			'idCotizacion'			=> $this->input->post('txtIdCotizacion'),
			'idCliente'				=> $cliente->idCliente,
			'documento'				=> 'Nota de crédito',
			'tipoComprobante'		=> 'egreso',
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondiciones'),
			'metodoPago'			=> $this->input->post('txtMetodoPagoTexto'),
			'formaPago'				=> $this->input->post('txtFormaPago'),
			'parcial'				=> 0,
			'observaciones'			=> $this->input->post('txtObservaciones'),
			
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,
			'idEmisor'				=> $this->input->post('selectEmisores'),
		);
		
		$this->db->insert('facturas',$data);
		$idFactura 			= $this->db->insert_id();
		$this->idFactura	= $idFactura;
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS Y ASOCIAR LA FACTURA PARCIAL EN CASO DE SER NECESARIO
		#-------------------------------------------------------------------------------------#
		
		$data=array
		(
			'idCotizacion'		=> $this->input->post('txtIdCotizacion'),
			'idFactura'			=> $idFactura,
			'porcentaje'		=> 100,
		);
		
		$this->db->insert('rel_factura_cotizacion',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS

		foreach($productos as $row)
		{
			$data=array
			(
				'idFactura'				=> $idFactura,
				'idProducto'			=> $row->idProducto,
				'nombre'				=> $row->nombre,
				'unidad'				=> $row->unidad,
				'precio'				=> $row->precio,
				'importe'				=> $row->importe,
				'cantidad'				=> $row->cantidad,
				'codigoInterno'			=> $row->codigoInterno,
				
				'descuento'				=> $row->descuento,
				'descuentoPorcentaje'	=> $row->descuentoPorcentaje,
			);
			
			$this->db->insert('facturas_detalles',$data);
		}
		
		//ASIGNAR NOTA A DEVOLUCIÓN
		$this->db->where('idDevolucion',$idDevolucion);
		$this->db->update('cotizaciones_devoluciones',array('idFactura'=>$idFactura));
	}
}
?>

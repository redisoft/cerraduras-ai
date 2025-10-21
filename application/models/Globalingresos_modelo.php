<?php
class Globalingresos_modelo extends CI_Model 
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
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 10 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}

	#------------------------------------------------------------------------------------------------------#
	#-----------------------------------------FACTURAS INGRESOS--------------------------------------------#
	#------------------------------------------------------------------------------------------------------#

	public function sumarIngresosGlobales($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,$idIngreso,$criterio=0)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total,
		coalesce(sum(a.subTotal),0) as subTotal,
		coalesce(sum(a.ivaTotal),0) as ivaTotal
		from catalogos_ingresos as a
		where a.idForma!='4'
		
		and a.idLicencia='$this->idLicencia'
		
		and a.idTraspaso=0
		and (select count(b.idFactura) from facturas as b
		inner join facturas_ingresos as c
		on b.idFactura=c.idFactura
		where a.idIngreso=c.idIngreso
		and b.cancelada='0' ) = 0 ";
		 
		 #and idTraspaso=0 
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';

		return $this->db->query($sql)->row();
	}
	
	
	public function obtenerIngresosGlobales($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,$idIngreso,$criterio=0)
	{
		$sql ="	select a.idIngreso, a.subTotal, a.ivaTotal, a.pago
		from catalogos_ingresos as a
		where a.idForma!='4'
		
		and a.idLicencia='$this->idLicencia'
		
		and a.idTraspaso=0
		
		and (select count(b.idFactura) from facturas as b
		inner join facturas_ingresos as c
		on b.idFactura=c.idFactura
		where a.idIngreso=c.idIngreso
		and b.cancelada='0' ) = 0  ";
		
		#and a.idFactura=0
		 
		 #and idTraspaso=0 
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNumeroIva0($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,$idIngreso,$criterio=0)
	{
		$sql ="	select count(a.idIngreso) as numero
		from catalogos_ingresos as a
		where a.idForma!='4'
		
		and a.idLicencia='$this->idLicencia'
		
		and a.ivaTotal=0
		
		and a.idTraspaso=0
		
		and (select count(b.idFactura) from facturas as b
		inner join facturas_ingresos as c
		on b.idFactura=c.idFactura
		where a.idIngreso=c.idIngreso
		and b.cancelada='0' ) = 0 ";
		 
		 #and idFactura=0 
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerNumeroIva16($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,$idIngreso,$criterio=0)
	{
		$sql ="	select count(a.idIngreso) as numero
		from catalogos_ingresos as a
		where a.idForma!='4'
		
		and a.idLicencia='$this->idLicencia'
		
		and a.ivaTotal>0 
		and a.idTraspaso=0
		and (select count(b.idFactura) from facturas as b
		inner join facturas_ingresos as c
		on b.idFactura=c.idFactura
		where a.idIngreso=c.idIngreso
		and b.cancelada='0' ) = 0 ";
		 
		 #and idTraspaso=0 
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';

		return $this->db->query($sql)->row()->numero;
	}
	
	public function registrarGlobalIngresos()
	{
		if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$this->load->helper('sat');
		
		#$idIngreso				= $this->input->post('txtIdIngreso');
		$idEmisor				= $this->input->post('selectEmisores');
		$configuracion			= $this->configuracion->obtenerEmisor($idEmisor);
		$cliente				= $this->facturacion->obtenerCliente($this->input->post('txtIdCliente'));
		$divisa					= $this->facturacion->obtenerDivisa($this->input->post('selectDivisas'));

		$data					= array();

		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 )
		{
			$data[0]	="0";
			$data[1]	="El cliente no tiene los datos fiscales necesarios para crear la factura";
			
			return $data;
		}
		
		$folio	= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$data[0]	="0";
			$data[1]	="Error de servidor SAT";
			
			return $data;
		}
		
		#$carpetaFel			= carpetaCfdi;
		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				="";
		$certificado		="";

		$ficheroXML	= xmlIngreso($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
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

		$ficheroXML		= xmlIngreso($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$divisa);
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$divisa);
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
			
			$this->configuracion->registrarBitacora('Registrar factura por ingreso','Contabilidad - Ingresos','Folio: '.$configuracion->serie.$folio); //Registrar bitácora
		}
		
		return $data;
	}

	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$divisa)
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
			#$this->resultado	= 'Error de servidor SAT';
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

			$this->agregarFacturaIngreso($data,$cliente,$configuracion,$divisa);
		}
	}

	public function agregarFacturaIngreso($timbre,$cliente,$configuracion,$divisa)
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
			/*'descuento'				=> $this->input->post('txtDescuentoNota'),
			'descuentoPorcentaje'	=> $this->input->post('txtDescuentoPorcentaje'),*/
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
			#'idIngreso'				=> $this->input->post('txtIdCotizacion'),
			'idCliente'				=> $cliente->idCliente,
			'documento'				=> 'Factura',
			'tipoComprobante'		=> 'ingreso',
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondiciones'),
			
			'parcial'				=> 0,
			'observaciones'			=> $this->input->post('txtObservaciones'),
			
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,
			'idEmisor'				=> $this->input->post('selectEmisores'),
			
			'metodoPago'			=> $this->input->post('metodoPagoTexto'),
			'formaPago'				=> trim($this->input->post('formaPagoTexto').' '.$this->input->post('txtCuentaPago')),
			'usoCfdi'				=> $this->input->post('usoCfdiTexto'),
			'globaIngresos'			=> '1',
		);
		
		$this->db->insert('facturas',$data);
		$idFactura 			= $this->db->insert_id();
		$this->idFactura	= $idFactura;
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);

		#GUARDAR EL DETALLE DE PRODUCTOS

		$data=array
		(
			'idFactura'				=> $idFactura,
			'idProducto'			=> 0,
			'nombre'				=> $this->input->post('txtConcepto'),
			'precio'				=> $this->input->post('txtSubTotal'),
			'importe'				=> $this->input->post('txtSubTotal'),
			'cantidad'				=> 1,
			'codigoInterno'			=> '',
			
			'descuento'				=> 0,
			'descuentoPorcentaje'	=> 0,
			
			'unidad'				=> $this->input->post('txtUnidadDescripcion'),
			'claveUnidad'			=> $this->input->post('txtClaveUnidad'),
			'claveProducto'			=> $this->input->post('txtClaveProducto'),
			'claveDescripcion'		=> $this->input->post('txtClaveDescripcion'),
		);
		
		$this->db->insert('facturas_detalles',$data);
		$idDetalle	= $this->db->insert_id();
		
		
		$data=array
		(
			'idDetalle'			=> $idDetalle,
			'importe'			=> $this->input->post('txtIva'),
			'tasa'				=> $this->input->post('txtIvaPorcentaje'),
			'tipoFactor'		=> 'Cuota',
			'impuesto'			=> '002',
			'base'				=> $this->input->post('txtSubTotal'),
			'nombreImpuesto'	=> 'IVA',
		);
		
		$this->db->insert('facturas_detalles_impuestos',$data);
		
		
		$this->asignarIngresosFactura($idFactura);
		
		//ASIGNAR NOTA A DEVOLUCIÓN
		/*$this->db->where('idIngreso',$this->input->post('txtIdIngreso'));
		$this->db->update('catalogos_ingresos',array('idFactura'=>$idFactura));
*/	}
	
	public function asignarIngresosFactura($idFactura)
	{
		$inicio						= $this->input->post('txtFechaInicial');
		$fin						= $this->input->post('txtFechaFinal');
		$idCuenta					= $this->input->post('selectCuentas');
		
		$idDepartamento				= $this->input->post('selectDepartamentos');
		$idProducto					= $this->input->post('selectProductos');
		$idGasto					= $this->input->post('selectGastos');
		$idCliente					= $this->input->post('txtIdClienteBusqueda');
		$idIngreso					= $this->input->post('idIngreso');
		$criterio					= $this->input->post('selectCriterio');
		
		$idDepartamento				= strlen($idDepartamento)==0?0:$idDepartamento;
		$idProducto					= strlen($idProducto)==0?0:$idProducto;
		$idGasto					= strlen($idGasto)==0?0:$idGasto;
		
		$ingresos					= $this->obtenerIngresosGlobales($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idCliente,0,$criterio);
		
		foreach($ingresos as $row)
		{
			#$this->db->where('idIngreso',$row->idIngreso);
			#$this->db->update('catalogos_ingresos',array('idFactura'=>$idFactura));
			$this->db->insert('facturas_ingresos',array('idFactura'=>$idFactura,'idIngreso'=>$row->idIngreso));
		}
	}
}
?>

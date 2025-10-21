<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contabilidad extends CI_Controller 
{
	 protected $_jss;
	 protected $_csstyle;
	 protected $cuota;
	  
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('contabilidad_modelo','contabilidad');
		$this->load->model('modelo_configuracion','configuracion');
		$this->load->model('modelousuario','modelousuario');
		$this->load->model('cuentas_modelo','cuentas');
		#$this->load->model('facturacion_modelo','facturacion');
		
		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$this->_fechaActual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				= $this->config->item('js');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	#CATÁLOGO DE CUENTAS

	public function index()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$Data['menuActivo']		= 'catalogoCuentas'; 
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);

		$data['tiposCuenta']	= $this->cuentas->obtenerTiposCuentas();
		$data["breadcumb"]		= 'Catálogo de cuentas';	
		
		$this->load->view("contabilidad/catalogo/index",$data);
		$this->load->view("pie", $Data);
	}
	
	public function obtenerCatalogo($limite=0)
	{
		$inicio	= $this->input->post('inicio');
		$fin	= $this->input->post('fin');
		
		#----------------------------------PAGINACIÓN------------------------------------#
		$paginas["base_url"]	= base_url()."contabilidad/obtenerCatalogo/";
		$paginas["total_rows"]	= $this->contabilidad->contarCatalogo($inicio,$fin);
		$paginas["per_page"]	= 20;
		$paginas["num_links"]	= 5;
		$paginas["uri_segment"]	= 3;
		
		$this->pagination->initialize($paginas);
		#--------------------------------------------------------------------------------#
		
		$data['catalogo']	    = $this->contabilidad->obtenerCatalogo($paginas["per_page"],$limite,$inicio,$fin);	
		$data['limite']		    = $limite+1;
		
		$this->load->view('contabilidad/catalogo/obtenerCatalogo',$data);
	}

	public function formularioCatalogo()
	{
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		
		$this->load->view("contabilidad/catalogo/formularioCatalogo",$data);
	}
	
	public function registrarCatalogo()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarCatalogo($this->input->post('chkCopiar')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCatalogoEditar()
	{
		$data['catalogo']	= $this->contabilidad->obtenerCatalogoEditar($this->input->post('idCatalogo'));
		
		$this->load->view("contabilidad/catalogo/obtenerCatalogoEditar",$data);
	}
	
	public function editarCatalogo()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->editarCatalogo());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarCatalogo()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarCatalogo($this->input->post('idCatalogo')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerSubCuentas()
	{
		$data['subcuentas']	= $this->contabilidad->obtenerSubCuentas($this->input->post('idCuenta'));
		
		$this->load->view("contabilidad/catalogo/obtenerSubCuentas",$data);
	}
	
	public function cuentasCatalogo()
	{
		$data['catalogo']	= $this->contabilidad->obtenerCatalogoEditar($this->input->post('idCatalogo'));
		#$data['cuentas']	= $this->contabilidad->obtenerCuentasCatalogo($this->input->post('idCatalogo'));
		
		$this->load->view("contabilidad/catalogo/cuentasCatalogo",$data);
	}
	
	public function obtenerCuentasCatalogo($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$tipo					= $this->input->post('tipo');
		$inicio					= $this->input->post('inicio').'-01';
		$fin					= $this->input->post('fin').'-01';
		
		#----------------------------------PAGINACIÓN------------------------------------#
		$paginas["base_url"]	= base_url()."contabilidad/obtenerCuentasCatalogo/";
		$paginas["total_rows"]	= $this->contabilidad->contarCuentasCatalogo($criterio,$tipo,$inicio,$fin);
		$paginas["per_page"]	= 20;
		$paginas["num_links"]	= 5;
		$paginas["uri_segment"]	= 3;
		
		$this->pagination->initialize($paginas);
		#--------------------------------------------------------------------------------#
		
		$data['cuentas']	    = $this->contabilidad->obtenerCuentasCatalogo($paginas["per_page"],$limite,$criterio,$tipo,$inicio,$fin);	
		$data['limite']		    = $limite+1;

		$this->load->view('contabilidad/catalogo/obtenerCuentasCatalogo',$data);
	}
	
	public function obtenerCuentasCatalogoDetalle($idCuentaCatalogo,$numero)
	{
		$data['cuentas']	   	 	= $this->contabilidad->obtenerCuentasCatalogoDetalle($idCuentaCatalogo);	
		$data['idCuentaCatalogo']	= $idCuentaCatalogo;
		$data['numero']				= $numero;

		$this->load->view('contabilidad/catalogo/obtenerCuentasCatalogoDetalle',$data);
	}
	
	//ASOCIAR CUENTAS CON CATÁLOGOS
	
	public function obtenerCuentasCatalogoAsociar()
	{
		$grupo				= $this->input->post('grupo');
		$idCuenta			= $this->input->post('idCuenta');
		$idSubCuenta		= $this->input->post('idSubCuenta');
		
		$data['cuentas']	= $this->contabilidad->obtenerCuentasCatalogoAsociar($grupo,$idCuenta,$idSubCuenta);	
		
		$this->load->view("contabilidad/catalogo/asociarCuenta/obtenerCuentasCatalogoAsociar",$data);
	}
	
	public function obtenerCuentasRegistro()
	{
		$data['cuentas']		= $this->contabilidad->obtenerCuentas($this->input->post('grupo'));
		
		$this->load->view("contabilidad/catalogo/asociarCuenta/obtenerCuentasRegistro",$data);
	}
	
	public function obtenerSubCuentasRegistro()
	{
		$data['subCuentas']		= $this->contabilidad->obtenerSubCuentas($this->input->post('idCuenta'));
		
		$this->load->view("contabilidad/catalogo/asociarCuenta/obtenerSubCuentasRegistro",$data);
	}
	
	public function formularioAsociarCuenta()
	{
		$data['tiposCuenta']	= $this->cuentas->obtenerTiposCuentas();	
		$data['cuentas']		= $this->contabilidad->obtenerCuentas($this->input->post('grupo'));
		$data['subCuentas']		= $this->contabilidad->obtenerSubCuentas(1);
		$data['grupo']			= $this->input->post('grupo');
		
		$this->load->view("contabilidad/catalogo/asociarCuenta/formularioAsociarCuenta",$data);
	}
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function formularioAgregarCuenta()
	{
		$idCuentaCatalogo			= $this->input->post('idCuentaCatalogo');
		$data['cuenta']				= $this->contabilidad->obtenerCuenta($idCuentaCatalogo);
		$data['cuentas']			= $this->contabilidad->obtenerCuentas($this->input->post('cuenta'));
		$data['idCuentaCatalogo']	= $idCuentaCatalogo;
		
		$this->load->view("contabilidad/catalogo/formularioAgregarCuenta",$data);
	}
	
	public function registrarCuenta()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarCuenta());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCuenta()
	{
		$data['cuenta']	= $this->contabilidad->obtenerCuenta($this->input->post('idCuentaCatalogo'));
		
		$this->load->view("contabilidad/catalogo/obtenerCuenta",$data);
	}
	
	public function editarCuenta()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->editarCuenta());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarCuenta()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarCuenta($this->input->post('idCuentaCatalogo')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function xmlCatalogo($criterio=0,$fecha)
	{
		$this->load->helper('catalogo');
		$this->load->helper('download');
		
		#$catalogo		= $this->contabilidad->obtenerCatalogoEditar($idCatalogo);
		$cuentas		= $this->contabilidad->obtenerCuentasCatalogoExportar($fecha.'-01',$fecha.'-01');
		$configuracion	= $this->configuracion->obtenerConfiguraciones(1);
		
		$xml			= xmlCatalogo($configuracion,$cuentas,$fecha);
		#$fichero		= carpetaFacturacion.$configuracion->rfc.'/catalogo/'.$catalogo->rfc.''.substr($catalogo->fecha,0,7).'CT.xml';
		$fichero		= carpetaFacturacion.$configuracion->rfc.'/catalogo/'.$configuracion->rfc.substr($fecha,0,4).substr($fecha,5,2).'CT.xml';
		
		guardarFichero($fichero,$xml);

		if($criterio==0)
		{
			#$descarga		= $catalogo->rfc.'_Catalogo_'.substr($catalogo->fecha,0,7).'.xml';
			$descarga		= $configuracion->rfc.substr($fecha,0,4).substr($fecha,5,2).'CT.xml';
			$data 			= file_get_contents($fichero);
		
			force_download($descarga, $data); 
		}
	}
	
	
	//----------------------------------------------------------------------------------------------------------------//
	//PARA ADMINISTRAR LA BALANZA DE COMPROBACIÓN
	//----------------------------------------------------------------------------------------------------------------//
	public function balanza()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$Data['menuActivo']		= 'balanza'; 
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);

		$data['tiposCuenta']	= $this->cuentas->obtenerTiposCuentas();
		$data["breadcumb"]		= 'Balanza de comprobación';	
		
		$this->load->view("contabilidad/balanza/index",$data);
		$this->load->view("pie", $Data);
		
		
		/*$data['empresa']	=$this->configuracion->obtenerConfiguracion();	
		$data['pagina']		='contabilidad/balanza/index';
		$data['titulo']		='Balanza de comprobación';
		$data['menu']		='contabilidad';
		$data['subMenu']	='balanzaComprobacion';*/
		
		#$this->load->view('principal',$data);
	}
	
	public function obtenerBalanza($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= '';
		$filtro					= $this->input->post('filtro');

		$data['cuentas']	    = $this->contabilidad->obtenerCuentasCatalogoBalanza($criterio,'',$inicio,$fin);	
		$data['limite']		    = $limite+1;
		$data['filtro']		    = $filtro;
		
		$this->load->view('contabilidad/balanza/obtenerBalanza',$data);
	}
	
	public function formularioBalanza()
	{
		$data['configuracion']	= $this->configuracion->obtenerConfiguracion();
		
		$this->load->view("contabilidad/balanza/formularioBalanza",$data);
	}
	
	public function cargarCuentaBalanza()
	{
		$data['cuentas']		= $this->contabilidad->obtenerCatalogoMes($this->input->post('fecha'));	
		$data['i']				= $this->input->post('i');	
		
		$this->load->view('contabilidad/balanza/cargarCuentaBalanza',$data);
	}
	
	public function registrarBalanza()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarBalanza());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerBalanzaEditar()
	{
		$data['balanza']	= $this->contabilidad->obtenerBalanzaEditar($this->input->post('idBalanza'));
		
		$this->load->view("contabilidad/balanza/obtenerBalanzaEditar",$data);
	}
	
	public function editarBalanza()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->editarBalanza());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarBalanza()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarBalanza($this->input->post('idBalanza')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCuentasBalanza()
	{
		$data['balanza']			= $this->contabilidad->obtenerBalanzaEditar($this->input->post('idBalanza'));
		$data['cuentasCatalogo']	= $this->contabilidad->obtenerCatalogoMes($data['balanza']->fecha);	
		$data['cuentas']			= $this->contabilidad->obtenerCuentasBalanza($this->input->post('idBalanza'));
		
		$this->load->view("contabilidad/balanza/obtenerCuentasBalanza",$data);
	}
	
	public function obtenerCuentasBalanzaIva()
	{
		$data['balanza']			= $this->contabilidad->obtenerBalanzaEditar($this->input->post('idBalanza'));
		$data['cuentas']			= $this->contabilidad->obtenerCuentasBalanza($this->input->post('idBalanza'));
		$data['iva']				= $this->configuracion->obtenerConfiguracionIva();	
		$data['deudor']				= $this->contabilidad->obtenerSaldoDeudor($this->input->post('idBalanza'));
		$data['acreedor']			= $this->contabilidad->obtenerSaldoAcreedor($this->input->post('idBalanza'));
		
		$this->load->view("contabilidad/balanza/obtenerCuentasBalanzaIva",$data);
	}
	
	public function guardarBalanzaComprobacion()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->guardarBalanzaComprobacion());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarCuentaBalanza()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarCuentaBalanza($this->input->post('idDetalle')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	/*public function xmlBalanza($idBalanza)
	{
		$this->load->helper('balanza');
		$this->load->helper('download');
		
		$balanza		= $this->contabilidad->obtenerBalanzaEditar($idBalanza);
		$cuentas		= $this->contabilidad->obtenerCuentasBalanza($idBalanza);
		$configuracion	= $this->configuracion->obtenerConfiguracion();
		
		$xml			= xmlBalanza($balanza,$cuentas);
		$fichero		= carpetaFacturacion.$configuracion->rfc.'/balanza/'.$balanza->rfc.'_'.substr($balanza->fecha,0,7).'.xml';
		
		guardarFichero($fichero,$xml);
		$descarga		= $balanza->rfc.'_Balanza_'.substr($balanza->fecha,0,7).'.xml';
		$data 			= file_get_contents($fichero);
		
		force_download($descarga, $data); 
	}*/
	
	public function xmlBalanza($idBalanza)
	{
		$this->load->helper('balanza');
		$this->load->helper('download');
		
		$balanza		= $this->contabilidad->obtenerBalanzaEditar($idBalanza);
		#$cuentas		= $this->contabilidad->obtenerPolizasBalanza($balanza->fecha);
		$cuentas		= $this->contabilidad->obtenerCatalogoMes($balanza->fecha);
		$configuracion	= $this->configuracion->obtenerConfiguracion();
		
		#$xml			= xmlBalanza($balanza,$cuentas);
		
		
		$xml='<?xml version="1.0" encoding="UTF-8"?>';
		#<BCE:Balanza xmlns:BCE="http://www.sat.gob.mx/balanza" Version="" RFC="" TotalCtas="'.count($cuentas).'" Mes="" Ano="">
		
		$xml.="\n".'<BCE:Balanza xmlns:BCE="www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion/BalanzaComprobacion_1_1.xsd" Version="'.$balanza->version.'" RFC="'.$balanza->rfc.'" Mes="'.substr($balanza->fecha,5,2).'" Anio="'.substr($balanza->fecha,0,4).'" TipoEnvio="N">';
		
		foreach($cuentas as $row)
		{
			$inicial	= 0;
			$saldos		= $this->contabilidad->obtenerMovimientosSaldoBalanzaCuenta($balanza->fecha,$row->idCuenta);
			$actual		= $this->contabilidad->obtenerMovimientosBalanzaCuenta($balanza->fecha,$row->idCuenta);
			
			if($row->nivel==2)
			{
				$inicial		= $this->contabilidad->obtenerCuentaNivel($row->nivel,$row->idCuenta);
				$inicial		= $inicial!=null?$inicial->saldoInicial:0;
			}
			
			$saldoInicial		= $inicial+$saldos->debe-$saldos->haber;
			$saldoFinal			= $saldoInicial+$actual->debe-$actual->haber;
			$xml.= "\n".'    <BCE:Ctas NumCta="'.$row->numeroCuenta.'" SaldoIni="'.Sprintf("% 01.2f",$saldoInicial).'" Debe="'.$actual->debe.'" Haber="'.$actual->haber.'" SaldoFin="'.Sprintf("% 01.2f",$saldoFinal).'"/>';  
			
			/*if($saldos!=null)
			{
				$saldoInicial		= $inicial+$saldos->debe-$saldos->haber;
				$saldoFinal			= $saldoInicial+$row->debe-$row->haber;
				$xml.= "\n".'    <BCE:Ctas NumCta="'.$row->numeroCuenta.'" SaldoIni="'.$saldoInicial.'" Debe="'.$row->debe.'" Haber="'.$row->haber.'" SaldoFin="'.$saldoFinal.'" />';
			}
			else
			{
				$saldoInicial		= $inicial;
				$saldoFinal			= $saldoInicial;
				
				$xml.= "\n".'    <BCE:Ctas NumCta="'.$row->numeroCuenta.'" SaldoIni="'.$saldoInicial.'" Debe="'.$row->debe.'" Haber="'.$row->haber.'" SaldoFin="'.$saldoFinal.'" />';
			}*/
		}
		
		$xml.="\n".'</BCE:Balanza>';
		
		
		$fichero		= carpetaFacturacion.$configuracion->rfc.'/balanza/'.$balanza->rfc.'_'.substr($balanza->fecha,0,7).'.xml';
		
		guardarFichero($fichero,$xml);
		$descarga		= $balanza->rfc.str_replace('-','',substr($balanza->fecha,0,7)).'BN.xml';
		$data 			= file_get_contents($fichero);
		
		force_download($descarga, $data); 
	}
	
	/*public function xmlBalanza($idBalanza)
	{
		$this->load->helper('balanza');
		$this->load->helper('download');
		
		$balanza		= $this->contabilidad->obtenerBalanzaEditar($idBalanza);
		$cuentas		= $this->contabilidad->obtenerPolizasBalanza($balanza->fecha);
		$configuracion	= $this->configuracion->obtenerConfiguracion();
		
		#$xml			= xmlBalanza($balanza,$cuentas);
		
		
		$xml='<?xml version="1.0" encoding="UTF-8"?>';
		#<BCE:Balanza xmlns:BCE="http://www.sat.gob.mx/balanza" Version="" RFC="" TotalCtas="'.count($cuentas).'" Mes="" Ano="">
		
		$xml.="\n".'<BCE:Balanza xmlns:BCE="www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion/BalanzaComprobacion_1_1.xsd" Version="'.$balanza->version.'" RFC="'.$balanza->rfc.'" Mes="'.substr($balanza->fecha,5,2).'" Anio="'.substr($balanza->fecha,0,4).'" TipoEnvio="N">';
		
		foreach($cuentas as $row)
		{
			$inicial	= 0;
			$saldos		= $this->contabilidad->obtenerMovimientosBalanzaCuenta($balanza->fecha,$row->idCuentaCatalogo);
			
			if($row->nivel==2)
			{
				$inicial		= $this->contabilidad->obtenerCuentaNivel($row->nivel,$row->idCuenta);
				$inicial		= $inicial!=null?$inicial->saldoInicial:0;
			}
			
			$saldoInicial		= $inicial+$saldos->debe-$saldos->haber;
			$saldoFinal			= $saldoInicial+$row->debe-$row->haber;
			
			#$xml.= "\n".'    <BCE:Ctas NumCta="'.$row->numeroCuenta.'" SaldoIni="'.$row->saldoInicial.'" Debe="'.$row->debe.'" Haber="'.$row->haber.'" SaldoFin="'.$row->saldoFinal.'" />';
			$xml.= "\n".'    <BCE:Ctas NumCta="'.$row->numeroCuenta.'" SaldoIni="'.$saldoInicial.'" Debe="'.$row->debe.'" Haber="'.$row->haber.'" SaldoFin="'.$saldoFinal.'" />';
		}
		
		$xml.="\n".'</BCE:Balanza>';
		
		
		$fichero		= carpetaFacturacion.$configuracion->rfc.'/balanza/'.$balanza->rfc.'_'.substr($balanza->fecha,0,7).'.xml';
		
		guardarFichero($fichero,$xml);
		$descarga		= $balanza->rfc.str_replace('-','',substr($balanza->fecha,0,7)).'BN.xml';
		$data 			= file_get_contents($fichero);
		
		force_download($descarga, $data); 
	}*/
	
	//----------------------------------------------------------------------------------------------------------------//
	//PARA ADMINISTRAR LAS PÓLIZAS
	//----------------------------------------------------------------------------------------------------------------//
	/*public function polizas()
	{
		$data['empresa']	=$this->configuracion->obtenerConfiguracion();	
		$data['pagina']		='contabilidad/polizas/index';
		$data['titulo']		='Pólizas';
		$data['menu']		='contabilidad';
		$data['subMenu']	='polizas';
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		
		$this->load->view('principal',$data);
	}*/
	
	public function polizas()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$Data['menuActivo']		= 'polizas'; 
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);

		#$data['tiposCuenta']	= $this->cuentas->obtenerTiposCuentas();	

		$data["breadcumb"]		= 'Pólizas';
		
		$this->load->view("contabilidad/polizas/index",$data);
		$this->load->view("pie", $Data);
	}
	
	public function obtenerPolizas($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$tipo					= $this->input->post('tipo');
		
		#----------------------------------PAGINACIÓN------------------------------------#
		$paginas["base_url"]	= base_url()."contabilidad/obtenerPolizas/";
		$paginas["total_rows"]	= $this->contabilidad->contarPolizas($inicio,$fin,$tipo);
		$paginas["per_page"]	= 20;
		$paginas["num_links"]	= 5;
		$paginas["uri_segment"]	= 3;
		
		$this->pagination->initialize($paginas);
		#--------------------------------------------------------------------------------#
		
		$data['polizas']	    = $this->contabilidad->obtenerPolizas($paginas["per_page"],$limite,$inicio,$fin,$tipo);	
		$data['limite']		    = $limite+1;
		
		$this->load->view('contabilidad/polizas/obtenerPolizas',$data);
	}
	
	public function obtenerPolizaConcepto()
	{
		$idConcepto				= $this->input->post('idConcepto');
		
		$data['concepto']		= $this->contabilidad->obtenerConcepto($idConcepto);
		$data['transacciones']	= $this->contabilidad->obtenerTransacciones($idConcepto);
		
		$this->load->view("contabilidad/polizas/obtenerPolizaConcepto",$data);
	}
	
	public function verPolizaConcepto()
	{
		$idConcepto				= $this->input->post('idConcepto');
		
		$data['concepto']		= $this->contabilidad->obtenerConcepto($idConcepto);
		$data['transacciones']	= $this->contabilidad->obtenerTransacciones($idConcepto);
		
		$this->load->view("contabilidad/polizas/verPolizaConcepto",$data);
	}
	
	public function cargarPartida()
	{
		$data['par']				= $this->input->post('par');
		
		$this->load->view("contabilidad/polizas/partidas/cargarPartida",$data);
	}
	
	public function formularioPolizas()
	{
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		
		$this->load->view("contabilidad/polizas/formularioPolizas",$data);
	}
	
	public function registrarPoliza()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarPoliza());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function guardarConcepto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->guardarConcepto());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCuentasContablesFiltro($filtro='numeroCuenta')
	{
		$catalogo = $this->cuentas->obtenerCuentasContablesFiltro($this->input->get('term'),$filtro);
		
		if($catalogo!=null)
		{
			foreach ($catalogo as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerPoliza()
	{
		$data['poliza']	= $this->contabilidad->obtenerPoliza($this->input->post('idPoliza'));
		
		$this->load->view("contabilidad/polizas/obtenerPoliza",$data);
	}
	
	public function editarPoliza()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->editarPoliza());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarPoliza()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarPoliza($this->input->post('idPoliza')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//CONCEPTOS DE LA PÓLIZA
	public function conceptosPoliza()
	{
		$data['poliza']	    = $this->contabilidad->obtenerPoliza($this->input->post('idPoliza'));	
		
		$this->load->view('contabilidad/polizas/conceptos/conceptosPoliza',$data);
	}
	
	public function obtenerConceptosPoliza($limite=0)
	{
		$idPoliza				= $this->input->post('idPoliza');
		$tipo					= $this->input->post('tipo');
		
		#----------------------------------PAGINACIÓN------------------------------------#
		$paginas["base_url"]	= base_url()."contabilidad/obtenerConceptosPoliza/";
		$paginas["total_rows"]	= $this->contabilidad->contarConceptosPoliza($idPoliza,$tipo);
		$paginas["per_page"]	= 20;
		$paginas["num_links"]	= 5;
		$paginas["uri_segment"]	= 3;
		
		$this->pagination->initialize($paginas);
		#--------------------------------------------------------------------------------#
		
		$data['conceptos']	    = $this->contabilidad->obtenerConceptosPoliza($paginas["per_page"],$limite,$idPoliza,$tipo);	
		$data['polizas']		= $this->configuracion->obtenerConfiguracionPolizas();
		$data['limite']		    = $limite+1;
		
		$this->load->view('contabilidad/polizas/conceptos/obtenerConceptosPoliza',$data);
	}
	
	public function formularioConceptos()
	{
		$data['cuentas']		= $this->contabilidad->obtenerCatalogoMes($this->input->post('fecha'));	
		$data['bancos']			= $this->contabilidad->obtenerBancos();
		$data['numero']			= $this->contabilidad->obtenerNumeroPoliza(1);
		$data['polizas']		= $this->configuracion->obtenerConfiguracionPolizas();	
		$data['fecha']			= $this->input->post('fecha');	
		
		$this->load->view('contabilidad/polizas/conceptos/formularioConceptos',$data);
	}
	
	public function obtenerTipoPoliza()
	{
		$data['tipoPoliza']		= $this->input->post('tipoPoliza');
		$data['polizas']		= $this->configuracion->obtenerConfiguracionPolizas();
		$data['numero']			= $this->contabilidad->obtenerNumeroPoliza($this->input->post('tipoPoliza'));
		
		$this->load->view('contabilidad/polizas/conceptos/obtenerTipoPoliza',$data);
	}
	
	public function registrarConcepto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarConcepto());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	/*public function obtenerConcepto()
	{
		$data['concepto']	    = $this->contabilidad->obtenerConcepto($this->input->post('idConcepto'));	
		$data['polizas']		= $this->configuracion->obtenerConfiguracionPolizas();
		
		$this->load->view('contabilidad/polizas/conceptos/obtenerConcepto',$data);
	}*/
	
	public function obtenerConcepto()
	{
		$data['concepto']	    = $this->contabilidad->obtenerConcepto($this->input->post('idConcepto'));	
		$data['transacciones']  = $this->contabilidad->obtenerTransacciones($this->input->post('idConcepto'));	
		$data['cheques']  		= $this->contabilidad->obtenerChequesConcepto($this->input->post('idConcepto'));	
		$data['transferencias'] = $this->contabilidad->obtenerTransferenciasConcepto($this->input->post('idConcepto'));	
		$data['grupos'] 	 	= $this->contabilidad->obtenerGruposTransaccion($this->input->post('idConcepto'));	
		$data['metodosPago']	= $this->contabilidad->obtenerMetodosConcepto($this->input->post('idConcepto'));	
		$data['cuentas']		= $this->contabilidad->obtenerCatalogoMes($this->input->post('fecha'));	
		$data['bancos']			= $this->contabilidad->obtenerBancos();	
		$data['polizas']		= $this->configuracion->obtenerConfiguracionPolizas();
		$data['metodos']		= $this->contabilidad->obtenerMetodos();
		$data['monedas']		= $this->contabilidad->obtenerMonedas();	
		
		$this->load->view('contabilidad/polizas/conceptos/obtenerConceptoEditar',$data);
	}
	
	public function editarConcepto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->editarConcepto());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarConcepto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarConcepto($this->input->post('idConcepto')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//TRANSACCIONES DE LA PÓLIZA
	public function obtenerTransacciones()
	{
		$data['concepto']	   	 	= $this->contabilidad->obtenerConcepto($this->input->post('idConcepto'));	
		$data['transacciones']	    = $this->contabilidad->obtenerTransacciones($this->input->post('idConcepto'));	
		$data['cuentasCatalogo']	= $this->contabilidad->obtenerCatalogoMes($data['concepto']->fechaPoliza);	
		
		$this->load->view('contabilidad/polizas/transacciones/obtenerTransacciones',$data);
	}
	
	public function obtenerConceptosTransaccion()
	{
		$data['conceptos']	   	 	= $this->contabilidad->obtenerConceptosTransaccion($this->input->post('idTransaccion'));	
		
		$this->load->view('contabilidad/polizas/transacciones/obtenerConceptosTransaccion',$data);
	}
	
	public function cargarCuentaTransaccion()
	{
		$data['cuentas']		= $this->contabilidad->obtenerCatalogoMes($this->input->post('fecha'));	
		$data['i']				= $this->input->post('i');	
		
		$this->load->view('contabilidad/polizas/transacciones/cargarCuentaTransaccion',$data);
	}
	
	public function registrarTransacciones()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarTransacciones());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarTransaccion()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarTransaccion($this->input->post('idTransaccion')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//CHEQUES
	public function obtenerCheques()
	{
		$data['transaccion']	= $this->contabilidad->obtenerTransaccion($this->input->post('idTransaccion'));	
		$data['bancos']			= $this->contabilidad->obtenerBancos();	
		$data['cheques']	    = $this->contabilidad->obtenerCheques($this->input->post('idTransaccion'));	
		
		$this->load->view('contabilidad/polizas/cheques/obtenerCheques',$data);
	}
	
	public function cargarCheque()
	{
		$data['cuentas']	= $this->contabilidad->obtenerCatalogoMes($this->input->post('fecha'));	
		$data['bancos']		= $this->contabilidad->obtenerBancos();	
		$data['i']			= $this->input->post('i');	
		
		$this->load->view('contabilidad/polizas/cheques/cargarCheque',$data);
	}
	
	public function registrarCheques()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarCheques());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarCheque()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarCheque($this->input->post('idCheque')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//TRANSFERENCIAS
	public function obtenerTransferencias()
	{
		$data['transaccion']	= $this->contabilidad->obtenerTransaccion($this->input->post('idTransaccion'));	
		$data['bancos']			= $this->contabilidad->obtenerBancos();	
		$data['transferencias']	= $this->contabilidad->obtenerTransferencias($this->input->post('idTransaccion'));	
		
		$this->load->view('contabilidad/polizas/transferencias/obtenerTransferencias',$data);
	}
	
	public function cargarTransferencia()
	{
		$data['bancos']		= $this->contabilidad->obtenerBancos();	
		$data['i']			= $this->input->post('i');	
		
		$this->load->view('contabilidad/polizas/transferencias/cargarTransferencia',$data);
	}
	
	public function registrarTransferencias()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarTransferencias());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarTransferencia()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarTransferencia($this->input->post('idTransferencia')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//COMPROBANTES
	public function obtenerComprobantes()
	{
		$data['transaccion']	= $this->contabilidad->obtenerTransaccion($this->input->post('idTransaccion'));	
		$data['comprobantes']	= $this->contabilidad->obtenerComprobantes($this->input->post('idTransaccion'));	
		
		$this->load->view('contabilidad/polizas/comprobantes/obtenerComprobantes',$data);
	}
	
	public function cargarComprobante()
	{
		$data['i']			= $this->input->post('i');	
		
		$this->load->view('contabilidad/polizas/comprobantes/cargarComprobante',$data);
	}
	
	public function registrarComprobantes()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->registrarComprobantes());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarComprobante()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarComprobante($this->input->post('idComprobante')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function xmlPoliza($idPoliza,$criterio=0)
	{
		$this->load->helper('poliza');
		$this->load->helper('download');
		
		$poliza				= $this->contabilidad->obtenerPoliza($idPoliza);
		$conceptos			= $this->contabilidad->obtenerConceptosXml($idPoliza);
		$transacciones		= $this->contabilidad->obtenerTransaccionesXml($idPoliza);
		$cheques			= $this->contabilidad->obtenerChequesXml($idPoliza);
		$transferencias		= $this->contabilidad->obtenerTransferenciasXml($idPoliza);
		$comprobantes		= $this->contabilidad->obtenerComprobantesXml($idPoliza);
		$metodos			= $this->contabilidad->obtenerMetodosXml($idPoliza);
		$configuracion		= $this->configuracion->obtenerConfiguracion();
		
		$xml				= xmlPoliza($poliza,$conceptos,$transacciones,$cheques,$transferencias,$comprobantes,$metodos);
		$fichero			= carpetaFacturacion.$configuracion->rfc.'/polizas/'.$poliza->rfc.obtenerMesContabilidadReporte($poliza->fecha).'PL.xml';
			
		guardarFichero($fichero,$xml);
		
		if($criterio==0)
		{
			$descarga		= $poliza->rfc.str_replace('-','',substr($poliza->fecha,0,7)).'PL.xml';
			$data 			= file_get_contents($fichero);
			
			force_download($descarga, $data); 
		}
	}
	
	//PROCESAR EL XML DE LAS PÓLIZAS
	public function subirXmlsss()
	{
		$this->load->helper('xml');
		
		if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
		{
			$fichero 		= $_SERVER['HTTP_X_FILE_NAME'];
			$tamano 		= $_SERVER['CONTENT_LENGTH'];
		} 
		else 
		{
			echo json_encode(array('0'	=> '0'));
			return;
		}
		
		if (!$tamano > 0) 
		{
			echo json_encode(array('0'	=> '0'));
			return;
		}
		
		file_put_contents(carpetaFacturacion.$fichero,file_get_contents("php://input"));
		chmod(carpetaFacturacion.$fichero, 0777);
		
		echo json_encode(procesarXmlCfdi(carpetaFacturacion.$fichero));
	}
	
	public function subirXml($tipo=0)
	{
		$this->load->helper('xml');
		
		$configuracion	= $this->configuracion->obtenerConfiguracion();
		
		if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
		{
			$fichero 		= $_SERVER['HTTP_X_FILE_NAME'];
			$tamano 		= $_SERVER['CONTENT_LENGTH'];
		} 
		else 
		{
			echo json_encode(array('0'	=> '0'));
			return;
		}
		
		if (!$tamano > 0) 
		{
			echo json_encode(array('0'	=> '0'));
			return;
		}
		
		file_put_contents(carpetaFacturacion.$configuracion->rfc.'/'.$fichero,file_get_contents("php://input"));
		chmod(carpetaFacturacion.$configuracion->rfc.'/'.$fichero, 0777);
		
		echo json_encode(array('0'	=> '1'));
		
		#echo json_encode(procesarXmlCfdi(carpetaFacturacion.$fichero));
	}
	
	public function obtenerXmlSistema($idFactura)
	{
		$factura	= $this->facturacion->obtenerFactura($idFactura);
		$emisor		= $this->configuracion->obtenerConfiguracion();
		$xml 		= $emisor->rfc.'_'.$factura->serie.$factura->folioInterno.'.xml';
		$carpeta	= carpetaFacturacion.$emisor->rfc."/cfdi/folio".$factura->serie.$factura->folioInterno.'/';
		
		if(!file_exists($carpeta))
		{
			crearDirectorio($carpeta);
		}
		
		if(!file_exists($carpeta.$xml))
		{
			guardarFichero($carpeta.$xml,$factura->xml);
		}
		
		return $carpeta.$xml;
	}
	
	public function obtenerDatosXml()
	{
		$this->load->helper('xml');
		
		$idFactura				= $this->input->post('idFactura');
		
		$configuracion			= $this->configuracion->obtenerConfiguracion();
		$data['cuentas']		= $this->contabilidad->obtenerCatalogoMes($this->input->post('fecha'));	
		$data['bancos']			= $this->contabilidad->obtenerBancos();
		$data['metodos']		= $this->contabilidad->obtenerMetodos();	
		$data['monedas']		= $this->contabilidad->obtenerMonedas();	
		$data['xml']			= procesarXmlCfdi($idFactura==0?carpetaFacturacion.$configuracion->rfc.'/'.$this->input->post('xml'):$this->obtenerXmlSistema($idFactura));
		$data['tipoPoliza']		= $this->input->post('tipoPoliza');
		$data['cobrada']		= $this->input->post('cobrada');
		$data['pagada']			= $this->input->post('pagada');
		
		$this->load->view('contabilidad/polizas/conceptos/obtenerDatosXml',$data);
	}

	
	//PARTIDAS
	
	public function obtenerComprobantesConcepto()
	{
		$idConcepto				= $this->input->post('idConcepto');
		$data['idConcepto']		= $idConcepto;
		$data['comprobantes']	= $this->contabilidad->obtenerComprobantesConcepto($idConcepto);
		$data['cuota']			= $this->cuota;
		
		$this->load->view('contabilidad/polizas/comprobantes/obtenerComprobantesConcepto',$data);
	}
	
	public function subirComprobanteConcepto($idConcepto=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xml');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idComprobante	= $this->contabilidad->subirComprobanteConcepto($idConcepto,$_FILES['file']['name'],$_FILES['file']['size']);
				
				if($idComprobante>0)
				{
					move_uploaded_file($archivoTemporal,carpetaXml.$idComprobante.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaXml.$idComprobante.'_'.$_FILES['file']['name']))
					{
						echo "1";
					}
					else
					{
						echo 'El comprobante no ha podido subir correctamente';
					}
				}
				else
				{
					echo 'Error al subir el comprobante';
				}
			} 
			else 
			{
				echo 'No se permiten estos archivos';
			}
		}
	} 

	public function borrarComprobanteConcepto()
	{
		if(!empty($_POST))
		{
			echo $this->contabilidad->borrarComprobanteConcepto($this->input->post('idComprobante'));
		}
		else
		{
			echo "0";
		}
	}
		
	public function descargarComprobanteConcepto($idComprobante)
	{
		$this->load->helper('download');

		$comprobante	= $this->contabilidad->obtenerComprobanteConcepto($idComprobante);
		
		force_download($comprobante->nombre, file_get_contents(carpetaXml.$idComprobante.'_'.$comprobante->nombre)); 
	}
	
	public function borrarPolizaConcepto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->borrarPolizaConcepto($this->input->post('idConcepto')));
			
		}
		else
		{
			echo json_encode(array('0'	=> '0'));
		}
	}
	
	public function cancelarPolizaConcepto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->contabilidad->cancelarPolizaConcepto($this->input->post('idConcepto')));
			
		}
		else
		{
			echo json_encode(array('0'	=> '0'));
		}
	}
}

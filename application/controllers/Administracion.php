<?php
class Administracion extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $_Variables;
	protected $cuota;

    function __construct()
	{
		parent::__construct();
		
		//verificar si el el usuario ha iniciado sesion
		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
		
		 $this->config->load('js',TRUE);
		 $this->config->load('style', TRUE);
		 
        $this->_fechaActual 	=mdate("%Y-%m-%d %H:%i:%s",now());
        $this->_iduser 			=$this->session->userdata('id');
        $this->_csstyle 		=$this->config->item('style');
  	    $this->_jss				=$this->config->item('js');
		
		$this->load->model("modelousuario","usuarios");
   	    $this->load->model("modeloclientes","clientes");
		$this->load->model("produccion_modelo","produccion");
		$this->load->model("materiales_modelo","materiales");
	    $this->load->model("proveedores_model","proveedores");
		$this->load->model("inventario_model","inventario");
        $this->load->model("bancos_model","bancos");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("administracion_modelo","administracion");
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("sie_modelo","sie");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
    }
	
	public function reporteBancos()
	{
		$this->load->view('administracion/reporteBancos');
	}
	
	#public function buscarPersonal($idPersonal)
	#{
		
	#}
	
	public function recursosHumanos($idPersonal=0,$limite=0)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente']; 
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['Jqui']			=$this->_jss['jqueryui'];   
		#$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='recursosHumanos';   
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']=$this->configuracion->obtenerPermisosBoton('55',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
				
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."administracion/recursosHumanos/".$idPersonal.'/';
		$registros	= $this->administracion->contarPersonal($idPersonal);
		$numero		= 25;
		$links		= 5;
		$uri		= 4;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		$data['personal']		= $this->administracion->obtenerPersonal($idPersonal,$numero,$limite);
		$data["breadcumb"]		= 'Recursos humanos';
		$data['inicio']  		= $limite+1;
		$data['idPersonal']  	= $idPersonal;
		$data['registros']  	= $registros;

		$this->load->view("recursos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function formularioPersonal()
	{
		$data['tiposDocumentos']		= $this->catalogos->obtenerTiposDocumentos();
		$data['estatus']				= $this->administracion->obtenerEstatus();
		$data['idPersonal']				= rand(1000000,10000000);
		
		//BORRAR DOCUMENTOS TEMPORALES QUE NO SE GUARDARON
		$temporal		= $this->administracion->verificarDocumentosTemporal();
		
		if($temporal>0)
		{
			$this->administracion->borrarDocumentosTemporales();
		}
		
		$this->load->view('recursos/formularioPersonal',$data);
	}
	
	public function obtenerPersonal()
	{
		$idPersonal						= $this->input->post('idPersonal');
		$data['personal']				= $this->administracion->obtenerRegistroPersonal($idPersonal);
		$data['departamentos']			= $this->administracion->obtenerDepartamentos();
		$data['puestos']				= $this->administracion->obtenerPuestos();
		$data['tiposDocumentos']		= $this->catalogos->obtenerTiposDocumentos();
		$data['estatus']				= $this->administracion->obtenerEstatus();
		$data['idPersonal']				= $idPersonal;
				
		$this->load->view('recursos/obtenerPersonal',$data);
	}
	
	public function borrarPersonal()
	{
	  $personal = $this->administracion->borrarPersonal($this->input->post('idPersonal'));
		
		$personal==1?
			$this->session->set_userdata('notificacion','El personal se ha borrar correctamente'):
			$this->session->set_userdata('errorNotificacion','Error al borrar al personal');
			
		#redirect('administracion/recursosHumanos');
		
		echo json_encode(array('1'));
	}
	
	public function agregarPersonal()
	{
		if(!empty($_POST))
		{
			$personal=$this->administracion->agregarPersonal();
			
			$personal==1?
				$this->session->set_userdata('notificacion','El personal se ha agregado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al agregar al personal');
				
			redirect('administracion/recursosHumanos');
		}
	}
	
	public function editarPersonal()
	{
		if(!empty($_POST))
		{
			$personal=$this->administracion->editarPersonal();
			
			$personal==1?
				$this->session->set_userdata('notificacion','El personal se ha editado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al editar al personal, el registro no tuvo cambios');
				
			redirect('administracion/recursosHumanos');
		}
	}
	
	public function obtenerDepartamentos()
	{
		$departamentos=$this->administracion->obtenerDepartamentos();
		
		echo'
		<select class="cajas" id="selectDepartamentos" name="selectDepartamentos" style="width:290px">
			<option value="0">Seleccione</option>';
			
			foreach($departamentos as $row)
			{
				echo'<option value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';	
			}
			
		echo'</select>';
	}
	
	public function formularioDepartamentos()
	{
		echo'
		<div class="ui-state-error" ></div>
		<table class="admintable" width="100%">
			<tr>
				<td class="key">Nombre</td>
				<td>
					<input type="text" style="width:280px" class="cajas" id="txtNombreDepartamento" />
				</td>
			</tr>
		</table>';
	}
	
	public function agregarDepartamento()
	{
		if(!empty($_POST))
		{
			$departamento=$this->administracion->agregarDepartamento();
			echo $departamento;
		}
	}
	
	public function obtenerPuestos()
	{
		$puestos=$this->administracion->obtenerPuestos();
		
		echo'
		<select class="cajas" id="selectPuestos" name="selectPuestos" style="width:290px">
			<option value="0">Seleccione</option>';
			
			foreach($puestos as $row)
			{
				echo'<option value="'.$row->idPuesto.'">'.$row->nombre.'</option>';	
			}
			
		echo'</select>';
	}
	
	public function formularioPuestos()
	{
		echo'
		<div class="ui-state-error" ></div>
		<table class="admintable" width="100%">
			<tr>
				<td class="key">Nombre</td>
				<td>
					<input type="text" style="width:280px" class="cajas" id="txtNombrePuesto" />
				</td>
			</tr>
		</table>';
	}
	
	public function agregarPuesto()
	{
		if(!empty($_POST))
		{
			$puesto=$this->administracion->agregarPuesto();
			echo $puesto;
		}
	}
	
	//ESTATUS DE EMPLEADOS
	public function obtenerEstatus()
	{
		$data['estatus']	= $this->administracion->obtenerEstatus();
		
		$this->load->view('recursos/estatus/obtenerEstatus',$data);
	}
	
	public function formularioEstatus()
	{
		$this->load->view('recursos/estatus/formularioEstatus');
	}
	
	public function registrarEstatus()
	{
		if(!empty($_POST))
		{
			echo $this->administracion->registrarEstatus();
		}
		else
		{
			echo "0";
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARA LOS COMPROBANTES
	public function obtenerComprobantes()
	{
		$idIngreso			= $this->input->post('idIngreso');
		$data['ficheros']	= $this->administracion->obtenerComprobantes($idIngreso);
		$data['ficheroXml']	= $this->administracion->obtenerComprobanteIngresoXml($idIngreso);
		$data['idIngreso']	= $idIngreso;
		$data['cuota']		= $this->cuota;
		
		$this->load->view('administracion/ingresos/obtenerComprobantes',$data);
	}
	
	public function borrarComprobante()
	{
		if(!empty($_POST))
		{
			echo $this->administracion->borrarComprobante($this->input->post('idComprobante'));
		}
		else
		{
			echo "0";
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARA LOS COMPROBANTES DE EGRESOS
	public function borrarComprobanteEgreso()
	{
		if(!empty($_POST))
		{
			$idComprobante		=$this->input->post('idComprobante');
			$comprobante		=$this->administracion->borrarComprobanteEgreso($idComprobante);
			
			echo $comprobante;
		}
	}
	
	public function obtenerComprobantesEgresos()
	{
		$idEgreso			= $this->input->post('idEgreso');
		$data['ficheros']	= $this->administracion->obtenerComprobantesEgresos($idEgreso);
		$data['ficheroXml']	= $this->administracion->obtenerComprobanteEgresoXml($idEgreso);
		$data['idEgreso']	= $idEgreso;
		$data['cuota']		= $this->cuota;
		
		$this->load->view('administracion/egresos/obtenerComprobantesEgresos',$data);
	}
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function poliza($idEgreso)
	{
		$this->load->library('mpdf/mpdf');
		$this->load->library('ccantidadletras');
		
		$data['egreso']		=$this->administracion->obtenerEgresoEditar($idEgreso);
		$data['nombre']		=$this->configuracion->obtenerNombre($data['egreso']->idNombre);
		$data['reporte']	='reportes/poliza/poliza';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['egreso']->pago);
		$this->ccantidadletras->setMoneda("pesos");
		$data['cantidadLetras']	=$this->ccantidadletras->PrimeraMayuscula();
		
		$html	=$this->load->view('reportes/poliza/principal',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',1,1,10,10,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function recibo($idEgreso)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');

		$data['egreso']		=$this->administracion->obtenerEgresoEditar($idEgreso);
		$data['reporte'] 	= 'reportes/recibo/recibo';

		$html=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,10,47,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function sugerirPrecios()
	{
		$iva	=$this->session->userdata('iva');
		$precioA=$this->input->post('precioA');
		$precioB=$this->input->post('precioB');
		$precioC=$this->input->post('precioC');
		$precioD=$this->input->post('precioD');
		$precioE=$this->input->post('precioE');
		
		$iva=$iva/100;
		
		$precioA	=$precioA+($precioA*$iva);
		$precioB	=$precioB+($precioB*$iva);
		$precioC	=$precioC+($precioC*$iva);
		$precioD	=$precioD+($precioD*$iva);
		$precioE	=$precioE+($precioE*$iva);
		
		echo '
		<select class="cajas" id="txtImporte" name="txtImporte" style="width:120px">
			<option value="'.$precioA.'">$'.number_format($precioA,2).'</option>
			<option value="'.$precioB.'">$'.number_format($precioB,2).'</option>
			<option value="'.$precioC.'">$'.number_format($precioC,2).'</option>
			<option value="'.$precioD.'">$'.number_format($precioD,2).'</option>
			<option value="'.$precioE.'">$'.number_format($precioE,2).'</option>
		</select>';
	}
	
	public function sugerirPrecioMaterial()
	{
		$precio	=$this->input->post('precio');
		
		echo '<input type="text" class="cajas" id="txtImporte" value="'.number_format($precio,2).'" />';
	}
	
	//EXCEL INGRESOS
	public function excelIngresos()
	{
		$this->load->library('excel/PHPExcel');
		
		$data['ingresos']	=$this->administracion->obtenerOtrosIngresos(0,0);
		
		$this->load->view('administracion/ingresos/excelIngresos',$data);
	}
	
	//EXCEL GASTOS
	public function excelEgresos()
	{
		$this->load->library('excel/PHPExcel');
		
		$data['egresos']	=$this->administracion->obtenerOtrosEgresos(0,0);
		
		$this->load->view('administracion/egresos/excelEgresos',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//HORARIOS DE PERSONAL
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerHorarios()
	{
		$idPersonal				= $this->input->post('idPersonal');
		$data['horarios']		= $this->administracion->obtenerHorarios($idPersonal);
		$data['personal']		= $this->administracion->obtenerRegistroPersonal($idPersonal);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('55',$this->session->userdata('rol'));
		
		$this->load->view("recursos/horarios/obtenerHorarios",$data);
	}
	
	public function registrarHorario()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->administracion->registrarHorario());
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function editarHorario()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->administracion->editarHorario());
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarHorario()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->administracion->borrarHorario($this->input->post('idHorario')));
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//CHECADOR
	
	public function checador()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'checador';   
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);

		#$data['personal']		= $this->administracion->obtenerPersonal($idPersonal,$numero,$limite);
		$data['inicio']  		= 1;

		$this->load->view("recursos/checador",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerInformacionPersonal()
	{
		$numeroEmpleado			= $this->input->post('numeroEmpleado');	
		
		$data['personal']		= $this->administracion->obtenerRegistroPersonalChequeo($numeroEmpleado);
		$data['chequeo']		= $this->administracion->obtenerRegistroChequeo($data['personal']!=null?$data['personal']->idPersonal:0);
		$data['horario']		= $this->administracion->obtenerHorarioDia($data['personal']!=null?$data['personal']->idPersonal:0);
		
		$this->load->view("recursos/obtenerInformacionPersonal",$data);
	}
	
	public function registrarChequeo()
	{	
		if(!empty($_POST))
		{
			echo $this->administracion->registrarChequeo();	
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerAsistencias()
	{
		$data['asistencias']	=$this->administracion->obtenerAsistenciasHoy();
		
		$this->load->view("recursos/obtenerAsistencias",$data);
	}
	
	//TARJETA DEL PERSONAL
	
	public function generarTarjeta($idPersonal=0)
	{
		$data['personal'] 		= $this->administracion->obtenerRegistroPersonal($idPersonal);
		
		$this->load->view('recursos/generarTarjeta',$data);
	}
	
	public function guardarImagenTarjeta()
	{
		file_put_contents("img/personal/".$this->input->post('numeroEmpleado').".bmp",file_get_contents($this->input->post('tarjeta')));
	}
	
	public function tarjetaPersonal($idPersonal=0)
	{
		set_time_limit(0); 
		ini_set("memory_limit","1500M");

		$this->load->library('mpdf/mpdf');

		$data['personal'] 		= $this->administracion->obtenerRegistroPersonal($idPersonal);
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['reporte']		= 'recursos/tarjetaPersonal';

		$html	=$this->load->view('reportes/principal',$data,true);

		$this->mpdf->mPDF('en-x','A7-L','','',8,0,8,0,0,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins 		= 1;
		$this->mpdf->useSubstitutions	= true; 
		$this->mpdf->simpleTables 		= true;
		$this->mpdf->packTableData 		= true;
		#$this->cacheTables 				= true;
		#$this->packTableData 			= true;    // required for cacheTables
		#$this->simpleTables 			= false;  // Cannot co-exist with cacheTables
		
		$this->mpdf->WriteHTML($html);
		#$this->mpdf->Output('media/ficheros/reporteVentas.pdf','F');
		$this->mpdf->Output();
	}
	
	//DOCUMENTOS DEL PERSONAL
	
	public function subirArchivoPersonal($idTipo=0,$idPersonal=0,$temporal=0)
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			$id					= rand(1000000,10000000);
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml','PDF');
			$archivo 			= pathinfo($_FILES['file']['name']);
			$fichero 			= $id.'_'.$_FILES['file']['name'];

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaPersonal.$fichero);

				if(file_exists(carpetaPersonal.$fichero))
				{
					$idDocumento	= $this->administracion->registrarDocumentoTemporal($idTipo,$archivo['basename'],$_FILES['file']['size'],$id,$idPersonal,$temporal);
					
					echo json_encode(array('1',$fichero,$archivo['extension'],$idDocumento));
				}
				else
				{
					echo json_encode(array('0','Error al mover el archivo, verifique los permisos'));
				}
			} 
			else
			{
				echo json_encode(array('0','No se permiten este tipo de archivos'));
			}
		}
	} 
	
	public function borrarDocumentoTemporal($temporal=1)
	{	
		if(!empty($_POST))
		{
			echo json_encode($this->administracion->borrarDocumentoTemporal($this->input->post('idDocumento'),$temporal));	
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	function descargarDocumentoPersonal($idDocumento) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$documento	= $this->administracion->obtenerDocumentoPersonal($idDocumento);
		$archivo 	= $documento->id.'_'.$documento->nombre;
		$data 		= file_get_contents(carpetaPersonal.$archivo); 
		
		force_download($documento->nombre, $data); 
	}
	
	//ADMINISTRACIÓN EGRESOS
	
	public function formularioOtrosEgresos()
	{
		$data['bancos']			= $this->bancos->obtenerBancos(1);
		$data['periodos']		= $this->configuracion->obtenerPeriodosProduccion();
		$data['formas']			= $this->configuracion->seleccionarFormas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['impuestos']		= $this->configuracion->obtenerImpuestos();
		
		$data['variables1']		= $this->catalogos->obtenerVariablesTipo(1);
		$data['variables2']		= $this->catalogos->obtenerVariablesTipo(2);
		$data['variables3']		= $this->catalogos->obtenerVariablesTipo(3);
		$data['variables4']		= $this->catalogos->obtenerVariablesTipo(4);
		
		$data['nivel1']			= $this->catalogos->obtenerNiveles1();
		
		$this->load->view('administracion/formularioOtrosEgresos',$data);
	}
	
	#EDITAR OTROS EGRESOS
	public function obtenerEgresoEditar()
	{
		$idEgreso					= $this->input->post('idEgreso');
		$data['bancos']				= $this->bancos->obtenerBancos(1);
		$data['egreso']				= $this->administracion->obtenerEgresoEditar($idEgreso);
		
		$data['cuenta']				= $this->bancos->obtenerCuenta($data['egreso']->idCuenta);
		$data['banco']				= $data['cuenta']!=null?$data['cuenta']->idBanco:0;
		$data['cuentas']			= $this->bancos->obtenerCuentasBanco($data['banco']);
		
		$data['departamentos']		= $this->administracion->obtenerDepartamentos(2);
		$data['nombres']			= $this->administracion->obtenerNombres();
		$data['productos']			= $this->administracion->obtenerProductos(2);
		$data['gastos']				= $this->administracion->obtenerTipoGasto(2);
		$data['proveedor']			= $this->proveedores->obtenerProveedor($data['egreso']->idProveedor);
		$data['formas']				= $this->configuracion->seleccionarFormas();
		$data['ivas']				= $this->configuracion->obtenerIvas();
		$data['impuestos']			= $this->configuracion->obtenerImpuestos();
		$data['cuentasContables']	= $this->administracion->obtenerCuentasContablesEgreso($idEgreso);
		
		$data['variables1']			= $this->catalogos->obtenerVariablesTipo(1);
		$data['variables2']			= $this->catalogos->obtenerVariablesTipo(2);
		$data['variables3']			= $this->catalogos->obtenerVariablesTipo(3);
		$data['variables4']			= $this->catalogos->obtenerVariablesTipo(4);
		
		$data['nivel1']				= $this->catalogos->obtenerNiveles1();
		$data['nivel2']				= $this->catalogos->obtenerNiveles2Catalogo($data['egreso']->idNivel1);
		$data['nivel3']				= $this->catalogos->obtenerNiveles3Catalogo($data['egreso']->idNivel2);
		
		$this->load->view('administracion/obtenerEgresoEditar',$data);
	}
	
	//EFECTIVO
	
	public function formularioEfectivo()
	{
		$data['financiera']				= $this->sie->obtenerFinanciera();
		
		$this->load->view('administracion/sie/formularioEfectivo',$data);
	}
	
	public function editarEfectivo()
	{	
		if(!empty($_POST))
		{
			echo json_encode($this->sie->editarEfectivo());	
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function formularioCuentas()
	{
		$data['financiera']				= $this->sie->obtenerFinanciera();
		$data['cuentas']				= $this->configuracion->obtenerCuentasSie();
		
		$this->load->view('administracion/sie/formularioCuentas',$data);
	}
	
	public function editarCuentas()
	{	
		if(!empty($_POST))
		{
			echo json_encode($this->sie->editarCuentas());	
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function formularioNoDisponible()
	{
		$data['financiera']				= $this->sie->obtenerFinanciera();
		
		$this->load->view('administracion/sie/formularioNoDisponible',$data);
	}
	
	public function editarNoDisponible()
	{	
		if(!empty($_POST))
		{
			echo json_encode($this->sie->editarNoDisponible());	
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	//VEHICULOS
	#-----------------------------------------------------------------------------------------------------------------------------------------#
	public function vehiculos()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->_fechaActual;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];                  
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='puestos'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('47',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Vehículos';
		
		$this->load->view("administracion/vehiculos/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function obtenerVehiculos()
	{
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('47',$this->session->userdata('rol'));
		$data['registros']	= $this->administracion->obtenerVehiculos();
		
		$this->load->view("administracion/vehiculos/obtenerRegistros",$data);
	}
	
	public function formularioVehiculos()
	{
		$this->load->view("administracion/vehiculos/formularioRegistro");
	}
	
	public function obtenerVehiculo()
	{
		if (!empty($_POST))
		{
			$data['registro']	=$this->administracion->obtenerVehiculo($this->input->post('idVehiculo'));
			$this->load->view("administracion/vehiculos/obtenerRegistro",$data);
		}
	}
	
	public function registrarVehiculo()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->administracion->registrarVehiculo());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarVehiculo()
	{
		if (!empty($_POST))
		{
			echo $this->administracion->editarVehiculo();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarVehiculo()
	{
		if (!empty($_POST))
		{
			echo $this->administracion->borrarVehiculo($this->input->post('idVehiculo'));
		}
		else
		{
			echo "0";
		}
	}
}
?>

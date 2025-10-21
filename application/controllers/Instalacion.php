<?php
class Instalacion extends CI_Controller
{
	protected $_fechaActual;

	function __construct()
	{
		parent::__construct();

		$this->idTienda		= $this->session->userdata('idTiendaActiva');

        $this->load->model("modelo_configuracion","configuracion");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("estaciones_modelo","estaciones");
		$this->load->model("modelousuario","usuarios");
	}

	public function index($cookie='estacion')
	{
		$configuracion		= $this->configuracion->obtenerConfiguracionCookie();
		
		$idLicencia			= get_cookie('idLicencia'.$configuracion->idCookie); 
		$idEstacion			= get_cookie('idEstacion'.$configuracion->idCookie); 
		
		
		if(strlen($idLicencia)>0 and strlen($idEstacion)>0)
		{
			if($cookie!='estacion')
			{
				redirect('login','refresh');
			}
		}
		
		$data['estilo']		= $this->configuracion->obtenerEstilo();
		$data['licencias']	= $this->configuracion->obtenerLicenciasActivas();
		$data['cookie']		= $cookie;
		
		$this->load->view("instalacion/login",$data);
	}
	
	public function registrarCookieInicial()
	{
		if(!empty($_POST))
		{
			
			$configuracion		= $this->configuracion->obtenerEstilo();
			
			/*delete_cookie('idTienda'); 
			delete_cookie('idEstacion'); 
			
			set_cookie('idTienda','temporal',120); 
			echo json_encode(array('1','Usuario correcto'));
			return;*/
			
			$password	= $this->input->post('txtPassword');
			$usuario	= $this->input->post('txtUsuario');
			
			$registro		= $this->usuarios->obtenerUsuarioLoginRegistro($usuario,$password);
			
			#if($configuracion->passwordTiendas==sha1($this->input->post('txtPassword')) and $configuracion->usuarioTiendas==$this->input->post('txtUsuario'))
			if($registro!=null)
			{
				
				delete_cookie('idLicencia'.$configuracion->idCookie); 
				delete_cookie('idEstacion'.$configuracion->idCookie); 
						
				set_cookie('idLicencia'.$configuracion->idCookie,'temporal',240); 
						
				echo json_encode(array('1','Usuario correcto'));
			}
			else
			{
				echo json_encode(array('0','El nombre de usuario y/0 contrasela son incorrectos'));
			}
		}
		else
		{
			echo json_encode(array('0','El nombre de usuario y/0 contrasela son incorrectos'));
		}
	}

	public function registrarCookieInicial1()
	{
		if(!empty($_POST))
		{
			require_once "application/libraries/ReCaptcha.php";
			
			$configuracion		= $this->configuracion->obtenerEstilo();
			
			/*delete_cookie('idTienda'); 
			delete_cookie('idEstacion'); 
			
			set_cookie('idTienda','temporal',120); 
			echo json_encode(array('1','Usuario correcto'));
			return;*/
			
			$password	= $this->input->post('txtPassword');
			$usuario	= $this->input->post('txtUsuario');
			
			$registro		= $this->usuarios->obtenerUsuarioLoginRegistro($usuario,$password);
			
			#if($configuracion->passwordTiendas==sha1($this->input->post('txtPassword')) and $configuracion->usuarioTiendas==$this->input->post('txtUsuario'))
			if($registro!=null)
			{
				$reCaptcha = new ReCaptcha(secret);
				
				if ($_POST["g-recaptcha-response"]) 
				{
					$resp = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"],$_POST["g-recaptcha-response"]);
				
					if ($resp != null && $resp->success) 
					{
						delete_cookie('idLicencia'.$configuracion->idCookie); 
						delete_cookie('idEstacion'.$configuracion->idCookie); 
						
						set_cookie('idLicencia'.$configuracion->idCookie,'temporal',240); 
						
						echo json_encode(array('1','Usuario correcto'));
					}
					else
					{
						echo json_encode(array('0','Verifique que el captcha sea correcto'));
					}
				}
				else
				{
					echo json_encode(array('0','Verifique que el captcha sea correcto'));
				}
			}
			else
			{
				echo json_encode(array('0','El nombre de usuario y/0 contrasela son incorrectos'));
			}
		}
		else
		{
			echo json_encode(array('0','El nombre de usuario y/0 contrasela son incorrectos'));
		}
	}
	
	public function registroEstacion()
	{
		$configuracion		= $this->configuracion->obtenerConfiguracionCookie();
		
		$idLicencia			= get_cookie('idLicencia'.$configuracion->idCookie); 
		
		if($idLicencia!='temporal')
		{
			redirect('instalacion','refresh');
		}
		
		$data['estilo']		= $this->configuracion->obtenerEstilo();
		$data['licencias']	= $this->configuracion->obtenerLicenciasActivas();
		
		$this->load->view("instalacion/registroEstacion",$data);
	}
	
	public function obtenerEstaciones()
	{
		if(!empty($_POST))
		{
			$data['idLicencia']	= $this->input->post('idLicencia');
			$data['estaciones']	= $this->estaciones->obtenerRegistrosLicencia($data['idLicencia']);
			
			$this->load->view("instalacion/obtenerEstaciones",$data);
			
		}
		else
		{
			echo "Error";
		}
	}
	
	public function registrarEstacion()
	{
		if(!empty($_POST))
		{
			$configuracion		= $this->configuracion->obtenerConfiguracionCookie();
			
			set_cookie('idLicencia'.$configuracion->idCookie,$this->input->post('selectSucursal'),duracion); 
			set_cookie('idEstacion'.$configuracion->idCookie,$this->input->post('selectEstaciones'),duracion);
			
			echo json_encode(array('1','Registro correcto'));
		}
		else
		{
			echo json_encode(array('0','Error en el registro'));
		}
	}
	
}
?>

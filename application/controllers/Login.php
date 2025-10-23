<?php
class Login extends CI_Controller
{
	protected $_csstyle;
 
	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('style', TRUE);
		
		$this->_csstyle = $this->config->item('style');
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("estaciones_modelo","estaciones");
	}

	public function index()
	{
		//OBTENER LOS COOKIES PARA IDENTIFICAR LA TIENDA Y LA ESTACIÓN
		
		$configuracion		= $this->configuracion->obtenerConfiguracionCookie();
		
		$idLicencia			= get_cookie('idLicencia'.$configuracion->idCookie);
		$idEstacion			= get_cookie('idEstacion'.$configuracion->idCookie);
		
		if(strlen($idLicencia)==0 or strlen($idEstacion)==0)
		{
			redirect('instalacion/index/instalacion','refresh');
		}
		
		 $data['estilo']	= $this->configuracion->obtenerConfiguracionLicencia($idLicencia);
		 $data['estacion']	= $this->estaciones->obtenerRegistro($idEstacion);
		
		
		if($data['estacion']==null)
		{
			redirect('instalacion','refresch');
		}
			 
		$this->load->view("login/login",$data);
		

		 $mensajes	="3";
		 $this->session->set_userdata('mensajes',$mensajes);
	}
	
	public function indexa()
	{
		 $data['estilo']	= $this->configuracion->obtenerEstilo();
		 
		 if(sistemaActivo=='olyess')
		 {
			 $data['licencias']	= $this->configuracion->obtenerLicenciasActivas();
			 
			 $this->load->view("login/loginOlyess",$data);
		 }
		 else
		 {
			 if(sistemaActivo=='cerraduras')
			 {
				 $data['licencias']	= $this->configuracion->obtenerLicenciasActivas();
			 
			 	$this->load->view("login/loginSucursales",$data);
			 }
			 else
			 {
				$this->load->view("login/login",$data); 
			 }
			  
		 }
		

		 $mensajes	="3";
		 $this->session->set_userdata('mensajes',$mensajes);
	}
	
	public function loginAdmin()
	{
		$Data['csslogin']=$this->_csstyle["csslogin"];
		$this->load->view("loginAdmin/cabezera",$Data);
		$this->load->view("loginAdmin/login");
		$this->load->view("loginAdmin/pie");
		$mensajes="3";
		$this->session->set_userdata('mensajes',$mensajes);
	}

	
	public function recuperarPassword()
	{
		$Data['csslogin']=$this->_csstyle["csslogin"];
		$this->load->view("login/cabezera",$Data);
		$this->load->view("login/recuperar");
		$this->load->view("login/pie");
	}
	
	
	public function confirmacion($pass,$user)
	{
		$Data['csslogin']=$this->_csstyle["csslogin"];
		
		$data['cambiar']=$this->modelousuario->obtenerPassword($pass,$user);
		
		$this->load->view("login/cabezera",$Data);
		$this->load->view("login/confirmar",$data);
		$this->load->view("login/pie");
	}
	
	public function confirmarPassword()
	{
		if(!empty($_POST))
		{
			$cambiar=$this->modelousuario->cambiarPassword();
			print($cambiar);
		}
	}
   
   	public function accesoWeb()
	{
		$user = $this->input->post('username');
		$pass = $this->input->post('password');
		
		$redux = $this->redux_auth->login($user,$pass);
		
		#echo 'id: '. $this->session->userdata('id');
		
		$this->session->set_userdata('idUsuarioAdmin','');
		$licencia=$this->modelousuario->obtenerUsuario($this->session->userdata('id'));

		if($licencia!=null)
		{
			$this->session->set_userdata('idLicencia',$licencia->idLicencia);
			echo "1";
		}
		else
		{
			echo "0";
		}
	}
	
	public function acceso()
	{
		$Data['csslogin']	= $this->_csstyle["csslogin"];
		$Data['estilo']		= $this->configuracion->obtenerEstilo();
		$isAjax				= $this->input->is_ajax_request() || $this->input->post('ajax');
		
		$reglas['username'] = "required";
		$reglas['password'] = "required";
		
		$fields['username'] = 'usuario';
		$fields['password'] = 'password';
		
		$this->validation->set_fields($fields);
		$this->validation->set_rules($reglas);
		
		if ($this->validation->run() == FALSE)
		{
			if($isAjax)
			{
				$this->output
					->set_status_header(400)
					->set_content_type('application/json')
					->set_output(json_encode(array(
						'success' => false,
						'message' => 'Usuario y contraseña son requeridos.'
					)));
				return;
			}

			$this->session->set_userdata('errorNotificacion','El nombre de usuario o contraseña son incorrectos - validacion');
			redirect('login');
		}
		else
		{
			$user 	= $this->input->post('username');
			$pass 	= $this->input->post('password');
			
			$redux 	= true; #$this->redux_auth->login($user,$pass);
			$anio	= date("Y");
		
			switch($redux)
			{
				case false: 
					if($isAjax)
					{
						$this->output
							->set_status_header(401)
							->set_content_type('application/json')
							->set_output(json_encode(array(
								'success' => false,
								'message' => 'El nombre de usuario o la contraseña son incorrectos.'
							)));
						return;
					}

					$this->session->set_userdata('errorNotificacion','El nombre de usuario o contraseña son incorrectos - login');
					redirect('login');
				break;
				case true:
				
				$this->session->set_userdata('idUsuarioAdmin','');
				$licencia	= $this->modelousuario->obtenerUsuarioLogin($user,$pass,$this->input->post('selectSucursal'));
				
				if($licencia!=null)
				{
					$this->session->set_userdata('id',$licencia->idUsuario);
					$this->session->set_userdata('role',$licencia->idRol);
					$this->session->set_userdata('idLicencia',$this->input->post('selectSucursal'));
					
					$this->modelousuario->accesoUsuario($licencia); //Actualizar la fecha de acceso
					$this->session->set_userdata('mensajeRedisoft','1');
				}
				else
				{
					if($isAjax)
					{
						$this->output
							->set_status_header(401)
							->set_content_type('application/json')
							->set_output(json_encode(array(
								'success' => false,
								'message' => 'No se pudo validar el acceso con el servidor.'
							)));
						return;
					}
					redirect('login/logout','refresh');
				}
				
				if($this->configuracion->comprobarHorario($licencia->idUsuario)==0)
				{
					if($isAjax)
					{
						$this->output
							->set_status_header(403)
							->set_content_type('application/json')
							->set_output(json_encode(array(
								'success' => false,
								'message' => 'El día u horario de acceso no están disponibles para el usuario.'
							)));
						return;
					}
					$this->session->set_userdata('errorNotificacion','El dia y horario de acceso no estan disponibles para el usuario');
					redirect('login');
				}

				if($isAjax)
				{
					$configuracionCookie = $this->configuracion->obtenerConfiguracionCookie();
					$idEstacionCookie    = get_cookie('idEstacion'.$configuracionCookie->idCookie);
					$this->output
						->set_content_type('application/json')
						->set_output(json_encode(array(
							'success'  => true,
							'redirect' => base_url()."principal/index/",
							'usuario'  => array(
								'username'   => $user,
								'idUsuario'  => $licencia->idUsuario,
								'idLicencia' => $this->input->post('selectSucursal'),
								'idEstacion' => $idEstacionCookie ? $idEstacionCookie : null,
								'idRol'      => $licencia->idRol
							)
						)));
					return;
				}

				redirect(base_url()."principal/index/","refresh");
				
				
				switch($this->redux_auth->id_logged_in_role())
				{
					case 0: 	
					redirect(base_url()."principal/index/","refresh");
					break;
					
					case 1: 
					redirect(base_url()."principal/index/","refresh");
					break;
					
					case 2:
					
					break;
				}
				
				break; 
				
			}
		}
	}
 
//********* Salir del Sistema
	public function logout()
	{
		if(isset($_SESSION))
		{
			#$this->session->sess_destroy();
		}

		if($this->session->userdata('usuarioSesion'))
		{
			$this->session->unset_userdata(array('usuarioSesion', 'id','idEstacion','idLicencia'));

			#$this->redux_auth->logout();

			redirect(base_url().'login/loginSesion','refresh');
		}
		else
		{
			$this->session->unset_userdata(array('usuarioSesion', 'id','idEstacion','idLicencia'));

			#$this->redux_auth->logout();

			redirect(base_url().'login','refresh');
		}

	}
	
	function enviarConfirmacion()
	{
		if(!empty($_POST))
		{
			$password=$this->modelousuario->obtenerPasswordCorreo();
			$configuracion=$this->configuracion->obtenerConfiguracion();
			
			if($password!=null)
			{
				#$email="licfloresdejesus@gmail.com";
				#$nombre='Mauricio Flores';
				
				$email=$configuracion['correo'];
				$nombre=$configuracion['nombre_empresa'];
				
				//$to="licfloresdejesus@gmail.com";
				$to=$this->input->post('correo');
				
				$this->load->library('email');
				$this->email->from($email,$nombre);
				#$this->email->to($this->system_library->settings['request_email']);
				$this->email->to($to);
				
				$asunto='Hola de click en este link para recuperar su contraseña: ';
				
				$asunto.=base_url()."login/confirmacion/".$password->password."/".$password->username; 
				
				$this->email->subject('Recuperar password');
				$this->email->message
				(
					$asunto
				);
				
				if (!$this->email->send())
				{
					print("0");
				}
				else
				{
					print("1");
				}
					
			}
			else
			{
				print("2");
			}
		}
	}//function


	public function loginSesion()
	{
		$data['estilo']		= $this->configuracion->obtenerEstilo();
		$data['licencias']	= $this->configuracion->obtenerLicenciasActivas();
		
		$this->load->view("login/loginSesion",$data);
	}

	public function accesoSesion()
	{
		$user 	= $this->input->post('username');
		$pass 	= $this->input->post('password');
			
		$redux 	= true;
		$anio	= date("Y");
		
		switch($redux)
		{
			case false: 
				$this->session->set_userdata('errorNotificacion','El nombre de usuario o contraseña son incorrectos - login');	
					
				redirect('login/loginSesion');
			break;
			case true:
				
			$this->session->set_userdata('idUsuarioAdmin','');
			$licencia	= $this->modelousuario->obtenerUsuarioLogin($user,$pass,$this->input->post('selectSucursal'),'1');
				
			if($licencia!=null)
			{
				$this->session->set_userdata('usuarioSesion',"100");
				$this->session->set_userdata('id',$licencia->idUsuario);
				$this->session->set_userdata('role',$licencia->idRol);
				$this->session->set_userdata('idLicencia',$this->input->post('selectSucursal'));
				$this->session->set_userdata('idEstacion',$this->input->post('selectEstaciones'));
					
				$this->modelousuario->accesoUsuario($licencia); //Actualizar la fecha de acceso
				$this->session->set_userdata('mensajeRedisoft','1');
			}
			else
			{
				redirect('login/loginSesion','refresh');
			}
				
			if($this->configuracion->comprobarHorario($licencia->idUsuario)==0)
			{
				$this->session->set_userdata('errorNotificacion','El dia y horario de acceso no estan disponibles para el usuario');	
					
				redirect('login/loginSesion','refresh');
			}

			redirect(base_url()."principal/index/","refresh");
				
				
			switch($this->redux_auth->id_logged_in_role())
			{
				case 0: 	
				redirect(base_url()."principal/index/","refresh");
				break;
					
				case 1: 
				redirect(base_url()."principal/index/","refresh");
				break;
					
				case 2:
					
				break;
			}
				
			break; 
				
		}
	}
}
?>

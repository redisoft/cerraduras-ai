<?php
class Importar extends CI_Controller
{
	protected $fecha;
	protected $idUsuario;
	protected $cuota;
	protected $precios;
	protected $hora;
	protected $horaExacta;
	protected $fechaCorta;
	
	function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
        $this->load->model("modeloclientes","clientes");
	 	$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("importar_modelo","importar");
		$this->load->model("materiales_modelo","materiales");
		$this->load->model("inventarioproductos_modelo","inventario");
		$this->load->model("crm_modelo","crm");
		$this->load->model("reportes_model","reportes");
		
		$this->idUsuario		= $this->session->userdata('id');
		$this->fecha 			= date('Y-m-d H:i:s');
		$this->fechaCorta		= date('Y-m-d');
		$this->hora 			= date('H:i:s');
		$this->horaExacta		= date('H');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
		
		$this->precios	= $this->session->userdata('precios');
  	}
	
	function descargarFormato($formato)
	{
		$this->load->helper('download');
		
		$formato 		= obtenerFormato($formato);
		$descarga 		= $formato.'.xls';
		$data 			= file_get_contents(carpetaImportar.$formato.'.xls'); 
		
		if($formato=='Formato productos')
		{
			if($this->precios=='1')
			{
				$data 			= file_get_contents(carpetaImportar.'Formato productosprecios.xls'); 
			}
		}

		force_download($descarga, $data); 
	}
	
	function descargarExportar($archivo)
	{
		$this->load->helper('download');
		
		$descarga 		= $archivo.'_'.date('Y-m-d').'.xls';
		$data 			= file_get_contents(carpetaImportar.$archivo.'.xls'); 

		force_download($descarga, $data); 
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//CLIENTES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function subirArchivoClientes()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoClientes.xls');

				if(file_exists(carpetaFicheros.'formatoClientes.xls'))
				{
					#echo "1";
					
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					$this->load->view('clientes/importar/importarClientes',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarClientes()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('clientes/importar/formularioImportarClientes',$data);
	}
	
	public function exportarClientes()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$data['clientes'] 	= $this->importar->exportarClientes($this->input->post('tipoRegistro'));
		
		$this->load->view('clientes/importar/exportarClientes',$data);
	}
	
	public function exportarPreinscritos()
	{
		$criterio		= trim($this->input->post('criterio'));
		$idPromotor		= $this->input->post('idPromotor');
		$idPrograma		= $this->input->post('idPrograma');
		$idCampana		= $this->input->post('idCampana');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$data['clientes'] 	= $this->importar->exportarClientes($this->input->post('tipoRegistro'));
		
		$this->load->view('clientes/importar/exportarClientes',$data);
	}
	
	public function exportarProspectos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$criterio				= $this->input->post('criterio');
		$idStatus				= $this->input->post('idStatus');
		$idEstatus				= $this->input->post('idEstatus');
		$idPromotor				= $this->input->post('idPromotor');
		$idTipo					= $this->input->post('idTipo');
		$criterioSeccion		= $this->input->post('criterioSeccion');
		$numeroSeguimientos		= $this->input->post('numeroSeguimientos');
		$idCampana				= $this->input->post('idCampana');
		$idPrograma				= $this->input->post('idPrograma');
		$idFuente				= $this->input->post('idFuente');
		
		$tipoFecha				= $this->input->post('tipoFecha');
		$inicial				= $this->input->post('inicial');
		$final					= $this->input->post('final');
		
		$idServicio		= 0;
		$fecha			= $this->input->post('fecha');
		$fechaFin		= $this->input->post('fechaFin');
		$idResponsable	= 0;
		$fechaMes		= 'mes';
		$idZona			= 0;
		$idResponsable	= 0;
		
		
		$this->load->library('excel/PHPExcel');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['clientes']		= $this->clientes->obtenerProspectosUsuario(0,0,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][5]->activo,$idZona,'asc',$idEstatus,$idPromotor,$fechaFin,$numeroSeguimientos,$idCampana,$idPrograma,$idFuente,$tipoFecha,$inicial,$final);
		
		$this->load->view('clientes/importar/exportarProspectos',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PROSPECTOS - - - - IEXE
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function subirArchivoProspectos()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoProspectos.xls');

				if(file_exists(carpetaFicheros.'formatoProspectos.xls'))
				{
					#echo "1";
					
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					$data['hora']			= $this->hora;
					$data['horaExacta']		= $this->horaExacta;
					
					//PROSPECTOS A 
					/*if($this->horaExacta>=15)
					{
						$data['fecha']				= $this->reportes->obtenerFechaFinCompleta($this->fechaCorta.' 09:00:00',1);
						$data['fechaSeguimiento']	= substr($data['fecha'],0,10);
						$data['hora']				= '09:00:00';
					}*/
					
					$this->load->view('clientes/prospectos/importar/importarProspectos',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarProspectos()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('clientes/prospectos/importar/formularioImportarProspectos',$data);
	}
	
	public function exportarProspectos1()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$data['clientes'] 	= $this->importar->exportarProspectos($this->input->post('tipoRegistro'));
		
		$this->load->view('clientes/prospectos/importar/exportarProspectos',$data);
	}
	
	public function comprobarRepetidos()
	{
		echo json_encode($this->importar->comprobarRepetidos());
	}
	
	public function obtenerRepetidos()
	{
		$data['repetidos']			= $this->importar->obtenerRepetidos();
		
		$this->load->view('clientes/prospectos/importar/obtenerRepetidos',$data);
	}
	
	
	public function subirArchivoProspectosImportar()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoProspectosImportar.xls');

				if(file_exists(carpetaFicheros.'formatoProspectosImportar.xls'))
				{
					#echo "1";
					
					$this->load->library('excel/PHPExcel');
					
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					#$this->load->view('clientes/prospectos/importarProspectos/importarProspectos',$data);
					$this->load->view('clientes/prospectos/importarProspectos/importar',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarProspectosRegistro()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('clientes/prospectos/importarProspectos/formularioImportarProspectos',$data);
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//IMPORTAR FACEBOOK
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function formularioImportarFacebook()
	{
		$data['cuota']			= $this->cuota;
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		
		$this->load->view('clientes/prospectos/importarFacebook/formularioImportarFacebook',$data);
	}
	
	public function importarFacebook()
	{
		$data	= array('upload_dir'=>carpetaFicheros,'max_file_size'=>'1073741824','discard_aborted_uploads'=>false);
		
		$this->load->library('UploadHandler',$data);
		
		
		
		#$upload_handler = new UploadHandler($data);
	}
	
	public function importarArchivosFacebook()
	{
		$data['archivos']			= $this->input->post('archivos');
		$data['indice']				= $this->input->post('indice');
		$data['idUsuario']			= $this->idUsuario;
		$data['fecha']				= $this->fecha;
		$data['idCampana']			= $this->input->post('idCampana');
		
		$data['fechaSeguimiento']	= $this->input->post('fecha');
		$data['hora']				= $this->input->post('hora');
		
		//$this->load->view('clientes/prospectos/importarFacebook/importarArchivosFacebook',$data);
		$this->load->view('clientes/prospectos/importarFacebookActualizar/importarArchivosFacebook',$data);
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PROSPECTOS - - - - IEXE
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function subirArchivoComparar()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'cotejar.xls');

				if(file_exists(carpetaFicheros.'formatoProspectos.xls'))
				{
					#echo "1";
					
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					$this->load->view('clientes/prospectos/comparar/importarComparar',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 
	
	public function formularioImportarComparar()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('clientes/prospectos/comparar/formularioImportarComparar',$data);
	}
	
	public function obtenerComparados()
	{
		$data['comparados']			= $this->importar->obtenerComparados();
		$data['correos']			= $this->importar->obtenerCorreos();
		
		$this->load->view('clientes/prospectos/comparar/obtenerComparados',$data);
	}
	
	//IMPORTAR PROSPECTOS ADMIN
	
	public function subirArchivoProspectosAdmin()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoProspectosAdmin.xls');

				if(file_exists(carpetaFicheros.'formatoProspectosAdmin.xls'))
				{
					#echo "1";
					
					$this->load->library('excel/PHPExcel');
					
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					$this->load->view('clientes/prospectos/importarProspectosAdmin/importar',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarProspectosAdmin()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('clientes/prospectos/importarProspectosAdmin/formularioImportarProspectos',$data);
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PROVEEDORES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function subirArchivoProveedores()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoProveedores.xls');

				if(file_exists(carpetaFicheros.'formatoProveedores.xls'))
				{
					#echo "1";
					
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					$this->load->view('proveedores/importar/importarProveedores',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarProveedores()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('proveedores/importar/formularioImportarProveedores',$data);
	}
	
	public function exportarProveedores()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$data['proveedores'] 	= $this->importar->exportarProveedores();
		
		$this->load->view('proveedores/importar/exportarProveedores',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//MATERIA PRIMA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function subirArchivoMateriales()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoMateriales.xls');

				if(file_exists(carpetaFicheros.'formatoMateriales.xls'))
				{
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					$this->load->view('materiales/importar/importarMateriales',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarMateriales()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('materiales/importar/formularioImportarMateriales',$data);
	}
	
	public function exportarMateriales()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$data['materiales'] 	= $this->importar->exportarMateriales();
		
		$this->load->view('materiales/importar/exportarMateriales',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PRODUCTOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function subirArchivoProductos()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoProductos.xls');

				if(file_exists(carpetaFicheros.'formatoProductos.xls'))
				{
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					if(sistemaActivo=='pinata')
					{
						$this->load->view('inventarioProductos/importar/importarProductosPinata',$data);
					}
					else
					{
						
						if($this->precios=='1')
						{
							$this->load->view('inventarioProductos/importar/importarProductosPrecios',$data);
						}
						else
						{
							$this->load->view('inventarioProductos/importar/importarProductos',$data);
						}
					}
					
					
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarProductos()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('inventarioProductos/importar/formularioImportarProductos',$data);
	}
	
	public function exportarProductos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$criterio				= $this->input->post('criterio');
		$orden					= $this->input->post('orden');
		$minimo					= $this->input->post('minimo');
		$codigoInterno			= $this->input->post('codigoInterno');
		
		$data['productos'] 	= $this->importar->exportarProductos($criterio,$orden,$minimo,$codigoInterno);
		
		if($this->precios=='1')
		{
			$this->load->view('inventarioProductos/importar/exportarProductosPrecios',$data);
		}
		else
		{
			$this->load->view('inventarioProductos/importar/exportarProductos',$data);
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PRODUCCIÓN
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function subirArchivoProduccion()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'formatoProduccion.xls');

				if(file_exists(carpetaFicheros.'formatoProduccion.xls'))
				{
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					$this->load->view('produccion/importar/importarProduccion',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 
	
	public function formularioImportarProduccion()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('produccion/importar/formularioImportarProduccion',$data);
	}
	
	public function exportarProduccion()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$data['productos'] 	= $this->importar->exportarProduccion();
		
		$this->load->view('produccion/importar/exportarProduccion',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//IMPORTAR EL CHECADOR
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function importarChecador()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('csv');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'checador.csv');

				if(file_exists(carpetaFicheros.'checador.csv'))
				{
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;
					
					$this->load->view('reportes/checador/importar/importar',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(csv)';
			}
		}
	} 

	public function formularioImportarChecador()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('reportes/checador/importar/formularioImportarChecador',$data);
	}
	
	public function importarChecadorsito()
	{
		$handle = fopen (carpetaFicheros.'checador.csv',"r");
		echo '<table border="1"><tr><td>First name</td><td>Last name</td></tr><tr>';
		
		while ($data = fgetcsv ($handle, 1000, ";")) 
		{
				$data = array_map("utf8_encode", $data); //added 
				$num = count ($data);
				
				for ($c=0; $c < $num; $c++) 
				{
					// output data
					echo "<td>$data[$c]</td>";
				}
				echo "</tr><tr>";
		}
	}
	
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//EGRESOS - - - - IEXE
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function importarEgresos()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('xls');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaFicheros.'importarEgresos.xls');

				if(file_exists(carpetaFicheros.'importarEgresos.xls'))
				{
					$data['idUsuario']		= $this->idUsuario;
					$data['fecha']			= $this->fecha;

					$this->load->view('administracion/egresos/importarEgresos',$data);
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de excel(xls)';
			}
		}
	} 

	public function formularioImportarEgresos()
	{
		$data['cuota']			= $this->cuota;
		
		$this->load->view('administracion/egresos/formularioImportarEgresos',$data);
	}
}
?>

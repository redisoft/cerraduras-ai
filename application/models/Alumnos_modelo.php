<?php
class Alumnos_modelo extends CI_Model
{
	protected $fecha;
	protected $idUsuario;

	function __construct()
	{
		parent::__construct();
		
		$this->fecha			= date('Y-m-d H:i:s');
		$this->idUsuario 		= $this->session->userdata('id');
	}

	#PARA LAS  VENTAS QUE SON DIRECTAS
	public function obtenerAlumno1()
	{
		$sql="select idCliente, nombre, paterno 
		from clientes
		where idCliente=95489 ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function comprobarAlumno($email='')
	{
		$sql=" select idCliente
		from clientes
		where email='$email'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarAlumno($data)
	{
		if($this->comprobarAlumno($data['email'])>0)
		{
			return array(false,'El registro esta duplicado');
		}
		
		$data['fechaRegistro']	= $this->fecha;
		
		$this->db->insert('alumnos_repositorio',$data);
		
		return $this->db->affected_rows() >= 1? array(true,'Registro correcto') : array(false,'Error en el registro');
	}
	
	
	public function obtenerAlumno($idAlumno)
	{
		$sql="select*
		from alumnos_repositorio
		where idAlumno='$idAlumno' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarRegistroClienteIexe($email)
	{
		$sql="select idCliente
		from clientes
		where email='$email'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	
	public function asignarPromotor()
	{
		$idAlumno		= $this->input->post('txtIdAlumno');
		$alumno			= $this->obtenerAlumno($idAlumno);
		
		if(!$this->comprobarRegistroClienteIexe(reemplazarApostrofe($alumno->email)))
		{
			return array('0',registroDuplicado);
			exit;
		}
		
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza

		$data=array
		(
			'empresa'			=> $alumno->nombre.' '.$alumno->apellido,
			'precio'			=> 1,	 
			'email'				=> $alumno->email,

			'idUsuario' 		=> $this->idUsuario,
			'fechaRegistro'		=> $this->fecha,
			'idLicencia'		=> 1,
			'idZona'			=> 5,
			'prospecto'			=> 1,
			'estado'			=> $alumno->estado,
			'limiteCredito'		=> 0,
			'idFuente'			=> 10,
			'pregunta'			=> $alumno->nombre,
			'nivelEstudios'		=> $alumno->nivelEstudios,
			
			'telefono'			=> $alumno->telefono,
			'movil'				=> $alumno->telefono2,
			'idMetodo'			=> 1,
			
			'nombre'			=> $alumno->nombre,
			'paterno'			=> $alumno->apellido,
			'idPromotor'		=> $this->input->post('selectPromotores'),
			'idCampana'			=> $this->input->post('selectCampana'),
			'idCampanaOriginal'	=> $this->input->post('selectCampana'),
			'fechaCaptacion'	=> $alumno->fechaRegistro,

		);
		
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('clientes', $data);
		$idCliente = $this->db->insert_id();

		#$this->configuracion->registrarBitacora('Registrar cliente','Clientes',$data['empresa']); //Registrar bitácora
		
		$data=array
		(
			'idCliente' 	=> $idCliente,
			'nombre' 		=> $alumno->nombre.' '.$alumno->apellido,
			'email' 		=> $alumno->email,
			'telefono' 		=> $alumno->telefono,

			'movil1'		=> $alumno->telefono2,

			'fechaRegistro' => $this->fecha,
			'idUsuario' 	=> $this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('clientes_contactos', $data); 
		$idContacto	= $this->db->insert_id();
		
		$data=array
		(
			'idCliente' 				=> $idCliente,
			'idPrograma'				=> $this->input->post('selectProgramasProspecto'),
			'periodoActual'				=> 1,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('clientes_academicos', $data); 
		
		#$this->registrarDocumentosCliente($idCliente);

		$fecha								= $this->input->post('txtFechaSeguimientoProspecto').' '.$this->input->post('txtHoraInicioProspecto');
			
		$seguimiento['fecha']				= $fecha;
		$seguimiento['comentarios']			= '';
		$seguimiento['fechaCierre']			= $fecha;
		$seguimiento['horaCierreFin']		= $this->input->post('txtHoraInicioProspecto');
		$seguimiento['idStatus']			= 4;
		$seguimiento['idEstatus']			= 4;
		$seguimiento['tipo']				= 1;
		$seguimiento['idServicio']			= 1;
		$seguimiento['folio']				= $this->crm->obtenerFolioSeguimientoCliente(1);
		
		$seguimiento['idResponsable']		= $this->input->post('selectPromotores');
		$seguimiento['idUsuarioRegistro']	= $this->input->post('selectPromotores');
		$seguimiento['idCliente']			= $idCliente;
		$seguimiento['idContacto']			= $idContacto;

		$detalle['fecha']					= $this->input->post('txtFechaSeguimientoProspecto');
		$detalle['hora']					= $this->input->post('txtHoraInicioProspecto');
		$detalle['fechaRegistro']			= $this->fecha;
		$detalle['observaciones']			= '';
		$detalle['fechaSeguimiento']		= $this->input->post('txtFechaSeguimientoProspecto');
		$detalle['horaInicial']				= $this->input->post('txtHoraInicioProspecto');
		$detalle['horaFinal']				= $this->input->post('txtHoraFinProspecto');
		$detalle['alerta']					= '1';
		
		$detalle['idCampana']				= $this->input->post('selectCampana');
		
		$this->importar->registrarSeguimientoInicial($seguimiento,$detalle);
		
		
		$this->db->where('idAlumno', $idAlumno);
		$this->db->update('alumnos_repositorio', array('activo'=>'0'));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',$idCliente);
		}
	}
	
	
}

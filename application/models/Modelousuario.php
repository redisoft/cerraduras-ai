<?php
class Modelousuario extends CI_Model
{

	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $fecha;

	function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		
		$this->_table 			= $this->config->item('datatables');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->fecha 			= date('Y-m-d');
	}
	
	public function getUsuarios($idUsuario)
	{
		$sql="select idUsuario,
		nombre as username 
		from usuarios
		where idUsuario='".$idUsuario."'
		";	
		
		#and idLicencia='$this->idLicencia'
		
		$usuario=$this->db->query($sql)->row()->username;
		
		return $usuario;
	}
 
	public function getNameUsuario($id)
	{
		$SQL="SELECT idUsuario,name 
		FROM ".$this->_table['usuarios']."
		WHERE idUsuario='".$id."'
		and idLicencia='$this->idLicencia'";
		
		$consulta=$this->db->query($SQL);
		
		foreach ($consulta->result() as $fila)
		{
		return $fila->name;
		}
	
	}//getNameUsuario

 public function getNameUsuarioCompleto($id){
        $SQL="SELECT * FROM ".$this->_table['usuarios']." 
		WHERE id='".$id."'
		and idLicencia='$this->idLicencia'";
   $consulta=$this->db->query($SQL);

   return $var = ($consulta->num_rows() == 1)? $consulta->row_array() : 0;

 }//getNameUsuarioCompleto

 public function getDatosUsuarioId($idc){

       $SQL="SELECT * FROM ".$this->_table['usuarios']." 
	   WHERE id='".$idc."'
	   and idLicencia='$this->idLicencia'";
  $consulta=$this->db->query($SQL);

    return $var = ($consulta->num_rows() == 1)? $consulta->row_array() : 0;

}
      
   public function getcountusuarios() 
   {
        return $this->db->count_all_results($this->_table['usuarios']);
    }

	public function addNuevoUsuario()
	{
		$data = array
		(
			"name" => $this->input->post('T1'),
			"username" => $this->input->post('T2'),
			"password" => md5($this->input->post('T3')),
			"role" => $this->input->post('T6'),
			"createDate" => $this->_fecha_actual,
			"correo" => $this->input->post('T5'),
			"create_by" => $this->session->userdata('id'),
			"idLicencia" => $this->idLicencia
		);
		
		
		$Regresa=$this->db->insert($this->_table['usuarios'],$data);
		return ($Regresa);//$this->db->affected_rows() == 1)? $this->db->insert_id() : NULL ;
	}//AddNuevoUsuario

	function cambiosUsuario()
	{
		$data = array
		(
			"name" => $this->input->post('T1'),
			"username" => $this->input->post('T2'),
			"password" => md5($this->input->post('T3')),
			"role" => $this->input->post('T6'),
			"modify_fech" => $this->_fecha_actual,
			"correo" => $this->input->post('T5'),
			"modify_by" => $this->session->userdata('id')
		);
		
		
		$str = $this->db->update($this->_table['usuarios'], $data, array('id' => $this->input->post('T7')));
		return $str;
	}//Fin de los datos
 
	function getRoll($idu)
	{
		$SQL="SELECT name,username,role 
		FROM ".$this->_table['usuarios']. " 
		WHERE id='".$idu."'";
		$consulta=$this->db->query($SQL);
		
		return $var = ($consulta->num_rows() == 1)? $consulta->row_array() : 0;
	
	}//getRol


function getRollName($idu)
{

      $SQL="SELECT name,username,role FROM ".$this->_table['usuarios']. " WHERE id='".$idu."'";
 $consulta=$this->db->query($SQL);

 if($consulta->num_rows()>0){

  $Datos=$consulta->row_array();

   return ($Datos['role']);

 }//IF
else{
    return NULL;
}
     //return $var = ($consulta->num_rows() == 1)? $consulta->row_array() : 0;

 }//getRol

	public function obtenerPassword($pass,$usuario)
	{
		$sql="select password 
			from usuarios 
			where password='$pass'
			and usuario='$usuario'";
			
		$query=$this->db->query($sql);
		
		return $query->row();
	}
	
	public function obtenerPasswordCorreo()
	{
		$correo=$this->input->post('correo');
		#$correo="licfloresdejesus@gmail.com";
		
		$sql="select * from usuarios 
			where correo='$correo'";
			
		$query=$this->db->query($sql);
		
		return $query->row();
	}
	
	public function cambiarPassword()
	{
		$password=$this->input->post('password');
		$usuario=$this->input->post('usuario');
			
		$data=array
		(
			'password'=>md5($password)
		);
		
		$this->db->where('username',$usuario);
		$this->db->update('usuarios',$data);
		
		if($this->db->affected_rows()==1)
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function obtenerUsuario($idUsuario)
	{
		$sql="select a.idLicencia ,a.idUsuario,
		a.apellidoPaterno, a.apellidoMaterno, a.nombre
		from usuarios as a
		inner join licencias as b
		on a.idLicencia=b.idLicencia
		where a.idUsuario='$idUsuario'
		and a.activo='1' ";
			
		return $this->db->query($sql)->row();
	}
    
    public function obtenerUsuarioRegistro($idUsuario)
	{
		$sql="select a.idLicencia ,a.idUsuario,
		concat(a.nombre, ' ', a.apellidoPaterno, ' ', a.apellidoMaterno) as nombre
		from usuarios as a
		where a.idUsuario='$idUsuario' ";
			
		return $this->db->query($sql)->row();
	}
	
	public function loginUsuarioAdmin($usuario,$pass)
	{
		$sql="select * from usuarios 
		where usuario='$usuario'
		and password=md5('$pass')
		and idLicencia='1'";
			
		return $this->db->query($sql)->row();
	}
	
	public function accesoUsuario($usuario)
	{
		$data=array
		(
			'fechaAcceso'	=> $this->_fecha_actual
		);
		
		if(sistemaActivo=='IEXE')
		{
			if($usuario->fechaAlerta!=$this->fecha)
			{
				$data['fechaAlerta']	= $this->fecha;
				$data['alerta']			= '1';
				
				$this->session->set_userdata('alertaActiva','1');
			}
		}

		
		$this->db->where('idUsuario',$usuario->idUsuario);
		$this->db->update('usuarios',$data);

		if($this->db->affected_rows()>=1)
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//HORARIOS DE USUARIOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerHorarios($idUsuario)
	{
		$sql= " select * from usuarios_horarios
		where idUsuario='$idUsuario' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarHorario()
	{
		$data =array
		(
			'horaInicial'			=> trim($this->input->post('txtHoraInicial')),
			'horaFinal'				=> trim($this->input->post('txtHoraFinal')),
			'idUsuario'				=> $this->input->post('txtIdUsuario'),
			
			'lunes'					=> $this->input->post('chkLunes')=='1'?'1':'0',
			'martes'				=> $this->input->post('chkMartes')=='1'?'1':'0',
			'miercoles'				=> $this->input->post('chkMiercoles')=='1'?'1':'0',
			'jueves'				=> $this->input->post('chkJueves')=='1'?'1':'0',
			'viernes'				=> $this->input->post('chkViernes')=='1'?'1':'0',
			'sabado'				=> $this->input->post('chkSabado')=='1'?'1':'0',
			'domingo'				=> $this->input->post('chkDomingo')=='1'?'1':'0',
		);
		
		$this->db->insert('usuarios_horarios',$data);
		
		$usuario	= $this->configuracion->obtenerUsuarioDetalle($this->input->post('txtIdUsuario'));
		$this->configuracion->registrarBitacora('Registrar horario','Configuración - Usuarios - Horarios',$usuario[0].', '.$usuario[0]); //Registrar bitácora

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerUsuarioHorario($idHorario)
	{
		$sql="select idUsuario
		from usuarios_horarios
		where idHorario='$idHorario' ";
		
		$horario	= $this->db->query($sql)->row();
		
		return $horario!=null?$horario->idUsuario:0;
	}
	
	public function editarHorario()
	{
		$data =array
		(
			'horaInicial'			=> trim($this->input->post('horaInicial')),
			'horaFinal'				=> trim($this->input->post('horaFinal')),
			'lunes'					=> $this->input->post('lunes'),
			'martes'				=> $this->input->post('martes'),
			'miercoles'				=> $this->input->post('miercoles'),
			'jueves'				=> $this->input->post('jueves'),
			'viernes'				=> $this->input->post('viernes'),
			'sabado'				=> $this->input->post('sabado'),
			'domingo'				=> $this->input->post('domingo'),
		);
		
		$this->db->where('idHorario', $this->input->post('idHorario'));
		$this->db->update('usuarios_horarios',$data);
		
		$idUsuario	= $this->obtenerUsuarioHorario($this->input->post('idHorario'));
		
		if($idUsuario>0)
		{
			$usuario	= $this->configuracion->obtenerUsuarioDetalle($idUsuario);
			
			$this->configuracion->registrarBitacora('Editar horario','Configuración - Usuarios - Horarios',$usuario[0].', '.$usuario[0]); //Registrar bitácora
		}

		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function borrarHorario($idHorario)
	{
		$idUsuario	= $this->obtenerUsuarioHorario($this->input->post('idHorario'));
		
		if($idUsuario>0)
		{
			$usuario	= $this->configuracion->obtenerUsuarioDetalle($idUsuario);
			
			$this->configuracion->registrarBitacora('Borrar horario','Configuración - Usuarios - Horarios',$usuario[0].', '.$usuario[0]); //Registrar bitácora
		}

		$this->db->where('idHorario',$idHorario);
		$this->db->delete('usuarios_horarios');

		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function obtenerUsuarioLogin($usuario,$password,$idLicencia,$ipad='0')
	{
		/*$sql=" select a.idLicencia, a.idUsuario, a.idRol
		from usuarios as a
		where a.usuario='$usuario'
		and a.password=sha1('$password') 
		and a.idLicencia='$idLicencia'
		and a.activo='1' ";*/
		
		
		$sql=" select a.idLicencia, a.idUsuario, a.idRol
		from usuarios as a
		where a.usuario='$usuario'
		and a.password=sha1('$password') 
		and a.activo='1' 
		and a.ipad='$ipad' ";
		
		$sql.=" and (select count(b.idRelacion) from usuarios_licencias as b where b.idUsuario=a.idUsuario and b.idLicencia='$idLicencia') > 0 ";

		#echo $sql; exit;
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUsuarioLoginRegistro($usuario,$password)
	{
		$sql=" select a.idLicencia, a.idUsuario, a.idRol
		from usuarios as a
		where a.usuario='$usuario'
		and a.password=sha1('$password') 
		and a.activo='1' ";
		
		return $this->db->query($sql)->row();
	}
}
?>

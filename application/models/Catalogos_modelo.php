<?php
class Catalogos_modelo extends CI_Model
{
	protected $fecha;
	protected $idLicencia;
	protected $resultado;
	protected $idUsuario;

	function __construct()
	{
		parent::__construct();

        $this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->fecha 			= mdate($datestring,now());
		$this->resultado		="1";
	}
	
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
	//MOTIVOS DE DEVOLUCIÓN
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYs
	public function obtenerMotivos()
	{
		$sql=" select * from cotizaciones_devoluciones_motivos
		where activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMotivo($idMotivo)
	{
		$sql=" select * from cotizaciones_devoluciones_motivos
		where idMotivo='$idMotivo'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarMotivoNombre($nombre)
	{
		$sql="select count(idMotivo) as numero
		from cotizaciones_devoluciones_motivos
		where nombre='$nombre' 
		and activo='1' ";
		
		return $this->db->query($sql)->row()->numero>0?false:true;
	}
	
	public function registrarMotivo()
	{
		if(!$this->comprobarMotivoNombre($this->input->post('nombre')))
		{
			return array('0',registroDuplicado);
			exit;
		}
		
		$data=array
		(
			'idLicencia'		=> $this->idLicencia,
			'nombre'			=> $this->input->post('nombre'),
		);
		
		$this->db->insert('cotizaciones_devoluciones_motivos', $data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarMotivo()
	{
		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
		);
		
		$this->db->where('idMotivo', $this->input->post('idMotivo'));
		$this->db->update('cotizaciones_devoluciones_motivos', $data);
		
		return $this->db->affected_rows() >= 1? "1" : "0";
	}
	
	public function borrarMotivo($idMotivo)
	{
	    $this->db->where('idMotivo',$idMotivo);
		$this->db->update('cotizaciones_devoluciones_motivos',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//TIPOS DE DEVOLUCIONES
	public function obtenerTiposDevolucion()
	{
		$sql=" select * from cotizaciones_devoluciones_tipos ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTipoDevolucionNombre($idTipo)
	{
		$sql=" select nombre 
		from cotizaciones_devoluciones_tipos
		where idTipo='$idTipo' ";
		
		$tipo= $this->db->query($sql)->row();
		
		return $tipo!=null?$tipo->nombre:'';
	}
	
	public function obtenerDiasSemanaActual()
	{
		$dia	= date('l', strtotime(date('Y-m-d')));
		$dia	= substr($dia,0,3);
		$fecha	= date('Y-m-d');
		$fecha1	= date('Y-m-d');
		$fecha2	= date('Y-m-d');
		
		switch($dia)
		{
			case 'Sun':
				$dia	= 1;
				$fecha1	= date('Y-m-d');
				$fecha2	= $this->db->query("select date_add('$fecha', interval 6 day) as fecha")->row()->fecha;
			break;
			
			case 'Mon':
				$dia	= 2;
				$fecha1	= $this->db->query("select date_sub('$fecha', interval 1 day) as fecha")->row()->fecha;
				$fecha2	= $this->db->query("select date_add('$fecha', interval 5 day) as fecha")->row()->fecha;
			
			break;
			
			case 'Tue':
				$dia	= 3;
				$fecha1	= $this->db->query("select date_sub('$fecha', interval 2 day) as fecha")->row()->fecha;
				$fecha2	= $this->db->query("select date_add('$fecha', interval 4 day) as fecha")->row()->fecha;
			break;
			
			case 'Wed':
				$dia	= 4;
				$fecha1	= $this->db->query("select date_sub('$fecha', interval 3 day) as fecha")->row()->fecha;
				$fecha2	= $this->db->query("select date_add('$fecha', interval 3 day) as fecha")->row()->fecha;
			break;
			
			case 'Thu':
				$dia	= 5;
				$fecha1	= $this->db->query("select date_sub('$fecha', interval 4 day) as fecha")->row()->fecha;
				$fecha2	= $this->db->query("select date_add('$fecha', interval 2 day) as fecha")->row()->fecha;
			break;
			
			case 'Fri':
				$dia	= 6;
				$fecha1	= $this->db->query("select date_sub('$fecha', interval 5 day) as fecha")->row()->fecha;
				$fecha2	= $this->db->query("select date_add('$fecha', interval 1 day) as fecha")->row()->fecha;
			break;
			
			case 'Sat':
				$dia=7;
				$fecha1	= $this->db->query("select date_sub('$fecha', interval 6 day) as fecha")->row()->fecha;
				$fecha2	= date('Y-m-d');
			break;
		}
		#echo $fecha1;
		return array($fecha1,$fecha2);
		/*if($dia==1)
		{
			$fecha1	= date('Y-m-d');
			$fecha2	= $this->db->query("select date_add('$fecha1', interval 6 day)");
		}
		
		if($dia==2)
		{
			$fecha1	= $this->db->query("select date_sub('$fecha1', interval 1 day)");
			$fecha2	= $this->db->query("select date_add('$fecha1', interval 5 day)");
		}*/
	}
	
	//DEPARTAMENTOS
	
	public function obtenerDepartamentos()
	{
		$sql=" select a.*
		from productos_departamentos as a
		where a.activo='1'
		order by a.nombre asc ";	
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDepartamento($idDepartamento)
	{
		$sql=" select * from productos_departamentos
		where idDepartamento='$idDepartamento'
		and activo='1' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarDepartamento()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtDepartamento')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('productos_departamentos',$data);
		
		$this->configuracion->registrarBitacora('Registrar departamento','Configuración - Departamentos ',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarDepartamento()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtDepartamento')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idDepartamento',$this->input->post('txtIdDepartamento'));
		$this->db->update('productos_departamentos',$data);
		
		$this->configuracion->registrarBitacora('Editar departamento','Configuración - Departamentos ',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerDetallesDepartamento($idDepartamento)
	{
		$sql="select nombre
		from productos_departamentos
		where idDepartamento='$idDepartamento'";	
		
		$departamento	= $this->db->query($sql)->row();
		
		return $departamento!=null?$departamento->nombre:'';
	}
	
	public function borrarDepartamento($idDepartamento)
	{
		$this->configuracion->registrarBitacora('Borrar departamento','Configuración - Departamentos',$this->obtenerDetallesDepartamento($idDepartamento)); //Registrar bitácora
		
	    $this->db->where('idDepartamento',$idDepartamento);
		$this->db->update('productos_departamentos',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	
	//MARCAS
	
	public function obtenerMarcas()
	{
		$sql=" select a.*
		from productos_marcas as a
		where a.activo='1'
		order by a.nombre asc ";	
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMarca($idMarca)
	{
		$sql=" select * from productos_marcas
		where idMarca='$idMarca'
		and activo='1' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarMarca()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtMarca')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('productos_marcas',$data);
		
		$this->configuracion->registrarBitacora('Registrar marca','Configuración - Marcas ',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarMarca()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtMarca')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idMarca',$this->input->post('txtIdMarca'));
		$this->db->update('productos_marcas',$data);
		
		$this->configuracion->registrarBitacora('Editar marca','Configuración - Marcas ',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerDetallesMarca($idMarca)
	{
		$sql="select nombre
		from productos_marcas
		where idMarca='$idMarca'";	
		
		$marca	= $this->db->query($sql)->row();
		
		return $marca!=null?$marca->nombre:'';
	}
	
	public function borrarMarca($idMarca)
	{
		$this->configuracion->registrarBitacora('Borrar marca','Configuración - Marcas',$this->obtenerDetallesMarca($idMarca)); //Registrar bitácora
		
	    $this->db->where('idMarca',$idMarca);
		$this->db->update('productos_marcas',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//OBTENER LOS DIFERENTES CATÁLOGOS
	public function registrarCatalogoNombre($nombre,$tipo)
	{
		$data=array
		(
			'nombre'	=>	$nombre,
		);

		if(strlen($data['nombre'])==0) return 0;

		$this->db->select('*');
		$this->db->from("productos_".$tipo);
		$this->db->where('nombre', $data['nombre']);
		$this->db->where('activo', '1');
		
		$query = $this->db->get()->row();

		if($query!=null)
		{ 
			switch($tipo)
			{
				case 'departamentos':return $query->idDepartamento;break;
				case 'marcas':return $query->idMarca;break;
				case 'lineas':return $query->idLinea;break;
				
			}
		}

	    $this->db->insert('productos_'.$tipo,$data);
		$id	= $this->db->insert_id();
		
		return $this->db->affected_rows()>=1?$id:"0"; 
	}
	
	#PARA LAS VARIABLES
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerVariablesTipo($tipo=0)
	{    
		$sql=" select * from catalogos_variables
		where  tipo='$tipo'
		order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVariables()
	{    
		$criterio	= $this->input->post('criterio');
		$tipo		= $this->input->post('tipo');
		
		$sql=" select * from productos_variables
		where nombre like '%$criterio%'
		and tipo='$tipo'
		order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVariable($idVariable)
	{    
		$sql=" select * from productos_variables 
		where idVariable=$idVariable ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerTipoVariable($tipo)
	{    
		$sql=" select variable".$tipo." as variable
		from configuracion  ";
		
		return $this->db->query($sql)->row()->variable;
	}
	
	public function registrarVariable()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre'),
			'tipo'		=> $this->input->post('tipo'),
		);
		
	    $this->db->insert('productos_variables',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarVariable()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre')
		);
		
		$this->db->where('idVariable',$this->input->post('idVariable'));
	    $this->db->update('productos_variables',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarVariable($idVariable,$tipo)
	{
		$sql="select count(idVariable".$tipo.") as numero
		from productos
		where idVariable".$tipo."=$idVariable ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function borrarVariable($idVariable,$tipo)
	{
		if($this->comprobarVariable($idVariable,$tipo)>0)
		{
			return "0";	
		}
		
	    $this->db->where('idVariable',$idVariable);
		$this->db->delete('productos_variables');
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//TIPOS DE DOCUMENTOS
	public function obtenerTiposDocumentos()
	{    
		$sql=" select * from recursos_personal_documentos_tipos
		where activo='1'
		order by idTipo asc ";
		
		return $this->db->query($sql)->result();
	}
	
	//TIPOS DE DOCUMENTOS
	public function obtenerTiposDocumentosCliente()
	{    
		$sql=" select * from clientes_documentos_tipos
		where activo='1'
		order by idTipo asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesCausaBaja($idCausa=0)
	{    
		$sql=" select * from clientes_bajas_causas_detalles
		where activo='1'
		and idCausa='$idCausa' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistrosCausaBajas()
	{    
		$sql=" select * from clientes_bajas_causas_detalles
		where activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesCausaNocuali($idCausa=0)
	{    
		$sql=" select * from clientes_nocuali_causas_detalles
		where activo='1'
		and idCausa='$idCausa' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistrosCausaNocuali()
	{    
		$sql=" select * from clientes_nocuali_causas_detalles
		where activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerClientesProspectos()
	{    
		$sql=" select * from seguimiento_prospectos
		where activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//NIVELES 1
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarNiveles1($criterio='')
	{    
		$sql=" select a.idNivel1
		from catalogos_niveles1 as a
		where a.activo='1' ";
		 
		 $sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerNiveles1($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.idNivel1, a.nombre,
		(select count(b.idNivel1) from catalogos_egresos as  b where b.idNivel1=a.idNivel1) as relaciones
		from catalogos_niveles1 as a
		where a.activo='1' ";
		 
		 $sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNivel1($idNivel1)
	{    
		$sql=" select * from catalogos_niveles1 
		where idNivel1=$idNivel1 ";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarNivel1()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre'),
		);
		
	    $this->db->insert('catalogos_niveles1',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarNivel1()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre')
		);
		
		$this->db->where('idNivel1',$this->input->post('idNivel1'));
	    $this->db->update('catalogos_niveles1',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarNivel1($idNivel1)
	{
	    $this->db->where('idNivel1',$idNivel1);
		$this->db->update('catalogos_niveles1',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//NIVELES 2
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarNiveles2($criterio='')
	{    
		$sql=" select a.idNivel2
		from catalogos_niveles2 as a
		where a.activo='1' ";
		 
		 $sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerNiveles2($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.idNivel2, a.nombre,
		(select b.nombre from catalogos_niveles1 as  b where b.idNivel1=a.idNivel1) as nivel1,
		(select count(b.idNivel2) from catalogos_egresos as  b where b.idNivel2=a.idNivel2) as relaciones
		from catalogos_niveles2 as a
		where a.activo='1' ";
		 
		 $sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNivel2($idNivel2)
	{    
		$sql=" select * from catalogos_niveles2 
		where idNivel2=$idNivel2 ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerNiveles2Catalogo($idNivel1)
	{    
		$sql=" select * from catalogos_niveles2 
		where idNivel1='$idNivel1'
		and activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNiveles3Catalogo($idNivel2)
	{    
		$sql=" select * from catalogos_niveles3 
		where idNivel2='$idNivel2'
		and activo='1'  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarNivel2()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre'),
			'idNivel1'	=> $this->input->post('idNivel1'),
		);
		
	    $this->db->insert('catalogos_niveles2',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarNivel2()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre')
		);
		
		$this->db->where('idNivel2',$this->input->post('idNivel2'));
	    $this->db->update('catalogos_niveles2',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarNivel2($idNivel2)
	{
	    $this->db->where('idNivel2',$idNivel2);
		$this->db->update('catalogos_niveles2',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//NIVELES 3
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarNiveles3($criterio='')
	{    
		$sql=" select a.idNivel3
		from catalogos_niveles3 as a
		where a.activo='1' ";
		 
		 $sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerNiveles3($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.idNivel3, a.nombre,
		(select b.nombre from catalogos_niveles2 as  b where b.idNivel2=a.idNivel2) as nivel2,
		(select count(b.idNivel3) from catalogos_egresos as  b where b.idNivel3=a.idNivel3) as relaciones
		from catalogos_niveles3 as a
		where a.activo='1' ";
		 
		 $sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNivel3($idNivel3)
	{    
		$sql=" select * from catalogos_niveles3 
		where idNivel3=$idNivel3 ";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarNivel3()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre'),
			'idNivel2'	=> $this->input->post('idNivel2'),
		);
		
	    $this->db->insert('catalogos_niveles3',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarNivel3()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre')
		);
		
		$this->db->where('idNivel3',$this->input->post('idNivel3'));
	    $this->db->update('catalogos_niveles3',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarNivel3($idNivel3)
	{
	    $this->db->where('idNivel3',$idNivel3);
		$this->db->update('catalogos_niveles3',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//FRECUENCIAS
	public function obtenerFrecuencias()
	{    
		$sql=" select * from sie_creditos_frecuencias 
		where activo='1'  ";
		
		return $this->db->query($sql)->result();
	}
	
	//GRADOS
	public function obtenerGrados()
	{    
		$sql=" select * from clientes_programas_grados 
		where activo='1'  ";
		
		return $this->db->query($sql)->result();
	}
	
	//TIPOS DE METAS
	public function obtenerTiposMetas()
	{    
		$sql=" select * from sie_prospectos_tipos 
		where activo='1'  ";
		
		return $this->db->query($sql)->result();
	}
	
	//TIPOS DE METAS
	public function obtenerMeses()
	{    
		$sql=" select * from meses   ";
		
		return $this->db->query($sql)->result();
	}
	
	
	public function obtenerRutas()
	{
		$sql=" select * from catalogos_rutas
		where activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMarcasProveedor($idProveedor=0)
	{    
		$sql=" select a.idMarca, a.nombre as value 
		from productos_marcas  as a
		where a.activo='1'
		and (select count(b.idRelacion) from productos_proveedores_marcas as b where b.idMarca=a.idMarca and b.idProveedor=$idProveedor) = 0 ";
		
		return $this->db->query($sql)->result();
	}
}

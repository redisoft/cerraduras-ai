<?php
class Nomina_modelo extends CI_Model
{
    protected $fecha;
    protected $idUsuario;
    protected $usuarioActivo;
	protected $idLicencia;
	protected $resultado;

    function __construct()
	{
		parent::__construct();
		
		$this->idUsuario 		=$this->session->userdata('id');
		$this->fecha 			=date('Y-m-d H:i:s');
		$this->usuarioActivo 	=$this->session->userdata('name');
		$this->resultado 		="1";
    }
	
	#PARA LOS CATÁLOGOS ESTATICOS
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerBancos()
	{    
		$sql=" select * from bancosnomina
		order by clave asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRiesgo()
	{    
		$sql=" select * from riesgo
		order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegimen()
	{    
		$sql=" select * from regimen
		order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	#PARA LOS PUESTOS
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerPuestos()
	{    
		$criterio	=$this->input->post('criterio');
		
		$sql=" select * from puestos
		where nombre like '%$criterio%'
		order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPuesto($idPuesto)
	{    
		$sql=" select * from puestos 
		where idPuesto=$idPuesto ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarPuestoEmpleado($nombre)
	{
		$sql ="select idPuesto
		from  puestos 
		where nombre='$nombre' ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarPuesto()
	{
		if(!$this->comprobarPuestoEmpleado($this->input->post('nombre')))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'	=>$this->input->post('nombre'),
			'idUsuario'	=>$this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('puestos',$data);
		
		$this->configuracion->registrarBitacora('Registrar puesto','Nómina - Puestos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function editarPuesto()
	{
		$data=array
		(
			'nombre'=>$this->input->post('nombre')
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idPuesto',$this->input->post('idPuesto'));
	    $this->db->update('puestos',$data);
		
		$this->configuracion->registrarBitacora('Editar puesto','Nómina - Puestos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarPuesto($idPuesto)
	{
		$sql="select count(idPuesto) as numero
		from empleados
		where idPuesto=$idPuesto ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function borrarPuesto($idPuesto)
	{
		if($this->comprobarPuesto($idPuesto)>0)
		{
			return "0";	
		}
		
		$puesto	= $this->obtenerPuesto($idPuesto);
		
	    $this->db->where('idPuesto',$idPuesto);
		$this->db->delete('puestos');
		
		if($puesto!=null)
		{
			$this->configuracion->registrarBitacora('Borrar puesto','Nómina - Puestos',$puesto->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#PARA LOS DEPARTAMENTOS
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerDepartamentos()
	{    
		$criterio	=$this->input->post('criterio');
		
		$sql=" select * from catalogos_departamentos
		where nombre like '%$criterio%'
		order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDepartamento($idDepartamento)
	{    
		$sql=" select * from catalogos_departamentos 
		where idDepartamento=$idDepartamento ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarDepartamentoEmpleado($nombre)
	{
		$sql="select idDepartamento
		from catalogos_departamentos
		where nombre='$nombre' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarDepartamento()
	{
		if(!$this->comprobarDepartamentoEmpleado($this->input->post('nombre')))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'	=>$this->input->post('nombre'),
			'idUsuario'	=>$this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('catalogos_departamentos',$data);
		
		$this->configuracion->registrarBitacora('Registrar departamento','Nómina - Departamentos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function editarDepartamento()
	{
		$data=array
		(
			'nombre'	=>$this->input->post('nombre')
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idDepartamento',$this->input->post('idDepartamento'));
	    $this->db->update('catalogos_departamentos',$data);
		
		$this->configuracion->registrarBitacora('Editar departamento','Nómina - Departamentos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarDepartamento($idDepartamento)
	{
		$sql="select count(idDepartamento) as numero
		from empleados
		where idDepartamento=$idDepartamento ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function borrarDepartamento($idDepartamento)
	{
		if($this->comprobarDepartamento($idDepartamento)>0)
		{
			return "0";	
		}
		
		$departamento	= $this->obtenerDepartamento($idDepartamento);
		
	    $this->db->where('idDepartamento',$idDepartamento);
		$this->db->delete('catalogos_departamentos');
		
		if($departamento!=null)
		{
			$this->configuracion->registrarBitacora('Borrar departamento','Nómina - Departamentos',$departamento->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#PARA LOS PERCEPCIONES
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerPercepciones()
	{    
		$sql=" select * from percepciones 
		order by clave asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function contarCatalogoPercepciones()
	{    
		$criterio	=$this->input->post('criterio');
		
		$sql=" select a.idPercepcion
		from catalogos_percepciones as a
		inner join percepciones as b
		on a.idPercepcion=b.idPercepcion
		where (a.concepto like '%$criterio%'
		or a.clave like '%$criterio%'
		or b.nombre like '%$criterio%') ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCatalogoPercepciones($numero,$limite)
	{    
		$criterio	=$this->input->post('criterio');
		
		$sql=" select a.*, b.nombre as percepcion,
		b.clave as clavePercepcion
		from catalogos_percepciones as a
		inner join percepciones as b
		on a.idPercepcion=b.idPercepcion
		where (a.concepto like '%$criterio%'
		or a.clave like '%$criterio%'
		or b.nombre like '%$criterio%')
		order by a.concepto asc ";
		
		$sql.= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPercepcion($idCatalogoPercepcion)
	{    
		$sql=" select * from catalogos_percepciones 
		where idCatalogoPercepcion=$idCatalogoPercepcion ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarPercepcionEmpleado($concepto,$clave)
	{
		$sql="select idPercepcion
		from catalogos_percepciones
		where concepto='$concepto'
		and clave='$clave' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarPercepcion()
	{
		if(!$this->comprobarPercepcionEmpleado($this->input->post('txtConcepto'),$this->input->post('txtClave')))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'idPercepcion'		=>$this->input->post('selectPercepciones'),
			'concepto'			=>$this->input->post('txtConcepto'),
			'clave'				=>$this->input->post('txtClave'),
			'importeGravado'	=>$this->input->post('txtImporteGravado'),
			'importeExento'		=>$this->input->post('txtImporteExento'),
			
			'idUsuario'			=>$this->idUsuario,
		);
		
	    $this->db->insert('catalogos_percepciones',$data);
		
		$this->configuracion->registrarBitacora('Registrar percepción','Nómina - Percepciones',$data['clave'].', '.$data['concepto']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function editarPercepcion()
	{
		$data=array
		(
			'idPercepcion'		=>$this->input->post('selectPercepciones'),
			'concepto'			=>$this->input->post('txtConcepto'),
			'clave'				=>$this->input->post('txtClave'),
			'importeGravado'	=>$this->input->post('txtImporteGravado'),
			'importeExento'		=>$this->input->post('txtImporteExento'),
		);
		
		$this->db->where('idCatalogoPercepcion',$this->input->post('txtIdCatalogoPercepcion'));
	    $this->db->update('catalogos_percepciones',$data);
		
		$this->configuracion->registrarBitacora('Editar percepción','Nómina - Percepciones',$data['clave'].', '.$data['concepto']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarPercepcion($idCatalogoPercepcion)
	{
		$percepcion	= $this->obtenerPercepcion($idCatalogoPercepcion);
		
		$this->db->where('idCatalogoPercepcion',$idCatalogoPercepcion);
		$this->db->delete('catalogos_percepciones');
		
		if($percepcion!=null)
		{
			$this->configuracion->registrarBitacora('Borrar percepción','Nómina - Percepciones',$percepcion->clave.', '.$percepcion->concepto); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#PARA LOS DEDUCCIONES
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerDeducciones()
	{    
		$sql=" select * from deducciones 
		order by clave asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function contarCatalogoDeducciones()
	{    
		$criterio	= $this->input->post('criterio');
		
		$sql=" select a.idDeduccion
		from catalogos_deducciones as a
		inner join deducciones as b
		on a.idDeduccion=b.idDeduccion
		where (a.concepto like '%$criterio%'
		or a.clave like '%$criterio%'
		or b.nombre like '%$criterio%') ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCatalogoDeducciones($numero,$limite)
	{    
		$criterio	= $this->input->post('criterio');
		
		$sql=" select a.*, b.nombre as deduccion,
		b.clave as claveDeduccion
		from catalogos_deducciones as a
		inner join deducciones as b
		on a.idDeduccion=b.idDeduccion
		where (a.concepto like '%$criterio%'
		or a.clave like '%$criterio%'
		or b.nombre like '%$criterio%')
		order by a.concepto asc ";
		
		$sql.= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDeduccion($idCatalogoDeduccion)
	{    
		$sql=" select * from catalogos_deducciones 
		where idCatalogoDeduccion=$idCatalogoDeduccion ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarDeduccionEmpleado($concepto,$clave)
	{
		$sql="select idDeduccion
		from catalogos_deducciones
		where concepto='$concepto'
		and clave='$clave' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarDeduccion()
	{
		if(!$this->comprobarDeduccionEmpleado($this->input->post('txtConcepto'),$this->input->post('txtClave')))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'idDeduccion'		=>$this->input->post('selectDeducciones'),
			'concepto'			=>$this->input->post('txtConcepto'),
			'clave'				=>$this->input->post('txtClave'),
			'importeGravado'	=>$this->input->post('txtImporteGravado'),
			'importeExento'		=>$this->input->post('txtImporteExento'),
			
			'idUsuario'			=>$this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('catalogos_deducciones',$data);
		
		$this->configuracion->registrarBitacora('Registrar deducción','Nómina - Deducciones',$data['clave'].', '.$data['concepto']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function editarDeduccion()
	{
		$data=array
		(
			'idDeduccion'		=>$this->input->post('selectDeducciones'),
			'concepto'			=>$this->input->post('txtConcepto'),
			'clave'				=>$this->input->post('txtClave'),
			'importeGravado'	=>$this->input->post('txtImporteGravado'),
			'importeExento'		=>$this->input->post('txtImporteExento'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idCatalogoDeduccion',$this->input->post('txtIdCatalogoDeduccion'));
	    $this->db->update('catalogos_deducciones',$data);
		
		$this->configuracion->registrarBitacora('Editar deducción','Nómina - Deducciones',$data['clave'].', '.$data['concepto']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarDeduccion($idCatalogoDeduccion)
	{
		$deduccion	= $this->obtenerDeduccion($idCatalogoDeduccion);
		
		$this->db->where('idCatalogoDeduccion',$idCatalogoDeduccion);
		$this->db->delete('catalogos_deducciones');
		
		if($deduccion!=null)
		{
			$this->configuracion->registrarBitacora('Borrar deducción','Nómina - Deducciones',$deduccion->clave.', '.$deduccion->concepto); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#PARA LOS EMPLEADOS
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarEmpleados()
	{    
		$criterio	=$this->input->post('criterio');
		
		$sql=" select count(a.idEmpleado) as numero
		from empleados as a
		where (a.nombre like '%$criterio%'
		or a.rfc like '%$criterio%'
		or a.curp like '%$criterio%'
		or a.numeroEmpleado like '%$criterio%'
		or a.registroPatronal like '%$criterio%' ) ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerEmpleados($numero,$limite)
	{    
		$criterio	= $this->input->post('criterio');
		
		$sql=" select a.*, 
		(select b.nombre from catalogos_departamentos as b where a.idDepartamento=b.idDepartamento) as departamento,
		(select c.nombre from puestos as c where a.idPuesto=c.idPuesto) as puesto
		from empleados as a
		where (a.nombre like '%$criterio%'
		or a.rfc like '%$criterio%'
		or a.curp like '%$criterio%'
		or a.numeroEmpleado like '%$criterio%'
		or a.registroPatronal like '%$criterio%' )
		order by a.nombre asc ";
		
		$sql.= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEmpleado($idEmpleado)
	{    
		$sql=" select a.*,  b.nombre as regimen,
		(select b.nombre from catalogos_departamentos as b where a.idDepartamento=b.idDepartamento) as departamento,
		(select c.nombre from puestos as c where a.idPuesto=c.idPuesto) as puesto,
		(select d.nombre from bancosnomina as d where a.idBanco=d.idBanco) as banco,
		(select e.clave from bancosnomina as e where a.idBanco=e.idBanco) as claveBanco,
		(select f.nombre from riesgo as f where a.idRiesgo=f.idRiesgo) as riesgo
		from empleados as a
		inner join regimen as b
		on a.idRegimen=b.idRegimen
		where a.idEmpleado='$idEmpleado' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarEmpleado($nombre,$numeroEmpleado)
	{
		$sql="select idEmpleado
		from empleados
		where nombre='$nombre'
		and numeroEmpleado='$numeroEmpleado' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarEmpleado()
	{
		if(!$this->comprobarEmpleado($this->input->post('txtNombre'),$this->input->post('txtNumeroEmpleado')))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'			=>$this->input->post('txtNombre'),
			'numeroEmpleado'	=>$this->input->post('txtNumeroEmpleado'),
			'registroPatronal'	=>$this->input->post('txtRegistroPatronal'),
			'curp'				=>$this->input->post('txtCurp'),
			'rfc'				=>$this->input->post('txtRfc'),
			'idRegimen'			=>$this->input->post('selectRegimen'),
			'numeroSeguridad'	=>$this->input->post('txtNumeroSeguro'),
			'fechaInicio'		=>$this->input->post('txtFechaInicio'),
			'idDepartamento'	=>$this->input->post('selectDepartamentos'),
			'idPuesto'			=>$this->input->post('selectPuestos'),
			'tipoContrato'		=>$this->input->post('selectTipoContrato'),
			'tipoJornada'		=>$this->input->post('selectTipoJornada'),
			'periodicidadPago'	=>$this->input->post('selectPeriodicidadPago'),
			'idRiesgo'			=>$this->input->post('selectRiesgo'),
			'idBanco'			=>$this->input->post('selectBancos'),
			'clabe'				=>$this->input->post('txtClabe'),
			'email'				=>$this->input->post('txtEmail'),
			'salarioDiario'		=>$this->input->post('txtSalarioDiario'),
			'salarioBase'		=>$this->input->post('txtSalarioBase'),
			
			'idUsuario'			=>$this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('empleados',$data);
		
		$this->configuracion->registrarBitacora('Registrar empleado','Nómina - Empleados',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function editarEmpleado()
	{
		$data=array
		(
			'nombre'			=>$this->input->post('txtNombre'),
			'numeroEmpleado'	=>$this->input->post('txtNumeroEmpleado'),
			'registroPatronal'	=>$this->input->post('txtRegistroPatronal'),
			'curp'				=>$this->input->post('txtCurp'),
			'rfc'				=>$this->input->post('txtRfc'),
			'idRegimen'			=>$this->input->post('selectRegimen'),
			'numeroSeguridad'	=>$this->input->post('txtNumeroSeguro'),
			'fechaInicio'		=>$this->input->post('txtFechaInicio'),
			'idDepartamento'	=>$this->input->post('selectDepartamentos'),
			'idPuesto'			=>$this->input->post('selectPuestos'),
			'tipoContrato'		=>$this->input->post('selectTipoContrato'),
			'tipoJornada'		=>$this->input->post('selectTipoJornada'),
			'periodicidadPago'	=>$this->input->post('selectPeriodicidadPago'),
			'idRiesgo'			=>$this->input->post('selectRiesgo'),
			'idBanco'			=>$this->input->post('selectBancos'),
			'clabe'				=>$this->input->post('txtClabe'),
			'email'				=>$this->input->post('txtEmail'),
			'salarioDiario'		=>$this->input->post('txtSalarioDiario'),
			'salarioBase'		=>$this->input->post('txtSalarioBase'),
			
			'idUsuario'			=>$this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idEmpleado',$this->input->post('txtIdEmpleado'));
	    $this->db->update('empleados',$data);
		
		$this->configuracion->registrarBitacora('Editar empleado','Nómina - Empleados',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerEmpleadoDetalle($idEmpleado)
	{
		$sql=" select nombre
		from empleados
		where idEmpleado='$idEmpleado' ";
		
		$empleado	= $this->db->query($sql)->row();
		
		return $empleado!=null?$empleado->nombre:'';
	}
	
	public function borrarEmpleado($idEmpleado)
	{
		$this->configuracion->registrarBitacora('Borrar empleado','Nómina - Empleados',$this->obtenerEmpleadoDetalle($idEmpleado)); //Registrar bitácora
		
		$this->db->where('idEmpleado',$idEmpleado);
		$this->db->delete('empleados');
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//FACTURACIÓN ELECTRONICA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerDiasTrabajados($inicio,$fin)
	{
		$sql=" select timestampdiff(day,'$inicio','$fin') as dias";
		
		return $this->db->query($sql)->row()->dias+1;
	}
	
	public function obtenerAntiguedad($inicio,$fin)
	{
		$sql=" select timestampdiff(week,'$inicio','$fin') as semanas";
		
		return $this->db->query($sql)->row()->semanas;
	}
	
	public function registrarRecibo($idEmpleado)
	{
		$this->load->helper('sat');

		#$idEmpleado		=$this->input->post('txtIdEmpleado');
		$idEmisor		=$this->input->post('selectEmisores');
		$idDivisa		=1;#$this->input->post('idDivisa');
		$data			=array();

		$empleado		=$this->obtenerEmpleado($idEmpleado);
		$antiguedad		=$this->obtenerAntiguedad($empleado->fechaInicio,date('Y-m-d'));
		$configuracion	=$this->facturacion->obtenerEmisor($idEmisor);
		$divisa			=$this->facturacion->obtenerDivisa(1);

		if(strlen($empleado->rfc)<12 or strlen($empleado->nombre)<5 or strlen($empleado->curp)<10 or strlen($empleado->numeroEmpleado)<1) 
		{
			$data[0]	="0"; #EL EMPLEADO NO CUENTA CON LOS DATOS FISCALES NECESARIOS PARA REGISTRAR EL RECIBO
			$data[1]	="El empleado no tiene los datos fiscales necesarios para crear el recibo";
			
			return $data;
		}
		
		$folio	=$this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$data[0]	="0"; #EL EMISOR NO TIENE FOLIOS DISPONIBLES
			$data[1]	="Sin folios suficientes para crear el comprobante";
			
			return $data;
		}
		
		$this->db->trans_start(); 
		
		$carpetaFel			='media/fel/';
		$carpetaUsuario		='media/fel/'.$configuracion->rfc.'/';
		$carpetaFolio		=$carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				=$carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				="";
		$certificado		="";
		$ficheroXML			=crearXmlRecibo($configuracion,$empleado,$sello,$certificado,$this->fecha,$folio,$divisa,$antiguedad);

		guardarArchivoXML($cfd,$ficheroXML);

		exec("xsltproc ".$carpetaFel.'cadenaoriginal_3_2.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt');
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha1 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');

		$certificado	=leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	=QuitarEspaciosXML($certificado,"B");
		$sello			=leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			=QuitarEspaciosXML($sello,"B");
		$cadena			=leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		=crearXmlRecibo($configuracion,$empleado,$sello,$certificado,$this->fecha,$folio,$divisa,$antiguedad);

		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$empleado,$configuracion,$divisa,$antiguedad);
		}
		
		$this->configuracion->registrarBitacora('Registrar recibo de nómina','Contabilidad - Nómina',$empleado->nombre.', $'.number_format($this->input->post('txtTotales'),decimales)); //Registrar bitácora
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			$data[0]	="0";
			$data[1]	=$this->resultado;
			
			return $data;
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();

			$data[0]	="1";
			$data[1]	='El recibo con folio '.$configuracion->serie.$folio.' se ha creado correctamente';
			
			return $data;
		}
	}
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$empleado,$configuracion,$divisa,$antiguedad)
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
			
			$this->resultado	=$respuesta['mensaje'];
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

			$this->registrarRegistroRecibo($data,$empleado,$configuracion,$divisa,$antiguedad);
		}
	}
	
	public function registrarRegistroRecibo($timbre,$empleado,$configuracion,$divisa,$antiguedad)
	{
		$data=array
		(
			'idCliente'					=>0,
			'idCotizacion'				=>0,
			'rfc'						=>$empleado->rfc,
			'empresa'					=>$empleado->nombre,
			'calle'						=>'',
			'numeroExterior'			=>'',
			'numeroInterior'			=>'',
			'colonia'					=>'',
			'localidad'					=>'',
			'municipio'					=>'',
			'estado'					=>'',
			'pais'						=>'México',
			'codigoPostal'				=>'',

			'telefono'					=>'',
			'email'						=>$empleado->email,
			
			'subTotal'					=>$this->input->post('txtPercepciones'),
			'descuento'					=>$this->input->post('txtDeducciones')-$this->input->post('txtTotalIsr'),
			#'descuentoPorcentaje'		=>0,
			'iva'						=>0,
			#'ivaPorcentaje'				=>0,
			'total'						=>$this->input->post('txtTotales'),
			
			'fecha'						=>$this->fecha,
			'folio'						=>$timbre['folio'],
			'serie'						=>$configuracion->serie,
			'xml'						=>$timbre['xml'],

			'cadenaOriginal'			=>$timbre['cadenaOriginal'],
			'selloSat'					=>$timbre['selloSat'],
			'selloDigital'				=>$timbre['selloDigital'],
			'UUID'						=>$timbre['UUID'],
			'certificadoSat'			=>$timbre['certificado'],
			'cadenaTimbre'				=>$timbre['cadenaTimbre'],	
			'fechaTimbrado'				=>$timbre['fechaTimbrado'],
			
			'documento'					=>'Recibo de Nómina',
			'tipoComprobante'			=>'egreso',
			'condicionesPago'			=>'',
			'metodoPago'				=>$this->input->post('txtMetodoPagoTexto'),
			'formaPago'					=>$this->input->post('txtFormaPago'),
			'idEmisor'					=>$this->input->post('selectEmisores'),
			
			#'notas'						=>$configuracion->notas,
			#'sucursales'				=>$configuracion->sucursales,
			
			'tipoCambio'				=>$divisa->tipoCambio,
			'divisa'					=>$divisa->nombre,
			'claveDivisa'				=>$divisa->clave,
			
			'retencionIsr'				=>$this->input->post('txtTotalIsr'),
			'totalGravadoPercepciones'	=>$this->input->post('txtTotalGravadoPercepciones'),
			'totalExentoPercepciones'	=>$this->input->post('txtTotalExentoPercepciones'),
			'totalGravadoDeducciones'	=>$this->input->post('txtTotalGravadoDeducciones'),
			'totalExentoDeducciones'	=>$this->input->post('txtTotalExentoDeducciones'),
		);
		
		$this->db->insert('facturas',$data);
		$idFactura = $this->db->insert_id();
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#-------------------------------------------------------------------------------------#

		$data=array
		(
			'idFactura'		=>$idFactura,
			'idProducto'	=>0,
			'nombre'		=>$this->input->post('txtConcepto'),
			'unidad'		=>'Servicio',
			#'clave'			=>'',
			'precio'		=>$this->input->post('txtTotales'),
			'importe'		=>$this->input->post('txtTotales'),
			'cantidad'		=>1,
		);
		
		$this->db->insert('facturas_detalles',$data);
		$idDetalle	=$this->db->insert_id();
		
		//REGISTRAR LAS PERCEPCIONES
		for($i=0;$i<$this->input->post('txtNumeroPercepciones');$i++)
		{
			if(strlen($this->input->post('txtTipoPercepcion'.$i))>0)
			{
				$data=array
				(
					'idFactura'			=>$idFactura,
					'concepto'			=>$this->input->post('txtConceptoPercepcion'.$i),
					'clave'				=>$this->input->post('txtClavePercepcion'.$i),
					'importeGravado'	=>$this->input->post('txtImporteGravadoPercepcion'.$i),
					'importeExento'		=>$this->input->post('txtImporteExentoPercepcion'.$i),
					'tipoPercepcion'	=>$this->input->post('txtTipoPercepcion'.$i),
					'nombrePercepcion'	=>$this->input->post('txtNombrePercepcion'.$i),
				);
				
				$this->db->insert('facturas_percepciones',$data);
			}
		}
		
		//REGISTRAR LAS DEDUCCIONES
		for($i=0;$i<$this->input->post('txtNumeroDeducciones');$i++)
		{
			if(strlen($this->input->post('txtTipoDeduccion'.$i))>0)
			{
				$data=array
				(
					'idFactura'			=>$idFactura,
					'concepto'			=>$this->input->post('txtConceptoDeduccion'.$i),
					'clave'				=>$this->input->post('txtClaveDeduccion'.$i),
					'importeGravado'	=>$this->input->post('txtImporteGravadoDeduccion'.$i),
					'importeExento'		=>$this->input->post('txtImporteExentoDeduccion'.$i),
					'tipoDeduccion'		=>$this->input->post('txtTipoDeduccion'.$i),
					'nombreDeduccion'	=>$this->input->post('txtNombreDeduccion'.$i),
				);
				
				$this->db->insert('facturas_deducciones',$data);
			}
		}
		
		//REGISTRAR LOS DATOS DEL EMPLEADO
		$data=array
		(
			'idFactura'			=>$idFactura,
			'nombre'			=>$empleado->nombre,
			'numeroEmpleado'	=>$empleado->numeroEmpleado,
			'registroPatronal'	=>$empleado->registroPatronal,
			'curp'				=>$empleado->curp,
			'rfc'				=>$empleado->rfc,
			'regimen'			=>$empleado->regimen,
			'numeroSeguridad'	=>$empleado->numeroSeguridad,
			'banco'				=>$empleado->banco,
			'clabe'				=>$empleado->clabe,
			'fechaInicio'		=>$empleado->fechaInicio,
			'tipoContrato'		=>$empleado->tipoContrato,
			'tipoJornada'		=>$empleado->tipoJornada,
			'periodicidadPago'	=>$empleado->periodicidadPago,
			'riesgo'			=>$empleado->riesgo,
			'salarioDiario'		=>$empleado->salarioDiario,
			'salarioBase'		=>$empleado->salarioBase,
			'puesto'			=>$empleado->puesto,
			'departamento'		=>$empleado->departamento,
			'diasTrabajados'	=>$this->input->post('txtDiasTrabajados'),
			'antiguedad'		=>$antiguedad,
			'fechaPago'			=>$this->input->post('txtFechaPago'),
			'fechaInicialPago'	=>$this->input->post('txtFechaInicialPago'),
			'fechaFinalPago'	=>$this->input->post('txtFechaFinalPago'),
		);
		
		$this->db->insert('facturas_empleados',$data);
	}
	
	//PARA OBTENER LOS RECIBOS
	public function contarRecibos($mes,$anio,$criterio)
	{
		$sql ="	select a.fecha
		from facturas as a
		inner join facturas_empleados as b
		on a.idFactura=b.idFactura
		where a.idFactura>0
		and ( a.folio like '%$criterio%'
		or b.nombre like '%$criterio%'
		or b.numeroEmpleado like '%$criterio%' ) ";

		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerRecibos($numero,$limite,$mes,$anio,$criterio)
	{
		$sql ="	select a.fecha, a.total, a.subTotal, 
		a.folio, a.serie, a.documento,
		a.cancelada, a.idFactura, a.cancelada,
		c.rfc, c.nombre as emisor,
		d.nombre as empleado
		from facturas as a
		inner join configuracion_emisores as c
		on a.idEmisor=c.idEmisor
		inner join facturas_empleados as d
		on a.idFactura=d.idFactura
		where a.idFactura>0
		and ( a.folio like '%$criterio%'
		or d.nombre like '%$criterio%'
		or d.numeroEmpleado like '%$criterio%' )  ";

		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';
		$sql.=" order by a.fecha desc ";
		$sql .=$numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
}
?>

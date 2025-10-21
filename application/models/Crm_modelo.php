<?php
class Crm_modelo extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $resultado;
	protected $_user_id;
	protected $fecha;
	protected $hora;
	protected $horaMedia;
	protected $idRol;

	function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		$this->_table 			= $this->config->item('datatables');

        $this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');

		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->resultado		= "1";
		$this->fecha 			= date('Y-m-d');
		$this->hora 			= date('H:i:s');
		$this->horaMedia 		= date('H:i:00');
		$this->idRol 			= $this->session->userdata('role');
	}
	
	public function registrarCrm()
	{
		$fecha			= trim($this->input->post('fecha'));
		$idStatus		= $this->input->post('idStatus');
		$comentarios	= $this->input->post('comentarios');
		$idResponsable	= $this->input->post('idResponsable');
		
		$data=array
		(
			'comentarios'		=> $comentarios,
			'bitacora' 			=> $this->input->post('bitacora'),
			'email' 			=> $this->input->post('email'),
			'fecha'				=> $fecha,
			'idCliente' 		=> $this->input->post('idCliente'),
			'idStatus' 			=> $idStatus,
			'idServicio' 		=> $this->input->post('idServicio'),
			'idResponsable' 	=> $idResponsable,
			#'monto' 			=> $this->input->post('monto'),
			'fechaCierre' 		=> trim($this->input->post('fechaCierre')),
			'lugar'				=> $this->input->post('lugar'),
			'tipo' 				=> 0,
			'idLicencia'		=> $this->idLicencia,
			'idTiempo'			=> $this->input->post('idTiempo'),
			
			'idContacto'		=> $this->input->post('idContacto'),
		);
		
		$this->db->insert('seguimiento', $data);
		
		if($idStatus==4)
		{
			$this->enviarCorreoLlamada($idResponsable,$comentarios,$fecha);
		}
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	//CRM PARA COTIZACIONES Y VENTAS --------- CLIENTES
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	public function obtenerUltimoSeguimientoCotizacion($idCotizacion,$permiso=0)
	{
		$sql="select a.*, b.nombre as servicio,
		d.nombre as status, d.color, d.idStatusIgual,
		concat(c.nombre,' ',c.apellidoPaterno,' ', c.apellidoMaterno) as responsable
		from seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where a.idCotizacion='$idCotizacion' ";
		
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		$sql.=" order by fecha desc
		limit 1 ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUltimoSeguimientoVenta($idCotizacion,$permiso=0)
	{
		$sql="select a.*, b.nombre as servicio,
		d.nombre as status, d.color, d.idStatusIgual,
		concat(c.nombre,' ',c.apellidoPaterno,' ', c.apellidoMaterno) as responsable
		from seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where a.idVenta='$idCotizacion' ";
		
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		$sql.=" order by fecha desc
		limit 1 ";	
		
		#echo $sql; exit;
		return $this->db->query($sql)->row();
	}
	
	public function obtenerClienteSeguimientoCotizacion($idCotizacion)
	{
		$sql=" select a.idCliente, b.empresa as  cliente, c.serie
		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotizaciones as c
		on a.idCotizacion=c.idCotizacion
		where a.idCotizacion='$idCotizacion'
		order by a.fecha desc
		limit 1 ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function contarSeguimientoServicio($idCliente,$inicio,$fin,$idServicio,$idCotizacion=0,$permiso=0)
	{
		$sql=" select a.idSeguimiento
		from seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		where a.idServicio='$idServicio'
		and tipo=0
		and a.idLicencia='$this->idLicencia'  ";	
		
		$sql.=$idCliente>0?"and  a.idCliente='$idCliente'":'';
		if($idServicio==1) $sql.=$idCotizacion>0?"and a.idCotizacion='$idCotizacion'":'';
		if($idServicio==2) $sql.=$idCotizacion>0?"and a.idVenta='$idCotizacion'":'';
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		
		#$sql.=" and a.fecha between '$inicio' and '$fin' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerSeguimientoServicio($numero,$limite,$idCliente,$inicio,$fin,$idServicio,$idCotizacion,$permiso=0)
	{
		$sql=" select a.*, b.nombre as servicio,
		concat(c.nombre,' ', c.apellidoPaterno,' ',c.apellidoMaterno) as responsable,
		d.nombre as status, d.color, d.idStatusIgual,
		
		(select concat('Folio: ',g.folio, ', Serie: ', g.serie) from cotizaciones as g where g.idCotizacion=a.idCotizacion) as cotizacion,
		(select concat('Folio: ',g.folio, ', Orden: ', g.ordenCompra) from cotizaciones as g where g.idCotizacion=a.idVenta) as venta
		
		from seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		
		where a.idServicio='$idServicio'
		and a.tipo=0
		and a.idLicencia='$this->idLicencia' ";	
		
		$sql.=$idCliente>0?"and  a.idCliente='$idCliente'":'';
		if($idServicio==1) $sql.=$idCotizacion>0?"and a.idCotizacion='$idCotizacion'":'';
		if($idServicio==2) $sql.=$idCotizacion>0?"and a.idVenta='$idCotizacion'":'';
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		#$sql.=" and a.fecha between '$inicio' and '$fin' ";
		
		$sql .= " order by fecha desc
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFolioSeguimientoCliente($tipo='0')
	{
		$sql=" select coalesce(max(folio),0) as folio
		from seguimiento
		where idLicencia='$this->idLicencia'
		and tipo='$tipo' ";	

		return $this->db->query($sql)->row()->folio+1;
	}
	
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	//CRM PARA COMPRAS --------- PROVEEDORES
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	public function obtenerUltimoSeguimientoCompra($idCompra)
	{
		$sql=" select a.*, b.nombre as servicio,
		d.nombre as status, d.color, d.idStatusIgual,
		concat(c.nombre,' ',c.apellidoPaterno,' ', c.apellidoMaterno) as responsable
		from proveedores_seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where a.idCompra='$idCompra'
		and a.idLicencia='$this->idLicencia'
		order by fecha desc
		limit 1 ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function contarSeguimientoServicioCompras($idProveedor,$inicio,$fin,$idServicio,$idCompra=0)
	{
		$sql=" select a.idSeguimiento
		from proveedores_seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		where a.idServicio='$idServicio'
		and tipo=0 
		and a.idLicencia='$this->idLicencia'";	
		
		$sql.=$idProveedor>0?"and  a.idProveedor='$idProveedor'":'';
		$sql.=$idCompra>0?"and a.idCompra='$idCompra'":'';
		
		
		#$sql.=" and a.fecha between '$inicio' and '$fin' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerSeguimientoServicioCompras($numero,$limite,$idProveedor,$inicio,$fin,$idServicio,$idCompra)
	{
		$sql=" select a.*, b.nombre as servicio,
		concat(c.nombre,' ', c.apellidoPaterno,' ',c.apellidoMaterno) as responsable,
		d.nombre as status, d.color, d.idStatusIgual,
		
		(select concat(g.nombre) from compras as g where g.idCompras=a.idCompra) as venta
		
		from proveedores_seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		
		where a.idServicio='$idServicio'
		and a.tipo=0
		and a.idLicencia='$this->idLicencia' ";	
		
		$sql.=$idProveedor>0?"and  a.idProveedor='$idProveedor'":'';
		$sql.=$idCompra>0?"and a.idCompra='$idCompra'":'';
		
		#$sql.=" and a.fecha between '$inicio' and '$fin' ";
		
		$sql .= " order by fecha desc
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFolioSeguimientoProveedor()
	{
		$sql=" select coalesce(max(folio),0) as folio
		from proveedores_seguimiento
		where idLicencia='$this->idLicencia' ";	

		return $this->db->query($sql)->row()->folio+1;
	}
	
	/*public function registrarDetalleSeguimiento()
	{
		$data=array
		(
			'fechaRegistro'		=> $this->_fecha_actual,
			'idSeguimiento' 	=> $this->input->post('txtIdSeguimiento'),
			'fecha' 			=> $this->input->post('txtFechaSeguimiento'),
			'hora' 				=> $this->input->post('txtHoraSeguimiento'),
			'observaciones' 	=> $this->input->post('txtObservacionesSeguimiento'),
			'idUsuario' 		=> $this->input->post('selectResponsableDetalle'),
		);
		
		$this->db->insert('seguimiento_detalles', $data);

		return ($this->db->affected_rows() >= 1)? array('1') : array('0') ;
	}*/
	
	public function registrarDetalleSeguimiento()
	{
		$data=array
		(
			'fechaRegistro'		=> $this->_fecha_actual,
			'idSeguimiento' 	=> $this->input->post('txtIdSeguimiento'),
			'fecha' 			=> $this->input->post('txtFechaSeguimiento'),
			'hora' 				=> $this->input->post('txtHoraCierre'),
			'observaciones' 	=> $this->input->post('txtObservacionesSeguimiento'),
			'idUsuario' 		=> $this->input->post('selectResponsableDetalle'),
			
			
			'fechaSeguimiento' 	=> $this->input->post('txtFechaSeguimiento'),
			'horaInicial' 		=> $this->input->post('txtHoraCierre'),
			'horaFinal' 		=> $this->input->post('txtHoraCierreFin'),
			'alerta' 			=> $this->input->post('chkAlertaSeguimiento')=='1'?'1':'0',
		);
		
		if(sistemaActivo=='IEXE')
		{
			$data['idCampana']	= $this->clientes->obtenerCampanaClienteSeguimiento($this->input->post('txtIdSeguimiento'));
		}
		
		$this->db->insert('seguimiento_detalles', $data);

		return ($this->db->affected_rows() >= 1)? array('1') : array('0') ;
	}
	
	public function obtenerDetallesSeguimiento($idSeguimiento)
	{
		$sql=" select a.*,
		(select  concat(b.nombre,' ', b.apellidoPaterno,' ',b.apellidoMaterno)  from usuarios as b where b.idUsuario=a.idUsuario) as usuario
		from seguimiento_detalles as a
		where a.idSeguimiento='$idSeguimiento' ";	

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesSeguimientoFecha($idSeguimiento,$fecha)
	{
		$sql=" select a.*,
		(select  concat(b.nombre,' ', b.apellidoPaterno,' ',b.apellidoMaterno)  from usuarios as b where b.idUsuario=a.idUsuario) as usuario
		from seguimiento_detalles as a
		where a.idSeguimiento='$idSeguimiento'
		and fecha='$fecha'
		order by horaInicial desc ";	

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesSeguimientoFechas($idSeguimiento)
	{
		$sql=" select a.*,
		(select  concat(b.nombre,' ', b.apellidoPaterno,' ',b.apellidoMaterno)  from usuarios as b where b.idUsuario=a.idUsuario) as usuario
		from seguimiento_detalles as a ";
		
		$sql.=" inner join clientes_campanas as c
		on a.idCampana=c.idCampana ";
		
		$sql.=" where a.idSeguimiento='$idSeguimiento' ";
		
		$sql.=" and c.fechaFinal >curdate() ";
		
		$sql.=" order by a.fechaSeguimiento desc, a.horaInicial desc ";	

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesSeguimientoCliente($idCliente)
	{
		$sql=" select a.*,
		(select  concat(b.nombre,' ', b.apellidoPaterno,' ',b.apellidoMaterno)  from usuarios as b where b.idUsuario=a.idUsuario) as usuario
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		where b.idCliente='$idCliente'
		order by a.fechaSeguimiento desc, a.horaInicial desc ";	

		return $this->db->query($sql)->result();
	}
	
	public function obtenerUltimoSeguimiento($idCliente)
	{
		$sql=" select a.*
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		where b.idCliente='$idCliente'
		order by a.fechaRegistro desc ";	

		return $this->db->query($sql)->row();
	}
	
	public function registrarDetalleSeguimientoFecha($baja=0)
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			'fechaRegistro'		=> $this->_fecha_actual,
			'idSeguimiento' 	=> $this->input->post('txtIdSeguimiento'),
			'fecha' 			=> $this->fecha,
			'hora' 				=> $this->hora,
			'observaciones' 	=> $this->input->post('txtComentarios'),
			'fechaSeguimiento' 	=> $baja==0?$this->input->post('txtFechaCierreEditar'):$this->_fecha_actual,
			'horaInicial' 		=> $this->input->post('txtHoraCierre'),
			'horaFinal' 		=> $this->input->post('txtHoraCierreFin'),
			'alerta' 			=> $this->input->post('chkAlertaSeguimiento')=='1'?'1':'0',
			
			'idCampana' 		=> $this->clientes->obtenerCampanaCliente($this->input->post('txtClienteId')),
			
			'idEmbudo' 			=> $this->input->post('rdEmbudo')>0?$this->input->post('rdEmbudo'):'',
			'idDetalleEmbudo' 	=> $this->input->post('selectDetallesEmbudo')>0?$this->input->post('selectDetallesEmbudo'):0,
			
			'interesado' 		=> $this->input->post('selectInteresado'),
			'cualificado' 		=> $this->input->post('selectCualificado'),
			
			'fichaPagoEnviada' 	=> $this->input->post('chkFichaPagoEnviada')=='1'?'1':'0',
			
			'idPromotor' 		=> $this->input->post('txtIdPromotor'),
			
			#'idUsuario' 		=> $this->input->post('selectResponsableDetalle'),
		);
		
		$this->db->insert('seguimiento_detalles', $data);
		$idDetalle	= $this->db->insert_id();
		
		//QUITARLE LO NUEVO
		$this->db->where('idCliente', $this->input->post('txtClienteId'));
		$this->db->update('clientes', array('nuevoRegistro'=>'0','reasignado'=>'0'));
		
		if($baja==0)
		{
			$this->registrarVentaPrograma(); //REGISTRAR LA VENTA DEL PROGRAMA
		}
		
		//METODOS
		$this->registrarMetodosDetalle($idDetalle);

		#return ($this->db->affected_rows() >= 1)? "1" : "0" ;
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function registrarMetodosDetalle($idDetalle=0)
	{
		$metodos = $this->input->post('txtNumeroMetodos');
		
		for($i=0;$i<$metodos;$i++)
		{
			if($this->input->post('chkMetodo'.$i)>0)
			{
				$this->db->insert('seguimiento_detalles_metodos', array('idDetalle'=>$idDetalle,'idMetodo'=>$this->input->post('chkMetodo'.$i),'contactado'=>$this->input->post('rdMetodoEmbudo'.$i)));
			}
			
		}
	}
	
	public function registrarDetalleBajaSeguimientoProspecto()
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			'fechaRegistro'		=> $this->_fecha_actual,
			'idSeguimiento' 	=> $this->input->post('txtIdSeguimiento'),
			'fecha' 			=> $this->fecha,
			'hora' 				=> $this->hora,
			'observaciones' 	=> $this->input->post('txtComentarios'),
			'fechaSeguimiento' 	=> $this->_fecha_actual,
			'horaInicial' 		=> $this->input->post('txtHoraCierre'),
			'horaFinal' 		=> $this->input->post('txtHoraCierreFin'),
			'alerta' 			=> $this->input->post('chkAlertaSeguimiento')=='1'?'1':'0',
			'idCampana' 		=> $this->clientes->obtenerCampanaCliente($this->input->post('txtClienteId')),
			'idEmbudo' 			=> $this->input->post('rdEmbudo')>0?$this->input->post('rdEmbudo'):'',
			'idDetalleEmbudo' 	=> $this->input->post('selectDetallesEmbudo')>0?$this->input->post('selectDetallesEmbudo'):0,
			'interesado' 		=> '0',
			'idProspecto' 		=> 0,
			'cualificado' 		=> '0',
			
			'idPromotor' 		=> $this->input->post('txtIdPromotor'),
		);
		
		$this->db->insert('seguimiento_detalles', $data);
		
		
		$this->db->where('idCliente', $this->input->post('txtClienteId'));
		$this->db->update('clientes', array('nuevoRegistro'=>'0','reasignado'=>'0'));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function registrarNocualiProspectoBaja()
	{
		$this->db->where('idCliente',$this->input->post('txtClienteId'));
		$this->db->update('clientes',array('idZona'=>8));
		
		$data=array
		(
			'fecha'			=> $this->_fecha_actual,
			'idCliente'		=> $this->input->post('txtClienteId'),
			'idCausa'		=> $this->input->post('selectEstatusNoCualificado'),
			'texto'			=> $this->input->post('txtTextoNoCualificado')
		);
		
		$this->db->insert('clientes_nocuali',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	
	public function registrarDetalleSeguimientoProspecto($baja=0)
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			'fechaRegistro'		=> $this->_fecha_actual,
			'idSeguimiento' 	=> $this->input->post('txtIdSeguimiento'),
			'fecha' 			=> $this->fecha,
			'hora' 				=> $this->hora,
			'observaciones' 	=> $this->input->post('txtComentarios'),
			'fechaSeguimiento' 	=> $baja==0?$this->input->post('txtFechaCierreEditar'):$this->_fecha_actual,
			'horaInicial' 		=> $this->input->post('txtHoraCierre'),
			'horaFinal' 		=> $this->input->post('txtHoraCierreFin'),
			'alerta' 			=> $this->input->post('chkAlertaSeguimiento')=='1'?'1':'0',
			'idCampana' 		=> $this->clientes->obtenerCampanaCliente($this->input->post('txtClienteId')),
			'idEmbudo' 			=> $this->input->post('rdEmbudo')>0?$this->input->post('rdEmbudo'):'',
			'idDetalleEmbudo' 	=> $this->input->post('selectDetallesEmbudo')>0?$this->input->post('selectDetallesEmbudo'):0,
			'interesado' 		=> $baja==0?'1':'0',
			'idProspecto' 		=> $this->input->post('selectProspectos'),
			'cualificado' 		=> '1',
			'fichaPagoEnviada' 	=> $this->input->post('chkFichaPagoEnviada')=='1'?'1':'0',
			
			'idPromotor' 		=> $this->input->post('txtIdPromotor'),
		);
		
		$this->db->insert('seguimiento_detalles', $data);
		$idDetalle	= $this->db->insert_id();
		
		$this->db->where('idCliente', $this->input->post('txtClienteId'));
		$this->db->update('clientes', array('reasignado'=>'0','nuevoRegistro'=>'0'));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function registrarDetalleNocualiProspecto($idDetalle)
	{
		$this->db->where('idCliente',$this->input->post('txtClienteId'));
		$this->db->update('clientes',array('idZona'=>8));
		
		$data=array
		(
			'fecha'			=> $this->_fecha_actual,
			'idCausa'		=> 5,
			'idCliente'		=> $this->input->post('txtClienteId'),
			'idDetalle'		=> $idDetalle,
			'texto'			=> $this->input->post('txtTextoProspecto')
		);
		
		$this->db->insert('clientes_nocuali',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	/*public function registrarMetodosDetalle($idDetalle=0)
	{
		$metodos = $this->input->post('metodos');
		
		for($i=0;$i<count($metodos);$i++)
		{
			if(isset($metodos[$i]))
			{
				$this->db->insert('seguimiento_detalles_metodos', array('idDetalle'=>$idDetalle,'idMetodo'=>$metodos[$i]));
			}
		}
	}*/
	
	public function obtenerVentaPrograma($idCliente,$idPrograma)
	{
		$sql=" select * from clientes_programas_ventas
		where idCliente='$idCliente' 
		and idPrograma='$idPrograma' ";	

		return $this->db->query($sql)->row();
	}
	
	public function obtenerProgramaVenta($idCliente)
	{
		$sql=" select * from clientes_programas_ventas
		where idCliente='$idCliente'  ";	

		return $this->db->query($sql)->row();
	}
	
	public function registrarVentaPrograma()
	{
		$venta				= $this->input->post('txtVenta');
		$ProgramaRegistro	= explode('|',$this->input->post('selectProgramas'));
		
		$idPrograma			= $ProgramaRegistro[0];
		
		if($venta>0 and $idPrograma>0)
		{
			$programa	= $this->configuracion->obtenerProgramasEditar($idPrograma);
			
			$data=array
			(
				'fecha'				=> $this->_fecha_actual,
				'idPrograma' 		=> $idPrograma,
				'venta' 			=> $venta,
				
				'idCliente' 		=> $this->input->post('txtClienteId'),
				'idPromotor' 		=> $this->input->post('txtIdPromotor'),
				'importe' 			=> $programa->importe,
				'comision' 			=> $programa->comision,
			);
			
			$this->db->insert('clientes_programas_ventas', $data);
			
			//ACTUALIZAR EL PROGRAMA
			$this->db->where('idCliente', $this->input->post('txtClienteId'));
			$this->db->update('clientes_academicos', array('idPrograma'=>$idPrograma));
		}
	}
	
	public function editarProgramaProspecto()
	{
		$idPrograma		= $this->input->post('idPrograma');
		
		$programa		= $this->configuracion->obtenerProgramasEditar($idPrograma);
		
		if($programa!=null)
		{
			//ACTUALIZAR EL PROGRAMA
			$this->db->where('idCliente', $this->input->post('idCliente'));
			$this->db->update('clientes_academicos', array('idPrograma'=>$idPrograma));
			
			return array($programa->cantidadInscripcion,$programa->cantidadColegiatura,$programa->cantidadReinscripcion);
		}
		
		return array(0,0,0);
		
	}
	
	public function editarCampanaProspecto()
	{
		//ACTUALIZAR EL PROGRAMA
		$this->db->where('idCliente', $this->input->post('idCliente'));
		$this->db->update('clientes', array('idCampana'=>$this->input->post('idCampana')));
	}
	
	
	public function editarFuenteProspecto()
	{
		//ACTUALIZAR EL PROGRAMA
		$this->db->where('idCliente', $this->input->post('idCliente'));
		$this->db->update('clientes', array('fuenteNueva'=>$this->input->post('idFuente')));
	}
	
	public function borrarDetalleSeguimiento($idDetalle)
	{
		$this->db->where('idDetalle', $idDetalle);
		$this->db->delete('seguimiento_detalles');

		return ($this->db->affected_rows() >= 1)? array('1') : array('0') ;
	}
	
	//EDITAR EL RESPONSABLE
	//=============================================================================================================
	public function editarResponsable()
	{
		$data=array
		(
			'idResponsable' 		=> $this->input->post('selectResponsable'),
		);
		
		$this->db->where('idSeguimiento', $this->input->post('txtIdSeguimiento'));
		$this->db->update('seguimiento', $data);
		
		$this->enviarCorreoResponsable($this->input->post('txtIdSeguimiento'));

		return ($this->db->affected_rows() >= 1)? array('1') : array('0') ;
	}
	
	public function enviarCorreoResponsable($idSeguimiento)
	{
		if(!empty($_POST))
		{
			$seguimiento	= $this->clientes->obtenerSeguimiento($idSeguimiento);
			
			$usuario		= $this->clientes->obtenerUsuario($seguimiento->idResponsable);
			$cliente		= $this->clientes->obtenerCliente($seguimiento->idCliente);
			$emisor			= $this->clientes->obtenerUsuario($seguimiento->idUsuarioRegistro);			
			
			if($emisor==null)
			{
				$emisor			= $this->clientes->obtenerUsuario($this->_user_id);	
			}
			
			$asunto			= 'Tienes un seguimiento pendiente en el CRM';
			
			/*$mensaje		=' <strong>Responsable: </strong>'.$usuario->nombre.'<br />';
			$mensaje		.= '<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($fecha).'<br />';
			$mensaje		.= '<strong>Comentarios: </strong> '.$seguimiento->comentarios.'<br />';
			$mensaje		.= '<strong>Cliente: </strong> '.$cliente->empresa;*/

			$mensaje		=nl2br($seguimiento->comentarios);
			$mensaje		.='<br /><br />Atte .<br />'.$emisor->nombre;
			
			$this->load->library('email');
			$this->email->from($emisor->correo,$emisor->nombre);
			$this->email->to($usuario->correo,$usuario->nombre);
			$this->email->subject($asunto);
			$this->email->message($mensaje);
			
			if (!$this->email->send())
			{
				#print("0");
			}
			else
			{
				#print("1");
			}
				
		}
		else
		{
			#print("2");
		}
	}
	
	//SEGUIMIENTO DIARIO
	public function obtenerSeguimientoDiario($idPromotor=0,$permiso=0,$idCampana=0)
	{
		/*$sql=" select a.idSeguimiento, a.idCliente, a.comentarios, a.fechaCierre as fecha, a.horaCierreFin,
		concat(b.nombre, ' ', b.paterno, ' ', b.materno) as prospecto,
		concat(c.nombre, ' ', c.apellidoPaterno, ' ', c.apellidoMaterno) as promotor,
		(select d.nombre from seguimiento_estatus as d where d.idEstatus=a.idEstatus) as estatus,
		
		(select d.nombre from clientes_fuentes as d where d.idFuente=b.idFuente) as fuente,
		
		b.movil, b.ladaMovil
		from seguimiento as a 
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join usuarios as c
		on b.idPromotor=c.idUsuario
		and b.prospecto='1'
		and date(a.fechaCierre)='$this->fecha' ";
		
		$sql.=$this->idRol!=1?" and b.idPromotor='$this->_user_id' ":'';
		
		$sql.=" order by fechaCierre asc ";	*/
		
		//EL SEGUIMIENTO SERA POR DETALLES
		$sql=" select a.idSeguimiento, a.idCliente, a.comentarios, a.fechaCierre as fecha, a.horaCierreFin,
		concat(b.nombre, ' ', b.paterno, ' ', b.materno) as prospecto,
		concat(c.nombre, ' ', c.apellidoPaterno, ' ', c.apellidoMaterno) as promotor,
		(select d.nombre from seguimiento_estatus as d where d.idEstatus=a.idEstatus) as estatus,
		(select d.nombre from clientes_fuentes as d where d.idFuente=b.idFuente) as fuente,
		b.movil, b.ladaMovil,
		
		
		
		(select f.horaInicial from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=a.idCliente order by concat(f.fechaSeguimiento,' ',f.horaInicial) desc limit 1) as horaInicial,
		(select f.horaFinal from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=a.idCliente order by concat(f.fechaSeguimiento,' ',f.horaInicial) desc limit 1) as horaFinal
		
		from seguimiento as a 
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join usuarios as c
		on b.idPromotor=c.idUsuario
		inner join seguimiento_detalles as e
		on e.idSeguimiento=a.idSeguimiento
		
		inner join clientes_campanas as h
		on b.idCampana=h.idCampana
		
		
		and b.prospecto='1'
		and b.activo='1'
		and b.idZona!=2
		and b.idZona!=8
		and date(e.fechaSeguimiento)='$this->fecha'
		
		and h.fechaFinal>curdate() ";
		#$sql.=$this->idRol!=1?" and b.idPromotor='$this->_user_id' ":'';
		
		/*
		(select f.horaInicial from seguimiento_detalles as f where f.idSeguimiento=a.idSeguimiento order by f.horaInicial desc limit 1) as horaInicial,
		(select f.horaFinal from seguimiento_detalles as f where f.idSeguimiento=a.idSeguimiento order by f.horaInicial desc limit 1) as horaFinal*/	
		
		
		if($permiso==0)
		{
			$sql.=" and b.idPromotor='$idPromotor' ";
		}
		else
		{
			$sql.=$idPromotor!=0?" and b.idPromotor='$idPromotor' ":'';
		}
		
		$sql.=$idCampana!=0?" and h.idCampana='$idCampana' ":'';
		
		
		//NO MOSTRAR SI EL SEGUIMIENTO DATOS POSTERIORES
		$sql.=" and (select count(f.idDetalle) from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento
		where g.idCliente=b.idCliente and f.fechaSeguimiento>curdate()) =0 ";
		
		
		$sql.=" and (select f.preinscrito from clientes_academicos as f where f.idCliente=b.idCliente limit 1) = '0' ";
		
		$sql.=" group by a.idSeguimiento
		order by horaInicial asc ";	
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientoDiarioFecha($idPromotor=0,$fecha='',$permiso,$idCampana=0)
	{
		//EL SEGUIMIENTO SERA POR DETALLES
		$sql=" select a.idSeguimiento, a.idCliente, a.comentarios, a.fechaCierre as fecha, a.horaCierreFin,
		concat(b.nombre, ' ', b.paterno, ' ', b.materno) as prospecto,
		concat(c.nombre, ' ', c.apellidoPaterno, ' ', c.apellidoMaterno) as promotor,
		(select d.nombre from seguimiento_estatus as d where d.idEstatus=a.idEstatus) as estatus,
		
		(select d.nombre from clientes_fuentes as d where d.idFuente=b.idFuente) as fuente,
		
		b.movil, b.ladaMovil,
		
		(select f.horaInicial from seguimiento_detalles as f where f.idSeguimiento=a.idSeguimiento and f.fechaSeguimiento='$fecha' order by f.horaInicial desc limit 1) as horaInicial,
		(select f.horaFinal from seguimiento_detalles as f where f.idSeguimiento=a.idSeguimiento and f.fechaSeguimiento='$fecha' order by f.horaInicial desc limit 1) as horaFinal
		
		from seguimiento as a 
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join usuarios as c
		on b.idPromotor=c.idUsuario
		
		
		inner join seguimiento_detalles as e
		on e.idSeguimiento=a.idSeguimiento
		
		inner join clientes_campanas as h
		on b.idCampana=h.idCampana
		
		
		and b.prospecto='1'
		
		and b.activo='1'
		and b.idZona!=2
		and b.idZona!=8
		
		and date(e.fechaSeguimiento)='$fecha'
		
		and h.fechaFinal>curdate() ";
		
		#$sql.=$this->idRol!=1?" and b.idPromotor='$this->_user_id' ":'';
		
		#$sql.=$idPromotor!=0?" and b.idPromotor='$idPromotor' ":'';
		
		$sql.=" and (select f.preinscrito from clientes_academicos as f where f.idCliente=b.idCliente limit 1) = '0' ";
		
		if($permiso==0)
		{
			$sql.=" and b.idPromotor='$idPromotor' ";
		}
		else
		{
			$sql.=$idPromotor!=0?" and b.idPromotor='$idPromotor' ":'';
		}
		
		$sql.=$idCampana!=0?" and h.idCampana='$idCampana' ":'';
		
		$sql.=" group by a.idSeguimiento
		order by horaInicial asc ";	

		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientoDiarioFechaAtrasado($idPromotor=0,$fecha='',$permiso,$idCampana=0)
	{
		//EL SEGUIMIENTO SERA POR DETALLES
		$sql=" select a.idSeguimiento, a.idCliente, a.comentarios, a.fechaCierre as fecha, a.horaCierreFin,
		concat(b.nombre, ' ', b.paterno, ' ', b.materno) as prospecto,
		concat(c.nombre, ' ', c.apellidoPaterno, ' ', c.apellidoMaterno) as promotor,
		(select d.nombre from seguimiento_estatus as d where d.idEstatus=a.idEstatus) as estatus,
		
		(select d.nombre from clientes_fuentes as d where d.idFuente=b.idFuente) as fuente,
		
		b.movil, b.ladaMovil,
		
		e.horaInicial, e.horaFinal
		
		from seguimiento as a 
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join usuarios as c
		on b.idPromotor=c.idUsuario
		inner join seguimiento_detalles as e
		on e.idSeguimiento=a.idSeguimiento
		
		inner join clientes_campanas as h
		on b.idCampana=h.idCampana
		
		
		and b.prospecto='1'
		
		and b.activo='1'
		and b.idZona!=2
		and b.idZona!=8
		
		and date(e.fechaSeguimiento)='$fecha'
		
		and h.fechaFinal>curdate()  ";
		
		$sql.=" and (select f.preinscrito from clientes_academicos as f where f.idCliente=b.idCliente limit 1) = '0' ";
	
		#$sql.=$idPromotor!=0?" and b.idPromotor='$idPromotor' ":'';
		
		if($permiso==0)
		{
			$sql.=" and b.idPromotor='$idPromotor' ";
		}
		else
		{
			$sql.=$idPromotor!=0?" and b.idPromotor='$idPromotor' ":'';
		}
		
		$sql.=$idCampana!=0?" and h.idCampana='$idCampana' ":'';
		
		//NO MOSTRAR SI EL SEGUIMIENTO DATOS POSTERIORES
		$sql.=" and (select count(f.idDetalle) from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento
		where g.idCliente=b.idCliente and f.fechaSeguimiento>'$fecha') = 0 ";
		
		$sql.=" group by a.idSeguimiento
		order by e.horaInicial desc ";	

		return $this->db->query($sql)->result();
	}
	
	//BAJAS
	
	public function registrarBaja()
	{
		$this->db->where('idCliente',$this->input->post('txtIdClienteBaja'));
		$this->db->update('clientes',array('fechaBaja'=>$this->_fecha_actual,'idCausa'=>$this->input->post('selectCausas'),'idZona'=>2));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function registrarBajaSeguimiento()
	{
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes',array('fechaBaja'=>$this->_fecha_actual,'bajaAnterior'=>'0','idZona'=>2,'idDetalle'=>$this->input->post('idCausa'),'texto'=>$this->input->post('textoBaja')));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function registrarNocuali()
	{
		$this->db->where('idCliente',$this->input->post('txtClienteId'));
		$this->db->update('clientes',array('idZona'=>8));
		
		$data=array
		(
			'fecha'			=> $this->_fecha_actual,
			'idCausa'		=> $this->input->post('selectEstatusCualificado'),
			'idCliente'		=> $this->input->post('txtClienteId'),
			'idDetalle'		=> '0',
			'texto'			=> $this->input->post('txtTextoCualificado')
		);
		
		$this->db->insert('clientes_nocuali',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function registrarDetalleNocuali()
	{
		$this->db->where('idCliente',$this->input->post('txtClienteId'));
		$this->db->update('clientes',array('idZona'=>8));
		
		$detalles		=  explode('|',$this->input->post('selectDetallesCualificado'));
		
		$data=array
		(
			'fecha'			=> $this->_fecha_actual,
			'idCausa'		=> 5,
			'idCliente'		=> $this->input->post('txtClienteId'),
			//'idDetalle'		=> $this->input->post('selectDetallesCualificado'),
			'idDetalle'		=> $detalles[0],
			'texto'			=> $this->input->post('txtTextoDetalle')
		);
		
		$this->db->insert('clientes_nocuali',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function registrarAlumnoSeguimiento()
	{
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes',array('idZona'=>1,'prospecto'=>0,'fechaInscripcion'=>$this->_fecha_actual));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function reactivarProspecto()
	{
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes',array('fechaBaja'=>null,'idZona'=>5,'bajaAnterior'=>'0'));
		
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->delete('clientes_nocuali');
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	
	//REGISTRAR EL CLIENTE DE IEXE
	public function registrarClienteIexe()
	{
		$this->load->helper('base');

		$dsn		= obtenerConexion('167.114.93.233','iexe2013','$2016Iexe_Universi-dad%$_16#_%2017.1','iexe2013_registro');
		#$dsn		= obtenerConexion('localhost','root','','iexe_alumnos');
			
		$base		= $this->load->database($dsn,true);
		
		$data =array
		(
			'nombre'			=> $this->input->post('nombre'),
			'fnacimiento'		=> $this->input->post('fnacimiento'),
			'apaterno'			=> $this->input->post('apaterno'),
			'amaterno'			=> $this->input->post('amaterno'),
			'telefono'			=> $this->input->post('telefono'),
			'email'				=> $this->input->post('email'),
			'promotor'			=> $this->input->post('promotor'),
		);
		
		$base->insert('registro',$data);
		$id	= $base->insert_id();
		
		$this->load->database('default',true);
		
		if($id>0)
		{
			$this->db->where('idCliente', $this->input->post('idCliente'));
			$this->db->update('clientes', array('idIexe'=>$id));
		}
		
		return "1";

		#return $this->db->affected_rows()>=1?"1":"0";
	}
	
	
	public function obtenerSeguimientoAlerta()
	{
		$sql=" select a.idDetalle, a.observaciones, a.fechaSeguimiento, a.horaInicial, a.horaFinal, a.idSeguimiento,
		b.idCliente, concat(c.nombre,' ',c.paterno,' ', c.materno) as alumno, c.movil, c.telefono, c.email,
		
		(select d.nombre from clientes_campanas as d where d.idCampana=c.idCampana) as campana
		
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		inner join clientes as c
		on c.idCliente=b.idCliente
		where a.fechaSeguimiento='$this->fecha'
		and a.horaInicial='$this->horaMedia'
		
		and a.visto='0' ";
		
		#and a.alerta='1'
		
		$sql.=" and b.idUsuarioRegistro='$this->_user_id' ";
		
		$sql.=" and (select f.confirmado from clientes_academicos as f where f.idCliente=c.idCliente) = '0' ";
		
		#echo $sql;

		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientoAlertaPasado()
	{
		$sql=" select a.idDetalle, a.observaciones, a.fechaSeguimiento, a.horaInicial, a.horaFinal,
		b.idCliente, concat(c.nombre,' ',c.paterno,' ', c.materno) as alumno, c.movil, c.telefono, c.email,
		
		d.nombre as campana
		
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		inner join clientes as c
		on c.idCliente=b.idCliente
		
		
		inner join clientes_campanas as d
		on d.idCampana=c.idCampana
		
		where a.fechaSeguimiento='$this->fecha'
		and a.horaInicial<'$this->horaMedia' ";
		 
		 #and a.alerta='1'
		 #and a.visto='0'
		 
		 
		 $sql.=" and (select f.confirmado from clientes_academicos as f where f.idCliente=c.idCliente) = '0' ";
		
		$sql.=" and d.fechaFinal>curdate() ";
		
		$sql.=" and b.idUsuarioRegistro='$this->_user_id' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientoAlertaPasadoFechas()
	{
		$sql=" select a.idDetalle, a.observaciones, a.fechaSeguimiento, a.horaInicial, a.horaFinal, a.idSeguimiento,
		b.idCliente, concat(c.nombre,' ',c.paterno,' ', c.materno) as alumno, c.movil, c.telefono, c.email,
		
		d.nombre as campana
		
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		inner join clientes as c
		on c.idCliente=b.idCliente
		inner join clientes_campanas as d
		on d.idCampana=c.idCampana
		where  c.prospecto='1'
		and c.idZona!=2
		and c.idZona!=8
		and c.activo='1' 
		and d.atrasos='0' ";

		$sql.=" and d.fechaFinal>curdate() ";
		#$sql.=" and (select concat(f.fechaSeguimiento,' ',f.horaInicial) from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=c.idCliente order by f.fechaSeguimiento desc, f.horaInicial desc limit 1 )< '$this->_fecha_actual'  ";
		$sql.=" and timestampdiff(minute,(select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=c.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) ,now()) / 60 >= 3 ";
		
		#$sql.=" and b.idUsuarioRegistro='$this->_user_id' ";
		$sql.=" and c.idPromotor='$this->_user_id' ";
		$sql.=" and a.idDetalle=(select f.idDetalle from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=c.idCliente order by f.fechaSeguimiento desc, f.horaInicial desc limit 1 ) ";
		
		$sql.=" and (select f.confirmado from clientes_academicos as f where f.idCliente=c.idCliente) = '0' ";
		
		$sql.=" group by c.idCliente ";
		$sql.=" order by a.fechaSeguimiento desc, a.horaInicial desc ";
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function pararAlertas()
	{
		$this->db->where('idUsuario',$this->_user_id);
		$this->db->update('usuarios',array('alerta'=>'0'));
		
		$this->session->set_userdata('alertaActiva','0');
	}
	
	public function procesarSeguimientosAlerta($seguimiento)
	{
		foreach($seguimiento as $row)
		{
			$this->db->where('idDetalle',$row->idDetalle);
			$this->db->update('seguimiento_detalles',array('visto'=>'1'));
		}
	}
	
	//ATRASOS
	//SEGUIMIENTO DIARIO
	public function obtenerSeguimientoAtrasos($idPromotor=0,$permiso=0,$nuevos=0)
	{
		$sql="";
		
		if($nuevos<2)
		{
			//EL SEGUIMIENTO SERA POR DETALLES
			$sql=" ( select a.idSeguimiento, a.idCliente, a.comentarios, a.fechaCierre as fecha, a.horaCierreFin,
			concat(b.nombre, ' ', b.paterno, ' ', b.materno) as prospecto,
			concat(c.nombre, ' ', c.apellidoPaterno, ' ', c.apellidoMaterno) as promotor,
			(select d.nombre from seguimiento_estatus as d where d.idEstatus=a.idEstatus) as estatus,
			(select d.nombre from clientes_fuentes as d where d.idFuente=b.idFuente) as fuente,
			b.movil, b.ladaMovil, e.horaInicial, e.horaFinal,
			'activo' as tipoSeguimiento
			from seguimiento as a 
			inner join clientes as b
			on a.idCliente=b.idCliente
			inner join usuarios as c
			on b.idPromotor=c.idUsuario
			inner join seguimiento_detalles as e
			on e.idSeguimiento=a.idSeguimiento ";
			
			$sql.=" inner join clientes_campanas as f
			on b.idCampana=f.idCampana ";
			
			$sql.=" where  b.idZona!=2
			and b.idZona!=8
			and b.activo='1'
			and b.prospecto='1'
			and f.atrasos='0'  ";
	
			$sql.=" and f.fechaFinal>curdate() ";
			
			$sql.=" and (select g.preinscrito from clientes_academicos as g where g.idCliente=b.idCliente limit 1) = '0' ";
			
			
			//LOS ATRASOS SERAN POR FECHA Y HORA NO POR LAS 24 HORAS
			$sql.=" and timestampdiff(minute,(select concat(f.fechaSeguimiento,' ',f.horaInicial) from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=b.idCliente order by f.fechaSeguimiento desc, f.horaInicial desc limit 1 ) ,now()) / 60 >= 3  ";
			#$sql.=" and (select concat(f.fechaSeguimiento,' ',f.horaInicial) from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=b.idCliente order by f.fechaSeguimiento desc, f.horaInicial desc limit 1 )< '$this->_fecha_actual'  ";
	
			#$sql.=$idPromotor!=0?" and b.idPromotor='$idPromotor' ":'';
			if($permiso==0)
			{
				$sql.=" and b.idPromotor='$idPromotor' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and b.idPromotor='$idPromotor' ":'';
			}
			
			$sql.=" group by a.idCliente ) ";
		}
		
		
		if($nuevos==0)
		{
			$sql.=" union ";
		}
		
		if($nuevos==0 or $nuevos==2)
		{
			$sql.=" (select 0 as idSeguimiento, a.idCliente, '' as comentarios, a.fechaRegistro as fecha,  time(a.fechaRegistro) as horaCierreFin,
			concat(a.nombre, ' ', a.paterno, ' ', a.materno) as prospecto,
			concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) as promotor,
			'Nuevo' as estatus,
			(select c.nombre from clientes_fuentes as c where c.idFuente=a.idFuente) as fuente,
			a.movil, a.ladaMovil, time(a.fechaRegistro) as horaInicial, time(a.fechaRegistro) as horaFinal,
			'nuevo' as tipoSeguimiento
			from clientes as a 
			inner join usuarios as b
			on a.idPromotor=b.idUsuario 
			
			inner join clientes_campanas as f
			on a.idCampana=f.idCampana
			
			
			and a.idZona!=2
			and a.idZona!=8
			and a.activo='1'
			and a.prospecto='1' 
			
			and f.atrasos='0' 
			
			and (select g.preinscrito from clientes_academicos as g where g.idCliente=a.idCliente limit 1) = '0'
			and timestampdiff(minute,a.fechaRegistro ,now()) / 60 >= 3
			and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=0 ";
			
			
			//LOS ATRASOS SERAN POR FECHA Y HORA NO POR LAS 24 HORAS
			#$sql.=" and timestampdiff(minute,(select concat(f.fechaSeguimiento,' ',f.horaInicial) from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=b.idCliente order by f.fechaSeguimiento desc, f.horaInicial desc limit 1 ) ,now()) / 60 >= 3  ";
			#$sql.=" and (select concat(f.fechaSeguimiento,' ',f.horaInicial) from seguimiento_detalles as f inner join seguimiento as g on g.idSeguimiento=f.idSeguimiento where g.idCliente=b.idCliente order by f.fechaSeguimiento desc, f.horaInicial desc limit 1 )< '$this->_fecha_actual'  ";
	
			#$sql.=$idPromotor!=0?" and a.idPromotor='$idPromotor' ":'';
			
			if($permiso==0)
			{
				$sql.=" and a.idPromotor='$idPromotor' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and a.idPromotor='$idPromotor' ":'';
			}
			
			
			$sql.=" and f.fechaFinal>curdate() ";
			
			$sql.=" group by a.idCliente ) ";
		}
		
		
		$sql.=" order by horaInicial asc ";	
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	//REPORTE DE PROSPECTOS
	public function contarReporte($criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$prospecto=-1,$idPromotor=0,$todos=0,$inicio='',$fin='',$seguimientos=0,$tipoFecha=0)
	{
		$sql=" select a.idCliente
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1'
		and a.idZona!=2
		and a.idZona!=8
		and idZona!=4 ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$seguimientos!=0?" and  (select count(d.idDetalle) from seguimiento_detalles as d inner join seguimiento as e on d.idSeguimiento=e.idSeguimiento where a.idCliente=e.idCliente) >4 ":'';
		$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicio' and '$fin' ":" and (select date(d.fechaInscrito) from clientes_academicos  as d where a.idCliente=d.idCliente and d.confirmado='1') between '$inicio' and '$fin'  ";
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerReporte($numero,$limite,$criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$prospecto=-1,$idPromotor=0,$todos=0,$inicio='',$fin='',$seguimientos=0,$tipoFecha=0)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select a.*, 
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampanaOriginal) as campanaOriginal,
		
		(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente,
		
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on d.idPrograma=e.idPrograma where a.idCliente=e.idCliente) as programa,
		
		(select count(d.idDetalle) from seguimiento_detalles as d inner join seguimiento as e on d.idSeguimiento=e.idSeguimiento where a.idCliente=e.idCliente) as numeroSeguimientos,
		
		(select d.fechaInscrito from clientes_academicos  as d where a.idCliente=d.idCliente) as fechaInscrito
		
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1'
		and a.idZona!=2
		and a.idZona!=8
		and idZona!=4  ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$seguimientos!=0?" and  (select count(d.idDetalle) from seguimiento_detalles as d inner join seguimiento as e on d.idSeguimiento=e.idSeguimiento where a.idCliente=e.idCliente) >4 ":'';
		
		$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicio' and '$fin' ":" and (select date(d.fechaInscrito) from clientes_academicos  as d where a.idCliente=d.idCliente and d.confirmado='1') between '$inicio' and '$fin' ";
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" order by a.nombre asc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerUltimosSeguimientos($idCliente=0)
	{
		$sql=" select a.observaciones
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		where b.idCliente='$idCliente'
		order by concat(a.fechaSeguimiento,' ',a.horaInicial) desc
		limit 1 ";
		
		return $this->db->query($sql)->result();
	}
	
	//REPORTE BAJAS
	public function obtenerReportebajas($idPrograma=0,$idCampana=0,$todos=0,$idCausa=-1,$inicio,$fin)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select count(distinct a.idCliente) as numeroBajas,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_bajas_causas as d where d.idCausa=a.idCausa) as causa,
		
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on d.idPrograma=e.idPrograma where a.idCliente=e.idCliente) as programa
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1'
		and a.idZona=2
		and a.prospecto='1'
		and a.bajaAnterior='1' ";

		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		$sql.=$idCausa!=-1?" and  a.idCausa='$idCausa' ":'';
		
		$sql.=" and  date(a.fechaBaja) between '$inicio' and '$fin' ";
		
		/*if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}*/
		
		$sql.=" group by a.idCausa";
		$sql.=" order by numeroBajas desc ";

		return $this->db->query($sql)->result();
	}
	
	//REPORTE BAJAS
	
	public function obtenerNumeroInscritosPromotor($idPromotor,$idCampana,$inicio,$fin)
	{	
		$sql=" select count(a.idCliente) as numero
		from clientes as a
		inner join catalogos_ingresos as b
		on a.idCliente=b.idCliente
		where a.activo='1'
		and a.idPromotor='$idPromotor'
		and b.idProducto=23 ";
		
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=" and  date(b.fecha) between '$inicio' and '$fin' " ;
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerNumeroBajasPromotor($idPromotor=0,$idCampana=0,$idPrograma=0,$idFuente=0,$inicio='',$fin='',$idCampanaOriginal=0)
	{	
		$sql=" select count(a.idCliente)  as numero
		from clientes as a 
		where a.idPromotor='$idPromotor'
		and a.activo='1'  
		and a.idZona=2 ";
		
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idCampanaOriginal!=0?" and  a.idCampanaOriginal='$idCampanaOriginal' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=strlen($inicio)>0?" and date(a.fechaRegistro) between '$inicio' and '$fin' ":'';
		$sql.=strlen($inicio)>0?" and date(a.fechaBaja) between '$inicio' and '$fin' ":'';
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerReportePromotores($idPromotor=0,$idCampana=0,$todos=0,$inicio,$fin)
	{
		$sql=" select count(distinct a.idCliente) as numeroProspectos, a.idPromotor,
		concat(b.nombre,' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor
		
		
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1'
		and b.activo='1' ";
		
		#$sql.=" and date(a.fechaInscripcion) between '$inicio' and '$fin' ";

		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		#$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		/*if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}*/
		
		$sql.=" group by a.idPromotor ";
		$sql.=" order by promotor asc ";
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetalleInscritos($idPromotor,$idCampana,$inicio,$fin)
	{	
		$sql=" select concat(a.nombre,' ',a.paterno,' ',a.materno) as alumno,
		a.email, a.telefono, a.movil, concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) as promotor,
		
		(select d.nombre from clientes_campanas as d where  d.idCampana=a.idCampana limit 1) as campana,
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on e.idPrograma=d.idPrograma where  e.idCliente=a.idCliente limit 1) as programa
		
		from clientes as a
		inner join catalogos_ingresos as b
		on a.idCliente=b.idCliente
		
		inner join usuarios as c
		on a.idPromotor=c.idUsuario
		
		where a.activo='1'
		and b.idProducto=23 ";
		
		$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		
		$sql.=" and  date(b.fecha) between '$inicio' and '$fin' " ;
		
		$sql.=" order by alumno asc, promotor asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNumeroSeguimientosProspecto($idPromotor,$idCampana,$numero,$prospecto='1')
	{	
		$sql=" select count(a.idCliente) as numero
		from clientes as a
		where a.idPromotor='$idPromotor'
		and a.activo='1'
		and a.idZona!=2 ";
		
		$sql.=$prospecto!='1'?" and  a.prospecto='1' ":'';
		
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		
		if($numero<4)
		{
			$sql.=" and (select count(b.idDetalle) from seguimiento_detalles as b inner join seguimiento as c on c.idSeguimiento=b.idSeguimiento where c.idCliente=a.idCliente) = $numero ";
		}
		else
		{
			$sql.=" and (select count(b.idDetalle) from seguimiento_detalles as b inner join seguimiento as c on c.idSeguimiento=b.idSeguimiento where c.idCliente=a.idCliente) > 3 ";
		}
		
		return $this->db->query($sql)->row()->numero;
	}
	
	/*public function obtenerReportePromotores($numero,$limite,$idPromotor=0,$idPrograma=0,$idCampana=0,$todos=0,$inicio,$fin)
	{
		$sql=" select count(distinct a.idCliente) as numeroInscritos, a.idPromotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_bajas_causas as d where d.idCausa=a.idCausa) as causa,
		
		concat(b.nombre,' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor,
		
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on d.idPrograma=e.idPrograma where a.idCliente=e.idCliente) as programa
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1'
		and a.idZona!=2
		and a.prospecto='0' ";
		
		$sql.=" and date(a.fechaInscripcion) between '$inicio' and '$fin' ";

		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" group by a.idPromotor, a.idCampana, (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) ";
		$sql.=" order by numeroInscritos desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}*/
	
	//PROSPECTOS NUEVOS
	public function obtenerNuevos($idPromotor,$permiso=0)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select a.idCliente, concat(a.nombre,' ',a.paterno,' ',a.materno) as prospecto,
		b.descripcion, a.ladaMovil, a.movil, a.fechaRegistro,
		(select concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) from usuarios as d where d.idUsuario=a.idPromotor ) as promotor,
		(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente,
		
		(select e.idSeguimiento from seguimiento as e where a.idCliente=e.idCliente and tipo='1' limit 1) as idSeguimiento

		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona
		
		inner join clientes_campanas as c
		on a.idCampana=c.idCampana
		
		
		where a.activo='1'
		and a.idZona!=2
		and a.idZona!=8
		and a.prospecto='1'
		and a.nuevoRegistro='1'
		and c.fechaFinal > curdate() ";
		
		//LOS NUEVOS SERAN SOLO POR BANDERA
		#$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=0";

		#$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';

		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		#$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		
		if($permiso==0)
		{
			$sql.=" and a.idPromotor='$idPromotor' ";
		}
		else
		{
			$sql.=$idPromotor!=0?" and a.idPromotor='$idPromotor' ":'';
		}

		$sql.=" order by prospecto asc  ";
		#$sql .= " limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarSeguimientoNuevos()
	{
		$seguimiento['fecha']			= $this->input->post('fechaSeguimiento');
		$seguimiento['comentarios']		= $this->input->post('observaciones');
		$seguimiento['fechaCierre']		= $this->input->post('fechaSeguimiento');
		$seguimiento['horaCierreFin']	= $this->input->post('horaInicial');
		$seguimiento['idStatus']		= 4;
		$seguimiento['idEstatus']		= 4;
		$seguimiento['tipo']			= 1;
		$seguimiento['idServicio']		= 1;
		$seguimiento['folio']			= $this->crm->obtenerFolioSeguimientoCliente(1);
		
		$seguimiento['idResponsable']		= $this->input->post('idPromotor');
		$seguimiento['idUsuarioRegistro']	= $this->input->post('idPromotor');
		$seguimiento['idCliente']			= $this->input->post('idCliente');
		$seguimiento['idContacto']			= $this->input->post('idContacto');

		$detalle['fecha']				= $this->input->post('fechaSeguimiento');
		$detalle['hora']				= $this->input->post('horaInicial');
		$detalle['fechaRegistro']		= $this->_fecha_actual;
		$detalle['observaciones']		= $this->input->post('observaciones');
		$detalle['fechaSeguimiento']	= $this->input->post('fechaSeguimiento');
		$detalle['horaInicial']			= $this->input->post('horaInicial');
		$detalle['horaFinal']			= $this->input->post('horaFinal');
		$detalle['alerta']				= $this->input->post('alerta')=='1'?'1':'0';
		
		$this->importar->registrarSeguimientoInicialRegistro($seguimiento,$detalle);
		
		$this->db->where('idCliente', $this->input->post('idCliente'));
		$this->db->update('clientes', array('nuevoRegistro'=>'0'));
		
		return "1";
	}
	
	//PREINSCRITOS
	public function registrarPreinscrito()
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			'fechaPreinscrito'		=> $this->_fecha_actual,
			'preinscrito' 			=> '1',
			
			'inscripcion' 			=> $this->input->post('txtInscripcion'),
			'colegiatura' 			=> $this->input->post('txtColegiatura'),
			'reinscripcion' 		=> $this->input->post('txtReinscripcion'),
			'titulacion' 			=> $this->input->post('txtTitulacion'),
			'cantidadInscripcion' 	=> $this->input->post('txtCantidadInscripcion'),
			'cantidadColegiatura' 	=> $this->input->post('txtCantidadColegiatura'),
			'cantidadReinscripcion' => $this->input->post('txtCantidadReinscripcion'),
			
			'mes' 					=> $this->input->post('selectMesPreinscrito'),
		);

		$this->db->where('idCliente', $this->input->post('txtClienteId'));
		$this->db->update('clientes_academicos', $data);
		
		$this->db->where('idCliente', $this->input->post('txtClienteId'));
		$this->db->update('clientes', array('nuevoRegistro'=>'0'));
		
		
		$this->registrarVentaProgramaPreinscrito(); //REGISTRAR LA VENTA DEL PROGRAMA
		$this->registrarPeriodoPreinscrito(); //REGISTRAR EL PERIODO
		
		$nombre 		= $_FILES['txtComprobante']['name'];
		
		if(strlen($nombre)>0)
		{
			$archivo 		= pathinfo($_FILES['txtComprobante']['name']);
			
			$idComprobante	= $this->clientes->subirFicheros($this->input->post('txtClienteId'),$nombre,$_FILES['txtComprobante']['size']);
			
			move_uploaded_file($_FILES['txtComprobante']['tmp_name'], carpetaClientes.basename($idComprobante."_".$nombre));
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function registrarPeriodoPreinscrito()
	{
		$idPeriodo	= $this->input->post('selectPeriodosRegistro');
		$idCliente	= $this->input->post('txtClienteId');
		
		if($this->comprobarPeriodo($idCliente,$idPeriodo)==0)
		{
			$data=array
			(
				'fecha'				=> $this->_fecha_actual,
				'idCliente' 		=> $idCliente,
				'idPeriodo' 		=> $idPeriodo,
			);
			
			$this->db->insert('clientes_periodos_relacion', $data);
		}
	}
	
	public function registrarPeriodoPreinscritoMatricula($idCliente=0)
	{
		$data=array
		(
			'fecha'				=> $this->_fecha_actual,
			'idCliente' 		=> $idCliente,
			'idPeriodo' 		=> 0,
		);
		
		$this->db->insert('clientes_periodos_relacion', $data);
	}
	
	public function comprobarPeriodo($idCliente,$idPeriodo)
	{
		$sql="select count(idRelacion) as numero
		from clientes_periodos_relacion
		where idCliente='$idCliente'
		and idPeriodo='$idPeriodo' ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function registrarDiplomadosPrograma($idPrograma=0)
	{
		$diplomados	= $this->configuracion->obtenerProgramasGrado(4);
		
		$this->db->where('idPrograma', $idPrograma);
		$this->db->where('idCliente', $this->input->post('txtClienteId'));
		$this->db->delete('clientes_diplomados');
		
		foreach($diplomados as $row)
		{
			if($this->input->post('chkIdPrograma'.$row->idPrograma)>0)
			{
				$data=array
				(
					'idPrograma' 		=> $idPrograma,
					'idCliente' 		=> $this->input->post('txtClienteId'),
					'idDiplomado' 		=> $row->idPrograma,
				);
				
				$this->db->insert('clientes_diplomados', $data);
			}
			
		}
	}
	
	public function registrarVentaProgramaPreinscrito()
	{
		$venta				= $this->input->post('txtVenta');
		$ProgramaRegistro	= explode('|',$this->input->post('selectProgramas'));
		$idPrograma			= $ProgramaRegistro[0];
		#$idPrograma		= $this->input->post('selectProgramas');
		
		if($ProgramaRegistro[1]==2)
		{
			$this->registrarDiplomadosPrograma($idPrograma);
		}
		
		if($venta>0 and $idPrograma>0)
		{
			$programa	= $this->configuracion->obtenerProgramasEditar($idPrograma);
			
			$data=array
			(
				'fecha'				=> $this->_fecha_actual,
				'idPrograma' 		=> $idPrograma,
				'venta' 			=> $venta,
				
				'idCliente' 		=> $this->input->post('txtClienteId'),
				'idPromotor' 		=> $this->input->post('txtIdPromotor'),
				'importe' 			=> $programa->importe,
				'comision' 			=> $programa->comision,
			);
			
			$this->db->insert('clientes_programas_ventas', $data);
			
			//ACTUALIZAR EL PROGRAMA
			$this->db->where('idCliente', $this->input->post('txtClienteId'));
			$this->db->update('clientes_academicos', array('idPrograma'=>$idPrograma));
		}
	}
	
	
	public function contarPreinscritos($inicio,$fin,$idUsuario=0,$criterio='',$idCampana=0,$idPrograma=0,$todos=0,$idFuente=0,$idCampanaOriginal=0,$mes='0')
	{
		$sql=" select distinct a.idCliente
		from clientes as a
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		inner join clientes_academicos as c
		on c.idCliente=a.idCliente 
		inner join clientes_campanas as d
		on a.idCampana=d.idCampana
		where a.activo='1'
		and a.idZona!=2 
		and c.preinscrito='1' ";
		
		# and a.prospecto='1'
		

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		#$sql.=$idUsuario!='0'?" and b.idUsuario='$idUsuario' ":'';
		$sql.=$idCampana!='0'?" and a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!='0'?" and c.idPrograma='$idPrograma' ":'';
		$sql.=$idFuente!=0?" and a.idFuente='$idFuente' ":'';
		$sql.=$idCampanaOriginal!='0'?" and a.idCampanaOriginal='$idCampanaOriginal' ":'';
		$sql.=$mes!='0'?" and c.mes='$mes' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idUsuario!=0?" and  a.idPromotor='$idUsuario' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idUsuario!=0?" and  a.idPromotor='$idUsuario' ":'';
			}
		}
		
		//AGREGAR LA CAMPAÑA
		$sql.=" and date(c.fechaPreinscrito) between '$inicio' and '$fin' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPreinscritos($numero,$limite,$inicio,$fin,$idUsuario=0,$criterio='',$idCampana=0,$idPrograma=0,$todos=0,$idFuente=0,$idCampanaOriginal=0,$mes='')
	{
		$sql=" select distinct a.idCliente, concat(a.nombre, ' ', a.paterno,' ', a.materno) as prospecto,
		a.lada, a.telefono, a.ladaMovil, a.movil, a.email,	a.prospecto as registro,	
		c.fechaPreinscrito, d.nombre as campana, c.confirmado, c.mes,
		concat(b.nombre, ' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor,
		(select e.nombre from clientes_programas as e where e.idPrograma=c.idPrograma limit 1) as programa,
		(select count(e.idFichero) from clientes_ficheros as e where e.idCliente=a.idCliente) as numeroArchivos,
		
		(select e.nombre from clientes_fuentes as e where e.idFuente=a.idFuente limit 1) as fuente,
		
		(select e.nombre from clientes_campanas as e where e.idCampana=a.idCampanaOriginal limit 1) as campanaOriginal
		
		
		from clientes as a
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		inner join clientes_academicos as c
		on c.idCliente=a.idCliente 
		inner join clientes_campanas as d
		on a.idCampana=d.idCampana
		where a.activo='1'
		and a.idZona!=2 
		and c.preinscrito='1' ";
		
		#a.prospecto='1'

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		#$sql.=$idUsuario!='0'?" and b.idUsuario='$idUsuario' ":'';
		$sql.=$idCampana!='0'?" and a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!='0'?" and c.idPrograma='$idPrograma' ":'';
		$sql.=$idFuente!=0?" and a.idFuente='$idFuente' ":'';
		$sql.=$idCampanaOriginal!='0'?" and a.idCampanaOriginal='$idCampanaOriginal' ":'';
		$sql.=$mes!='0'?" and c.mes='$mes' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idUsuario!=0?" and  a.idPromotor='$idUsuario' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idUsuario!=0?" and  a.idPromotor='$idUsuario' ":'';
			}
		}
		
		//AGREGAR LA CAMPAÑA
		$sql.=" and date(c.fechaPreinscrito) between '$inicio' and '$fin'
		order by c.fechaPreinscrito asc ";

		$sql .=$numero>0? " limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPreinscritosVigentes()
	{
		$sql=" select count(a.idCliente) as numero
		from clientes as a
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		inner join clientes_academicos as c
		on c.idCliente=a.idCliente 
		inner join clientes_campanas as d
		on a.idCampana=d.idCampana
		where a.activo='1'
		and a.idZona!=2 
		and c.preinscrito='1'
		and d.fechaFinal>=curdate() ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerNumeroPreinscritosPromotor($idPromotor,$idCampana,$inicio,$fin)
	{	
		$sql=" select count(a.idCliente) as numero
		from clientes as a
		inner join clientes_academicos as b
		on a.idCliente=b.idCliente
		where a.activo='1'
		and a.idPromotor='$idPromotor'
		and b.preinscrito='1'
		and b.confirmado='1' ";
		
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=" and  date(b.fechaPreinscrito) between '$inicio' and '$fin' " ;
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerDetallePreinscritos($idPromotor,$idCampana,$inicio,$fin)
	{	
		$sql=" select concat(a.nombre,' ',a.paterno,' ',a.materno) as alumno,
		a.email, a.telefono, a.movil, concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) as promotor,
		
		(select d.nombre from clientes_campanas as d where  d.idCampana=a.idCampana limit 1) as campana,
		(select d.nombre from clientes_programas as d where  d.idPrograma=b.idPrograma limit 1) as programa
		
		from clientes as a
		inner join clientes_academicos as b
		on a.idCliente=b.idCliente
		
		inner join usuarios as c
		on a.idPromotor=c.idUsuario
		
		where a.activo='1'
		and b.preinscrito='1' 
		and b.confirmado='1'";
		
		$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		
		$sql.=" and  date(b.fechaPreinscrito) between '$inicio' and '$fin' " ;
		
		$sql.=" order by alumno asc, promotor asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function validarProspecto()
	{
		$data=array
		(
			'confirmado' 			=> '1',
			'fechaInscrito'			=> $this->_fecha_actual,
		);
		
		$this->db->where('idCliente', $this->input->post('idCliente'));
		$this->db->update('clientes_academicos', $data);
		
		//CONVERTIR A ALUMNO
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes',array('prospecto'=>'0','idZona'=>1,'fechaInscripcion'=>$this->_fecha_actual));
		
		$this->enviarCorreoValidacion($this->input->post('idCliente'));
		
		return $this->db->affected_rows() >= 1? "1" : "0";
	}
	
	public function enviarCorreoValidacion($idCliente)
	{
		if(!empty($_POST))
		{
			$mensaje='';
			$cliente		= $this->clientes->obtenerCliente($idCliente);
			
			$usuario		= $this->clientes->obtenerUsuario($cliente->idPromotor);

			$asunto			= 'Pago recibido';
			
			/*$mensaje		=' <strong>Responsable: </strong>'.$usuario->nombre.'<br />';
			$mensaje		.= '<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($fecha).'<br />';
			$mensaje		.= '<strong>Comentarios: </strong> '.$seguimiento->comentarios.'<br />';
			$mensaje		.= '<strong>Cliente: </strong> '.$cliente->empresa;*/

			#$mensaje		=nl2br($seguimiento->comentarios);
			$mensaje		.='
			Apreciable '.$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno.',<br /><br />
		
			Hemos recibido su pago, muchas gracias. <br />
			'.(date('H')<16?'En el transcurso del día':'El día de mañana').' recibirá los accesos de la plataforma, así como las instrucciones de ingreso. <br /><br />
			
			Cualquier duda favor de comunicarse y con gusto lo atenderemos.';
			
			$this->load->library('email');
			$this->email->from($usuario->correo,$usuario->nombre);
			#$this->email->to($cliente->email,$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno);
			$this->email->to($cliente->email.', juancarlos.franco@iexe.edu.mx');
			$this->email->subject($asunto);
			$this->email->message($mensaje);

			if (!$this->email->send())
			{
				#print("0");
			}
			else
			{
				#print("1");
			}
				
		}
		else
		{
			#print("2");
		}
	}
	
	public function borrarPreinscrito()
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			#'fechaPreinscrito'		=> $this->_fecha_actual,
			'preinscrito' 			=> '0',
			'confirmado' 			=> '0',
		);
		
		$this->db->where('idCliente', $this->input->post('idCliente'));
		$this->db->update('clientes_academicos', $data);
		
		$this->db->where('idCliente', $this->input->post('idCliente'));
		$this->db->delete('clientes_programas_ventas');


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	//EDITAR ACADEMICOS
	
	public function registrarTotalesAcademicosProspecto()
	{
		$data=array
		(
			'inscripcion'				=> $this->input->post('inscripcion'),
			'colegiatura'				=> $this->input->post('colegiatura'),
			'reinscripcion'				=> $this->input->post('reinscripcion'),
			'titulacion'				=> $this->input->post('titulacion'),
			
			'cantidadInscripcion'		=> $this->input->post('cantidadInscripcion'),
			'cantidadColegiatura'		=> $this->input->post('cantidadColegiatura'),
			'cantidadReinscripcion'		=> $this->input->post('cantidadReinscripcion'),
		);
		
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes_academicos', $data); 
		
		return $this->db->affected_rows() >= 1? "1" : "0";
	}
	
	//EDITAR MATRICULA
	
	public function registrarMatricula()
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			'matricula'				=> $this->input->post('txtMatricula'),
			'mes'					=> $this->input->post('selectMesRegistro'),
		);
		
		$this->db->where('idAcademico',$this->input->post('txtIdAcademico'));
		$this->db->update('clientes_academicos', $data); 
		
		
		$data=array
		(
			'idPeriodo'				=> $this->input->post('selectPeriodoRegistro'),
		);
		
		$this->db->where('idRelacion',$this->input->post('txtIdRelacion'));
		$this->db->update('clientes_periodos_relacion', $data); 
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function editarEstatusSeguimientoDetalle()
	{
		$data=array
		(
			'idEstatus'				=> $this->input->post('idEstatus'),
		);
		
		if($this->input->post('idEstatus')==3)
		{
			$data=array
			(
				'idEstatus'				=> $this->input->post('idEstatus'),
				'fechaResuelta'			=> $this->input->post('fechaResuelta'),
				'horaResuelta'			=> $this->input->post('horaResuelta'),
			);
		}
		
		$this->db->where('idSeguimiento',$this->input->post('idSeguimiento'));
		$this->db->update('seguimiento', $data); 
		
		return $this->db->affected_rows() >= 1? "1" : "0";
	}
	
	//REPORTE PARA PROSPECTOS
	
	public function obtenerReporteProspectos($idPromotor=0,$idCampana=0,$idPrograma=0,$idFuente=0,$todos=0,$inicio='',$fin='',$idCampanaOriginal=0)
	{
		$sql=" select count(distinct a.idCliente) as numeroProspectos, a.idPromotor,
		concat(b.nombre,' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor,
		(select c.nombre from clientes_campanas as c where c.idCampana=a.idCampana) as campana,
		(select c.nombre from clientes_campanas as c where c.idCampana=a.idCampanaOriginal) as campanaOriginal,
		(select c.nombre from clientes_fuentes as c where c.idFuente=a.idFuente) as fuente,
		(select c.nombre from clientes_programas as c inner join clientes_academicos as d on d.idPrograma=c.idPrograma where d.idCliente=a.idCliente) as programa

		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1' ";
		
		$sql.=" and date(a.fechaRegistro) between '$inicio' and '$fin' ";

		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idCampanaOriginal!=0?" and  a.idCampanaOriginal='$idCampanaOriginal' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		
		/*if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}*/
		
		$sql.=" group by a.idPromotor ";
		$sql.=" order by promotor asc ";
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	//EDITAR ACADEMICOS Y VENTA
	public function editarComision()
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			'inscripcion'				=> $this->input->post('inscripcion'),
			'colegiatura'				=> $this->input->post('colegiatura'),
			'reinscripcion'				=> $this->input->post('reinscripcion'),
			'titulacion'				=> $this->input->post('titulacion'),
			'idPrograma'				=> $this->input->post('idPrograma'),
		);
		
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes_academicos', $data); 
		
		$data=array
		(
			'idCampana'				=> $this->input->post('idCampana'),
		);
		
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes', $data); 
		
		
		$programa		= $this->configuracion->obtenerProgramasEditar($this->input->post('idPrograma'));
		
		$data=array
		(
			'venta'				=> $this->input->post('venta'),
			'comision'			=> $programa->comision,
			'idPrograma'		=> $this->input->post('idPrograma'),
		);
		
		$this->db->where('idVenta',$this->input->post('idVenta'));
		$this->db->update('clientes_programas_ventas', $data); 
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	//REPORTE DE PROSPECTOS
	public function contarInscritos($criterio='',$idPrograma=0,$idCampana=0,$idPromotor=0,$todos=0,$inicio,$fin)
	{
		$sql=" select a.idCliente
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		inner join clientes_academicos as c
		on a.idCliente=c.idCliente
		where a.activo='1'
		and c.confirmado='1'
		and date(c.fechaInscrito) between '$inicio' and '$fin' ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerInscritos($numero,$limite,$criterio='',$idPrograma=0,$idCampana=0,$idPromotor=0,$todos=0,$inicio,$fin)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select a.*, c.fechaInscrito,
		datediff(c.fechaInscrito,a.fechaRegistro) as numeroDias,
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on d.idPrograma=e.idPrograma where a.idCliente=e.idCliente) as programa

		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join clientes_academicos as c
		on a.idCliente=c.idCliente
		where a.activo='1'
		
		and c.confirmado='1'
		and date(c.fechaInscrito) between '$inicio' and '$fin'  ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" order by a.fechaRegistro asc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	#PLANTILLAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerPlantillas($permiso,$tipoPlantilla,$idPrograma=0)
	{
		$sql=" select a.*,
		(select concat(b.nombre, ' ', b.apellidoPaterno, ' ',b.apellidoMaterno) from usuarios as b where b.idUsuario=a.idUsuario) as promotor,
		(select b.nombre from clientes_programas as b where b.idPrograma=a.idPrograma) as programa
		from correos_plantillas as a
		where a.idPlantillaPadre=0
		and tipoPlantilla='$tipoPlantilla' ";
		
		$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
		$sql.=$idPrograma!=0?" and  a.idPrograma='$idPrograma' ":'';
		
		$sql.=" order by a.fecha desc  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerAdjuntosPlantilla($idPlantillaPadre)
	{
		$sql="select * from correos_plantillas
		where idPlantillaPadre='$idPlantillaPadre'
		order by fecha desc  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPlantilla($idPlantilla)
	{
		$sql="select * from correos_plantillas
		where idPlantilla='$idPlantilla'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarDirectorioPlantilla($path)
	{
		$path = rtrim( strval( $path ), '/' ) ;
		
		$d = dir( $path );
		
		if( ! $d )
			return false;
		
		while ( false !== ($current = $d->read()) )
		{
			if( $current === '.' || $current === '..')
				continue;
			
			$file = $d->path . '/' . $current;
			
			if( is_dir($file) )
				$this->borrarDirectorioPlantilla($file);
			
			if( is_file($file) )
				unlink($file);
		}
		
		rmdir( $d->path );
		$d->close();
		return true;
	}
	
	public function borrarPlantilla($idPlantilla)
	{
		$plantilla	= $this->obtenerPlantilla($idPlantilla);
		
		$this->db->where('idPlantilla',$idPlantilla);
		$this->db->delete('correos_plantillas');
		
		$carpeta		= carpetaPlantillas.'plantilla_'.$plantilla->idPlantilla.'/';
		
		if($plantilla->idPlantillaPadre>0)
		{
			$carpeta		= carpetaPlantillas.'plantilla_'.$plantilla->idPlantillaPadre.'/';
		}
		
		
		if($this->db->affected_rows()>=1)
		{
			#$this->configuracion->registrarBitacora('Borrar fichero','Clientes',$fichero->nombre); //Registrar bitácora
			
			if(file_exists($carpeta.$plantilla->idPlantilla.'_'.$plantilla->nombre))
			{
				unlink($carpeta.$plantilla->idPlantilla.'_'.$plantilla->nombre);
			}
			
			if($plantilla->idPlantillaPadre==0)
			{
				$adjuntos	= $this->crm->obtenerAdjuntosPlantilla($idPlantilla);
				
				foreach($adjuntos as $row)
				{
					$this->db->where('idPlantilla',$row->idPlantilla);
					$this->db->delete('correos_plantillas');
		
					if(file_exists($carpeta.$row->idPlantilla.'_'.$row->nombre))
					{
						unlink($carpeta.$row->idPlantilla.'_'.$row->nombre);
					}
				}
				
				#unlink($carpeta);
				
				$this->borrarDirectorioPlantilla($carpeta);
			}
			
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function subirPlantilla($nombre,$tamano,$idPlantillaPadre=0,$extension='',$idUsuario=0,$tipoPlantilla='',$idPrograma=0)
	{
		$data=array
		(
			'nombre'			=> $nombre,
			'tamano'			=> $tamano,
			'fecha'				=> $this->_fecha_actual,
			
			'idPlantillaPadre'	=> $idPlantillaPadre,
			'extension'			=> $extension,
			
			'tipoPlantilla'		=> $tipoPlantilla,
			'idUsuario'			=> $tipoPlantilla=='0'?$idUsuario:1,
			'idPrograma'		=> $tipoPlantilla=='1'?$idPrograma:0,
		);
		
		#$data	= procesarArreglo($data);
		$this->db->insert('correos_plantillas',$data);
		$idPlantilla=$this->db->insert_id();
		
		#$this->configuracion->registrarBitacora('Subir fichero','Clientes',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idPlantilla:0;
	}
	
	
	public function obtenerArchivoPlantilla($Rupa='',$idPlantilla=0)
	{ 
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		#$ruta	= carpetaPlantillas.'plantilla_1/';
		$ruta	= strlen($Rupa)==0?carpetaPlantillas.'plantilla_'.$idPlantilla.'/':$Rupa;
		
		// abrir un directorio y listarlo recursivo 
		if (is_dir($ruta)) 
		{ 
			if ($dh = opendir($ruta)) 
			{ 
				while (($file = readdir($dh)) !== false) 
				{ 
					//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio 
					//mostraría tanto archivos como directorios 
					#echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file); 
					
					if (!is_dir($ruta . $file))
					{
						$trozos 	= explode(".", $file); 
						$extension 	= end($trozos); 

						 if($extension=='html')
						 {
							 closedir($dh); 
							 
							return $ruta . $file;
						 }
						 
						 if($extension=='htm')
						 {
							 closedir($dh); 
							 
							 return $ruta . $file;
						 }
						 
						 if($extension=='xhtml')
						 {
							 closedir($dh); 
							 
							 return $ruta . $file;
						 }
					}
					
					if (is_dir($ruta . $file) && $file!="." && $file!="..")
					{ 
						$this->obtenerArchivoPlantilla($ruta . $file . "/"); 
					} 
				} 
				
				closedir($dh); 
				
				return '0';
			} 
		}
		
		else 
		{
			return '0';
		}
	}
	
	public function registrarEnvioPlantilla($idCliente,$idPlantilla)
	{
		$data=array
		(
			'idCliente'			=> $idCliente,
			'idPlantilla'		=> $idPlantilla,
			'fecha'				=> $this->_fecha_actual,
			'idUsuario'			=> $this->_user_id,
		);

		$this->db->insert('correos_plantillas_envios',$data);
	}
	
	public function editarPlantilla()
	{
		$html			= $this->input->post('html');
		$idPlantilla	= $this->input->post('idPlantilla');
		$extension		= $this->input->post('extension');
		$archivo		= $this->input->post('archivo');
		$carpeta		= carpetaPlantillas.'plantilla_'.$idPlantilla.'/';
		
		if($extension=='zip')
		{
			$html  	= str_replace('src="'.base_url().$carpeta,'src="',$html);
		}

		guardarArchivoXML($archivo,$html);
		
		echo "1";
	}
	
	public function editarPromotorPlantilla()
	{
		$this->db->where('idPlantilla',$this->input->post('idPlantilla'));
		$this->db->update('correos_plantillas',array('idUsuario'=>$this->input->post('idUsuario')));

		return $this->db->affected_rows()>=1?1:0;
	}
	
	public function obtenerPlantillasEnviadas($idCliente=0)
	{
		$sql=" select a.*, c.nombre as plantilla,
		concat(b.nombre, ' ', b.paterno, ' ',b.materno) as alumno, b.empresa,
		(select d.nombre from clientes_programas as d where d.idPrograma=c.idPrograma) as programa
		from correos_plantillas_envios as a
		
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join correos_plantillas as c
		on c.idPlantilla=a.idPlantilla
		
		where a.idCliente='$idCliente' ";

		$sql.=" order by a.fecha desc  ";
		
		return $this->db->query($sql)->result();
	}
	
	//OBTENER LA ÚLTIMA CONEXIÓN DE LOS ALUMNOS PREINSCRITOS
	
	public function obtenerUltimaConexionPreinscrito($matricula)
	{
		/*$dsn 		= 'mysqli://iexe2013_elastix:Elastix%&892#@iexe.edu.mx/iexe2013_elastix';
		$base		= $this->load->database($dsn,true);*/
		
		$this->load->helper('base');
		
		$bases		= obtenerBases();
		
		foreach($bases as $row)
		{
			$dsn		= obtenerConexion('iexe.edu.mx','iexe2013_iexe','Iexe%2015$',$row);
			$base		= $this->load->database($dsn,true);
			
			$sql ="	select from_unixtime(lastaccess) as ultimaConexion
			FROM `mdl_user` 
			WHERE username='$matricula'  ";
	
			$conexion	 = $base->query($sql)->row();
			
			if($conexion!=null)
			{
				$this->load->database('default',true);
				
				return $conexion->ultimaConexion;
			}
		}

		$this->load->database('default',true);
		
		return '';
	}
	
	//REPORTE DE PROSPECTOS
	public function contarPrimerContacto($criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$prospecto=-1,$idPromotor=0,$todos=0,$inicio='',$fin='',$seguimientos=0,$tipoFecha=0)
	{
		$sql=" select a.idCliente
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1'
		and a.idZona!=2
		and a.idZona!=8
		and idZona!=4 ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$seguimientos!=0?" and  (select count(d.idDetalle) from seguimiento_detalles as d inner join seguimiento as e on d.idSeguimiento=e.idSeguimiento where a.idCliente=e.idCliente) >4 ":'';
		
		$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicio' and '$fin' ":" 
		and (select date(d.fechaSeguimiento) from seguimiento_detalles  as d inner join seguimiento as e on e.idSeguimiento=d.idSeguimiento  where a.idCliente=e.idCliente order by d.fechaSeguimiento limit 1) between '$inicio' and '$fin' ";
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerPrimerContacto($numero,$limite,$criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$prospecto=-1,$idPromotor=0,$todos=0,$inicio='',$fin='',$seguimientos=0,$tipoFecha=0)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select a.*, 
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampanaOriginal) as campanaOriginal,
		
		(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente,
		
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on d.idPrograma=e.idPrograma where a.idCliente=e.idCliente) as programa,
		
		(select count(d.idDetalle) from seguimiento_detalles as d inner join seguimiento as e on d.idSeguimiento=e.idSeguimiento where a.idCliente=e.idCliente) as numeroSeguimientos,
		
		(select d.fechaInscrito from clientes_academicos  as d where a.idCliente=d.idCliente) as fechaInscrito,
		
		(select concat(d.fecha,' ',d.hora) from seguimiento_detalles  as d inner join seguimiento as e on e.idSeguimiento=d.idSeguimiento  where a.idCliente=e.idCliente order by d.fecha asc limit 1,1) as fechaContacto
		
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where a.activo='1'
		and a.idZona!=2
		and a.idZona!=8
		and idZona!=4  ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$seguimientos!=0?" and  (select count(d.idDetalle) from seguimiento_detalles as d inner join seguimiento as e on d.idSeguimiento=e.idSeguimiento where a.idCliente=e.idCliente) >4 ":'';
		
		$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicio' and '$fin' ":" 
		and (select date(d.fecha) from seguimiento_detalles  as d inner join seguimiento as e on e.idSeguimiento=d.idSeguimiento  where a.idCliente=e.idCliente order by d.fecha asc limit 1,1) between '$inicio' and '$fin' ";
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" order by a.nombre asc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function contarSeguimientosPromotor($idUsuarioRegistro=0,$fecha)
	{
		$sql=" select count(a.idDetalle) as numero
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		
		where b.idUsuarioRegistro='$idUsuarioRegistro'
		and date(a.fechaRegistro)='$fecha'
		and b.tipo='1' ";
		
		$sql.=" and a.fechaRegistro != concat(a.fechaSeguimiento, ' ', a.horaInicial)";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function sumarSeguimientosPromotor($inicio,$fin)
	{
		$sql=" select count(a.idDetalle) as numero, b.idUsuarioRegistro
		from seguimiento_detalles as a
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		
		where date(a.fechaRegistro) between '$inicio' and '$fin'
		
		and b.tipo='1' ";
		
		$sql.=" and a.fechaRegistro != concat(a.fechaSeguimiento, ' ', a.horaInicial)";
		
		$sql.=" group by b.idUsuarioRegistro ";

		return $this->db->query($sql)->result();
	}
	
	//REPORTE DE MÉDTODO
	public function contarMetodo($criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='')
	{
		$sql=" select a.idCliente
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento 
		inner join seguimiento_detalles_metodos as e
		on e.idDetalle=d.idDetalle ";

		$sql.=" where a.activo='1'
		and a.idZona!=2
		
		and idZona!=4 ";
		
		#and a.idZona!=8
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		$sql.=$idMetodo!=0?" and  e.idMetodo='$idMetodo' ":'';

		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		
		#$sql.=" group by a.idCliente ";
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerMetodo($numero,$limite,$criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='')
	{
		#$orden=" order by a.empresa asc ";
		#(select f.nombre from seguimiento_metodos as f where f.idMetodo=e.idMetodo) as metodo
		$sql=" select a.idCliente, a.empresa, a.nombre, a.paterno, a.materno,  d.fechaRegistro, a.email, a.telefono, a.movil,
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select f.nombre from clientes_fuentes as f where f.idFuente=a.idFuente) as fuente,
		(select f.nombre from clientes_programas as f inner join clientes_academicos as g on f.idPrograma=g.idPrograma where a.idCliente=g.idCliente) as programa,
		
		(select f.nombre from seguimiento_metodos as f where f.idMetodo=e.idMetodo) as metodo

		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento
		
		inner join seguimiento_detalles_metodos as e
		on e.idDetalle=d.idDetalle ";

		$sql.=" where a.activo='1'
		
		and a.idZona!=2
		and idZona!=4  ";
		#and a.idZona!=8
		
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		$sql.=$idMetodo!=0?" and  e.idMetodo='$idMetodo' ":'';

		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		#$sql.=" group by a.idCliente ";		
		$sql.=" order by d.fechaRegistro desc  ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;

		return $this->db->query($sql)->result();
	}
	
	public function contarMetodoProspectos($criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='')
	{
		$sql=" select distinct(a.idCliente)
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento
		
		inner join seguimiento_detalles_metodos as e
		on e.idDetalle=d.idDetalle
		
		where a.activo='1'
		and a.idZona!=2
		and idZona!=4 ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		#$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$idMetodo!=0?" and  e.idMetodo='$idMetodo' ":'';
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";

		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		return $this->db->query($sql)->num_rows();
	}
	
	public function contarMetodoGlobal($criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='',$contactado='',$cualificado='',$interesado='',$idCausa=0,$idDetalle=0)
	{
		$sql=" select a.idCliente
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento 
		
		inner join seguimiento_detalles_metodos as e
		on e.idDetalle=d.idDetalle
		
		where a.activo='1'
		and a.idZona!=2
		
		and idZona!=4 ";
		
		#and a.idZona!=8
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		#$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$idMetodo!=0?" and  e.idMetodo='$idMetodo' ":'';
		$sql.=$contactado!=''?" and  e.contactado='$contactado' ":'';
		$sql.=$cualificado!=''?" and  d.cualificado='$cualificado' ":'';
		$sql.=$interesado!=''?" and  d.interesado='$interesado' ":'';
		$sql.=$idCausa!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idCausa='$idCausa' )>0 ":'';
		$sql.=$idDetalle!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idDetalle='$idDetalle' )>0 ":'';

		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		
		$sql.=" group by a.idCliente, contactado, cualificado, interesado ";
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerMetodoGlobal($numero,$limite,$criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='',$contactado='',$cualificado='',$interesado='',$idCausa=0,$idDetalle=0)
	{
		#$orden=" order by a.empresa asc ";
		#(select f.nombre from seguimiento_metodos as f where f.idMetodo=e.idMetodo) as metodo
		$sql=" select a.idCliente, a.empresa, a.nombre, a.paterno, a.materno,  d.fechaRegistro, a.email, a.telefono, a.movil,
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor, d.fichaPagoEnviada,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select f.nombre from clientes_fuentes as f where f.idFuente=a.idFuente) as fuente,
		(select f.nombre from clientes_programas as f inner join clientes_academicos as g on f.idPrograma=g.idPrograma where a.idCliente=g.idCliente) as programa,
		
		e.contactado, d.cualificado, d.interesado,
		
		(select concat(f.idCausa,'|',g.nombre,'|',f.texto) from clientes_nocuali as f inner join clientes_nocuali_causas as g on g.idCausa=f.idCausa where a.idCliente=f.idCliente limit 1 ) as causa,
		(select concat(f.idDetalle,'|',g.nombre,'|',f.texto) from clientes_nocuali as f inner join clientes_nocuali_causas_detalles as g on g.idDetalle=f.idDetalle where a.idCliente=f.idCliente limit 1 ) as detalleCausa

		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento
		
		inner join seguimiento_detalles_metodos as e
		on e.idDetalle=d.idDetalle  ";
		
	
	
		$sql.=" where a.activo='1'
		
		and a.idZona!=2
		and idZona!=4  ";
		#and a.idZona!=8
		
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=$idMetodo!=0?" and  e.idMetodo='$idMetodo' ":'';
		$sql.=$contactado!=''?" and  e.contactado='$contactado' ":'';
		$sql.=$cualificado!=''?" and  d.cualificado='$cualificado' ":'';
		$sql.=$interesado!=''?" and  d.interesado='$interesado' ":'';
		$sql.=$idCausa!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idCausa='$idCausa' )>0 ":'';
		$sql.=$idDetalle!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idDetalle='$idDetalle' )>0 ":'';

		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" group by a.idCliente, contactado, cualificado, interesado ";
		$sql.=" order by d.fechaRegistro desc  ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;

		return $this->db->query($sql)->result();
	}
	
	//PARA EL REPORTE DE PROSPECTOS SIN METODO
	
	public function contarProspectosGlobal($criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='',$idProspecto=0,$idDetalleProspecto=0)
	{
		$sql=" select a.idCliente
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento ";

		$sql.=" where a.activo='1'
		and a.idZona!=2
		
		and idZona!=4 ";
		
		#and a.idZona!=8
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		#$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=" and d.interesado='1' and d.cualificado!='0' ";
		$sql.=$idProspecto!=0?" and d.idProspecto='$idProspecto' ":'';
		$sql.=$idDetalleProspecto!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idDetalle='$idDetalleProspecto' )>0 ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		
		$sql.=" group by a.idCliente ";
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerProspectosGlobal($numero,$limite,$criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='',$idProspecto=0,$idDetalleProspecto=0)
	{
		#$orden=" order by a.empresa asc ";
		#(select f.nombre from seguimiento_metodos as f where f.idMetodo=e.idMetodo) as metodo
		$sql=" select a.idCliente, a.empresa, a.nombre, a.paterno, a.materno,  d.fechaRegistro, a.email, a.telefono, a.movil,
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select f.nombre from clientes_fuentes as f where f.idFuente=a.idFuente) as fuente,
		(select f.nombre from clientes_programas as f inner join clientes_academicos as g on f.idPrograma=g.idPrograma where a.idCliente=g.idCliente) as programa,
		
		d.cualificado, d.interesado

		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento ";
	
	
		$sql.=" where a.activo='1'
		
		and a.idZona!=2
		and idZona!=4  ";
		#and a.idZona!=8
		
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		$sql.=" and d.interesado='1' and d.cualificado!='0' ";
		$sql.=$idProspecto!=0?" and d.idProspecto='$idProspecto' ":'';
		$sql.=$idDetalleProspecto!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idDetalle='$idDetalleProspecto' )>0 ":'';

		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" group by a.idCliente ";		
		$sql.=" order by d.fechaRegistro desc  ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	
	//REPOSITORIO
	public function contarRepositorio($criterio='',$inicio='',$fin='')
	{
		$sql=" select a.idAlumno
		from alumnos_repositorio as a 
		where a.idAlumno>0 
		and a.activo='1' ";

		$sql.=strlen($criterio)>0?" and (concat(a.nombre,' ', a.apellido) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.telefono2 like '%$criterio%') ":'';

		$sql.=" and date(a.fechaRegistro) between '$inicio' and '$fin' ";
		

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerRepositorio($numero,$limite,$criterio='',$inicio='',$fin='')
	{
		$sql=" select a.*
		from alumnos_repositorio as a 
		where a.idAlumno>0 
		and a.activo='1'  ";

		$sql.=strlen($criterio)>0?" and (concat(a.nombre,' ', a.apellido) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.telefono2 like '%$criterio%') ":'';

		$sql.=" and date(a.fechaRegistro) between '$inicio' and '$fin' ";
		
		$sql.=" order by a.fechaRegistro desc, a.nombre asc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function contarDetalleEmbudo($idEmbudo,$idCliente)
	{
		$sql=" select count(a.idEmbudo) as numero
		from seguimiento_detalles as a 
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		where a.idEmbudo='$idEmbudo' 
		and b.idCliente='$idCliente'  ";

		return $this->db->query($sql)->row()->numero;
	}
	
	//REPORTE EMBUDO
	
	public function obtenerReporteEmbudo($idPrograma=0,$idCampana=0,$inicio,$fin)
	{
		$sql=" select count(a.idDetalle) as numeroEmbudos, 
		a.interesado, a.cualificado, d.contactado
		from seguimiento_detalles as a 
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		inner join clientes as c
		on c.idCliente=b.idCliente
		
		inner join seguimiento_detalles_metodos as d
		on d.idDetalle=a.idDetalle

		where c.activo='1'
		and c.idZona!=2
		and c.prospecto='1'
		and d.contactado!='2'
		
		and a.idDetalle in (
		select max(f.idDetalle)
		from seguimiento_detalles as f
	    group by f.idSeguimiento) ";

		$sql.=$idCampana!=0?" and  c.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=c.idCliente limit 1) = '$idPrograma' ":'';
		#$sql.=$idCausa!=-1?" and  a.idCausa='$idCausa' ":'';
		
		$sql.=" and  date(a.fechaRegistro) between '$inicio' and '$fin' ";

		$sql.=" group by c.idCliente";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesEmbudo($query)
	{
		$sinRespuesta	= 0;
		$contactado		= 0;
		$cualificado	= 0;
		$noCualificado	= 0;
		$noInteresado	= 0;
		$interesado		= 0;
		
		foreach($query as $row)
		{
			if($row->contactado=='1') $contactado++;
			if($row->contactado=='0') $sinRespuesta++;
			if($row->cualificado=='1') $cualificado++;
			if($row->cualificado=='0') $noCualificado++;
			if($row->interesado=='1') $interesado++;
			if($row->interesado=='0') $noInteresado++;
		}
		
		/*$data[0]['valor']	= $contactado;
		$data[0]['nombre']  = 'Contactado';
		
		$data[1]['valor']	= $sinRespuesta;
		$data[1]['nombre']  = 'Sin respuesta';
		
		$data[2]['valor']	= $cualificado;
		$data[2]['nombre']  = 'Cualificado';
		
		$data[3]['valor']	= $noCualificado;
		$data[3]['nombre']  = 'Contactado';
		
		$data[4]['valor']	= $interesado;
		$data[4]['nombre']  = 'Interesado';
		
		$data[5]['valor']	= $noInteresado;
		$data[5]['nombre']  = 'No interesado';*/
		
		
		$data=array
		(
			'Contactado' 		=> $contactado,
			'Sin respuesta' 	=> $sinRespuesta,
			'Cualificado' 		=> $cualificado,
			'No cualificado' 	=> $noCualificado,
			'Interesado' 		=> $interesado,
			'No interesado' 	=> $noInteresado,
		);
		
		#arsort($data);

		/*foreach($data as $x => $x_value) 
		{
			echo "Key=" . $x . ", Value=" . $x_value;
			echo "<br>";
		}*/
		
		
		return $data;
		
	}
	
	public function obtenerConRespuesta($idPrograma=0,$idCampana=0,$inicio,$fin)
	{
		$sql=" select count(a.idDetalle) as numeroEmbudos, a.interesado, a.cualificado
		from seguimiento_detalles as a 
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		inner join clientes as c
		on c.idCliente=b.idCliente
		
		inner join seguimiento_detalles_metodos as d
		on d.idDetalle=a.idDetalle

		where c.activo='1'
		and c.idZona!=8
		and c.prospecto='1'
		and d.contactado='1' ";

		$sql.=$idCampana!=0?" and  c.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=c.idCliente limit 1) = '$idPrograma' ":'';
		#$sql.=$idCausa!=-1?" and  a.idCausa='$idCausa' ":'';
		
		$sql.=" and  date(a.fechaRegistro) between '$inicio' and '$fin' ";

		$sql.=" group by c.idCliente";

		return $this->db->query($sql)->num_rows();
	}
	
	/*public function obtenerConRespuesta($idPrograma=0,$idCampana=0,$inicio,$fin)
	{
		$sql=" select count(a.idDetalle) as numeroEmbudos, a.interesado, a.cualificado
		from seguimiento_detalles as a 
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		inner join clientes as c
		on c.idCliente=b.idCliente
		
		inner join seguimiento_detalles_metodos as d
		on d.idDetalle=a.idDetalle

		where c.activo='1'
		and c.idZona!=8
		and c.prospecto='1'
		and d.contactado='1' ";

		$sql.=$idCampana!=0?" and  c.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=c.idCliente limit 1) = '$idPrograma' ":'';
		#$sql.=$idCausa!=-1?" and  a.idCausa='$idCausa' ":'';
		
		$sql.=" and  date(a.fechaRegistro) between '$inicio' and '$fin' ";

		$sql.=" group by c.idCliente";

		return $this->db->query($sql)->num_rows();
	}*/
	
	/*public function obtenerReporteEmbudo($idPrograma=0,$idCampana=0,$inicio,$fin)
	{
		$sql=" select count(a.idEmbudo) as numeroEmbudos, d.nombre, a.idEmbudo
		from seguimiento_detalles as a 
		inner join seguimiento as b
		on a.idSeguimiento=b.idSeguimiento
		inner join clientes as c
		on c.idCliente=b.idCliente
		
		inner join seguimiento_embudos as d
		on d.idEmbudo=a.idEmbudo

		where c.activo='1'
		and c.idZona!=2
		and c.idZona!=8
		and c.prospecto='1' ";

		$sql.=$idCampana!=0?" and  c.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=c.idCliente limit 1) = '$idPrograma' ":'';
		#$sql.=$idCausa!=-1?" and  a.idCausa='$idCausa' ":'';
		
		$sql.=" and  date(a.fechaRegistro) between '$inicio' and '$fin' ";

		$sql.=" group by a.idEmbudo";
		$sql.=" order by numeroEmbudos desc ";

		return $this->db->query($sql)->result();
	}*/
	
	//CONTACTADO
	public function contarDetallesMetodo($contactado,$idCliente)
	{
		$sql=" select a.idRelacion
		from seguimiento_detalles_metodos as a 
		inner join seguimiento_detalles as b
		on a.idDetalle=b.idDetalle
		
		inner join seguimiento as c
		on c.idSeguimiento=b.idSeguimiento
		
		where a.contactado='$contactado' 
		and c.idCliente='$idCliente' 
		group by b.idDetalle ";

		#$numero	= $this->db->query($sql)->row();
		
		#return $numero!=null?$numero->numero:0;
		
		return $this->db->query($sql)->num_rows();
	}
	
	//PARA EL REPORTE DE FICHA ENVIADA
	
	public function contarFichaEnviada($criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='',$idProspecto=0,$idDetalleProspecto=0)
	{
		$sql=" select a.idCliente
		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento ";

		$sql.=" where a.activo='1'
		and a.idZona!=2
		and idZona!=4
		and d.fichaPagoEnviada='1' ";
		
		#and a.idZona!=8
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		#$sql.=$prospecto!=-1?" and  a.prospecto='$prospecto' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		#$sql.=" and d.interesado='1' and d.cualificado!='0' ";
		$sql.=$idProspecto!=0?" and d.idProspecto='$idProspecto' ":'';
		$sql.=$idDetalleProspecto!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idDetalle='$idDetalleProspecto' )>0 ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		
		#$sql.=" group by a.idCliente ";
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerFichaEnviada($numero,$limite,$criterio='',$idFuente=0,$idPrograma=0,$idCampana=0,$idMetodo=0,$idPromotor=0,$todos=0,$inicio='',$fin='',$idProspecto=0,$idDetalleProspecto=0)
	{
		#$orden=" order by a.empresa asc ";
		#(select f.nombre from seguimiento_metodos as f where f.idMetodo=e.idMetodo) as metodo
		$sql=" select a.idCliente, a.empresa, a.nombre, a.paterno, a.materno,  d.fechaRegistro, a.email, a.telefono, a.movil,
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select f.nombre from clientes_fuentes as f where f.idFuente=a.idFuente) as fuente,
		(select f.nombre from clientes_programas as f inner join clientes_academicos as g on f.idPrograma=g.idPrograma where a.idCliente=g.idCliente) as programa,
		
		d.cualificado, d.interesado

		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		
		inner join seguimiento as c
		on c.idCliente=a.idCliente
		
		inner join seguimiento_detalles as d
		on d.idSeguimiento=c.idSeguimiento ";
	
	
		$sql.=" where a.activo='1'
		
		and a.idZona!=2
		and idZona!=4 
		and d.fichaPagoEnviada='1' ";
		#and a.idZona!=8
		
		$sql.=" and  date(d.fechaRegistro) between '$inicio' and '$fin' ";
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
		#$sql.=" and d.interesado='1' and d.cualificado!='0' ";
		$sql.=$idProspecto!=0?" and d.idProspecto='$idProspecto' ":'';
		$sql.=$idDetalleProspecto!=0?" and (select count(f.idCausa) from clientes_nocuali as f where a.idCliente=f.idCliente and f.idDetalle='$idDetalleProspecto' )>0 ":'';

		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		#$sql.=" group by a.idCliente ";		
		$sql.=" order by d.fechaRegistro desc  ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
}

<?php
class Administracion_modelo extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;
	protected $fecha;
	protected $hora;
	protected $idRol;

    function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->_user_name 		= $this->session->userdata('name');
		$this->fecha 			= date('Y-m-d');
		$this->hora 			= date('H:i:s');
		
		$this->idRol 			= $this->session->userdata('role');
    }
	
	public function obtenerComprobantesEgresosCompra($idCompra)
	{
		$sql=" select a.*  
		from catalogos_egresos_comprobantes as a
		inner join catalogos_egresos as b
		on a.idEgreso=b.idEgreso
		where b.idCompra='$idCompra' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function borrarComprobanteEgreso($idComprobante)
	{
		$comprobante	=$this->obtenerComprobanteEgreso($idComprobante);
		
		$this->db->where('idComprobante',$idComprobante);
		$this->db->delete('catalogos_egresos_comprobantes');
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar comprobante','Contabilidad - Egresos - Comprobantes',$comprobante->nombre); //Registrar bitácora
			
			if(file_exists('media/ficheros/comprobantesEgresos/'.$comprobante->idComprobante.'_'.$comprobante->nombre))
			{
				unlink('media/ficheros/comprobantesEgresos/'.$comprobante->idComprobante.'_'.$comprobante->nombre);
			}
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function obtenerComprobantesEgresos($idEgreso)
	{
		$sql="select *  from catalogos_egresos_comprobantes
		where idEgreso='$idEgreso'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerComprobanteEgreso($idComprobante)
	{
		$sql="select *  from catalogos_egresos_comprobantes
		where idComprobante='$idComprobante'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerComprobanteEgresoXml($idEgreso)
	{
		$sql=" select *  from catalogos_egresos_comprobantes
		where idEgreso='$idEgreso'
		and xml='1' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function subirFicherosEgreso($idEgreso,$comprobante,$tamano,$xml='0')
	{
		$data=array
		(
			'nombre'	=> $comprobante,
			'tamano'	=> $tamano,
			'idEgreso'	=> $idEgreso,
			'fecha'		=> $this->_fecha_actual,
			'xml'		=> $xml=='1'?'1':'0',
		);
		
		$this->db->insert('catalogos_egresos_comprobantes',$data);
		$idComprobante	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar comprobante','Contabilidad - Egresos - Comprobantes',$comprobante); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idComprobante:0;
	}
	
	#OTROS INGRESOS
	#-----------------------------------------------------------------------------------------------------------------
	public function borrarComprobante($idComprobante)
	{
		$comprobante	=$this->obtenerComprobante($idComprobante);
		
		$this->db->where('idComprobante',$idComprobante);
		$this->db->delete('catalogos_ingresos_comprobantes');
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar comprobante','Contabilidad - Ingresos - Comprobantes',$comprobante->nombre); //Registrar bitácora
			
			if(file_exists('media/ficheros/comprobantes/'.$comprobante->idComprobante.'_'.$comprobante->nombre))
			{
				unlink('media/ficheros/comprobantes/'.$comprobante->idComprobante.'_'.$comprobante->nombre);
			}
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function obtenerComprobantes($idIngreso)
	{
		$sql="select *  from catalogos_ingresos_comprobantes
		where idIngreso='$idIngreso'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerComprobanteIngresoXml($idIngreso)
	{
		$sql=" select *  from catalogos_ingresos_comprobantes
		where idIngreso='$idIngreso'
		and xml='1' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerComprobante($idComprobante)
	{
		$sql="select *  from catalogos_ingresos_comprobantes
		where idComprobante='$idComprobante'";
		
		return $this->db->query($sql)->row();
	}
	
	public function subirFicheros($idIngreso,$comprobante,$tamano,$xml='0')
	{
		$data=array
		(
			'nombre'	=> $comprobante,
			'tamano'	=> $tamano,
			'idIngreso'	=> $idIngreso,
			'fecha'		=> $this->_fecha_actual,
			'xml'		=> $xml=='1'?'1':'0',
		);
		
		$this->db->insert('catalogos_ingresos_comprobantes',$data);
		$idComprobante	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar comprobante','Contabilidad - Ingresos - Comprobantes',$comprobante); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idComprobante:0;
	}

	public function contarOtrosIngresos($idProducto=0,$idDepartamento=0,$idGasto=0)
	{
		$criterio	= $this->input->post('criterio');
		$inicio		= $this->input->post('inicio');
		$fin		= $this->input->post('fin');
		$idCuenta	= $this->input->post('idCuenta');
		
		$sql="select a.idIngreso
		from catalogos_ingresos as a
		where a.idTraspaso='0' 
		
		and a.idLicencia='$this->idLicencia'
		
		and (a.producto like '%$criterio%'
		or a.transferencia like '%$criterio%'
		or a.cheque like '%$criterio%'  
		
		".(sistemaActivo=='IEXE'?" or 
		(select count(c.idCliente) 
		from clientes as c
		where c.idCliente=a.idCliente
		and (concat(c.nombre, ' ', c.paterno, ' ', c.materno) 
		like '%$criterio%' 
		or c.email like '%$criterio%' 
		or c.telefono like '%$criterio%' 
		or c.movil like '%$criterio%' )) >0 ":'')." 
		
		) ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
	
		$sql.=$idProducto>0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idDepartamento>0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idGasto>0?" and a.idGasto='$idGasto' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCuentaBancoIngreso($idCuenta)
	{
		$sql=" select a.cuenta, a.clabe, a.tarjetaCredito, b.nombre as banco
		from cuentas as a 
		inner join bancos as b
		on a.idBanco=b.idBanco
		where a.idCuenta='$idCuenta' ";
		
		return $this->db->query($sql)->row();
	}

	public function obtenerOtrosIngresos($numero,$limite,$idProducto=0,$idDepartamento=0,$idGasto=0)
	{
		$criterio	= $this->input->post('criterio');
		$inicio		= $this->input->post('inicio');
		$fin		= $this->input->post('fin');
		$idCuenta	= $this->input->post('idCuenta');

		$sql="select a.*, b.nombre as formaPago,
		(select c.idFactura
		from facturas as c 
		inner join facturas_ingresos as d
		on d.idFactura=c.idFactura
		where a.idIngreso=d.idIngreso
		order by c.idFactura desc limit 1) as idFactura,
		
		(select c.nombre from productos as c where c.idProducto=a.idProductoCatalogo) as productoCatalogo,

		(select c.empresa from clientes as c where c.idCliente=a.idCliente) as cliente
		
		".(sistemaActivo=='IEXE'?", (select concat(c.nombre, ' ', c.paterno, ' ', c.materno) from clientes as c where c.idCliente=a.idCliente) as alumno":'')."
		
		".(sistemaActivo=='IEXE'?", (select c.nombre from clientes_campanas as c inner join clientes as d on c.idCampana=d.idCampana where d.idCliente=a.idCliente limit 1) as campana":'')."
		
		
		
		from catalogos_ingresos as a
		inner join catalogos_formas as b
		on a.idForma=b.idForma
		where (a.producto like '%$criterio%'
		or a.transferencia like '%$criterio%'
		or a.cheque like '%$criterio%'
		".(sistemaActivo=='IEXE'?" or 
		(select count(c.idCliente) 
		from clientes as c
		where c.idCliente=a.idCliente
		and (concat(c.nombre, ' ', c.paterno, ' ', c.materno) 
		like '%$criterio%' 
		or c.email like '%$criterio%' 
		or c.telefono like '%$criterio%' 
		or c.movil like '%$criterio%' )) >0 ":'')." ) 
		
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
		
		$sql.=$idProducto>0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idDepartamento>0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idGasto>0?" and a.idGasto='$idGasto' ":'';
		
		$sql.=" order by fecha desc  ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';  
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function sumarOtrosIngresos($idProducto=0,$idDepartamento=0,$idGasto=0)
	{
		$criterio	= $this->input->post('criterio');
		$inicio		= $this->input->post('inicio');
		$fin		= $this->input->post('fin');
		$idCuenta	= $this->input->post('idCuenta');

		$sql=" select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join catalogos_formas as b
		on a.idForma=b.idForma
		where (a.producto like '%$criterio%'
		or a.transferencia like '%$criterio%'
		or a.cheque like '%$criterio%')
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
		
		$sql.=$idProducto>0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idDepartamento>0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idGasto>0?" and a.idGasto='$idGasto' ":'';
	
		return $this->db->query($sql)->row()->total;
	}
	
	#EDITAR EL OTRO INGRESO
	public function obtenerIngresoEditar($idIngreso)
	{
		/*
		 b.cuenta, c.idBanco
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as c
		on b.idBanco=c.idBanco
		*/
		$sql=" select a.*,
		(select b.empresa from clientes as b where b.idCliente=a.idCliente) as cliente,
		(select b.razonSocial from clientes as b where b.idCliente=a.idCliente) as razonSocial,
		(select concat(b.nombre, ' ', b.paterno, ' ', b.materno) from clientes as b where b.idCliente=a.idCliente) as alumno,
		(select c.nombre from productos as c where c.idProducto=a.idProductoCatalogo) as productoCatalogo
		from catalogos_ingresos as a
		where a.idIngreso='$idIngreso' ";

		return $this->db->query($sql)->row();
	}
	
	#CATALOGO DEPARTAMENTOS
	#-----------------------------------------------------------------------------------------------------------------
	public function registrarDepartamento()
	{
		$data=array
		(
			'nombre'	=>$this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_departamentos',$data);
		
		$this->configuracion->registrarBitacora('Registrar departamento','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerDepartamentos($tipo=0)
	{
		$sql="select * from catalogos_departamentos
		where tipo='0' ";
		
		$sql.=$tipo=='1'?" or tipo='1' ":"";
		$sql.=$tipo=='2'?" or tipo='2' ":"";
		
		$sql.=" order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	#CATALOGO NOMBRES
	#-----------------------------------------------------------------------------------------------------------------
	public function registrarNombre()
	{
		$data=array
		(
			'nombre'	=>$this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_nombres',$data);
		
		$this->configuracion->registrarBitacora('Registrar nombre','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerNombres()
	{
		$sql="select * from catalogos_nombres
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNombre($idNombre)
	{
		$sql="select * from catalogos_nombres
		where idNombre='$idNombre'";
		
		return $this->db->query($sql)->row();
	}
	
	#CATALOGO PRODUCTOS
	#-----------------------------------------------------------------------------------------------------------------
	public function registrarProducto()
	{
		$data=array
		(
			'nombre'	=>$this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_productos',$data);
		
		$this->configuracion->registrarBitacora('Registrar producto','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerProductos($tipo=0)
	{
		$sql=" select * from catalogos_productos
		where tipo='0' ";
		
		$sql.=$tipo=='1'?" or tipo='1' ":"";
		$sql.=$tipo=='2'?" or tipo='2' ":"";
		
		$sql.=" order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	#CATALOGO TIPO GASTOS
	#-----------------------------------------------------------------------------------------------------------------
	public function registrarTipoGasto()
	{
		$data=array
		(
			'nombre'	=> $this->input->post('nombre'),
			'tipo'		=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_gastos',$data);
		
		$this->configuracion->registrarBitacora('Registrar tipo de gasto','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerTipoGasto($tipo=0)
	{
		$sql=" select * from catalogos_gastos
		where tipo='0' ";

		$sql.=$tipo=='1'?" or tipo='1' ":"";
		$sql.=$tipo=='2'?" or tipo='2' ":"";
		
		$sql.=" order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	#REGISTRAR INGRESO
	#-----------------------------------------------------------------------------------------------------------------
	public function obtenerFolio()
	{
		$sql="select coalesce(max(folio),0) as folio 
		from  cotizaciones ";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function registrarVentaIngreso($idCliente,$idProducto,$fecha,$total,$cantidad)
	{
		#$fecha			=$this->input->post('fecha');

		#$cantidad		=$this->input->post('cantidad');
		#$total			=$this->input->post('pago');
		#$total			=$total*$cantidad;

		$folio			=$this->obtenerFolio();
		$iva			=$this->session->userdata('iva');
		$iva			=$iva/100;
		$subTotal		=$total/(1+$iva);

		
		#--------------------------ORDEN DE VENTAS----------------------------#
		$serie	="COT-".date('Y-m-d').'-'.$folio;
		$venta	="VEN-".$folio;
		#---------------------------------------------------------------------#

		$comentarios	="Venta directa";
		
		$data=array
		(
			'ordenCompra'		=>$venta,
			'idCliente'			=>$idCliente,
			'fecha'				=>$fecha,
			'fechaPedido'		=>$fecha,
			'fechaEntrega'		=>$fecha,
			'serie'				=>$serie,
			'estatus'			=>'1',
			'idUsuario'			=>$this->_user_id,
			'fechaCompra'		=>$fecha,
			'pago'				=>0,
			'cambio'			=>0, 
			'descuento'			=>0,
			'subTotal'			=>$subTotal,
			'iva'				=>$iva,
			'total'				=>$total,
			'folio'				=>$folio,
			'idLicencia'		=>$this->idLicencia,
			'comentarios'		=>$comentarios,
			'idDivisa'			=>1,
			'tipoCambio'		=>1,
		);
		
		$this->db->insert('cotizaciones',$data);
		$idCotizacion	=$this->db->insert_id();
		
		$data=array
		(
			'idCotizacion' 		=>$idCotizacion,
			'cantidad' 			=>$cantidad,
			'precio' 			=>$subTotal,
			'importe' 			=>$subTotal*$cantidad,
			'idProduct' 		=>$idProducto,
			'tipo' 				=>$subTotal,
			'nombre' 			=>$this->input->post('nombreProducto'),
			'fechaInicio' 		=>$fecha,
			'fechaVencimiento' 	=>$fecha,
			'notificar' 		=>0,
			'facturado' 		=>'0',
			#'enviado' 			=>'1',
			#'entregado' 		=>'1',
			#'produccion' 		=>'1',
		);
		
		$idPeriodo	=$this->input->post('idPeriodo');
		
		if($idPeriodo>0)
		{
			$periodo	=$this->obtenerPeriodo($idPeriodo);
			$fechaFin	=$this->obtenerFechaFinServicio($periodo->valor*$cantidad,$periodo->factor,$fecha);
			
			$data['fechaInicio']		=$fecha;
			$data['fechaVencimiento']	=$fechaFin;
			$data['servicio']			=1;
			$data['notificar']			=1;
		}
		
		$this->db->insert('cotiza_productos',$data);
		
		 return $idCotizacion;  
	}
	
	public function obtenerPeriodo($idPeriodo)
	{
		$sql="select * from produccion_periodos
		where idPeriodo='$idPeriodo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFechaFinServicio($valor,$factor,$fecha)
	{
		$sql="select date_add('".$fecha."',interval ".$valor." $factor) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function registrarIngreso()
	{
		//SUBIR EL ARCHIVO
		$archivo 		= pathinfo($_FILES['archivoIngreso']['name']);
		$xml			= $this->input->post('chkXml');
		
		if($xml=='1' and strlen($_FILES['archivoIngreso']['name'])>3)
		{
			$extensiones 		= array('xml','pdf');
			
			if (!in_array($archivo['extension'],$extensiones)) 
			{
				return array("0",'El archivo seleccionado no es un xml');
			}
		}
		
		$this->db->trans_start();
		
		$idCliente		= $this->input->post('txtIdCliente');
		$idProducto		= $this->input->post('txtIdProducto');
		$fecha			= $this->input->post('txtFechaIngreso');
		$idForma		= $this->input->post('selectFormas');
		$idVenta		= $this->input->post('txtIdVenta');
		$pago			= $this->input->post('txtTotal');
		$cantidad		= $this->input->post('txtCantidad');
		
		/*if($idCliente>0 and $idProducto>0)
		{
			$pago		= $pago*$cantidad;
			$idVenta	= $this->registrarVentaIngreso($idCliente,$idProducto,$fecha,$pago,$cantidad);
		}*/
		
		$data=array
		(
			'idVenta'			=> $idVenta,
			'pago'				=> $this->input->post('txtTotal'),
			'subTotal'			=> $this->input->post('txtImporte'),
			'ivaTotal'			=> $this->input->post('txtTotalIva'),
			'iva'				=> $this->input->post('selectIva'),
			'incluyeIva'		=> $this->input->post('selectIva')>0?'1':'0',
			
			'fecha'				=> $fecha,
			'formaPago'			=> '',
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('txtNumeroTransferencia'),
			'cheque'			=> $this->input->post('txtNumeroCheque'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $idForma==2?$this->input->post('selectNombres'):0,
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			'idProducto'		=> $this->input->post('selectProductos'),
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idCliente'			=> $idCliente,
			'factura'			=> $this->input->post('txtFactura'),
			'remision'			=> $this->input->post('selectFacturaRemision'),
			
			'idProductoCatalogo'=> $idProducto,
			'cantidad'			=> $cantidad,
			'idForma'			=> $idForma,
			
			'idVariable1'		=> $this->input->post('selectVariables1'),
			'idVariable2'		=> $this->input->post('selectVariables2'),
			'idVariable3'		=> $this->input->post('selectVariables3'),
			'idVariable4'		=> $this->input->post('selectVariables4'),
			'idLicencia'		=> $this->idLicencia,
			'idUsuario'			=> $this->_user_id,
			
		);
		
		if($idForma=='4')
		{
			$data['notificacion']	=1;
		}
		
		if(sistemaActivo=='IEXE')
		{
			$data['idPeriodo']	= $this->input->post('selectPeriodosRegistro');
		}
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		$idIngreso	=$this->db->insert_id();
		
		if($idForma!='4')
		{
			$this->contabilidad->registrarPolizaIngreso($data['fecha'],$data['producto'],0,$data['pago'],$idIngreso); //REGISTRAR LA PÓLIZA DE INGRESO
			
			if(sistemaActivo=='IEXE' and $this->input->post('selectProductos')==23)
			{
				//CONVERTIR A CLIENTE
				#$this->db->where('idCliente',$idCliente);
				#$this->db->update('clientes',array('prospecto'=>'0','idZona'=>1,'fechaInscripcion'=>$this->_fecha_actual));
			}
			
		}
		
		$this->configuracion->registrarBitacora('Registrar ingreso','Contabilidad - Ingresos / Egresos',$data['producto'].', $'.number_format($pago,decimales)); //Registrar bitácora
		
		$archivo 		= $_FILES['archivoIngreso']['name'];
		
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->subirFicheros($idIngreso,$archivo,$_FILES['archivoIngreso']['size'],$this->input->post('chkXml'));
			
			move_uploaded_file($_FILES['archivoIngreso']['tmp_name'], carpetaIngresos.basename($idComprobante."_".$archivo));
		}
		
		#CUANDO SE REPITA UN PERIODO
		if($idForma=='4')
		{
			$repetir	= $this->input->post('txtRepetir');
			$idPeriodo	= $this->input->post('selectPeriodos');
			
			if($repetir>0)
			{
				$periodo		=$this->obtenerPeriodo($idPeriodo);
				
				for($i=1;$i<=$repetir;$i++)
				{
					$fechaRegistro	=$this->obtenerFechaFinServicio($periodo->valor,$periodo->factor,$fecha);
					$fecha			=$fechaRegistro;
					$idVenta		=0;
					
					if($idCliente>0 and $idProducto>0) //Registrar la venta
					{
						$idVenta	=$this->registrarVentaIngreso($idCliente,$idProducto,$fecha,$pago,$cantidad);
					}

					$this->registrarIngresoRepetido($fecha,$pago,$idCliente,$idVenta,$idProducto,$cantidad,$idForma);
				}
			}
		}
		
		//ASOCIAR EL INGRESO CON EL CATÁLOGO DE CUENTAS
		$this->registrarCuentasIngresos($idIngreso);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array("0",'Error al registrar el ingreso');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array("1",'El ingreso se ha registrado correctamente');
		}
	}
	
	public function registrarCuentasIngresos($idIngreso)
	{
		for($i=0;$i<=$this->input->post('txtNumeroCuentas');$i++)
		{
			$idCuentaCatalogo	= $this->input->post('txtIdCuentaCatalogo'.$i);
			
			if($idCuentaCatalogo>0)
			{
				$data=array
				(
					'idCuentaCatalogo'	=> $idCuentaCatalogo,
					'idIngreso'			=> $idIngreso
				);
				
				$this->db->insert('catalogos_ingresos_cuentas',$data);
			}
		}
	}
	
	public function obtenerCuentasContablesIngreso($idIngreso)
	{
		$sql=" select a.*, b.codigoAgrupador, b.numeroCuenta,
		b.descripcion, b.nivel, b.naturaleza
		from catalogos_ingresos_cuentas as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idIngreso='$idIngreso' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarIngresoRepetido($fecha,$pago,$idCliente,$idVenta,$idProducto,$cantidad,$idForma)
	{
		$data=array
		(
			'idVenta'			=> $idVenta,
			
			'pago'				=> $this->input->post('txtTotal'),
			'subTotal'			=> $this->input->post('txtImporte'),
			'ivaTotal'			=> $this->input->post('txtTotalIva'),
			'iva'				=> $this->input->post('selectIva'),
			'incluyeIva'		=> $this->input->post('selectIva')>0?'1':'0',
			
			'fecha'				=> $fecha,
			'formaPago'			=> '',
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('txtNumeroTransferencia'),
			'cheque'			=> $this->input->post('txtNumeroCheque'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $idForma==2?$this->input->post('selectNombres'):0,
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			'idProducto'		=> $this->input->post('selectProductos'),
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),

			'comentarios'		=> $this->input->post('txtComentarios'),
			'idCliente'			=> $idCliente,
			'factura'			=> $this->input->post('txtFactura'),
			'remision'			=> $this->input->post('selectFacturaRemision'),
			
			'idProductoCatalogo'=> $idProducto,
			'cantidad'			=> $cantidad,
			'idForma'			=> $idForma,
			
			'idVariable1'		=> $this->input->post('selectVariables1'),
			'idVariable2'		=> $this->input->post('selectVariables2'),
			'idVariable3'		=> $this->input->post('selectVariables3'),
			'idVariable4'		=> $this->input->post('selectVariables4'),
			'idLicencia'		=> $this->idLicencia,
			'idUsuario'			=> $this->_user_id,
		);
		
		if($idForma=='4')
		{
			$data['notificacion']=1;
		}
		
		$this->db->insert('catalogos_ingresos',$data);
	}                                 
	
	#OBTENER EL OTRO EGRESO
	public function obtenerEgresoEditar($idEgreso)
	{
		/*
		, b.cuenta, c.idBanco
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as c
		on b.idBanco=c.idBanco
		*/
		$sql="select a.*,
		(select b.nombre from recursos_personal as b where b.idPersonal=a.idPersonal) as personal,
		(select c.nombre from productos as c where c.idProducto=a.idProductoCatalogo) as productoCatalogo
		from catalogos_egresos as a
		where a.idEgreso='$idEgreso'";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerConceptoEgreso($idEgreso)
	{
		$sql="select producto, pago
		from catalogos_egresos
		where idEgreso='$idEgreso' ";
		
		$egreso	= $this->db->query($sql)->row();
		
		return $egreso!=null?array($egreso->producto,$egreso->pago):array('Sin detalles de egreso','0.00');
	}
	
	#EDITAR INGRESO
	#-----------------------------------------------------------------------------------------------------------------
	
	public function obtenerConceptoIngreso($idIngreso)
	{
		$sql="select producto, pago
		from catalogos_ingresos
		where idIngreso='$idIngreso' ";
		
		$ingreso	= $this->db->query($sql)->row();
		
		return $ingreso!=null?array($ingreso->producto,$ingreso->pago):array('Sin detalles de ingreso','0.00');
	}
	
	public function editarIngreso()
	{
		$idForma		= $this->input->post('selectFormas');
		
		$data=array
		(
			'pago'				=> $this->input->post('txtTotal'),
			'subTotal'			=> $this->input->post('txtImporte'),
			'ivaTotal'			=> $this->input->post('txtTotalIva'),
			
			#'ivaTotal'			=> $this->input->post('txtTotal')-$this->input->post('txtImporte'),
			
			'iva'				=> $this->input->post('selectIva'),
			'incluyeIva'		=> $this->input->post('selectIva')>0?'1':'0',
			
			'idForma'			=> $this->input->post('selectFormas'),
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('txtNumeroTransferencia'),
			'cheque'			=> $this->input->post('txtNumeroCheque'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $idForma==2?$this->input->post('selectNombres'):0,
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			#'idProducto'		=> $this->input->post('txtConcepto'),
			'idProducto'		=> $this->input->post('selectProductos'),
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			
			'fecha'				=> $this->input->post('txtFechaIngreso'),
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idCliente'			=> $this->input->post('txtIdCliente'),
			//'factura'			=> $this->input->post('txtFactura'),
			'cantidad'			=> $this->input->post('txtCantidad'),
			
			'factura'			=> $this->input->post('txtFactura'),
			'remision'			=> $this->input->post('selectFacturaRemision'),
			
			'idVariable1'		=> $this->input->post('selectVariables1'),
			'idVariable2'		=> $this->input->post('selectVariables2'),
			'idVariable3'		=> $this->input->post('selectVariables3'),
			'idVariable4'		=> $this->input->post('selectVariables4'),
			
			'idProductoCatalogo'		=> $this->input->post('txtIdProducto'),
		);
		
		if(sistemaActivo=='IEXE')
		{
			$data['idPeriodo']	= $this->input->post('selectPeriodosRegistro');
		}
		
		if($idForma=='4')
		{
			$data['notificacion']	=1;
		}
		
		$this->db->where('idIngreso',$this->input->post('txtIdIngreso'));
		$this->db->update('catalogos_ingresos',$data);
		
		$data	= procesarArreglo($data);
		$this->configuracion->registrarBitacora('Editar ingreso','Contabilidad - Ingresos / Egresos',$data['producto'].', $'.number_format($this->input->post('txtTotal'),decimales)); //Registrar bitácora
		
		//ASOCIAR EL INGRESO CON EL CATÁLOGO DE CUENTAS
		$this->db->where('idIngreso',$this->input->post('txtIdIngreso'));
		$this->db->delete('catalogos_ingresos_cuentas');
		
		$this->registrarCuentasIngresos($this->input->post('txtIdIngreso'));
		
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	#BORRAR INGRESO
	#-----------------------------------------------------------------------------------------------------------------
	public function borrarIngreso($idIngreso)
	{
		$ingreso	= $this->obtenerConceptoIngreso($idIngreso);
		
		$this->db->where('idIngreso',$idIngreso);
		$this->db->delete('catalogos_ingresos');
		
		$this->db->where('idIngreso',$idIngreso);
		$this->db->delete('catalogos_ingresos_cuentas');
		
		$this->configuracion->registrarBitacora('Borrar ingreso','Contabilidad - Ingresos / Egresos',$ingreso[0].', $'.number_format($ingreso[1],decimales)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	#OTROS EGRESOS
	#-----------------------------------------------------------------------------------------------------------------
	public function contarOtrosEgresos($idNivel1=0,$idNivel2=0,$idNivel3=0,$idPersonal=0)
	{
		$criterio	=$this->input->post('criterio');
		$inicio		= $this->input->post('inicio');
		$fin		= $this->input->post('fin');
		$idCuenta	=$this->input->post('idCuenta');
		
		$sql="select idEgreso
		from catalogos_egresos
		where idTraspaso='0'
		and idLicencia='$this->idLicencia'
		and (producto like '%$criterio%'
		or transferencia like '%$criterio%'
		or cheque like '%$criterio%') ";
		
		$sql.=" and date(fecha) between '$inicio' and '$fin' ";
		$sql.=$idCuenta>0?" and idCuenta='$idCuenta' ":'';
		
		$sql.=$idNivel1>0?" and idNivel1='$idNivel1' ":'';
		$sql.=$idNivel2>0?" and idNivel2='$idNivel2' ":'';
		$sql.=$idNivel3>0?" and idNivel3='$idNivel3' ":'';
		$sql.=$idPersonal>0?" and idPersonal='$idPersonal' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerOtrosEgresos($numero,$limite,$idNivel1=0,$idNivel2=0,$idNivel3=0,$idPersonal=0)
	{
		$criterio	=$this->input->post('criterio');
		$inicio		= $this->input->post('inicio');
		$fin		= $this->input->post('fin');
		$idCuenta	=$this->input->post('idCuenta');

		$sql=" select a.*, b.nombre as formaPago,
		(select c.nombre from recursos_personal as c where  c.idPersonal=a.idPersonal) as personal,
		(select c.nombre from productos as c where c.idProducto=a.idProductoCatalogo) as productoCatalogo,
		
		(select c.nombre from catalogos_niveles1 as c where c.idNivel1=a.idNivel1) as nivel1,
		(select c.nombre from catalogos_niveles2 as c where c.idNivel2=a.idNivel2) as nivel2,
		(select c.nombre from catalogos_niveles3 as c where c.idNivel3=a.idNivel3) as nivel3
		
		from catalogos_egresos as a
		inner join catalogos_formas as b
		on a.idForma=b.idForma
		where (a.producto like '%$criterio%'
		or a.transferencia like '%$criterio%'
		or a.cheque like '%$criterio%') 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
		
		$sql.=$idNivel1>0?" and a.idNivel1='$idNivel1' ":'';
		$sql.=$idNivel2>0?" and a.idNivel2='$idNivel2' ":'';
		$sql.=$idNivel3>0?" and a.idNivel3='$idNivel3' ":'';
		$sql.=$idPersonal>0?" and idPersonal='$idPersonal' ":'';
		
		$sql.=" order by a.fecha desc ";
		$sql .= $numero>0? "limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarOtrosEgresos($idNivel1=0,$idNivel2=0,$idNivel3=0,$idPersonal=0)
	{
		$criterio	=$this->input->post('criterio');
		$inicio		= $this->input->post('inicio');
		$fin		= $this->input->post('fin');
		$idCuenta	=$this->input->post('idCuenta');

		$sql=" select coalesce(sum(a.pago),0) as total
		from catalogos_egresos as a
		inner join catalogos_formas as b
		on a.idForma=b.idForma
		where (a.producto like '%$criterio%'
		or a.transferencia like '%$criterio%'
		or a.cheque like '%$criterio%') 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
		
		$sql.=$idNivel1>0?" and a.idNivel1='$idNivel1' ":'';
		$sql.=$idNivel2>0?" and a.idNivel2='$idNivel2' ":'';
		$sql.=$idNivel3>0?" and a.idNivel3='$idNivel3' ":'';
		$sql.=$idPersonal>0?" and idPersonal='$idPersonal' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function registrarCompraEgreso($idProveedor,$idMaterial,$cantidad,$pago,$fecha)
	{
		$data=array
		(
			'fechaCompra' 	=>$fecha,
			'total'			=>$pago,
			'nombre'		=>'Compra '.$this->input->post('producto'),
			'idProveedor'	=>$idProveedor,
			'idLicencia'	=>$this->idLicencia,
			'subTotal'		=>$this->input->post('incluyeIva')==0?0:$pago/(1+($this->session->userdata('iva')/100)),
			'ivaPorcentaje'	=>$this->input->post('incluyeIva')==0?0:$this->session->userdata('iva'),
			'iva'			=>$this->input->post('incluyeIva')==0?$pago:$pago-$pago/(1+($this->session->userdata('iva')/100)),
		);
		
		$this->db->insert('compras',$data);
		$idCompra	=$this->db->insert_id();

		$data=array
		(
			'idCompra' 		=>$idCompra,
			'idMaterial' 	=>$idMaterial,
			'cantidad' 		=>$cantidad,
			
			#'total' 		=>$pago,
			#'precio' 		=>$pago
			'total'			=>$this->input->post('incluyeIva')==0?$pago:$pago/(1+($this->session->userdata('iva')/100)),
			'precio'		=>$this->input->post('incluyeIva')==0?$pago:$pago/(1+($this->session->userdata('iva')/100)),
		);
		
		$this->db->insert('compra_detalles',$data);
		
		return $idCompra;
	}
	
	public function registrarEgreso()
	{
		//SUBIR EL ARCHIVO
		$archivo 		= pathinfo($_FILES['archivoEgreso']['name']);
		$xml			= $this->input->post('chkXml');
		
		if($xml=='1' and strlen($_FILES['archivoEgreso']['name'])>3)
		{
			$extensiones 		= array('xml','pdf');
			
			if (!in_array($archivo['extension'],$extensiones)) 
			{
				return array("0",'El archivo seleccionado no es un xml');
			}
		}
		
		$this->db->trans_start();
		
		$idProveedor	= $this->input->post('txtIdProveedor');
		$idMaterial		= $this->input->post('idMaterial');
		$idCompra		= $this->input->post('txtIdCompra');
		$pago			= $this->input->post('txtImporte');
		$cantidad		= $this->input->post('txtCantidad');
		$fecha			= $this->input->post('txtFechaEgreso');
		$idForma		= $this->input->post('selectFormas');
		
		/*if($idProveedor>0 and $idMaterial>0)
		{
			$pago		=$pago*$cantidad;
			
			$idCompra	=$this->registrarCompraEgreso($idProveedor,$idMaterial,$cantidad,$pago,$fecha);
			
		}*/
		
		$data=array
		(
			#'pago'				=> $pago,
			
			'pago'				=> $this->input->post('txtTotal'),
			'subTotal'			=> $this->input->post('txtImporte'),
			'ivaTotal'			=> $this->input->post('txtTotalIva'),
			'iva'				=> $this->input->post('selectIva'),
			'incluyeIva'		=> $this->input->post('selectIva')>0?'1':'0',
			
			'fecha'				=> $fecha,
			'formaPago'			=> '',
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('txtNumeroTransferencia'),
			'cheque'			=> $this->input->post('txtNumeroCheque'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $idForma==2?$this->input->post('selectNombres'):'',
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			#'idProducto'		=> $this->input->post('txtConcepto'),
			'idProducto'		=> $this->input->post('selectProductos'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			#'iva'				=> $this->input->post('chkIva')=='1'?$this->input->post('txtIvaPorcentaje'):'0',
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			#'incluyeIva'		=> $this->input->post('chkIva')=='1'?'1':'0',
			'cajaChica'			=> $this->input->post('chkCajaChica')=='1'?'1':'0',
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idProveedor'		=> $this->input->post('txtIdProveedor'),
			'esRemision'		=> $this->input->post('selectFacturaRemision'),
			'remision'			=> $this->input->post('txtRemision'),
			
			'idVariable1'		=> $this->input->post('selectVariables1'),
			'idVariable2'		=> $this->input->post('selectVariables2'),
			'idVariable3'		=> $this->input->post('selectVariables3'),
			'idVariable4'		=> $this->input->post('selectVariables4'),
			
			'idPersonal'		=> $this->input->post('txtIdPersonal'),
			
			'idCompra'			=> $idCompra,
			#'idMaterial'		=> $idMaterial,
			'cantidad'			=> $cantidad,
			#'subTotal'			=> $this->input->post('incluyeIva')==0?$pago:$pago/(1+($this->session->userdata('iva')/100)),
			#'subTotal'			=> $this->input->post('chkIva')=='0'?$pago:$pago/(1+($this->input->post('txtIvaPorcentaje')/100)),
			'idForma'			=> $idForma,
			
			'idProductoCatalogo'=> $this->input->post('txtIdProducto'),
			'idLicencia'		=> $this->idLicencia,
			
			'idNivel1'			=> $this->input->post('selectNivel1'),
			'idNivel2'			=> $this->input->post('selectNivel2'),
			'idNivel3'			=> $this->input->post('selectNivel3'),
            'idUsuario'			=> $this->_user_id,
		);
		
		if($idForma=='4')
		{
			$data['notificacion']	=1;
		}
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_egresos',$data);
		$idEgreso	=$this->db->insert_id();
		
		$this->contabilidad->registrarPolizaEgreso($data['fecha'],$data['producto'],0,$data['pago'],$idEgreso); //REGISTRAR LA PÓLIZA DE INGRESO
		
		$this->configuracion->registrarBitacora('Registrar egreso','Contabilidad - Ingresos / Egresos',$data['producto'].', $'.number_format($pago,decimales)); //Registrar bitácora
		
		//SUBIR EL ARCHIVO
		
		$archivo 		= $_FILES['archivoEgreso']['name'];
		
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->subirFicherosEgreso($idEgreso,$archivo,$_FILES['archivoEgreso']['size'],$xml);
			
			move_uploaded_file($_FILES['archivoEgreso']['tmp_name'], carpetaEgresos.basename($idComprobante."_".$archivo));
		}
		
		#CUANDO SE REPITA UN PERIODO
		if($idForma=='4')
		{
			$repetir	=$this->input->post('txtRepetir');
			$idPeriodo	=$this->input->post('selectPeriodos');
			
			if($repetir>0)
			{
				$periodo		=$this->obtenerPeriodo($idPeriodo);
				
				for($i=1;$i<=$repetir;$i++)
				{
					$fechaRegistro	=$this->obtenerFechaFinServicio($periodo->valor,$periodo->factor,$fecha);
					$fecha			=$fechaRegistro;
					$idCompra		=0;
					
					if($idProveedor>0 and $idMaterial>0)
					{
						$idCompra	=$this->registrarCompraEgreso($idProveedor,$idMaterial,$cantidad,$pago,$fecha);
					}

					$this->registrarEgresoRepetido($fecha,$pago,$idProveedor,$idCompra,$idMaterial,$cantidad,$idForma);
				}
			}
		}
		
		$this->registrarCuentasEgresos($idEgreso);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	public function registrarCuentasEgresos($idEgreso)
	{
		for($i=0;$i<=$this->input->post('txtNumeroCuentas');$i++)
		{
			$idCuentaCatalogo	= $this->input->post('txtIdCuentaCatalogo'.$i);
			
			if($idCuentaCatalogo>0)
			{
				$data=array
				(
					'idCuentaCatalogo'	=> $idCuentaCatalogo,
					'idEgreso'			=> $idEgreso
				);
				
				$this->db->insert('catalogos_egresos_cuentas',$data);
			}
		}
	}
	
	public function obtenerCuentasContablesEgreso($idEgreso)
	{
		$sql=" select a.*, b.codigoAgrupador, b.numeroCuenta,
		b.descripcion, b.nivel, b.naturaleza
		from catalogos_egresos_cuentas as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idEgreso='$idEgreso' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarEgresoRepetido($fecha,$pago,$idProveedor,$idCompra,$idMaterial,$cantidad,$idForma)
	{
		$data=array
		(
			#'pago'				=> $pago,
			
			'pago'				=> $this->input->post('txtTotal'),
			'subTotal'			=> $this->input->post('txtImporte'),
			'ivaTotal'			=> $this->input->post('txtTotalIva'),
			'iva'				=> $this->input->post('selectIva'),
			'incluyeIva'		=> $this->input->post('selectIva')>0?'1':'0',
			
			'fecha'				=> $fecha,
			'formaPago'			=> '',
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('txtNumeroTransferencia'),
			'cheque'			=> $this->input->post('txtNumeroCheque'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $idForma==2?$this->input->post('selectNombres'):0,
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			#'idProducto'		=> $this->input->post('txtConcepto'),
			'idProducto'		=> $this->input->post('selectProductos'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			#'iva'				=> $this->input->post('chkIva')=='1'?$this->input->post('txtIvaPorcentaje'):'0',
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			#'incluyeIva'		=> $this->input->post('chkIva')=='1'?'1':'0',
			'cajaChica'			=> $this->input->post('chkCajaChica')=='1'?'1':'0',
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idProveedor'		=> $idProveedor,
			'esRemision'		=> $this->input->post('selectFacturaRemision'),
			'remision'			=> $this->input->post('txtRemision'),
			
			'idVariable1'		=> $this->input->post('selectVariables1'),
			'idVariable2'		=> $this->input->post('selectVariables2'),
			'idVariable3'		=> $this->input->post('selectVariables3'),
			'idVariable4'		=> $this->input->post('selectVariables4'),
			
			'idPersonal'		=> $this->input->post('txtIdPersonal'),
			
			'idCompra'			=>$idCompra,
			#'idMaterial'		=>$idMaterial,
			'cantidad'			=> $cantidad,
			
			#'subTotal'			=>$this->input->post('incluyeIva')==0?$pago:$pago/(1+($this->session->userdata('iva')/100)),
			#'subTotal'			=> $this->input->post('chkIva')=='0'?$pago:$pago/(1+($this->input->post('txtIvaPorcentaje')/100)),
			'idForma'			=> $idForma,
			
			'idProductoCatalogo'=> $this->input->post('txtIdProducto'),
			'idLicencia'		=> $this->idLicencia,
			
			
			'idNivel1'			=> $this->input->post('selectNivel1'),
			'idNivel2'			=> $this->input->post('selectNivel2'),
			'idNivel3'			=> $this->input->post('selectNivel3'),
            'idUsuario'			=> $this->_user_id,
		);
		
		if($idForma=='4')
		{
			$data['notificacion']	=1;
		}
		
		$this->db->insert('catalogos_egresos',$data);
	}
	
	#EDITAR EGRESO
	public function editarEgreso()
	{
		$this->db->trans_start();
		
		#$incluyeIva=$this->input->post('incluyeIva')==false?0:1;
		$incluyeIva		= $this->input->post('incluyeIva')!='false'?1:0;
		$pago			= $this->input->post('pago');
		$idForma		= $this->input->post('selectFormas');
		
		$data=array
		(
			'pago'				=> $this->input->post('txtTotal'),
			'subTotal'			=> $this->input->post('txtImporte'),
			'ivaTotal'			=> $this->input->post('txtTotalIva'),
			'iva'				=> $this->input->post('selectIva'),
			'incluyeIva'		=> $this->input->post('selectIva')>0?'1':'0',
			
			'idForma'			=> $this->input->post('selectFormas'),
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('txtNumeroTransferencia'),
			'cheque'			=> $this->input->post('txtNumeroCheque'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $idForma==2?$this->input->post('selectNombres'):0,
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			#'idProducto'		=> $this->input->post('txtConcepto'),
			'idProducto'		=> $this->input->post('selectProductos'),
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idProveedor'		=> $this->input->post('txtIdProveedor'),
			'esRemision'		=> $this->input->post('selectFacturaRemision'),
			'remision'			=> $this->input->post('txtRemision'),
			'fecha'				=> $this->input->post('txtFechaEgreso'),
			'cantidad'			=> $this->input->post('txtCantidad'),
			
			'idVariable1'		=> $this->input->post('selectVariables1'),
			'idVariable2'		=> $this->input->post('selectVariables2'),
			'idVariable3'		=> $this->input->post('selectVariables3'),
			'idVariable4'		=> $this->input->post('selectVariables4'),
			
			'idPersonal'		=> $this->input->post('txtIdPersonal'),
			
			'idProductoCatalogo'=> $this->input->post('txtIdProducto'),
			
			
			'idNivel1'			=> $this->input->post('selectNivel1'),
			'idNivel2'			=> $this->input->post('selectNivel2'),
			'idNivel3'			=> $this->input->post('selectNivel3'),
		);
		
		if($this->input->post('idForma')=='4')
		{
			$data['notificacion']	=1;
		}
		
		$data	= procesarArreglo($data);
		$this->db->where('idEgreso',$this->input->post('txtIdEgreso'));
		$this->db->update('catalogos_egresos',$data);
		
		$this->configuracion->registrarBitacora('Editar egreso','Contabilidad - Ingresos / Egresos',$data['producto'].', $'.number_format($data['pago'],decimales)); //Registrar bitácora
		
		//ASOCIAR EL EGRESO CON EL CATÁLOGO DE CUENTAS
		$this->db->where('idEgreso',$this->input->post('txtIdEgreso'));
		$this->db->delete('catalogos_egresos_cuentas');
		
		$this->registrarCuentasEgresos($this->input->post('txtIdEgreso'));
		
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
	
	
	#BORRAR EGRESO
	#-----------------------------------------------------------------------------------------------------------------
	public function borrarEgreso($idEgreso)
	{
		$egreso	= $this->obtenerConceptoEgreso($idEgreso);
		
		$this->db->where('idEgreso',$idEgreso);
		$this->db->delete('catalogos_egresos');
		
		$this->db->where('idEgreso',$idEgreso);
		$this->db->delete('catalogos_egresos_cuentas');

		$this->configuracion->registrarBitacora('Borrar egreso','Contabilidad - Ingresos / Egresos',$egreso[0].', $'.number_format($egreso[1],decimales)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	#TRASPASOS
	#-----------------------------------------------------------------------------------------------------------------
	public function contarTraspasos()
	{
		$sql="select idTraspaso
		from catalogos_traspasos
		where idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerTraspasos($numero,$limite)
	{
		$sql="select a.*, b.cuenta, c.nombre as banco
		from catalogos_traspasos as a
		inner join cuentas as b
		on a.idCuentaOrigen=b.idCuenta 
		inner join bancos as c
		on c.idBanco=b.idBanco
		where a.idLicencia='$this->idLicencia' ";
		
		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentaDestino($idCuenta)
	{
		$sql="select a.*, b.nombre as banco
		from cuentas as a
		inner join bancos as b
		on b.idBanco=a.idBanco
		where a.idCuenta='$idCuenta'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerTraspaso($idTraspaso)
	{
		$sql="select * from catalogos_traspasos
		where idTraspaso='$idTraspaso' ";

		return $this->db->query($sql)->row();
	}
	
	public function borrarTraspaso()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$idTraspaso	= $this->input->post('idTraspaso');
		
		$traspaso=$this->obtenerTraspaso($idTraspaso);
		
		if($traspaso!=null)
		{
		
			$this->db->where('idTraspaso',$idTraspaso);
			$this->db->delete('catalogos_traspasos');
			
			$this->db->where('idTraspaso',$idTraspaso);
			$this->db->delete('catalogos_ingresos');
			
			$this->db->where('idTraspaso',$idTraspaso);
			$this->db->delete('catalogos_egresos');
			
			$this->configuracion->registrarBitacora('Borrar traspaso','Contabilidad - Traspasos','Cuenta origen: '.$this->obtenerCuentaDetalle($traspaso->idCuentaOrigen).', Cuenta destino: '.$this->obtenerCuentaDetalle($traspaso->idCuentaDestino).', $'.number_format($traspaso->monto,decimales)); //Registrar bitácora
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
	
	public function registrarTraspaso()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$data=array
		(
			'monto'				=> $this->input->post('monto'),
			'fecha'				=> $this->input->post('fecha'),
			'idCuentaOrigen'	=> $this->input->post('idCuentaOrigen'),
			'idCuentaDestino'	=> $this->input->post('idCuentaDestino'),
			'idLicencia'		=> $this->idLicencia,
		);
		
		$this->db->insert('catalogos_traspasos',$data);
		$idTraspaso	=$this->db->insert_id();
		
		#GENERAR UN EGRESO
		$data=array
		(
			'pago'				=> $this->input->post('monto'),
			#'fecha'				=> $this->_fecha_actual,
			'fecha'				=> $this->input->post('fecha'),
			'idCuenta'			=> $this->input->post('idCuentaOrigen'),
			'formaPago'			=> '',
			'concepto'			=> 'Traspaso',
			'Producto'			=> 'Traspaso',
			'idTraspaso'		=> $idTraspaso,
			'idForma'			=> '1',
			'idLicencia'		=> $this->idLicencia,
            'idUsuario'			=> $this->_user_id,
		);
		
		$this->db->insert('catalogos_egresos',$data);
		
		#GENERAR UN INGRESO
		$data=array
		(
			'pago'				=> $this->input->post('monto'),
			#'fecha'				=> $this->_fecha_actual,
			'fecha'				=> $this->input->post('fecha'),
			'idCuenta'			=> $this->input->post('idCuentaDestino'),
			'formaPago'			=> '',
			'concepto'			=> 'Traspaso',
			'Producto'			=> 'Traspaso',
			'idTraspaso'		=> $idTraspaso,
			'idForma'			=> '1',
			'idLicencia'		=> $this->idLicencia,
			'idUsuario'			=> $this->_user_id,
		);
		
		$this->db->insert('catalogos_ingresos',$data);
		
		$this->configuracion->registrarBitacora('Registrar traspaso','Contabilidad - Traspasos','Cuenta origen: '.$this->obtenerCuentaDetalle($this->input->post('idCuentaOrigen')).', Cuenta destino: '.$this->obtenerCuentaDetalle($this->input->post('idCuentaDestino')).', $'.number_format($this->input->post('monto'),decimales)); //Registrar bitácora
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	public function obtenerCuentaDetalle($idCuenta)
	{
		$sql="select cuenta
		from cuentas
		where idCuenta='$idCuenta'";
		
		$cuenta=$this->db->query($sql)->row();
		
		return $cuenta!=null?$cuenta->cuenta:'';
	}
	
	#REPORTE BANCOS
	#-----------------------------------------------------------------------------------------------------------------
	
	
	public function contarReporteBancos()
	{
		$sql="select idTraspaso
		from catalogos_traspasos";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerReporteBancosIngresos()#$numero,$limite)
	{
		$idCuenta		=$this->input->post('idCuenta');
		$fechaInicio	=$this->input->post('fechaInicio');
		$fechaFin		=$this->input->post('fechaFin');
		
		$sql="select a.*, b.cuenta
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":"";
		
		$sql.=strlen($fechaInicio)>0?" and a.fecha between '".$fechaInicio."' and '".$fechaFin."'":"";
		
		$sql.=" order by fecha asc";
		#$sql .= " limit $limite,$numero ";
		
		#$i=0;
		#$reporte=array();
		
		/*foreach($this->db->query($sql)->result() as $row)
		{
			$reporte[$i]['fecha']		=$row->fecha;
			$reporte[$i]['concepto']	=$row->concepto;
			$reporte[$i]['pago']		=$row->pago;
			$reporte[$i]['receptor']	=$row->nombreReceptor;
			$reporte[$i]['formaPago']	=$row->formaPago;
			
			$i++;
		}
		
		return $reporte;*/
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerReporteBancos()#$numero,$limite)
	{
		$idCuenta		=$this->input->post('idCuenta');
		$fechaInicio	=$this->input->post('fechaInicio');
		$fechaFin		=$this->input->post('fechaFin');
		
		$sql="select a.*, b.cuenta
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":"";
		
		$sql.=strlen($fechaInicio)>0?" and a.fecha between '".$fechaInicio."' and '".$fechaFin."'":"";
		
		$sql.=" order by fecha asc";
		#$sql .= " limit $limite,$numero ";
		
		$i=0;
		$reporte=array();
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$reporte[$i]['fecha']		=$row->fecha;
			$reporte[$i]['concepto']	=$row->producto;
			$reporte[$i]['pago']		=$row->pago;
			$reporte[$i]['receptor']	=$row->nombreReceptor;
			$reporte[$i]['formaPago']	=$row->formaPago;
			
			$i++;
		}
		
		return $reporte;
		
		#return $this->db->query($sql)->result();
	}
	
	public function obtenerIngresosFecha($idCuenta,$fecha)#$numero,$limite)
	{
		#$idCuenta		=$this->input->post('idCuenta');
		#$fechaInicio	=$this->input->post('fechaInicio');
		#$fechaFin		=$this->input->post('fechaFin');
		
		$sql="select a.*, b.cuenta
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":"";
		
		$sql.=" and date(a.fecha)='".$fecha."'";
		#$sql.=strlen($fechaInicio)>0?" and a.fecha between '".$fechaInicio."' and '".$fechaFin."'":"";
		
		$sql.=" order by fecha asc";
		#$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	#CAJA CHICA
	#-----------------------------------------------------------------------------------------------------------------
	public function contarCajaChica()
	{
		$sql="select idEgreso
		from catalogos_egresos
		where idGasto='0' 
		and idCuenta='1'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCajaChica($idEgreso)
	{
		$sql="select * from catalogos_caja
		where idEgreso='$idEgreso' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetalleCajaChica($idCaja)
	{
		$sql="select * from catalogos_caja
		where idCaja='$idCaja' ";

		return $this->db->query($sql)->row();
	}
	
	public function registrarCajaChica()
	{
		#$incluyeIva=$this->input->post('incluyeIva')==false?0:1;
		#$incluyeIva=$this->input->post('incluyeIva')!='false'?1:0;
		
		$data=array
		(
			'importe'			=> $this->input->post('importe'),
			'fecha'				=> $this->_fecha_actual,
			'concepto'			=> $this->input->post('concepto'),
			'idEgreso'			=> $this->input->post('idEgreso'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_caja',$data);
		
		$this->configuracion->registrarBitacora('Registrar concepto caja chica','Contabilidad - Egresos - Caja chica',$data['concepto'].', $'.number_format($data['importe'],decimales)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	#EDITAR EGRESO
	public function editarCajaChica()
	{
		#$incluyeIva=$this->input->post('incluyeIva')!='false'?1:0;
		
		#echo $incluyeIva.'->';
		
		$data=array
		(
			'importe'			=>$this->input->post('importe'),
			'concepto'			=>$this->input->post('concepto'),
		);
		
		$this->db->where('idCaja',$this->input->post('idCaja'));
		$this->db->update('catalogos_caja',$data);
		
		$this->configuracion->registrarBitacora('Editar concepto caja chica','Contabilidad - Egresos - Caja chica',$data['concepto'].', $'.number_format($data['importe'],decimales)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	
	#BORRAR CAJA CHICA
	#-----------------------------------------------------------------------------------------------------------------
	public function borrarCajaChica($idCaja)
	{
		$caja=$this->obtenerDetalleCajaChica($idCaja);
		
		$this->db->where('idCaja',$idCaja);
		$this->db->delete('catalogos_caja',$data);
		
		$this->configuracion->registrarBitacora('Borrar concepto caja chica','Contabilidad - Egresos - Caja chica',$caja->concepto.', $'.number_format($caja->importe,decimales)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function obtenerRegistroCajaChica($idCaja)
	{
		$sql="select * from catalogos_caja
		where idCaja='$idCaja' ";
		
		
		return $this->db->query($sql)->row();
	}
	
	
	//OBTENER EL SALDO DE MES
	public function obtenerUltimoDia($fecha)
	{
		$sql="select day(last_day('$fecha')) as ultimo";
		
		return $this->db->query($sql)->row()->ultimo;
	}
	
	public function obtenerSaldoInicialCuenta($idCuenta)
	{
		$sql="select saldoInicial
		from cuentas
		where idCuenta='$idCuenta'";
		
		return $this->db->query($sql)->row()->saldoInicial;
	}
	
	public function obtenerIngresosMes($idCuenta,$mes,$anio)
	{
		$sql="select sum(pago) as ingreso
		from catalogos_ingresos
		where date(fecha)<'$anio-$mes-01'
		and formaPago!='Programado' 
		and idLicencia='$this->idLicencia' ";
		
		/*(month(fecha)<'$mes'
		and year(fecha)<='$anio')*/
		#where idCuenta='$idCuenta'
		$sql.=$idCuenta>0?" and idCuenta='$idCuenta'":'';
		
		$ingreso=$this->db->query($sql)->row()->ingreso;
		
		#echo $sql.'<br />';
		return $ingreso!=null?$ingreso:0;
	}
	
	public function obtenerEgresosMes($idCuenta,$mes,$anio)
	{
		$sql="select sum(pago) as egreso
		from catalogos_egresos
		where date(fecha)<'$anio-$mes-01'
		and formaPago!='Programado' 
		and idLicencia='$this->idLicencia' ";
		/*(month(fecha)<'$mes'
		and year(fecha)<='$anio')*/
		#where idCuenta='$idCuenta'
		$sql.=$idCuenta>0?" and idCuenta='$idCuenta'":'';
		
		$egreso=$this->db->query($sql)->row()->egreso;
		
		return $egreso!=null?$egreso:0;
	}
	
	public function obtenerEgresosDia($idCuenta,$fecha)
	{
		$sql="select a.*, b.cuenta,
		c.nombre as formaPago
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		
		and a.idForma!='4'
		and a.idLicencia='$this->idLicencia'   ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":"";
		
		$sql.=strlen($fecha)>0?" and date(a.fecha)='$fecha' ":'';
		
		$sql.=" order by date(a.fecha) asc, 
		time(a.fecha) asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerIngresosDia($idCuenta,$fecha)
	{
		$sql="select a.*, b.cuenta,
		c.nombre as formaPago
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		
		and a.idForma!='4'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":"";
		
		$sql.=strlen($fecha)>0?" and date(a.fecha)='$fecha' ":'';
		
		$sql.=" order by date(a.fecha) asc, 
		time(a.fecha) asc";
		
		return $this->db->query($sql)->result();
	}
	
	
	public function obtenerMovimientosMes($idCuenta,$mes,$anio,$orden)
	{
		$sql=" ( select distinct a.idIngreso as id, a.fecha as fecha, a.producto, a.nombreReceptor, a.formaPago, a.pago, b.cuenta,
		c.nombre as formaPago, 'ingreso' as movimiento
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		
		and a.idForma!='4'
		and a.idLicencia='$this->idLicencia'
		and month(a.fecha)='$mes'
		and year(a.fecha)='$anio' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":"";
		$sql.=" ) ";
		
		$sql.=" union ";
		
		$sql.=" ( select distinct a.idEgreso as id, a.fecha as fecha, a.producto, a.nombreReceptor, a.formaPago, a.pago, b.cuenta,
		c.nombre as formaPago, 'egreso' as movimiento
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		
		and a.idForma!='4'
		and a.idLicencia='$this->idLicencia'
		and month(a.fecha)='$mes'
		and year(a.fecha)='$anio' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":"";
		$sql.=" ) ";
		
		
		$sql.=" order by fecha ".$orden;
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	#PARA EL AREA DE RECURSOS HUMANOS
	#----------------------------------------------------------------------------------------------------------------------#
	public function obtenerPersonalActivo()
	{
		$sql=" select count(idPersonal) as personal
		from recursos_personal
		where idLicencia='$this->idLicencia'
		and  idEstatus!=1";

		return $this->db->query($sql)->row()->personal;
	}
	
	public function obtenerPersonalInactivo()
	{
		$sql=" select count(idPersonal) as personal
		from recursos_personal
		where idLicencia='$this->idLicencia'
		and  idEstatus=1";

		return $this->db->query($sql)->row()->personal;
	}
	
	//TIPOS DE DOCUMENTOS
	public function obtenerNumeroDocumentos($idTipo)
	{    
		$sql=" select count(idDocumento) from `recursos_personal_documentos`
		where idTipo='$idTipo'
		GROUP BY idTipo, idPersonal";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPersonalRegistro($idPuesto=0)
	{
		$sql=" select idPersonal, nombre 
		from recursos_personal
		where idLicencia='$this->idLicencia' ";
		
		$sql.=$idPuesto!=0?" and idPuesto='$idPuesto'":'';
		
		$sql.="order by nombre asc ";

		return $this->db->query($sql)->result();
	}
	
	public function contarPersonal($idPersonal)
	{
		$sql="select idPersonal
		from recursos_personal
		where idLicencia='$this->idLicencia'
		and estatus='1'  ";
		
		$sql.=$idPersonal!=0?" and idPersonal='$idPersonal'":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPersonal($idPersonal,$numero,$limite)
	{
		$sql=" select a.*, 
		(select b.nombre from puestos as b where b.idPuesto=a.idPuesto) as puesto,
		(select b.nombre from catalogos_departamentos as b where b.idDepartamento=a.idDepartamento) as departamento,
		(select b.nombre from recursos_personal_estatus as b where b.idEstatus=a.idEstatus) as estatus
		from recursos_personal as a
		where a.idLicencia='$this->idLicencia'
		and a.estatus='1' ";
		
		$sql.=$idPersonal!=0?" and a.idPersonal='$idPersonal'":'';
		
		$sql .= " order by nombre asc 
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistroPersonal($idPersonal)
	{
		$sql=" select a.*, 
		(select b.nombre from puestos as b where b.idPuesto=a.idPuesto) as puesto,
		(select b.nombre from catalogos_departamentos as b where b.idDepartamento=a.idPuesto) as departamento
		from recursos_personal as a 
		where a.idPersonal='$idPersonal'";
		
		return $this->db->query($sql)->row();
	}

	public function obtenerDepartamentosPersonal()
	{
		$sql="select * from catalogos_departamentos
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function agregarDepartamento()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'idUsuario'		=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_departamentos',$data);
		
		$this->configuracion->registrarBitacora('Registrar departamento','Contabilidad - Recursos humanos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function obtenerPuestos()
	{
		$sql="select * from puestos
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function agregarPuesto()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'idUsuario'		=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('puestos',$data);
		
		$this->configuracion->registrarBitacora('Registrar puesto','Contabilidad - Recursos humanos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows() >=1?"1":"0";
	}
	
	//ESTATUS EMPLEADOS
	public function obtenerEstatus()
	{
		$sql="select * from recursos_personal_estatus
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarEstatus()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('recursos_personal_estatus',$data);
		
		$this->configuracion->registrarBitacora('Registrar estatus','Contabilidad - Recursos humanos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows() >=1?"1":"0";
	}
	
	public function pagarNomina()
	{
		$data=array
		(
			'pago'				=>$this->input->post('pago'),
			'fecha'				=>$this->input->post('fecha'),
			'formaPago'			=>$this->input->post('formaPago'),
			'idCuenta'			=>$this->input->post('idCuenta'),
			'transferencia'		=>$this->input->post('transferencia'),
			'cheque'			=>$this->input->post('cheque'),
			'idDepartamento'	=>$this->input->post('idDepartamento'),
			'idNombre'			=>$this->input->post('idNombre'),
			'producto'			=>$this->input->post('producto'),
			'idProducto'		=>$this->input->post('idProducto'),
			'idGasto'			=>$this->input->post('idGasto'),
			'iva'				=>$this->session->userdata('iva'),
			'nombreReceptor'	=>$this->input->post('nombreReceptor'),
			'incluyeIva'		=>$this->input->post('incluyeIva'),
			'cajaChica'			=>0,
			'idPersonal'		=>$this->input->post('idPersonal'),
			'inicio'			=>$this->input->post('inicio'),
			'fin'				=>$this->input->post('fin'),
			'dias'				=>$this->input->post('dias'),
			'comentarios'		=>$this->input->post('comentarios'),
            'idUsuario'			=> $this->_user_id,
			
		);
		
		$this->db->insert('catalogos_egresos',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	
	#REPORTE DE FLUJO DE EFECTIVO
	#--------------------------------------------------------------------------------------------------------------#
	
	#ENTRADAS 
	public function obtenerEntradaProductos($mes,$anio)
	{
		$sql="select sum(a.pago) as pago, 
		b.nombre as producto, b.idProducto
		from catalogos_ingresos as a
		inner join catalogos_productos as b
		on a.idProducto=b.idProducto 
		where (month(a.fecha)='$mes'
		and year(a.fecha)='$anio' )
		and a.idTraspaso=0 
		and a.idLicencia='$this->idLicencia' ";

		$sql.=" group by b.idProducto ";
		
		return $this->db->query($sql)->result();
	}
	
	#SALIDAS
	public function obtenerSalidasProductos($mes,$anio)
	{
		$sql="select b.idDepartamento, 
		sum(a.pago) as pago, 
		b.nombre as departamento, a.fecha
		from catalogos_departamentos as b
		inner join catalogos_egresos as a
		on a.idDepartamento=b.idDepartamento 
		where a.idTraspaso=0 
		and (month(a.fecha)='$mes'
		and year(a.fecha)='$anio')
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" group by a.idDepartamento  ";

		return $this->db->query($sql)->result();
	}
	
	#REPORTE DE FLUJO DE CAJA CHICA
	public function obtenerCajasChicas()
	{
		$sql="select b.idProducto, b.nombre as cajaChica
		FROM catalogos_egresos as a
		INNER JOIN catalogos_productos AS b
		ON a.idProducto=b.idProducto
		WHERE cajaChica=1
		and a.idLicencia='$this->idLicencia'
		GROUP BY a.idProducto ";

		$sql .= " order by b.nombre desc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerEntradasCaja($idProducto,$mes,$anio)
	{
		$sql="select coalesce(sum(a.pago),0) as pago, 
		b.idProducto, b.nombre as producto
		from catalogos_egresos as a
		inner join catalogos_productos as b
		on a.idProducto=b.idProducto
		where a.cajaChica=1 
		and b.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and (month(a.fecha)='$mes' 
		and  year(a.fecha)<='$anio') ";
		
		#echo $sql.'<br />';
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerSalidasCaja($mes,$anio)#,$idProducto)
	{
		$sql="select a.*, b.fecha
		from catalogos_caja as a
		inner join catalogos_egresos as b
		on a.idEgreso=b.idEgreso
		where b.idLicencia='$this->idLicencia' ";
		
		$sql.=" and month(b.fecha)='$mes'
		and year(b.fecha)='$anio' ";
		
		#$sql.=" group by b.idDepartamento ";
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSalidaCaja($mes,$anio,$idProducto)
	{
		
		$sql="select coalesce(sum(a.importe),0) as pago
		from catalogos_caja as a
		inner join catalogos_egresos as b
		on a.idEgreso=b.idEgreso
		where b.idProducto='$idProducto'
		and b.idLicencia='$this->idLicencia' ";
		
		$sql.=" and month(b.fecha)<='$mes'
		and year(b.fecha)<='$anio' ";
		
		#$sql.=" group by b.idDepartamento ";
		
		#echo $sql;
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerSaldoCaja($mes,$anio,$idProducto)
	{
		$sql="select sum(a.pago) as pago, b.idProducto, 
		b.nombre as producto 
		from catalogos_egresos as a 
		inner join catalogos_productos as b 
		on b.idProducto=a.idProducto 
		and month(a.fecha)='$mes' 
		and year(a.fecha)='$anio' 
		where b.idProducto='$idProducto' 
		and a.idLicencia='$this->idLicencia'";

		return $this->db->query($sql)->row()->pago;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//HORARIOS DE PERSONAL
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerHorarios($idPersonal)
	{
		$sql= " select * from recursos_personal_horarios
		where idPersonal='$idPersonal' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarHorario()
	{
		$data =array
		(
			'horaInicial'			=> trim($this->input->post('txtHoraInicial')),
			'horaFinal'				=> trim($this->input->post('txtHoraFinal')),
			'idPersonal'			=> $this->input->post('txtIdPersonal'),
			
			'lunes'					=> $this->input->post('chkLunes')=='1'?'1':'0',
			'martes'				=> $this->input->post('chkMartes')=='1'?'1':'0',
			'miercoles'				=> $this->input->post('chkMiercoles')=='1'?'1':'0',
			'jueves'				=> $this->input->post('chkJueves')=='1'?'1':'0',
			'viernes'				=> $this->input->post('chkViernes')=='1'?'1':'0',
			'sabado'				=> $this->input->post('chkSabado')=='1'?'1':'0',
			'domingo'				=> $this->input->post('chkDomingo')=='1'?'1':'0',
		);
		
		$this->db->insert('recursos_personal_horarios',$data);

		return $this->db->affected_rows()>=1?array('1','El registro ha sido exitoso'):array('0','Error en el registro'); 
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
		$this->db->update('recursos_personal_horarios',$data);

		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function borrarHorario($idHorario)
	{
		$this->db->where('idHorario',$idHorario);
		$this->db->delete('recursos_personal_horarios');

		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//CHECADOR
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerRegistroPersonalChequeo($numeroEmpleado)
	{
		$sql=" select a.*, b.nombre as departamento,
		c.nombre as puesto
		from recursos_personal as a
		inner join catalogos_departamentos as b
		on a.idDepartamento=b.idDepartamento
		inner join puestos as c
		on a.idPuesto=c.idPuesto
		where a.numeroAcceso='$numeroEmpleado'
		and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerHorarioDia($idPersonal)
	{
		$dia	= obtenerDiaActual(date('Y-m-d'));
		
		$sql=" select ".$dia.", horaInicial, horaFinal
		from recursos_personal_horarios
		where idPersonal='$idPersonal'
		and ".$dia."='1' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerAsistenciasHoy()
	{
		$sql="select a.*, b.*
		from recursos_personal as a
		inner join recursos_personal_chequeo as b
		on a.idPersonal=b.idPersonal
		where b.fecha='".$this->obtenerFechaCriterio('curdate()')."'
		and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerAsistenciaPersonal($idPersonal)
	{
		$sql = "select * from recursos_personal_chequeo
		where idPersonal='$idPersonal'";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function obtenerDiferenciaEntrada1($idPersonal,$horaActual)
	{
		$personal	= $this->obtenerHorarioDia($idPersonal);
		$sql		= "select timediff('".$personal->horaInicial."', '".$horaActual."') as diferenciaEntrada";
		
		#echo $sql;
		$diferencia	= $this->db->query($sql)->row()->diferenciaEntrada;
		
		$sql		= "select hour('".$diferencia."') as horas";
		$horas		= $this->db->query($sql)->row()->horas;
		$horas		= $horas>0?$horas*60:0;
		
		$sql		= "select minute('".$diferencia."') as minutos";
		$minutos	= $this->db->query($sql)->row()->minutos;
		
		$sql		= "select second('".$diferencia."') as segundos";
		$segundos	= $this->db->query($sql)->row()->segundos;
		
		$total		 =+$horas+$minutos+($segundos/60);
		
		#echo $diferencia;
		if($diferencia[0]=='-')
		{
			$total=$total*(-1);
		}
		
		return $total;
	}
	
	public function obtenerDiferenciaSalida1($idPersonal,$horaActual)
	{
		$personal=$this->obtenerHorarioDia($idPersonal);
		
		$sql="select timediff('".$personal->horaFinal."', '".$horaActual."') as diferenciaSalida";
		
		$diferencia	=$this->db->query($sql)->row()->diferenciaSalida;
		
		$sql		="select hour('".$diferencia."') as horas";
		$horas		=$this->db->query($sql)->row()->horas;
		$horas		=$horas>0?$horas*60:0; //NUMERO DE MINUTOS POR LAS HORAS
		
		$sql		="select minute('".$diferencia."') as minutos";
		$minutos	=$this->db->query($sql)->row()->minutos;
		
		$sql		="select second('".$diferencia."') as segundos";
		$segundos	=$this->db->query($sql)->row()->segundos;
		
		$total		=$horas+$minutos+($segundos/60);
		
		#echo 'Diferencia: '.$diferencia[0].'<br />';
		
		if($diferencia[0]!='-')
		{
			$total=$total*(-1);
		}
		
		return $total;
	}
	
	public function obtenerDiferenciaEntrada($idPersonal,$horaActual)
	{
		$personal	= $this->obtenerHorarioDia($idPersonal);
		$sql		= "select timestampdiff(second, '".$this->_fecha_actual."', '".$this->fecha.' '.$personal->horaInicial."') as diferenciaEntrada";
		
		#echo $sql;
		$segundos		= $this->db->query($sql)->row()->diferenciaEntrada;
		$operacion		= $segundos/60;		
		$minutos		= intval($operacion);
		
		$decimales		= explode('.',$operacion);
		$decimal		= isset($decimales[1])?$decimales[1]:0;
		$segundosTotal	= 0;
		
		if($decimal!=0)
		{
			$segundosTotal	= ($segundos<0?$segundos*-1:$segundos)-(($minutos<0?$minutos*-1:$minutos)*60);
			$segundosTotal	= $segundosTotal<10?$segundosTotal/10:$segundosTotal;
		}

		#$total		 = ($operacion<0?'-':''). $minutos.'.'.(str_replace('.','',$segundosTotal));
		$total		 = ($operacion<0?'-':''). ($minutos<0?$minutos*-1:$minutos).'.'.(str_replace('.','',$segundosTotal));

		return $total;
	}

	
	
	public function obtenerDiferenciaSalida($idPersonal,$horaActual)
	{
		$personal=$this->obtenerHorarioDia($idPersonal);
		
		$sql		= "select timestampdiff(second, '".$this->_fecha_actual."', '".$this->fecha.' '.$personal->horaFinal."') as diferenciaSalida";

		$segundos		= $this->db->query($sql)->row()->diferenciaSalida;
		$operacion		= $segundos/60;		
		$minutos		= intval($operacion);
		
		$decimales		= explode('.',$operacion);
		$decimal		= isset($decimales[1])?$decimales[1]:0;
		$segundosTotal	= 0;

		if($decimal!=0)
		{
			$segundosTotal= ($segundos<0?$segundos*-1:$segundos)-(($minutos<0?$minutos*-1:$minutos)*60);
			$segundosTotal	= $segundosTotal<10?$segundosTotal/10:$segundosTotal;
		}

		#$total		 = ($operacion<0?'-':''). $minutos.'.'.(str_replace('.','',$segundosTotal));
		$total		 = ($operacion<0?'-':''). ($minutos<0?$minutos*-1:$minutos).'.'.(str_replace('.','',$segundosTotal));
		
		return $total;
	}
	
	public function obtenerFechaCriterio($criterio)
	{
		$sql="select ".$criterio." as fecha ";
		
		return $this->db->query($sql)->row()->fecha;
	}
	
	public function registrarChequeo()
	{
		$this->load->helper('datosgenerales');

		$idPersonal		= $this->input->post('idPersonal');
		$chequeo		= $this->obtenerRegistroChequeo($idPersonal);
		$fechaActual	= $this->obtenerFechaCriterio('curdate()');
		$horaActual		= $this->obtenerFechaCriterio('curtime()');
			
		if($chequeo==null)
		{
			#$tolerancia		= $configuracion->toleranciaRetardos*(-1);
			$tolerancia		= 0;
			$retardo		= $this->obtenerDiferenciaEntrada($idPersonal,$horaActual);
			$dia 			= date( "l", strtotime ($this->fecha)); //Obtener el dia actual
			$dia			= obtenerDiaNombre($dia);
			
			if($retardo>=$tolerancia and $retardo<=0)
			{
				$retardo=0;
			}
			
			$data=array
			(
				'idPersonal'		=> $idPersonal,
				'horaEntrada'		=> $this->hora,
				'dia'				=> $dia,
				'fecha'				=> $this->fecha,
				'retardoMinutos'	=> $retardo,
				'idPeriodo'			=> 0,
			);
		
			$this->db->insert('recursos_personal_chequeo',$data);
		}
		else
		{
			$salida	= $this->obtenerDiferenciaSalida($idPersonal,$horaActual);
			
			$data=array
			(
				'horaSalida'		=>  $this->hora,
				'salidaMinutos'		=> $salida,
			);
			
			$this->db->where('idChequeo',$chequeo->idChequeo);
			$this->db->update('recursos_personal_chequeo',$data);
		}
		
		return(($this->db->affected_rows() >= 1)? "1" : "0");
	}
	
	public function obtenerRegistroChequeo($idPersonal)
	{
		$fecha		= $this->obtenerFechaCriterio('curdate()');#date('Y-m-d');
		
		$sql = "select * from recursos_personal_chequeo
		where idPersonal='$idPersonal'
		and fecha='$fecha' ";

		return $this->db->query($sql)->row();
	}
	
	//PERSONAL
	public function agregarPersonal()
	{
		$this->db->trans_start();
		
		$data=array
		(
			'idPuesto'			=> $this->input->post('selectPuestos'),
			'idDepartamento'	=> $this->input->post('selectDepartamentos'),
			'idUsuario'			=> $this->_user_id,
			'idLicencia'		=> $this->idLicencia,
			'fecha'				=> $this->_fecha_actual,
			'fechaIngreso'		=> $this->input->post('txtFechaIngreso'),
			'nombre'			=> $this->input->post('txtNombre'),
			'salario'			=> $this->input->post('txtSalario'),
			'calle'				=> $this->input->post('txtCalle'),
			'numero'			=> $this->input->post('txtNumero'),
			'colonia'			=> $this->input->post('txtColonia'),
			'localidad'			=> $this->input->post('txtLocalidad'),
			'municipio'			=> $this->input->post('txtMunicipio'),
			'estado'			=> $this->input->post('txtEstado'),
			'pais'				=> $this->input->post('txtPais'),
			'codigoPostal'		=> $this->input->post('txtCodigoPostal'),
			'telefono'			=> $this->input->post('txtTelefono'),
			'celular'			=> $this->input->post('txtCelular'),
			'imss'				=> $this->input->post('txtImss'),
			'curp'				=> $this->input->post('txtCurp'),
			'comentarios'		=> $this->input->post('txtComentarios'),
			'numeroAcceso'		=> $this->input->post('txtNumeroAcceso'),
			'email'				=> $this->input->post('txtEmail'),
			'email2'			=> $this->input->post('txtEmail2'),
			
			'idEstatus'			=> $this->input->post('selectEstatus'),
			
			'contactoParentesto'		=> $this->input->post('txtContactoParentesco'),
			'contactoDireccion'			=> $this->input->post('txtContactoDireccion'),
			'contactoTelefono'			=> $this->input->post('txtContactoTelefono'),
		);
		
		$fotografia =$_FILES['txtFotografia']['name'];
		
		if(strlen($fotografia)>1)
		{
			$data['fotografia']=$fotografia;
		}
		
		$data	= procesarArreglo($data);
		$this->db->insert('recursos_personal',$data);
		$idPersonal=$this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar personal','Contabilidad - Recursos humanos',$data['nombre']); //Registrar bitácora
		
		#-------------------------------------------------------------------------------#
		
		if(strlen($fotografia)>1)
		{
			$directorio  	= "img/personal/";
			$archivo 		= $directorio . basename($idPersonal."_".$fotografia);
			
			move_uploaded_file($_FILES['txtFotografia']['tmp_name'], $archivo);
		}
		#-------------------------------------------------------------------------------#
		
		$this->registrarDocumentosPersonal($idPersonal);
		
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
	
	public function editarPersonal()
	{
		$idPersonal		=$this->input->post('txtIdPersonal');
		
		$data=array
		(
			'idPuesto'			=>$this->input->post('selectPuestos'),
			'idDepartamento'	=>$this->input->post('selectDepartamentos'),
			'fechaIngreso'		=>$this->input->post('txtFechaIngreso'),
			'nombre'			=>$this->input->post('txtNombre'),
			'salario'			=>$this->input->post('txtSalario'),
			'calle'				=>$this->input->post('txtCalle'),
			'numero'			=>$this->input->post('txtNumero'),
			'colonia'			=>$this->input->post('txtColonia'),
			'localidad'			=>$this->input->post('txtLocalidad'),
			'municipio'			=>$this->input->post('txtMunicipio'),
			'estado'			=>$this->input->post('txtEstado'),
			'pais'				=>$this->input->post('txtPais'),
			'codigoPostal'		=>$this->input->post('txtCodigoPostal'),
			'telefono'			=>$this->input->post('txtTelefono'),
			'celular'			=>$this->input->post('txtCelular'),
			'imss'				=>$this->input->post('txtImss'),
			'curp'				=>$this->input->post('txtCurp'),
			'comentarios'		=>$this->input->post('txtComentarios'),
			'idUsuarioEdicion'	=>$this->_user_id,
			'numeroAcceso'		=>$this->input->post('txtNumeroAcceso'),
			'email'				=> $this->input->post('txtEmail'),
			'email2'			=> $this->input->post('txtEmail2'),
			'idEstatus'			=> $this->input->post('selectEstatus'),
			
			
			'contactoParentesto'		=> $this->input->post('txtContactoParentesco'),
			'contactoDireccion'			=> $this->input->post('txtContactoDireccion'),
			'contactoTelefono'			=> $this->input->post('txtContactoTelefono'),
		);
		
		$fotografia =$_FILES['txtFotografia']['name'];
		
		if(strlen($fotografia)>1)
		{
			$data['fotografia']=$fotografia;
		}
		
		$data	= procesarArreglo($data);
		$this->db->where('idPersonal',$idPersonal);
		$this->db->update('recursos_personal',$data);
		
		$this->configuracion->registrarBitacora('Editar personal','Contabilidad - Recursos humanos',$data['nombre']); //Registrar bitácora
		#-------------------------------------------------------------------------------#
		
		if(strlen($fotografia)>1)
		{
			$directorio  	= "img/personal/";
			$archivo 		= $directorio . basename($idPersonal."_".$fotografia);
			
			move_uploaded_file($_FILES['txtFotografia']['tmp_name'], $archivo);
		}
		#-------------------------------------------------------------------------------#
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function borrarPersonal($idPersonal)
	{
		/*$this->db->where('idPersonal',$idPersonal);
		$this->db->delete('recursos_personal');*/
		
		$this->db->where('idPersonal',$idPersonal);
		$this->db->update('recursos_personal',array('estatus'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	//ARCHIVOS DEL PERSONAL
	
	public function obtenerDocumentosPersonal($idPersonal)
	{
		$sql="select * from recursos_personal_documentos
		where idPersonal='$idPersonal'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDocumentoPersonal($idDocumento)
	{
		$sql="select * from recursos_personal_documentos
		where idDocumento='$idDocumento'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerDocumentosTipoPersonal($idTipo,$idPersonal)
	{
		$sql="select * from recursos_personal_documentos
		where idTipo='$idTipo'
		and idPersonal='$idPersonal' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarDocumentosPersonal($idPersonal)
	{
		$idPersonalDocumentos	= $this->input->post('txtIdPersonalDocumentos');
		$documentos				= $this->obtenerDocumentosTemporal($idPersonalDocumentos);
		
		foreach($documentos as $row)
		{
			$data =array
			(
				'idTipo'			=> $row->idTipo,
				'nombre'			=> $row->nombre,
				'tamano'			=> $row->tamano,
				'fecha'				=> $this->_fecha_actual,
				'id'				=> $row->id,
				'idPersonal'		=> $idPersonal,
			);
			
			$this->db->insert('recursos_personal_documentos',$data);
		}
		
		$this->borrarDocumentosTemporal($idPersonalDocumentos);
	}
	
	public function borrarDocumentosTemporal($idPersonal)
	{
		$this->db->where('idPersonal',$idPersonal);
		$this->db->delete('recursos_personal_documentos_temporal');
	}
	
	public function registrarDocumentoTemporal($idTipo,$nombre,$tamano,$id,$idPersonal,$temporal)
	{
		$data =array
		(
			'idTipo'			=> $idTipo,
			'nombre'			=> $nombre,
			'tamano'			=> $tamano,
			'fecha'				=> $this->_fecha_actual,
			'id'				=> $id,
			'idPersonal'		=> $idPersonal,
		);
		
		if($temporal==1)
		{
			$this->db->insert('recursos_personal_documentos_temporal',$data);
		}
		else
		{
			$this->db->insert('recursos_personal_documentos',$data);
		}

		$idDocumento=$this->db->insert_id();

		return $this->db->affected_rows()>=1?$idDocumento:0;
	}
	
	public function obtenerDocumentosTemporal($idPersonal)
	{
		$sql="select * from recursos_personal_documentos_temporal
		where idPersonal='$idPersonal'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDocumentoTemporal($idDocumento)
	{
		$sql="select * from recursos_personal_documentos_temporal
		where idDocumento='$idDocumento'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarDocumentoTemporal($idDocumento,$temporal=1)
	{
		$documento	= $temporal==1?$this->obtenerDocumentoTemporal($idDocumento):$this->obtenerDocumentoPersonal($idDocumento);
		
		$this->db->where('idDocumento',$idDocumento);
		
		if($temporal==1)
		{
			$this->db->delete('recursos_personal_documentos_temporal');
		}
		else
		{
			$this->db->delete('recursos_personal_documentos');
		}
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar documento','Administración - Recursos humanos',$documento->nombre); //Registrar bitácora
			
			if(file_exists(carpetaPersonal.$documento->id.'_'.$documento->nombre))
			{
				unlink(carpetaPersonal.$documento->id.'_'.$documento->nombre);
			}
			
			return array("1");
		}
		else
		{
			return array("0");
		}
	}
	
	public function verificarDocumentosTemporal()
	{
		$sql=" select count(idDocumento) as numero from recursos_personal_documentos_temporal
		where date(fecha)<'$this->fecha' ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	//BORRAR DOCUMENTOS TEMPORAL
	public function obtenerDocumentosTemporalFecha()
	{
		$sql="select * from recursos_personal_documentos_temporal
		where date(fecha)<'$this->fecha'";
		
		return $this->db->query($sql)->result();
	}
	
	public function borrarDocumentosTemporales()
	{
		$documentos	= $this->obtenerDocumentosTemporalFecha();
		
		foreach($documentos as $row)
		{
			$this->db->where('idDocumento',$row->idDocumento);
			$this->db->delete('recursos_personal_documentos_temporal');
			
			#$this->configuracion->registrarBitacora('Borrar documento','Administración - Recursos humanos',$documento->nombre); //Registrar bitácora
			
			if(file_exists(carpetaPersonal.$row->id.'_'.$row->nombre))
			{
				unlink(carpetaPersonal.$row->id.'_'.$row->nombre);
			}
		}
	}
	
	//PAGOS DE ALUMNOS
	public function obtenerPagos($numero,$limite,$criterio='',$idPrograma=0,$idCampana=0,$idPromotor=0,$todos=0)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select a.idCliente, concat(a.nombre,' ',a.paterno, ' ', a.materno) as alumno, c.fecha, c.pago, c.producto,
		concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) promotor,
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on d.idPrograma=e.idPrograma where a.idCliente=e.idCliente) as programa

		from clientes as a 
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		inner join catalogos_ingresos as c
		on a.idCliente=c.idCliente
		where a.activo='1'
		and length(a.nombre) > 0  ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		
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
		
		$sql.=" order by a.nombre asc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	#PARA LOS VEHÍCULOS
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerVehiculos()
	{    
		$criterio	=$this->input->post('criterio');
		
		$sql=" select * from recursos_vehiculos
		where activo='1'
		and (modelo like '%$criterio%' or
		marca like '%$criterio%')
		order by modelo asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVehiculo($idVehiculo)
	{    
		$sql=" select * from recursos_vehiculos 
		where idVehiculo='$idVehiculo' ";
		
		return $this->db->query($sql)->row();
	}
	

	public function registrarVehiculo()
	{
		$data=array
		(
			'modelo'	=> $this->input->post('txtModelo'),
			'marca'		=> $this->input->post('txtMarca'),
			'idUsuario'	=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('recursos_vehiculos',$data);
		
		$this->configuracion->registrarBitacora('Registrar vehículo','Administracion - Vehículos',$data['modelo']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function editarVehiculo()
	{
		$data=array
		(
			'modelo'	=> $this->input->post('txtModelo'),
			'marca'		=> $this->input->post('txtMarca'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idVehiculo',$this->input->post('txtIdVehiculo'));
	    $this->db->update('recursos_vehiculos',$data);
		
		$this->configuracion->registrarBitacora('Editar vehículo','Administracion - Vehículos',$data['modelo']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function borrarVehiculo($idVehiculo)
	{
		$registro	= $this->obtenerVehiculo($idVehiculo);
		
	    $this->db->where('idVehiculo',$idVehiculo);
		$this->db->update('recursos_vehiculos',array('activo'=>'0'));
		
		if($registro!=null)
		{
			$this->configuracion->registrarBitacora('Borrar vehículo','Administracion - Vehículos',$registro->modelo); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
}
?>

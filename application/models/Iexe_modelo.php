<?php
class Iexe_modelo extends CI_Model
{
	protected $idLicencia;
	protected $fecha;
	protected $hora;
	protected $horaMedia;
	protected $idRol;
	protected $idUsuario;
	protected $fechaCompleta;

	function __construct()
	{
		parent::__construct();

        $this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');

		$this->resultado		= "1";
		$this->fecha 			= date('Y-m-d');
		$this->fechaCompleta 	= date('Y-m-d H:i:s');
		$this->hora 			= date('H:i:s');
		$this->horaMedia 		= date('H:i:00');
		$this->idRol 			= $this->session->userdata('role');
	}

	public function obtenerClientesActivos()
	{
		$sql=" select a.idCliente
		from clientes as a
		inner join clientes_academicos as b
		on a.idCliente=b.idCliente
		where a.activo='1'
		and a.idZona=1 ";

		return $this->db->query($sql)->result();
	}
	
	public function desactivarClientesActivos()
	{
		$clientes	= $this->obtenerClientesActivos();
		
		foreach($clientes as $row)
		{
			$this->db->where('idCliente',$row->idCliente);
			$this->db->update('clientes',array('idZona'=>2,'fechaBaja'=>$this->fecha,'idCausa'=>12));
		}
	}
	
	public function obtenerClientesMatricula($matricula)
	{
		$sql=" select a.idCliente
		from clientes as a
		inner join clientes_academicos as b
		on a.idCliente=b.idCliente
		where b.matricula='$matricula' ";

		return $this->db->query($sql)->row();
	}
	
	
	
	public function obtenerPeriodoNombre($nombre)
	{
		$sql=" select * from clientes_periodos 
		where nombre='$nombre' 
		and activa='1' ";

		return $this->db->query($sql)->row();
	}
	
	public function registrarPeriodoAlumno($idCliente,$idPeriodo)
	{
		$data=array
		(
			'fecha'				=> $this->fechaCompleta,
			'idCliente' 		=> $idCliente,
			'idPeriodo' 		=> $idPeriodo,
		);
		
		$this->db->insert('clientes_periodos_relacion', $data);
	}
	
	public function regresarAlumnosBajas()
	{
		$sql=" SELECT a.idCliente, a.empresa, a.nombre, a.paterno, a.activo, a.idZona, 
		a.prospecto, b.preinscrito, a.fechaBaja, a.idCausa, b.matricula
		FROM clientes AS a
		INNER JOIN clientes_academicos AS b
		ON a.idCliente=b.idCliente
		WHERE b.preinscrito='1'
		AND a.idZona=2 ";

		$alumnos	= $this->db->query($sql)->result();
		
		foreach($alumnos as $row)
		{
			$this->db->where('idCliente',$row->idCliente);
			$this->db->update('clientes',array('idZona'=>1,'fechaBaja'=>null,'idCausa'=>0,'activo'=>'1'));
		}
	}
	
	public function codigoCarreras()
	{
		$claves	= array('LAE','LDC','LSP','LCP','MFP','MAPP','MEPP','MSPP','MBA','DPP');
		
		return $claves;
	}
	
	public function obtenerrIdCarreras($numero)
	{
		$claves	= array('4','6','13','2','17','5','7','8','18','10');
		
		return $claves[$numero];
	}
	
	public function obtenerAlumnosMatricula($matricula)
	{
		$sql=" SELECT a.idCliente, a.empresa, a.nombre, a.paterno, a.materno, 
		c.matricula 
		FROM clientes AS a 
		INNER JOIN zonas AS b
		ON a.idZona=b.idZona 
		INNER JOIN clientes_academicos AS c
		ON c.idCliente=a.idCliente 
		WHERE a.activo='1'  
		AND LENGTH(c.matricula)>2
		AND  a.idZona='1'  
		AND c.idPrograma=0
		
		and matricula like '$matricula%'";

		return $this->db->query($sql)->result();
	}
	
	public function asignarAlumnoPrograma()
	{
		$carreras	= $this->codigoCarreras();
		
		for($i=0;$i<count($carreras);$i++)
		{
			$alumnos	= $this->obtenerAlumnosMatricula($carreras[$i]);
			
			foreach($alumnos as $row)
			{
				$this->db->where('idCliente',$row->idCliente);
				$this->db->update('clientes_academicos',array('idPrograma'=>$this->obtenerrIdCarreras($i)));
			}
		}
	}
	

	//REVISAR LOS ALUMNOS

	public function obtenerConsultasAlumnos()
	{
		$data=array();
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid		
									inner join mdl_context c on b.contextid=c.id 		
									inner join mdl_user_info_data d on d.userid=a.id		
									inner join mdl_user_info_field e on  e.id=d.fieldid		
									where c.contextlevel = 50 
									and (c.instanceid = 466 or c.instanceid =  467 or c.instanceid =  468 or c.instanceid =  469 or c.instanceid =  470 or c.instanceid =  471 or
									     c.instanceid =  472 or c.instanceid =  473 or c.instanceid =  474 or c.instanceid =  475 or c.instanceid =  476 or c.instanceid =  477 or
										 c.instanceid =  478 or c.instanceid =  479 or c.instanceid =  480)		
									and e.shortname='trimestre' and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva'		
									and b.roleid = 5",
			'base'=>0
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email 
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id 
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid where c.contextlevel = 50 
									and (c.instanceid = 264 or c.instanceid = 263 or c.instanceid = 262 or c.instanceid = 268 or c.instanceid = 267 or c.instanceid = 266 
									or c.instanceid = 271 or c.instanceid = 270 or c.instanceid = 269 or c.instanceid = 274 or c.instanceid = 272 or c.instanceid = 273 
									or c.instanceid = 277 or c.instanceid = 278 or c.instanceid = 275)		
									and e.shortname='trimestre' 
									and d.data <> 'Baja temporal' 
									and d.data <> 'Baja definitiva'		
									and b.roleid = 5",
			'base'=>1
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email 
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid	
									inner join mdl_context c on b.contextid=c.id 
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 272 or c.instanceid = 273 or c.instanceid = 274 or c.instanceid = 275 or c.instanceid = 276 or c.instanceid = 277 or c.instanceid = 280 or 
									     c.instanceid = 279 or c.instanceid = 278 or c.instanceid = 281 or c.instanceid = 282 or c.instanceid = 283 or c.instanceid = 284 or c.instanceid = 285 or 
									     c.instanceid = 286)		
									and e.shortname='trimestre' 
									and d.data <> 'Baja temporal' 
									and d.data <> 'Baja definitiva'	
									and b.roleid = 5",
			'base'=>2
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.lastname, a.firstname, a.email 
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid	
									inner join mdl_context c on b.contextid=c.id 
									inner join mdl_user_info_data d on d.userid=a.id
									inner join mdl_user_info_field e on e.id=d.fieldid
									where c.contextlevel = 50 	
									and (c.instanceid = 51 or c.instanceid = 52 or c.instanceid = 53 or c.instanceid = 54 or c.instanceid = 55 or c.instanceid = 56 or c.instanceid = 60 or c.instanceid = 61 or c.instanceid = 62 or c.instanceid = 67 or c.instanceid = 69 or c.instanceid = 70 or c.instanceid = 71) 
									and e.shortname='cuatrimestre' 
									and d.data <> 'Baja temporal' 
									and d.data <> 'Baja definitiva'
									and b.roleid = 5",
			'base'=>5
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 144 or c.instanceid = 145 or c.instanceid = 146 or c.instanceid = 147 or c.instanceid = 148 or c.instanceid = 149 
									or c.instanceid = 150 or c.instanceid = 151 or c.instanceid = 152 or c.instanceid = 157 or c.instanceid = 158 or c.instanceid = 159 
									or c.instanceid = 161 or c.instanceid = 162 or c.instanceid = 203) 
									and e.shortname='trimestre' 
									and d.data <> 'Baja temporal' 
									and d.data <> 'Baja definitiva' 
									and b.roleid = 5",
			'base'=>3
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email 
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 154 or c.instanceid = 155 or c.instanceid = 156 or c.instanceid = 163 or c.instanceid = 164 or c.instanceid = 165 
									or c.instanceid = 166 or c.instanceid = 167 or c.instanceid = 168 or c.instanceid = 169 or c.instanceid = 170 or c.instanceid = 171 
									or c.instanceid = 200 or c.instanceid = 201 or c.instanceid = 202) 
									and e.shortname='trimestre' 
									and d.data <> 'Baja temporal' 
									and d.data <> 'Baja definitiva' 
									and b.roleid = 5",
			'base'=>3 
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email 
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 305 or c.instanceid = 306 or c.instanceid = 307 or c.instanceid = 308 or c.instanceid = 311 or c.instanceid = 310 or c.instanceid = 312 
									or c.instanceid = 309 or c.instanceid = 313 or c.instanceid = 317 or c.instanceid = 314 or c.instanceid = 315 or c.instanceid = 273 or c.instanceid = 272 
									or c.instanceid = 271 or c.instanceid = 270) 
									and e.shortname='cuatrimestre' 
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email 
									from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 330 or c.instanceid = 327 or c.instanceid = 328 or c.instanceid = 329 or c.instanceid = 335 or c.instanceid = 331 or c.instanceid = 333 
									or c.instanceid = 334 or c.instanceid = 336 or c.instanceid = 337 or c.instanceid = 338 or c.instanceid = 339 or c.instanceid = 278 or c.instanceid = 276 
									or c.instanceid = 275 or c.instanceid = 274) 
									and e.shortname='cuatrimestre' 
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email 
			from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 356 or c.instanceid = 355 or c.instanceid = 354 or c.instanceid = 353 or c.instanceid = 369 or c.instanceid = 368 or c.instanceid = 367 
									or c.instanceid = 366 or c.instanceid = 373 or c.instanceid = 372 or c.instanceid = 371 or c.instanceid = 370 or c.instanceid = 279 or c.instanceid = 280 or c.instanceid = 281 
									or c.instanceid = 282) 
									and e.shortname='cuatrimestre' 
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, d.data, a.email 
			from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid =348 or c.instanceid = 351 or c.instanceid = 350 or c.instanceid = 349 or c.instanceid = 358 or c.instanceid = 359 or c.instanceid = 360 
									or c.instanceid = 361 or c.instanceid = 365 or c.instanceid = 364 or c.instanceid = 363 or c.instanceid = 362 or c.instanceid = 242 or c.instanceid = 243 
									or c.instanceid = 244 or c.instanceid = 245) 
									and e.shortname='cuatrimestre' 
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		return $data;
	}
	
	
	public function desactivarClientesActivosIexe()
	{
		$this->load->database('default',true);
		
		$clientes	= $this->obtenerClientesActivos();
		
		foreach($clientes as $row)
		{
			$this->db->where('idCliente',$row->idCliente);
			$this->db->update('clientes',array('idZona'=>6));
		}
	}
	
	public function activarClienteMatricula($matricula)
	{
		$this->load->database('default',true);
		
		$cliente	= $this->obtenerClientesMatricula($matricula);
		
		if($cliente!=null)
		{
			$this->db->where('idCliente',$cliente->idCliente);
			$this->db->update('clientes',array('idZona'=>1,'fechaBaja'=>null,'idCausa'=>0,'activo'=>'1','prospecto'=>'0'));
			
			return 1;
		}	
		else
		{
			return 0;	
		}
		
		return 0;	
	}
	
	public function procesarConsultasAlumnos2()
	{
		$this->load->helper('base');
		$numero=0;
		
		$this->desactivarClientesActivosIexe();

		$consultas		= $this->obtenerConsultasAlumnos();
		$bases			= obtenerBases();
		
		echo '
		<table class="admintable" width="100%">
			<tr>
				<th>#</th>
				<th>ID</th>
				<th>Matricula</th>
				<th>Nombre</th>
				<th>Email</th
				<th>Procesado</th>
			</tr>';
		
		$i=1;
		foreach($consultas as $row)
		{
			$dsn		= obtenerConexion('iexe.edu.mx','iexe2013_iexe','Iexe%2015$',$bases[$row['base']]);
			$base		= $this->load->database($dsn,true);
			
			#echo $row['query'].'<br /><br />';
			
			$alumnos	 = $base->query($row['query'])->result();
			
			if($alumnos!=null)
			{
				$numero+=count($alumnos);
				
				foreach($alumnos as $alu)
				{
					$procesado	= $this->activarClienteMatricula($alu->username);
					
					if($procesado==0)
					{
						$clienteIexe	= $this->obtenerClienteRegistroIexe($alu->username);
						
						if($clienteIexe!=null)
						{
							$clienteRedisoft	= $this->obtenerClienteRegistroRedisoft($clienteIexe->email);
							
							if($clienteRedisoft!=null)
							{
								$this->load->database('default',true);
								
								$this->db->where('idCliente',$clienteRedisoft->idCliente);
								$this->db->update('clientes',array('activo'=>'1','prospecto'=>'0','idZona'=>'1'));
								
								$this->db->where('idCliente',$clienteRedisoft->idCliente);
								$this->db->update('clientes_academicos',array('matricula'=>$alu->username));
							}
							else
							{
								echo '
								<tr>
									<td>'.$i.'</td>
									<td>'.$alu->id.'</td>
									<td>'.$alu->username.'</td>
									<td>'.$alu->firstname.'</td>
									<td>'.$clienteIexe->email.'</td>
									<td>'.$procesado.'</td>
								</tr>';
								
								$i++;
							}
						}
						
						else
						{
							echo '
							<tr>
								<td>'.$i.'</td>
								<td>'.$alu->id.'</td>
								<td>'.$alu->username.'</td>
								<td>'.$alu->firstname.'</td>
								<td></td>
								<td>'.$procesado.'</td>
							</tr>';
							
							$i++;
						}
						
						

					}
				}
			}
		}
		
		echo '</table>';
		
		#echo 'Numero: '.$numero;

	}
	
	
	public function procesarConsultasAlumnos()
	{
		$this->load->helper('base');
		$numero=0;
		
		$this->desactivarClientesActivosIexe();

		$consultas		= $this->obtenerConsultasAlumnos();
		$bases			= obtenerBases();
		
		$this->load->library('excel/PHPExcel');
		
		PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
		$excel = new PHPExcel();
		
		$excel
		->getProperties()
		->setCreator(empresa)
		->setLastModifiedBy(empresa)
		->setTitle(empresa)
		->setSubject(empresa)
		->setDescription(empresa)
		->setKeywords(empresa)
		->setCategory(empresa);
		
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
		
		$i=1;
		
		$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
		$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
		$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE IXE');
		
		$i++;
		$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
		
		$excel->getActiveSheet()->setCellValue('A'.$i, 'ID IEXE');
		$excel->getActiveSheet()->setCellValue('B'.$i, 'Matrícula');
		$excel->getActiveSheet()->setCellValue('C'.$i, 'Nombre');
		$excel->getActiveSheet()->setCellValue('D'.$i, 'Email');
		$excel->getActiveSheet()->setCellValue('E'.$i, 'Procesado');
		
		/*echo '
		<table class="admintable" width="100%">
			<tr>
				<th>#</th>
				<th>ID</th>
				<th>Matricula</th>
				<th>Nombre</th>
				<th>Email</th
				<th>Procesado</th>
			</tr>';*/
		
		$i++;
		foreach($consultas as $row)
		{
			$dsn		= obtenerConexion('iexe.edu.mx','iexe2013_iexe','Iexe%2015$',$bases[$row['base']]);
			$base		= $this->load->database($dsn,true);
			
			#echo $row['query'].'<br /><br />';
			
			$alumnos	 = $base->query($row['query'])->result();
			
			if($alumnos!=null)
			{
				$numero+=count($alumnos);
				
				foreach($alumnos as $alu)
				{
					$procesado	= $this->activarClienteMatricula($alu->username);
					
					if($procesado==0)
					{
						/*echo '
						<tr>
							<td>'.$i.'</td>
							<td>'.$alu->id.'</td>
							<td>'.$alu->username.'</td>
							<td>'.$alu->firstname.' '.$alu->lastname.'</td>
							<td>'.$alu->email.'</td>
							<td>'.$procesado.'</td>
						</tr>';*/
						
						
						$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($alu->id, PHPExcel_Cell_DataType::TYPE_STRING);
						$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($alu->username, PHPExcel_Cell_DataType::TYPE_STRING);
						$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($alu->firstname.' '.$alu->lastname, PHPExcel_Cell_DataType::TYPE_STRING);
						$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($alu->email, PHPExcel_Cell_DataType::TYPE_STRING);
						$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($procesado==0?'No':'Si', PHPExcel_Cell_DataType::TYPE_STRING);
						
						$excel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
						$i++;

					}
				}
			}
		}
		
		#echo '</table>';
		
		
		if($i>3)
		{

			$excel->getActiveSheet()->setTitle('Reporte');
			$excel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
			
			$objWriter->save(str_replace('.php', '.xls',"media/ficheros/archivoIexe.xls"));
			
			$this->load->helper('download');

			$data 			= file_get_contents("media/ficheros/archivoIexe.xls"); 
	
			force_download('archivoIexe.xls', $data); 
		}
		else
		{
			echo 'Sin resultados para el archivo de excel';
		}

	}
	
	public function obtenerClienteRegistroRedisoft($email)
	{
		$this->load->database('default',true);
		
		$sql="select idCliente, email
		from clientes
		where email like '%$email%' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerClienteRegistroIexe($matricula)
	{
		$dsn		= obtenerConexion('iexe.edu.mx','iexe2013','$2016Iexe-Universi-dad%$_16#2018_!2=1_201&','iexe2013_registro');
		$base		= $this->load->database($dsn,true);
		
		$sql="select concat(nombre,' ', apaterno, ' ', amaterno) AS nombre, matricula, email 
		from registro 
		WHERE matricula LIKE '%$matricula%'";
		
		return $base->query($sql)->row();
	}
	
	public function revisarAlumnosIexe()
	{
		$this->load->helper('base');
		$numero=0;


		$consultas		= $this->obtenerConsultasAlumnos();
		$bases			= obtenerBases();

		foreach($consultas as $row)
		{
			$dsn		= obtenerConexion('iexe.edu.mx','iexe2013_iexe','Iexe%2015$',$bases[$row['base']]);
			$base		= $this->load->database($dsn,true);
			
			#echo $row['query'].'<br /><br />';
			
			$alumnos	 = $base->query($row['query'])->result();
			
			if($alumnos!=null)
			{
				$numero+=count($alumnos);
			}
		}

		
		echo 'Numero: '.$numero;

	}
	
	//SIMULACION DE ACTIVACION
	public function simularActivarClienteMatricula($matricula)
	{
		$this->load->database('default',true);
		
		$cliente	= $this->obtenerClientesMatricula($matricula);
		
		if($cliente!=null)
		{
			#$this->db->where('idCliente',$cliente->idCliente);
			#$this->db->update('clientes',array('idZona'=>1,'fechaBaja'=>null,'idCausa'=>0,'activo'=>'1'));
			
			return 1;
		}	
		else
		{
			return 0;	
		}
		
		return 0;	
	}
	
	public function simularProcesarConsultasAlumnos()
	{
		$this->load->helper('base');
		$numero=0;

		$consultas		= $this->obtenerConsultasAlumnos();
		$bases			= obtenerBases();
		
		$i=1;
		echo '
		<table class="admintable" width="100%">
			<tr>
				<th>#</th>
				<th>ID</th>
				<th>Matricula</th>
				<th>Nombre</th>
				<th>Email</th>
				<th>Procesado</th>
			</tr>';
		
		foreach($consultas as $row)
		{
			$dsn		= obtenerConexion('iexe.edu.mx','iexe2013_iexe','Iexe%2015$',$bases[$row['base']]);
			$base		= $this->load->database($dsn,true);
			
			#echo $row['query'].'<br /><br />';
			
			$alumnos	 = $base->query($row['query'])->result();
			
			if($alumnos!=null)
			{
				$numero+=count($alumnos);
				
				foreach($alumnos as $alu)
				{
					$procesado	= $this->simularActivarClienteMatricula($alu->username);
					
					#if($procesado==0)
					{
						echo '
						<tr>
							<td>'.$i.'</td>
							<td>'.$alu->id.'</td>
							<td>'.$alu->username.'</td>
							<td>'.$alu->firstname.' '.$alu->lastname.'</td>
							<td>'.$alu->email.'</td>
							<td>'.$procesado.'</td>
						</tr>';
						
						$i++;
					}
				}
			}
		}
		
		echo '</table>';
		
		#echo 'Numero: '.$numero;

	}
	
}

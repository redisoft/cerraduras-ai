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
	
	/*public function obtenerConsultasAlumnos()
	{
		$data=array();
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid		
			inner join mdl_context c on b.contextid=c.id 		
			inner join mdl_user_info_data d on d.userid=a.id		
			inner join mdl_user_info_field e on  e.id=d.fieldid		
			where c.contextlevel = 50 
			and (c.instanceid = 430 or c.instanceid = 431 or c.instanceid = 432 or c.instanceid = 433 or c.instanceid = 434 or c.instanceid = 435 or c.instanceid = 436 
			or c.instanceid = 438 or c.instanceid = 437 or c.instanceid = 439 or c.instanceid = 441 or c.instanceid = 440 or c.instanceid = 442 or c.instanceid = 444 
			or c.instanceid = 443 or c.instanceid = 463 or c.instanceid = 462 or c.instanceid = 461)		
			and e.shortname='trimestre' and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva'		
			and b.roleid = 5",
			'base'=>0
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id 
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid where c.contextlevel = 50 
			and (c.instanceid = 227 or c.instanceid = 228 or c.instanceid = 229 or c.instanceid = 230 or c.instanceid = 231 or 
			c.instanceid = 232 or c.instanceid = 233 or c.instanceid = 234 or c.instanceid = 235 or c.instanceid = 236 or c.instanceid = 237 
			or c.instanceid = 238 or c.instanceid = 239 or c.instanceid = 240 or c.instanceid = 241 or c.instanceid = 258 or c.instanceid = 259 
			or c.instanceid = 260 or c.instanceid = 261)		
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva'		
			and b.roleid = 5",
			'base'=>1
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid	
			inner join mdl_context c on b.contextid=c.id 
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 233 or c.instanceid = 234 or c.instanceid = 235 or c.instanceid = 236 or c.instanceid = 237 or c.instanceid = 238 
			or c.instanceid = 239 or c.instanceid = 240 or c.instanceid = 241 or c.instanceid = 242 or c.instanceid = 243 or c.instanceid = 244 
			or c.instanceid = 245 or c.instanceid = 246 or c.instanceid = 247 or c.instanceid = 266 or c.instanceid = 267 or c.instanceid = 268 
			or c.instanceid = 269 or c.instanceid = 270)		
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva'	
			and b.roleid = 5",
			'base'=>2
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid	
			inner join mdl_context c on b.contextid=c.id 
			inner join mdl_user_info_data d on d.userid=a.id
			inner join mdl_user_info_field e on e.id=d.fieldid
			where c.contextlevel = 50 	
			and (c.instanceid = 43 or c.instanceid = 44 or c.instanceid = 45 or c.instanceid = 46 or c.instanceid = 47 or c.instanceid = 48 or c.instanceid = 49) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva'
			and b.roleid = 5",
			'base'=>5
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 106 or c.instanceid =  107 or c.instanceid = 108 or c.instanceid = 109 or c.instanceid = 110 or c.instanceid = 111 
			or c.instanceid = 112 or c.instanceid = 113 or c.instanceid = 114 or c.instanceid = 127 or c.instanceid = 128 or c.instanceid = 129 
			or c.instanceid = 130 or c.instanceid = 131 or c.instanceid = 132 or c.instanceid = 133 or c.instanceid = 139 or c.instanceid = 140 
			or c.instanceid = 141 or c.instanceid = 142) 
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva' 
			and b.roleid = 5",
			'base'=>3
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 115 or c.instanceid = 116 or c.instanceid = 117 or c.instanceid = 118 or c.instanceid = 119 or c.instanceid = 120
			or c.instanceid = 121 or c.instanceid = 122 or c.instanceid = 123 or c.instanceid = 124 or c.instanceid = 125 or c.instanceid = 126 
			or c.instanceid = 136 or c.instanceid = 137 or c.instanceid = 138) 
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva' 
			and b.roleid = 5",
			'base'=>3
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 219 or c.instanceid = 221 or c.instanceid = 222 or c.instanceid = 223 or c.instanceid = 225 or c.instanceid = 230 or c.instanceid = 231 
			or c.instanceid = 232 or c.instanceid = 251 or c.instanceid = 257 or c.instanceid = 259 or c.instanceid = 260 or c.instanceid = 284 or c.instanceid = 285 
			or c.instanceid = 288 or c.instanceid = 286 or c.instanceid = 287) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 238 or c.instanceid = 239 or c.instanceid = 240 or c.instanceid = 241 or c.instanceid = 247 or c.instanceid = 248 or c.instanceid = 249 
			or c.instanceid = 250 or c.instanceid = 266 or c.instanceid = 267 or c.instanceid = 268 or c.instanceid = 269 or c.instanceid = 289 or c.instanceid = 290 
			or c.instanceid = 291 or c.instanceid = 292 or c.instanceid = 293) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 168 or c.instanceid = 169 or c.instanceid = 170 or c.instanceid = 171 or c.instanceid = 252 or c.instanceid = 253 or c.instanceid = 254 
			or c.instanceid = 255 or c.instanceid = 261 or c.instanceid = 262 or c.instanceid = 263 or c.instanceid = 265 or c.instanceid = 300 or c.instanceid = 301 
			or c.instanceid = 302 or c.instanceid = 303) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 215 or c.instanceid = 216 or c.instanceid = 217 or c.instanceid = 218 or c.instanceid = 226 or c.instanceid = 227 
			or c.instanceid = 228 or c.instanceid = 229 or c.instanceid = 233 or c.instanceid = 234 or c.instanceid = 235 or c.instanceid = 236 
			or c.instanceid = 242 or c.instanceid = 243 or c.instanceid = 244 or c.instanceid = 245 or c.instanceid = 295 or c.instanceid = 296 
			or c.instanceid = 297 or c.instanceid = 298) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		return $data;
	}*/
	
	public function obtenerConsultasAlumnos2()
	{
		$data=array();
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid		
			inner join mdl_context c on b.contextid=c.id 		
			inner join mdl_user_info_data d on d.userid=a.id		
			inner join mdl_user_info_field e on  e.id=d.fieldid		
			where c.contextlevel = 50 
			and (c.instanceid = 466 or c.instanceid = 467 or c.instanceid = 468 or c.instanceid = 469 or c.instanceid = 470 or c.instanceid = 471 or c.instanceid = 472 
			or c.instanceid = 473 or c.instanceid = 474 or c.instanceid = 475 or c.instanceid = 476 or c.instanceid = 477 or c.instanceid = 478 or c.instanceid = 479 
			or c.instanceid = 480)		
			and e.shortname='trimestre' and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva'		
			and b.roleid = 5",
			'base'=>0
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id 
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid where c.contextlevel = 50 
			and (c.instanceid = 262 or c.instanceid = 263 or c.instanceid = 264 or c.instanceid = 267 or c.instanceid = 268 or 
			c.instanceid = 269 or c.instanceid = 270 or c.instanceid = 271 or c.instanceid = 272 or c.instanceid = 273 or c.instanceid = 274 
			or c.instanceid = 275 or c.instanceid = 277 or c.instanceid = 278 or c.instanceid = 266)		
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva'		
			and b.roleid = 5",
			'base'=>1
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid	
			inner join mdl_context c on b.contextid=c.id 
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 272 or c.instanceid = 273 or c.instanceid = 274 or c.instanceid = 275 or c.instanceid = 276 or c.instanceid = 277 
			or c.instanceid = 278 or c.instanceid = 279 or c.instanceid = 280 or c.instanceid = 281 or c.instanceid = 282 or c.instanceid = 283 
			or c.instanceid = 284 or c.instanceid = 285 or c.instanceid = 286)		
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva'	
			and b.roleid = 5",
			'base'=>2
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid	
			inner join mdl_context c on b.contextid=c.id 
			inner join mdl_user_info_data d on d.userid=a.id
			inner join mdl_user_info_field e on e.id=d.fieldid
			where c.contextlevel = 50 	
			and (c.instanceid = 43 or c.instanceid = 44 or c.instanceid = 45 or c.instanceid = 46 or c.instanceid = 47 or c.instanceid = 48 or c.instanceid = 49) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva'
			and b.roleid = 5",
			'base'=>5
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 144 or c.instanceid =  145 or c.instanceid = 146 or c.instanceid = 147 or c.instanceid = 148 or c.instanceid = 149 
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
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 154 or c.instanceid = 155 or c.instanceid = 156 or c.instanceid = 163 or c.instanceid = 164 or c.instanceid = 165
			or c.instanceid = 166 or c.instanceid = 167 or c.instanceid = 168 or c.instanceid = 200 or c.instanceid = 201 or c.instanceid = 202 
			or c.instanceid = 169 or c.instanceid = 170 or c.instanceid = 171) 
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva' 
			and b.roleid = 5",
			'base'=>3
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 219 or c.instanceid = 221 or c.instanceid = 222 or c.instanceid = 223 or c.instanceid = 225 or c.instanceid = 230 or c.instanceid = 231 
			or c.instanceid = 232 or c.instanceid = 251 or c.instanceid = 257 or c.instanceid = 259 or c.instanceid = 260 or c.instanceid = 284 or c.instanceid = 285 
			or c.instanceid = 288 or c.instanceid = 286 or c.instanceid = 287) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 238 or c.instanceid = 239 or c.instanceid = 240 or c.instanceid = 241 or c.instanceid = 247 or c.instanceid = 248 or c.instanceid = 249 
			or c.instanceid = 250 or c.instanceid = 266 or c.instanceid = 267 or c.instanceid = 268 or c.instanceid = 269 or c.instanceid = 289 or c.instanceid = 290 
			or c.instanceid = 291 or c.instanceid = 292 or c.instanceid = 293) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 168 or c.instanceid = 169 or c.instanceid = 170 or c.instanceid = 171 or c.instanceid = 252 or c.instanceid = 253 or c.instanceid = 254 
			or c.instanceid = 255 or c.instanceid = 261 or c.instanceid = 262 or c.instanceid = 263 or c.instanceid = 265 or c.instanceid = 300 or c.instanceid = 301 
			or c.instanceid = 302 or c.instanceid = 303) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 215 or c.instanceid = 216 or c.instanceid = 217 or c.instanceid = 218 or c.instanceid = 226 or c.instanceid = 227 
			or c.instanceid = 228 or c.instanceid = 229 or c.instanceid = 233 or c.instanceid = 234 or c.instanceid = 235 or c.instanceid = 236 
			or c.instanceid = 242 or c.instanceid = 243 or c.instanceid = 244 or c.instanceid = 245 or c.instanceid = 295 or c.instanceid = 296 
			or c.instanceid = 297 or c.instanceid = 298) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		return $data;
	}
	
	public function obtenerConsultasAlumnos()
	{
		$data=array();
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email
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
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email
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
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email 
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
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, a.lastname, a.email 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid	
			inner join mdl_context c on b.contextid=c.id 
			inner join mdl_user_info_data d on d.userid=a.id
			inner join mdl_user_info_field e on e.id=d.fieldid
			where c.contextlevel = 50 	
			and (c.instanceid = 43 or c.instanceid = 44 or c.instanceid = 45 or c.instanceid = 46 or c.instanceid = 47 or c.instanceid = 48 or c.instanceid = 49) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva'
			and b.roleid = 5",
			'base'=>5
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 144 or c.instanceid = 145 or c.instanceid = 146 or c.instanceid = 149 or c.instanceid = 148 or c.instanceid = 147 or c.instanceid = 150 or c.instanceid = 151 or 
			c.instanceid = 152 or c.instanceid = 159 or c.instanceid = 157 or c.instanceid = 158 or c.instanceid = 162 or c.instanceid = 161 or c.instanceid = 203 or c.instanceid = 154 or 
			c.instanceid =  155 or c.instanceid =  156 or c.instanceid =  163 or c.instanceid =  164 or c.instanceid =  165 or c.instanceid =  166 or c.instanceid =  167 or c.instanceid =  168 or 
			c.instanceid =  200 or c.instanceid =  201 or c.instanceid =  202 or c.instanceid =  169 or c.instanceid =  170 or c.instanceid =  171) 
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva' 
			and b.roleid = 5",
			'base'=>3
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 115 or c.instanceid = 116 or c.instanceid = 117 or c.instanceid = 118 or c.instanceid = 119 or c.instanceid = 120
			or c.instanceid = 121 or c.instanceid = 122 or c.instanceid = 123 or c.instanceid = 124 or c.instanceid = 125 or c.instanceid = 126 
			or c.instanceid = 136 or c.instanceid = 137 or c.instanceid = 138) 
			and e.shortname='trimestre' 
			and d.data <> 'Baja temporal' 
			and d.data <> 'Baja definitiva' 
			and b.roleid = 5",
			'base'=>3
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 219 or c.instanceid = 221 or c.instanceid = 222 or c.instanceid = 223 or c.instanceid = 225 or c.instanceid = 230 or c.instanceid = 231 
			or c.instanceid = 232 or c.instanceid = 251 or c.instanceid = 257 or c.instanceid = 259 or c.instanceid = 260 or c.instanceid = 284 or c.instanceid = 285 
			or c.instanceid = 288 or c.instanceid = 286 or c.instanceid = 287) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 238 or c.instanceid = 239 or c.instanceid = 240 or c.instanceid = 241 or c.instanceid = 247 or c.instanceid = 248 or c.instanceid = 249 
			or c.instanceid = 250 or c.instanceid = 266 or c.instanceid = 267 or c.instanceid = 268 or c.instanceid = 269 or c.instanceid = 289 or c.instanceid = 290 
			or c.instanceid = 291 or c.instanceid = 292 or c.instanceid = 293) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 168 or c.instanceid = 169 or c.instanceid = 170 or c.instanceid = 171 or c.instanceid = 252 or c.instanceid = 253 or c.instanceid = 254 
			or c.instanceid = 255 or c.instanceid = 261 or c.instanceid = 262 or c.instanceid = 263 or c.instanceid = 265 or c.instanceid = 300 or c.instanceid = 301 
			or c.instanceid = 302 or c.instanceid = 303) 
			and e.shortname='cuatrimestre' 
			and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5",
			'base'=>4
		);
		
		$data[]=array
		(
			'query'=>"SELECT  distinct(a.id), a.username, a.firstname, d.data, a.lastname, a.email 
			from mdl_user a 
			inner join mdl_role_assignments b on a.id=b.userid 
			inner join mdl_context c on b.contextid=c.id
			inner join mdl_user_info_data d on d.userid=a.id 
			inner join mdl_user_info_field e on  e.id=d.fieldid 
			where c.contextlevel = 50 
			and (c.instanceid = 215 or c.instanceid = 216 or c.instanceid = 217 or c.instanceid = 218 or c.instanceid = 226 or c.instanceid = 227 
			or c.instanceid = 228 or c.instanceid = 229 or c.instanceid = 233 or c.instanceid = 234 or c.instanceid = 235 or c.instanceid = 236 
			or c.instanceid = 242 or c.instanceid = 243 or c.instanceid = 244 or c.instanceid = 245 or c.instanceid = 295 or c.instanceid = 296 
			or c.instanceid = 297 or c.instanceid = 298) 
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
			
			return $cliente;
		}	
		else
		{
			return null;	
		}
		
		return null;	
	}
	
	public function simularProcesarConsultasAlumnos()
	{
		$this->load->helper('base');
		$numero=0;

		$consultas		= $this->obtenerConsultasAlumnos();
		$bases			= obtenerBases();
		$sql="";
		
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
					$cliente	= $this->simularActivarClienteMatricula($alu->username);
					
					#if($procesado==0)
					{
						echo '
						<tr>
							<td>'.$i.'</td>
							<td>'.$alu->id.'</td>
							<td>'.$alu->username.'</td>
							<td>'.$alu->firstname.' '.$alu->lastname.'</td>
							<td>'.$alu->email.'</td>
							<td>'.($cliente!=null?$cliente->idCliente:'').'</td>
						</tr>';
						
						if($cliente!=null)
						{
							$sql.=" 
							or a.idCliente='$cliente->idCliente'";
						}
						
						$i++;
					}
				}
			}
		}
		
		echo '</table>';
		
		
		echo nl2br($sql);
		
		#echo 'Numero: '.$numero;

	}
	
}

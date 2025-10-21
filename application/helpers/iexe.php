<?php
class Plataformas 
{

//conexion plataforma mapp   	  
            private function conexion_mapp() {
                $conexion1 = mysql_connect("localhost", "iexe2013_iexe", "Iexe%2015$");
                mysql_select_db("iexe2013_iexe", $conexion1);
            }

//conexion plataforma mepp   	  
            private function conexion_mepp() {
                $conexion2 = mysql_connect("localhost", "iexe2013_iexe", "Iexe%2015$");
                mysql_select_db("iexe2013_mepp", $conexion2);
            }

//conexion msppp
            private function conexion_msppp() {
                $conexion3 = mysql_connect("localhost", "iexe2013_iexe", "Iexe%2015$");
                mysql_select_db("iexe2013_msppp", $conexion3);
            }

//conexion plataforma licenciaturas
            private function conexion_licenciaturas() {
                $conexion6 = mysql_connect("localhost", "iexe2013_iexe", "Iexe%2015$");
                mysql_select_db("iexe2013_licenciaturas", $conexion6);
            }
			
//conexion plataforma maestrias
            private function conexion_maestrias() {
                $conexion7 = mysql_connect("localhost", "iexe2013_iexe", "Iexe%2015$");
                mysql_select_db("iexe2013_maestrias", $conexion7);
            }			

            private function conexion_dpp() {
                $conexion4 = mysql_connect("localhost", "iexe2013_iexe", "Iexe%2015$");
                mysql_select_db("iexe2013_doctorados", $conexion4);
            }

            private function conexion_sistemaregistro() {
                $conexion5 = mysql_connect("localhost", "iexe2013_registr", "@Poseidon%");
                mysql_select_db("iexe2013_registro", $conexion5);
            }

			public function resultadomapp() {
			$this->conexion_mapp();
			mysql_query("SET NAMES 'utf8'");
			$sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data 
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
			and b.roleid = 5")or die(mysql_error());
			return mysql_num_rows($sql);
			}
			
			public function resultadomepp() {
			$this->conexion_mepp();
			mysql_query("SET NAMES 'utf8'");
			$sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data 
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
			and b.roleid = 5")or die(mysql_error());
			return mysql_num_rows($sql);
			}

			public function resultadomsppp() 
			{
			$this->conexion_msppp();
			mysql_query("SET NAMES 'utf8'");
			$sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data 
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
			and b.roleid = 5")or die(mysql_error());
			return mysql_num_rows($sql);
			}

			public function resultadodpp() {
			$this->conexion_dpp();
			mysql_query("SET NAMES 'utf8'");
			$sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname 
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
			and b.roleid = 5")or die(mysql_error());
			return mysql_num_rows($sql);
			}

			public function resultadomfp() {
			$this->conexion_maestrias();
			mysql_query("SET NAMES 'utf8'");
			$sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data 
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
			and b.roleid = 5")or die(mysql_error());
			return mysql_num_rows($sql);
			}		


			
			public function resultadoman() {
			$this->conexion_maestrias();
			mysql_query("SET NAMES 'utf8'");
			$sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data 
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
			and b.roleid = 5")or die(mysql_error());
			return mysql_num_rows($sql);
			}


            public function resultadolcpap() {
                $this->conexion_licenciaturas();
                mysql_query("SET NAMES 'utf8'");
                $sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data 
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
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5")or die(mysql_error());
                return mysql_num_rows($sql);
            }	


            public function resultadolsp() {
                $this->conexion_licenciaturas();
                mysql_query("SET NAMES 'utf8'");
                $sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 238 or c.instanceid = 239 or c.instanceid = 240 or c.instanceid = 241 or c.instanceid = 247 or c.instanceid = 248 or c.instanceid = 249 
									or c.instanceid = 250 or c.instanceid = 266 or c.instanceid = 267 or c.instanceid = 268 or c.instanceid = 269 or c.instanceid = 289 or c.instanceid = 290 
									or c.instanceid = 291 or c.instanceid = 292 or c.instanceid = 293) 
									and e.shortname='cuatrimestre' 
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5")or die(mysql_error());
                return mysql_num_rows($sql);
            }	


            public function resultadolae() {
                $this->conexion_licenciaturas();
                mysql_query("SET NAMES 'utf8'");
                $sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
									inner join mdl_role_assignments b on a.id=b.userid 
									inner join mdl_context c on b.contextid=c.id
									inner join mdl_user_info_data d on d.userid=a.id 
									inner join mdl_user_info_field e on  e.id=d.fieldid 
									where c.contextlevel = 50 
									and (c.instanceid = 168 or c.instanceid = 169 or c.instanceid = 170 or c.instanceid = 171 or c.instanceid = 252 or c.instanceid = 253 or c.instanceid = 254 
									or c.instanceid = 255 or c.instanceid = 261 or c.instanceid = 262 or c.instanceid = 263 or c.instanceid = 265 or c.instanceid = 300 or c.instanceid = 301 
									or c.instanceid = 302 or c.instanceid = 303) 
									and e.shortname='cuatrimestre' 
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5")or die(mysql_error());
                return mysql_num_rows($sql);
            }	

            public function resultadold() {
                $this->conexion_licenciaturas();
                mysql_query("SET NAMES 'utf8'");
                $sql = mysql_query("SELECT  distinct(a.id), a.username, a.firstname, d.data from mdl_user a 
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
									and d.data <> 'Baja temporal' and d.data <> 'Baja definitiva' and b.roleid = 5")or die(mysql_error());
                return mysql_num_rows($sql);
            }			
			
            //devuelve el numero de alumnos inscritos consultando la base de datos de registro   	  
            public function inscritos_sistema_registro() {
                $this->conexion_sistemaregistro();
                mysql_query("SET NAMES 'utf8'");
                $consulta = mysql_query("
				SELECT id,promotor, nombre, apaterno, programainscribe, estatus, periodo, matricula 
				FROM registro WHERE (estatus='liberado') 
				and (					
					Periodo='PeriodoC18L' or
					Periodo='PeriodoD18M' or
					Periodo='PeriodoB18D' or
					Periodo='PeriodoC18' or
					Periodo='PeriodoB18L'
					) 
                 and usuario=''
				order by  programainscribe asc
				")or die(mysql_error());
                return mysql_num_rows($consulta);
            }

            //#Operaciones que determinan el numero de alumnos de primer trimestre por plataforma.      
            public function inscritos_primeromapp() {
                $this->conexion_mapp();
                $consulta = mysql_query("SELECT id  from mdl_user where id in (SELECT userid FROM mdl_role_assignments where roleid = 5
				and contextid in (SELECT id FROM mdl_context where contextlevel = 50
                and (instanceid = 466 or instanceid = 467 or instanceid = 468)))")or die(mysql_error());
                return mysql_num_rows($consulta);
            }

            public function inscritos_primeromepp() {
                $this->conexion_mepp();
                $consulta = mysql_query("SELECT id from mdl_user where id in (SELECT userid FROM mdl_role_assignments where roleid = 5 
				and contextid in (SELECT id FROM mdl_context where contextlevel = 50 
				and (instanceid = 462 or instanceid = 463 or instanceid = 464)))")or die(mysql_error());
                return mysql_num_rows($consulta);
            }

            public function inscritos_primeromsppp() {
                $this->conexion_msppp();
                $consulta = mysql_query("SELECT id from mdl_user where id in (SELECT userid FROM mdl_role_assignments where roleid = 5  
				and contextid in (SELECT id FROM mdl_context where contextlevel = 50 
				and (instanceid = 272 or instanceid = 273 or instanceid = 274)))")or die(mysql_error());
                return mysql_num_rows($consulta);
            }
			
            public function inscritos_primeromaestrias() {
                $this->conexion_maestrias();
                $consulta = mysql_query("SELECT id from mdl_user where id in (SELECT userid FROM mdl_role_assignments where roleid = 5 
				and contextid in (SELECT id FROM mdl_context where contextlevel = 50 
				and (instanceid = 144 or instanceid = 145 or instanceid = 146 or instanceid = 154 or instanceid = 155 or instanceid = 156)))")or die(mysql_error());
                return mysql_num_rows($consulta);
            }			

            public function inscritos_primerodpp() {
                $this->conexion_dpp();
                $consulta = mysql_query("SELECT * from mdl_user where id in (SELECT userid FROM mdl_role_assignments where roleid = 5 and contextid in (SELECT id FROM mdl_context where contextlevel = 50 and instanceid = 13))")or die(mysql_error());
                return mysql_num_rows($consulta);
            }

            public function inscritos_primerolicenciaturas() {
                $this->conexion_licenciaturas();
                $consulta = mysql_query("SELECT * from mdl_user where id in (SELECT userid FROM mdl_role_assignments where roleid = 5 and contextid in (SELECT id FROM mdl_context where contextlevel = 50 and instanceid = 13 or instanceid = 5 or instanceid = 4 and instanceid = 21 or instanceid = 11 or instanceid = 10 and instanceid = 22 or instanceid = 6 or instanceid = 14                and instanceid = 2 or instanceid = 15 or instanceid = 16 and instanceid = 17 or instanceid = 18 or instanceid = 19 or instanceid = 19                ))")or die(mysql_error());
                return mysql_num_rows($consulta);
            }


            public function total_alumnos() {

   		$maestrias1 = $this->resultadomapp() + $this->resultadomepp() + $this->resultadomsppp()+$this->resultadomfp()+$this->resultadoman();
		$doctorados1=$this->resultadodpp();
	        $licenciaturas1=$this->resultadolcpap()+$this->resultadolsp()+$this->resultadolae()+$this->resultadold();
		$total1= $maestrias1+$doctorados1+$licenciaturas1;
                return $total1;
            }



            public function totales_inscritos_primero() {
                $valortotal = 1000 - ($this->inscritos_sistema_registro() + $this->inscritos_primeromapp() + $this->inscritos_primeromepp() + $this->inscritos_primeromsppp() + $this->inscritos_primerodpp());
                return $valortotal;
            }

        }

?>
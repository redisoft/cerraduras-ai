
<?php
echo'
<div style="width:90%; margin-top:1%;">
    <ul id="pagination-digg" class="ajax-pagPreinscritos">'.$this->pagination->create_links().'</ul>
</div>';
?>

<table class="admintable" width="100%">
    <?php
    echo '
    <tr>
        <td colspan="12" class="sinbordeTransparente">
            <ul class="menuTabs">
				<li style="margin-top:10px; width: 150px" onclick="window.location.href=\''.base_url().'clientes/activos\'">Alumnos</li> 
                <li style="margin-top:10px; width: 150px" onclick="window.location.href=\''.base_url().'clientes\'">Universo</li> 
                <li class="activado" style="margin-top:10px; width: 150px" onclick="window.location.href=\''.base_url().'clientes/preinscritos\'">Pre-Inscritos</li> 
                
            </ul>
        </td>
    </tr>';
    ?>
    
    <tr>
        <th class="encabezadoPrincipal" width="2%" align="center" >#<br /><?=$registros?></th>
        <th class="encabezadoPrincipal" align="left"><?php echo 'Alumno'; ?></th>
        <th class="encabezadoPrincipal" align="left">
            
            <select id="selectProgramaBusqueda" name="selectProgramaBusqueda" onchange="obtenerPreinscritos()" style="width:120px" class="cajas">
                <option value="0">Programa</option>
                <?php
                foreach($programas as $row)
                {
                    echo '<option '.($idPrograma==$row->idPrograma?'selected="selected"':'').' value="'.$row->idPrograma.'">'.$row->nombre.'</option>';
                }
                ?>
            </select>
            
        </th>
        
        <th class="encabezadoPrincipal" align="left">
            
            <select id="selectCampanasBusqueda" name="selectCampanasBusqueda" onchange="obtenerPreinscritos()" style="width:120px" class="cajas">
                <option value="0">Campaña</option>
                <?php
                foreach($campanas as $row)
                {
                    echo '<option '.($idCampana==$row->idCampana?'selected="selected"':'').' value="'.$row->idCampana.'">'.$row->nombre.'</option>';
                }
                ?>
            </select>
        </th>
        
        <th class="encabezadoPrincipal" align="left">
            
            <select id="selectMesBusqueda" name="selectMesBusqueda" onchange="obtenerPreinscritos()" style="width:80px" class="cajas">
                <option value="">Mes</option>
                <?php
                foreach($meses as $row)
                {
                    echo '<option '.($mes==$row->nombre?'selected="selected"':'').' >'.$row->nombre.'</option>';
                }
                ?>
            </select>
        </th>
        
        <th class="encabezadoPrincipal" align="left">
            
            <select id="selectPeriodosBusqueda" name="selectPeriodosBusqueda" onchange="obtenerPreinscritos()" style="width:80px" class="cajas">
                <option value="0">Periodo</option>
                <?php
                foreach($periodos as $row)
                {
                    echo '<option '.($idPeriodo==$row->idPeriodo?'selected="selected"':'').' value="'.$row->idPeriodo.'" >'.$row->nombre.' ('.obtenerFechaMesCorto($row->fechaInicial).' | '.obtenerFechaMesCorto($row->fechaFinal).')</option>';
                }
                ?>
            </select>
        </th>
        
        <th class="encabezadoPrincipal" >
            <select id="selectPromotorBusqueda" name="selectPromotorBusqueda" onchange="obtenerPreinscritos()" style="width:100px" class="cajas">
                <option value="0">Promotor</option>
                <?php
                foreach($promotores as $row)
                {
                    echo '<option '.($idPromotor==$row->idUsuario?'selected="selected"':'').' value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
                }
                ?>
            </select>
        </th>
        <th class="encabezadoPrincipal" >
            <select id="selectMatriculaBusqueda" name="selectMatriculaBusqueda" onchange="obtenerPreinscritos()" style="width:100px" class="cajas">
            	<option value="0">Matrícula</option>
                <option <?=$matricula==1?'selected="selected"':''?> value="1">Con matrícula</option>
                <option <?=$matricula==2?'selected="selected"':''?> value="2">Sin matrícula</option>
            </select>
        
         </th>
        <th class="encabezadoPrincipal" >Correo </th>
        <th class="encabezadoPrincipal" >Teléfono</th>
        <th class="encabezadoPrincipal" width="35%">Acciones</th>
    </tr>

<?php
$i	= 1;
$c	= $inicio;
foreach ($clientes as $row)
{
    $estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';

    ?>
        <tr <?php echo $estilo?>>
            <td align="right"> <?php echo $c?> </td>
            <td align="left" onclick="fichaTecnicaCliente(<?=$row->idCliente?>)"> <?php echo '<a>'.$row->prospecto.'</a>';?> </td>
            <td align="left">  <?php echo $row->programa ?> </td>
            <td align="left">  <?php echo $row->campana ?> </td>
            <td align="left">  <?php echo $row->mes ?> </td>
            <td align="left">  <?php echo $row->periodo ?> </td>
            <td align="left">  <?php echo $row->promotor ?> </td>
            
            <td align="left">  <?php echo $row->matricula ?> </td>
             
            <td align="center" style="display:none"> 
            <?php 
            echo'<span><div style="background-color: '.$row->color.'" class="circuloStatus"></div>
            <i style="font-weight:100">'.$row->status.'
            
            <br />'.obtenerFechaMesCorto($row->fechaSeguimiento).'<br />
            Entre '.obtenerHora($row->horaInicial,0).' y '.obtenerHora($row->horaFinal,0).'</i></span>';
            
             ?>
            
            </td>
            
            <td align="left" title="<?php echo $row->email ?>">  <?php echo substr($row->email,0,20) ?> </td>
            <td align="left">  <?php echo $row->telefono.' '.$row->movil ?> </td>
            
            
            <td align="center" valign="middle">
                <?php
                echo '
				
				<img src="'.base_url().'img/pagos.png" id="btnCobros'.$i.'" width="22" height="22" title="Cobros" style="cursor:pointer" onclick="formularioCobrosPreinscritos('.$row->idCliente.')" />
                &nbsp;&nbsp;&nbsp;&nbsp;
				
				 <img src="'.base_url().'img/'.(strlen($row->matricula)>0?'matriculaEditada':'matricula').'.png" id="btnMatricula'.$i.'" width="22" height="22" title="Matrícula" style="cursor:pointer" onclick="formularioMatricula('.$row->idCliente.')" />
                &nbsp;&nbsp;
				
                <img src="'.base_url().'img/estadocuenta.png" id="btnEstadoCuenta'.$i.'" width="22" height="22" title="Estado de cuenta" style="cursor:pointer" onclick="obtenerEstadoCuenta('.$row->idCliente.')" />
                &nbsp;&nbsp;
                    
                
				
                
                &nbsp;
                <img id="btnCrm'.$i.'" src="'.base_url().'img/crm.png" width="22" height="22" border="0" title="Seguimiento" style="cursor:pointer" onclick="obtenerSeguimientoCliente('.$row->idCliente.')" />';

                //DE MOMENTO LOS PROYECTOS QUEDARAN PENDIENTES
                echo '
                <!--&nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnProyectos'.$i.'" src="'.base_url().'img/notas.png" width="22" height="22" title="Proyectos" style="cursor:pointer" onclick="obtenerProyectos('.$row->idCliente.')" />-->
            
                 &nbsp;&nbsp;&nbsp;
                <img id="btnFicheros'.$i.'" src="'.base_url().'img/'.($row->numeroArchivos>0?'ficheroCargado':'fichero').'.png" width="22" height="22" title="Ficheros" style="cursor:pointer" onclick="obtenerFicheros('.$row->idCliente.')" />
                
                &nbsp;&nbsp;
                <img id="btnEditar'.$i.'" style="cursor:pointer" src="'.base_url().'img/editar.png" width="22" height="22" title="Editar cliente" alt="Editar cliente" onclick="accesoEditarCliente('.$row->idCliente.')" />
                
             
			 
               
                &nbsp;&nbsp;&nbsp;
                <img id="btnBorrar'.$i.'" onclick="accesoBorrarCliente('.$row->idCliente.')" src="'.base_url().'img/borrar.png" width="22" height="22" border="0" title="Borrar Cliente" alt="Borrar Cliente" />
                
                <br />';

                echo'
				<a>Cobros</a>
				<a>Matrícula</a>
				<a>Edo. cta.</a>
               
                <a id="a-btnCrm'.$i.'">CRM</a>
                <a id="a-btnFicheros'.$i.'">Ficheros</a>
                
                <a id="a-btnEditar'.$i.'">Editar</a>

                <a id="a-btnBorrar'.$i.'">Borrar</a>';
                
                if($permiso[1]->activo==0)
                {
                    echo '
                    <script>
                        desactivarBotonSistema(\'btnFicheros'.$i.'\');
                    </script>';
                }
                

                if($permisoCrm[0]->activo==0)
                {
                    echo '
                    <script>
                        desactivarBotonSistema(\'btnCrm'.$i.'\');
                    </script>';
                }
                
                if($permiso[2]->activo==0)
                { 
                    echo '
                    <script>
                        desactivarBotonSistema(\'btnEditar'.$i.'\');
                    </script>';
                }
                
                if($permiso[3]->activo==0)
                { 
                    echo '
                    <script>
                        desactivarBotonSistema(\'btnBorrar'.$i.'\');
                    </script>';
                }

            ?>
        </td>
    </tr>
    
    <?php
    $i++;
    $c++;
 }
?>

</table>


<?php
echo'
<div style="width:90%; margin-bottom:1%;">
    <ul id="pagination-digg" class="ajax-pagPreinscritos">'.$this->pagination->create_links().'</ul>
</div>';

?>
<script src="<?php echo base_url()?>js/recursos.js"></script>
<script src="<?php echo base_url()?>js/administracion/horarios.js"></script>

<script src="<?php echo base_url()?>js/administracion/personal/documentos.js"></script>
<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUploadDocumentos.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">

 <table class="toolbar" width="100%" >
    <tr>
    
     <?php
		echo'
		<td align="center" valign="middle" style="border:none">
			<a id="btnRegistrarPersonal" class="toolbar" onclick="formularioPersonal()">
				<img src="'.base_url().'img/gastos.png" width="30px;" height="30px;" style="cursor:pointer;" title="Personal" />
				<br />
				Personal  
			</a>
		</td>';
		
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnRegistrarPersonal\');
			</script>';
		}
		?>

        <td width="55%" style="text-align:left">
            <input  type="text" class="cajas" id="txtBuscarPersonal" style="width:430px" placeholder="Buscar personal" /> 
            <?php
            if($idPersonal>0)
            {
                echo '<img onclick="window.location.href=\''.base_url().'administracion/recursosHumanos\'" src="'.base_url().'img/quitar.png" title="Borrar busqueda" class="borrarBusqueda" />';
            }
          ?>   
        </td>
	</tr>
</table>
 </div>
</div>
<div class="listproyectos">
<div id="procesandoPersonal"></div>
<?php
if($personal!=null)
{
	$i=$inicio;
	
	echo '
	<div style="margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal" width="8%">Fotografia</th>
			<th class="encabezadoPrincipal" width="20%">Nombre</th>
			<th class="encabezadoPrincipal">Puesto</th>
			<th class="encabezadoPrincipal">Correo</th>
			<th class="encabezadoPrincipal">Celular</th>
			<th class="encabezadoPrincipal">Estatus</th>
			<th class="encabezadoPrincipal" width="18%">Acciones</th>
		</tr>';
	
	foreach($personal as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td align="center">';
			
			$imagen="img/personal/default.png";
			
			if(file_exists("img/personal/".$row->idPersonal.'_'.$row->fotografia))
			{
				$imagen="img/personal/".$row->idPersonal.'_'.$row->fotografia;
			}
			
			echo '<img src="'.base_url().$imagen.'" style="max-height:80px; max-width:80px;" />';
			echo'
			</td>
			<td>'.$row->nombre.'</td>
			<td>'.$row->puesto.'</td>
			<td>'.$row->email.'</td>
			<td>'.$row->celular.'</td>
			<td>'.$row->estatus.'</td>
			
			<td align="center">
				<img  id="btnEditar'.$i.'" onclick="obtenerPersonal('.$row->idPersonal.')" src="'.base_url().'img/editar.png" style="height:22px; width:22px;" />
				&nbsp;
				
				
				<img id="btnHorariosUsuario'.$i.'" onclick="obtenerHorarios('.$row->idPersonal.')" src="'.base_url().'img/horarios.png" width="22" height="22" title="Horarios">
				 &nbsp;&nbsp;
				 
				 '.($row->numeroAcceso>5?'
				<a id="btnTarjeta'.$i.'" target="_blank" href="'.base_url().'administracion/generarTarjeta/'.$row->idPersonal.'">
				<img  src="'.base_url().'img/tarjeta.png" width="22" height="22" title="Tarjeta"></a>&nbsp;&nbsp;':'').'

				&nbsp;
				
				<a id="btnBorrar'.$i.'" onclick="borrarPersonal('.$row->idPersonal.',\'¿Realmente desea borrar el registro?\')">
					<img src="'.base_url().'img/borrar.png" style="height:22px; width:22px;" />	
				</a>
				<br />

				<a id="a-btnEditar'.$i.'">Editar</a>
				 '.($row->numeroAcceso>5?'<a id="a-btnHorariosUsuario'.$i.'">Horarios</a>':'').'
				<a id="a-btnTarjeta'.$i.'">Tajerta</a>
				<a id="a-btnBorrar'.$i.'">Borrar</a>
			</td>
			
		</tr>';
		
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnHorariosUsuario'.$i.'\');
				desactivarBotonSistema(\'btnTarjeta'.$i.'\');
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
		
		$i++;
	}
	
	echo '</table>';
	
	if($registros>12)
	{
		echo'
		<div>
			<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
		</div>';
	}
	
}
else
{
	echo '<div class="Error_validar">Sin registro de personal</div>';
}
?>

<!-- OTROS INGRESOS-->
<div id="ventanaPersonal" title="Personal">
    
    <div id="formularioPersonal"></div>
</div>

<div id="ventanaDepartamentos" title="Agregar departamento">
    <div style="width:100%;" id="agregandoDepartamento"></div>
    <div id="formularioDepartamentos"></div>
</div>

<div id="ventanaPuestos" title="Agregar puesto">
<div style="width:100%;" id="agregandoPuesto"></div>
<div id="formularioPuestos"></div>																			
</div>

<div id="ventanaEstatus" title="Agregar estatus">
    <div id="registrandoEstatus"></div>
    <div id="formularioEstatus"></div>
</div>


<div id="ventanaEditarPersonal" title="Editar personal">
<div style="width:100%;" id="editandoPersonal"></div>
<div id="obtenerPersonal"></div>
</div>

<div id="ventanaHorarios" title="Horarios">
	<div id="procesandoHorarios"></div>
    <div id="obtenerHorarios"></div>
</div>



</div>
</div>


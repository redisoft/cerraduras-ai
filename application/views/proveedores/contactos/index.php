<script type="text/javascript" src="<?php echo base_url()?>js/proveedores/proveedores.js"></script>
<div class="derecha">

<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
  
    <table class="toolbar" border="0" width="33%">
       
       <?php
	   
	   if($idProveedor>0)
	   {
		   echo '
			<tr>
				<td align="center" style="border:none" width="10%" >
					<a id="btnCompras" href="'.base_url().'compras/index/'.$idProveedor.'" class="toolbar" >
						<img src="'.base_url().'img/compras.png" width="30px" title="Compras" /> <br />
						Compras
					</a>
			   </td>
			   
			   <td align="center" style="border:none" width="10%" >
					<a id="btnContactos" >
						<img src="'.base_url().'img/contactos.png" width="30px" title="Contactos" /> <br />
						Contactos
					</a>
			   </td>
			   
			   <td align="center" style="border:none" width="10%" >
					<a class="toolbar" onclick="obtenerFichaTecnicaProveedor('.$idProveedor.')" >
						<img src="'.base_url().'img/fichaTecnica.png" width="30px" title="Ficha técnica" /> <br />
						Ficha técnica
					</a>
			   </td>
			   
			</tr>';
			
			echo '
			<script>
				desactivarBotonSistema(\'btnContactos\');
			</script>';
			
			if($materiales[0]->activo==0 and $productos[0]->activo==0 and $inventarios[0]->activo==0 and $servicios[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnCompras\');
				</script>';
			}
	   }
	   ?>
       
    </table>
  
</div>

<table style="width:10%">
	<tr>
    	<td align="center">
             <img onclick="formularioContactos()" id="btnRegistrarContacto" src="<?php  print(base_url()); ?>img/add.png" width="32" height="32" title="Agregar contacto" style="cursor:pointer" />
            <br />
            <a>Agregar</a>
        </td>
    </tr>
   
</table>

<div class="listproyectos">
<?php

if($permiso[1]->activo==0)
{ 
	echo '
	<script>
		desactivarBotonSistema(\'btnRegistrarContacto\');
	</script>';
}

if(!empty($contactos))
{
	?>
	<table class="admintable" width="100%">
	<tr>
	  <th class="encabezadoPrincipal" align="left">#</th>
		<th class="encabezadoPrincipal" align="left">Nombre</th>
		<th class="encabezadoPrincipal" align="left">T&eacute;lefono</th>
		<th class="encabezadoPrincipal" align="left">Email</th>
		<th class="encabezadoPrincipal" align="left">Departamento</th>
		<th class="encabezadoPrincipal" align="left">Extensi&oacute;n</th>
		<th class="encabezadoPrincipal">Acciones</th>
	</tr>
	
	<?php
	$i=1;
	foreach ($contactos as $row)
	{
		$estilo=$i%2>0?'class="sombreado" ':'class="sinSombra"';
		?>    
   		<tr <?php echo $estilo?>>
            <td align="left"><?php echo $i?></td>
            <td align="left"> <?php echo $row->nombre ?> </td>
            <td align="left"> <?php echo $row->telefono ?> </td>
            <td align="left"> <?php echo $row->email ?> </td>
            <td align="left"><?php echo $row->departamento ?></td>
            <td align="left"> <?php echo $row->extension ?> </td>
            <td align="center" valign="middle">
                <img id="btnEditar<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar contacto" onclick="obtenerContactoEditar(<?php echo $row->idContacto?>)" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 
                <img id="btnBorrar<?php echo $i?>" src="<?php echo base_url()?>/img/borrar.png" width="22" onclick="borrarContactoProveedor(<?php echo $row->idContacto?>,'¿Realmente desea borrar el contacto?','<?php echo $idProveedor?>')" height="22" />
                <br />
                <a id="a-btnEditar<?php echo $i?>">Editar</a> &nbsp;
                <a id="a-btnBorrar<?php echo $i?>">Borrar</a>
            </td>
		</tr>
	
		<?php
		
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
	?>
	</table>
	
	
	
	<?php
}
else
{
	echo'<br><br>
	<div class="Error_validar" style="width:95%;">No hay registros de contactos </div>';
}
?>
</div>

<div id="ventanaRegistrarContacto" title="Contactos de proveedores">
<div id="registrandoProveedor"></div>
<div id="errorAgregarContacto" class="ui-state-error" ></div>
<table class="admintable" width="100%;">
    <tr>
        <td class="key">Nombre:</td>
        <td>
            <input type="text" name="T1" id="T1" class="cajas" style="width:220px;" /> 
        </td>
    </tr>
    
     <tr>
        <td class="key">Departamento:</td>
        <td>
            <input name="T4" type="text" class="cajas" id="T4" style="width:220px;" /> 
        </td>
    </tr>
    
    <tr>
        <td class="key">Tel&eacute;fono:</td>
        <td>
            <input type="text" name="T2" id="T2" class="cajas" style="width:220px;" />
            <input type="hidden" name="txtIdProveedor" id="txtIdProveedor" value="<?php echo $idProveedor?>"  />
         </td>
    </tr>
    
    <tr>
        <td class="key">Extension:</td>
        <td>
            <input type="text" name="extension" id="extension" class="cajas" style="width:220px;">
        </td>
    </tr>
    
    <tr>
        <td class="key">Email:</td>
        <td>
            <input type="text" name="T3" id="T3" class="cajas" style="width:220px;"  /> 
        </td>
    </tr>
    
   
    
</table>
</div>

<div id="ventanaEditarContacto" title="Editar contacto">
<div style="width:99%;" id="editandoContacto"></div>
<div id="errorEditarContacto" class="ui-state-error" ></div>
<div id="obtenerContacto"></div>
</div>

</div>

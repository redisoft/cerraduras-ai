<script src="<?php echo base_url()?>js/configuracion/usuarios.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/configuracion/horarios.js?v=<?=rand()?>"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
   <!--<div class="seccionDiv">
    Lista de usuarios
    </div>-->

     <table class="toolbar" width="100%">
        <tr>
            <td width="5%" align="center" valign="middle"><a  href="<?php print(base_url()."configuracion/"); ?>" > <span class="icon-option" title="Configuración de Sistema"> <img src="<?php print(base_url()); ?>img/configure.png"  width="30" height="30" border="0" title="Configuración de Sistema" /> </span> Sistema </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/listauser"); ?>" class="escalaGrisesConfiguracion" > <span class="icon-option" title="Lista de usuarios"> <img src="<?php print(base_url()); ?>img/clientes.png"   width="30" height="30" title="Lista de usuarios" /></span> Usuarios </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/roles"); ?>" > <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/roles.png"   width="30" height="30" title="Roles" /></span> Roles </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos"); ?>" > <span class="icon-option" title="Banco"> <img src="<?php print(base_url()); ?>img/banco.png"   width="30" height="30" title="Banco" /></span> Banco </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos/cuentas"); ?>" > <span class="icon-option" title="Cuentas"> <img src="<?php print(base_url()); ?>img/dinero.png"   width="30" height="30" title="Cuentas" /></span> Cuentas </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/unidades"); ?>" > <span class="icon-option" title="Unidades"> <img src="<?php print(base_url()); ?>img/bascula.png"   width="30" height="30" title="Unidades" /></span> Unida. </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/zonas"); ?>" > <span class="icon-option" > <img src="<?php print(base_url()); ?>img/zona.png" width="30" height="30" title="<?php echo $this->session->userdata('identificador')?>" /></span> <?php echo $this->session->userdata('identificador')?> </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/facturacion"); ?>"><span class="icon-option" title="FEL"><img src="<?php print(base_url()); ?>img/fel.png"  width="30" height="34" title="Facturación electronica"/></span>Emiso.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/impuestos"); ?>" ><span class="icon-option" title="Impuestos"><img src="<?php print(base_url()); ?>img/impuestos.png"  width="30" height="34" title="Impuestos"/></span>Impuestos</a></td>
             <?php
            if(sistemaActivo=='IEXE')
			{
				?>
                <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/programasComisiones"); ?>" ><span class="icon-option" title="Comisiones"><img src="<?php print(base_url()); ?>img/comisiones.png"  style="max-width: 30px; max-height: 30px" title="Comisiones"  /></span>Comisiones</a></td>
                <?php
			}
			?>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/procesos"); ?>" ><span class="icon-option" title="Estilo"><img src="<?php print(base_url()); ?>img/produccion.png"  width="30" height="34" title="Procesos"  /></span>Procesos</a></td>
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/divisas"); ?>" ><span class="icon-option" title="Divisas"><img src="<?php print(base_url()); ?>img/divisas.jpg"  width="30" height="34" title="Divisas"  /></span>Divisas</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/catalogosContables"); ?>" ><span class="icon-option" title="Catálogos contables"><img src="<?php print(base_url()); ?>img/engranes.png"  width="30" height="34" title="Catálogos contables"  /></span>Cat. Cont.</a></td> 
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/lineas"); ?>" ><span class="icon-option" title="Líneas"><img src="<?php print(base_url()); ?>img/lineas.png"  width="30" height="34" title="Lineas"  /></span>Líneas</a></td>      
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/servicios"); ?>" ><span class="icon-option" title="Servicios"><img src="<?php print(base_url()); ?>img/servicios.png"  width="30" height="34" title="Servicios"  /></span>Servi.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/formasPago"); ?>" ><span class="icon-option" title="Formas de pago"><img src="<?php print(base_url()); ?>img/formas.png"  width="30" height="34" /></span>F. Pago</a></td>
            <!--td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."tiendas"); ?>" ><span class="icon-option" title="Tiendas"><img src="<?php print(base_url()); ?>img/tienda.png"  width="30" height="34" title="Tiendas"  /></span>Tiendas</a></td-->
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/categorias"); ?>" ><span class="icon-option" title="Categorías"><img src="<?php print(base_url()); ?>img/categorias.png"  width="30" height="34" title="Categorías"  /></span>Categorías</a></td>
<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."estaciones"); ?>" ><span class="icon-option" title="Estaciones"><img src="<?php print(base_url()); ?>img/estaciones.png"  width="30" height="34" title="Estaciones"  /></span>Estaciones</a></td>           
        </tr>
    </table>
    </div>
</div>

<div class="listproyectos">

<table class="toolbar" width="80%">
    <tr>
        <td style="border:none"  align="center" valign="middle" class="button">
            <a class="icon-option" id="btnRegistrarUsuario" title="Añadir cliente" style="cursor:pointer" onclick="formularioUsuarios()">
                <img src="<?php echo base_url() ?>img/add.png" title="Añadir cliente" /> <br />
                Añadir usuario
            </a>
			<?php
           	if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarUsuario\');
				</script>';
			}
           ?>
       </td>
       
       <td align="center" >
            <input type="text"  name="txtCriterio" id="txtCriterio" class="busquedas" placeholder="Buscar por nombre, usuario"  style="width:400px;"/>
        </td>
    </tr>
  </table>
  

<div id="procesandoUsuarios"></div>
<div id="obtenerUsuarios">
	 <input type="hidden"  name="selectRolBusqueda" id="selectRolBusqueda" value="0"/>
</div>

<div id="ventanaEditarUsuario" title="Editar usuario">
<div style="width:100%;" id="editandoUsuario"></div>
<div id="errorEditarUsuario" class="ui-state-error" ></div>
<div id="obtenerUsuario"></div>
</div>

<div id="ventanaRegistrarUsuario" title="Registrar usuario">
<div style="width:100%;" id="registrandoUsuario"></div>
<div id="errorAgregarUsuario" class="ui-state-error" ></div>
<div id="formularioUsuario"></div>
</div>

<div id="ventanaHorarios" title="Horarios">
	<div id="procesandoHorarios"></div>
    <div id="obtenerHorarios"></div>
</div>


</div>
</div>

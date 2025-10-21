<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
    <!--<div class="seccionDiv">
    	Estilo
    </div>-->
 	<table class="toolbar" width="100%">
        <tr>
            <td width="5%" align="center" valign="middle"><a  href="<?php print(base_url()."configuracion/"); ?>" > <span class="icon-option" title="Configuración de Sistema"> <img src="<?php print(base_url()); ?>img/configure.png"  width="30" height="30" border="0" title="Configuración de Sistema" /> </span> Sistema </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/listauser"); ?>" > <span class="icon-option" title="Lista de usuarios"> <img src="<?php print(base_url()); ?>img/clientes.png"   width="30" height="30" title="Lista de usuarios" /></span> Usuarios </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/roles"); ?>" > <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/roles.png"   width="30" height="30" title="Roles" /></span> Roles </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos"); ?>" > <span class="icon-option" title="Banco"> <img src="<?php print(base_url()); ?>img/banco.png"   width="30" height="30" title="Banco" /></span> Banco </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos/cuentas"); ?>" > <span class="icon-option" title="Cuentas"> <img src="<?php print(base_url()); ?>img/dinero.png"   width="30" height="30" title="Cuentas" /></span> Cuentas </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/unidades"); ?>" > <span class="icon-option" title="Unidades"> <img src="<?php print(base_url()); ?>img/bascula.png"   width="30" height="30" title="Unidades" /></span> Unida. </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/zonas"); ?>" > <span class="icon-option" > <img src="<?php print(base_url()); ?>img/zona.png" width="30" height="30" title="<?php echo $this->session->userdata('identificador')?>" /></span> <?php echo $this->session->userdata('identificador')?> </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/facturacion"); ?>"><span class="icon-option" title="FEL"><img src="<?php print(base_url()); ?>img/fel.png"  width="30" height="34" title="Facturación electronica"/></span>Emiso.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/impuestos"); ?>" ><span class="icon-option" title="Impuestos"><img src="<?php print(base_url()); ?>img/impuestos.png"  width="30" height="34" title="Impuestos"/></span>Impuestos</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/estilo"); ?>" class="escalaGrisesConfiguracion"><span class="icon-option" title="Estilo"><img src="<?php print(base_url()); ?>img/estilo.png"  width="30" height="34" title="Estilo"  /></span>Estilo</a></td>
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

<div class="listproyectos" >

<form  name="form" id="form" action="<?php echo base_url().'configuracion/actualizarVigilancia'?>" method="post">
 <table class="admintable" width="100%" >
 <tr class="sombreado">
     <td class="key">Azul</td>
     <td title="Activar azul" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/Azul'">
     	<div style="width:80px; height:30px; background-color:#0284c0; float:left"></div>
         &nbsp;
        <?php
			if($this->session->userdata('estilo')=="Azul")
				echo "Activado";
        ?>
     </td>
</tr>
<tr class="sinSombra">
     <td class="key">Naranja</td>
     <td title="Activar naranja" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/Naranja'">
     	<div style="width:80px; height:30px; background-color:#db8223; float:left"></div>
         &nbsp;
         <?php
			if($this->session->userdata('estilo')=="Naranja")
				echo "Activado";
        ?>
     </td>
</tr>
<tr class="sombreado">
     <td class="key">Negro</td>
     <td title="Activar negro" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/Negro'">
     	<div style="width:80px; height:30px; background-color:#000000; float:left"></div>
         &nbsp;
         <?php
			if($this->session->userdata('estilo')=="Negro")
				echo "Activado";
        ?>
     </td>
</tr>

<tr class="sinSombra">
     <td class="key">Rojo</td>
     <td title="Activar rojo" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/Rojo'">
     	<div style="width:80px; height:30px; background-color:#CF1919; float:left"></div>
         &nbsp;
         <?php
			if($this->session->userdata('estilo')=="Rojo")
				echo "Activado";
        ?>
     </td>
</tr>

	<tr class="sombreado">
        <td  class="key">Verde hierba</td>
        <td title="Activar verde hierba" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/Verde'">
        <div style="width:80px; height:30px; background-color:#647505; float:left"></div>
        &nbsp;
        <?php
			if($this->session->userdata('estilo')=="Verde")
			echo "Activado";
        ?>
        </td>
	</tr>
    
    <tr class="sombreado">
        <td  class="key">Verde</td>
        <td title="Activar verde normal" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/VerdeNormal'">
        <div style="width:80px; height:30px; background-color:#108137; float:left"></div>
        &nbsp;
        <?php
			if($this->session->userdata('estilo')=="VerdeNormal")
			echo "Activado";
        ?>
        </td>
	</tr>
    
    <tr class="sombreado">
        <td  class="key">#bf80ff</td>
        <td title="Activar bf80ff" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/bf80ff'">
        <div style="width:80px; height:30px; background-color:#bf80ff; float:left"></div>
        &nbsp;
        <?php
			if($this->session->userdata('estilo')=="bf80ff")
			echo "Activado";
        ?>
        </td>
	</tr>
    
    <tr class="sombreado">
        <td  class="key">Rosa</td>
        <td title="Activar rosa" onclick="window.location.href='<?php echo base_url()?>configuracion/activarEstilo/Rosa'">
        <div style="width:80px; height:30px; background-color:#F9A6CC; float:left"></div>
        &nbsp;
        <?php
			if($this->session->userdata('estilo')=="Rosa")
			echo "Activado";
        ?>
        </td>
	</tr>
   
   
    
</table>
 </form>
 </div>

</div>






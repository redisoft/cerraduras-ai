<div class="arriba">
    <div class="barraMenu" id="barraTop">
    	<img src="<?php echo base_url()?>img/menuBarra/desconectado-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/desconectado.png" style="display:none"  />
        
        <img src="<?php echo base_url()?>img/menuBarra/ayuda.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/ayuda-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/configuracion.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/configuracion-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/conectado.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/conectado-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/email.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/email-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/manual.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/manual-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/recargar.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/recargar-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/salir-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/salir.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/tutorial-b.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/tutorial.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/usuario.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/usuario-b.png" style="display:none"  />
        
        <img src="<?php echo base_url()?>img/menuBarra/conectados.png" style="display:none"  />
        <img src="<?php echo base_url()?>img/menuBarra/conectados-b.png" style="display:none"  />
         

        
        <ul class="menuBarra" >
        	
            <div class="col-md-3 text-left" style="color:#FFF; font-size:1.5vh; padding-top:0.8vh ">
              
                    <?php echo obtenerFechaMesLargo(date('Y-m-d H:i:s'))?>
                
            </div>
            
            <div class="col-md-5 text-center" style="color:#FFF; font-size:1.5vh; padding-top:0.8vh ">
                
                    <?php echo $this->configuracion->obtenerNombreEmpresa()?>
                
            </div>
            
            <!--<div class="col-md-1 text-center"></div>-->
        
        <div class="col-md-4 text-center" >
        <li id="menuUsuarioRegistrado" class="usuarioRegistrado">
        	
        	<?php echo  substr($this->session->userdata('nombreUsuarioSesion'),0,15)?>
            
            <ul>
            	<?php
				if($this->session->userdata('idTiendaActiva')==0 and $this->session->userdata('usuarioActivo')!='conta')
				{
					?>
                    <li title="Configuración" id="menuConfiguracion" onclick="window.location.href='<?php echo base_url()?>configuracion'" class="configuracion" <?php if ($this->session->userdata('checador')=='1' or $this->session->userdata('reportes')=='1') echo 'style="display:none"';?>>Configuración</li>
                    <?php
				}
				?>
				
                <li title="Salir del sistema" id="menuSalir" onclick="window.location.href='<?php echo base_url()?>login/logout'" class="salir">Salir</li>
            </ul>
        </li>
        	<!-- elementos removidos -->
            <!-- <li class="ayuda" ... > ... -->
            <!-- <li id="menuRecargar" ... > -->
            <!-- <li id="menuDesconectado" ... > -->
            <!-- <li class="conectados" ... > -->
            <?php

            </li>
			
			<?php
			if($this->session->userdata('tiendaLocal')=='1' and $this->session->userdata('rol')=='1')
			{
				echo '
				<li id="menuRecargar" onclick="formularioSincronizacion(0)" title="Actualizar hacia el servidor" class="nube"></li>
				<li id="menuRecargar" onclick="formularioSincronizacion(1)" title="Actualizar desde el servidor" class="local"></li>';
			}
			?>
            
            </div>
            
        </ul>
    </div>
	<div class="barraAuxiliar" id="barraOffline">
		<div class="contenedorOffline">
			<span id="btnInstalarPWA" class="boton-instalar-pos" title="Instalar aplicación" style="display:none">Instalar app</span>
			<span id="btnSincronizarPOS" class="boton-sync-pos" title="Sincronizar catálogo y ventas pendientes">Sincronizar</span>
			<span id="btnPendientesPOS" class="boton-pendientes-pos" title="Ver ventas guardadas sin conexión">Pendientes</span>
			<span id="estadoConexion" class="estado-conexion">Conectado</span>
		</div>
	</div>
</div>



<div class="header" style="height:0.2vh"></div>
<div class="main">

<!-- Main -->

<div class="cuerpo">
<!-- cuerpo -->

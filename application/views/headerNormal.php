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
         
        
    	<!--<img src="<?php echo base_url()?>img/menuBarra/recargar.png" title="Recargar" />
        <img src="<?php echo base_url()?>img/menuBarra/ayuda.png" title="Ayuda" />-->
        
        <ul class="menuBarra" >
        	
            <span style="float:left; color:#FFF; font-size:14px; margin-left:6px; margin-top:3px">
            	
                <?php echo obtenerFechaMesLargo(date('Y-m-d H:i:s'))?>
            </span>
        
        <li id="menuUsuarioRegistrado" class="usuarioRegistrado">
        	
        	<?php echo  substr($this->session->userdata('nombreUsuarioSesion'),0,15)?>
            
            <ul>
            	<?php
				if($this->session->userdata('idTiendaActiva')==0)
				{
					?>
                    <li title="Configuración" id="menuConfiguracion" onclick="window.location.href='<?php echo base_url()?>configuracion'" class="configuracion" <?php echo $this->session->userdata('checador')=='1'?'style="display:none"':''?>>Configuración</li>
                    <?php
				}
				?>
				
                <li title="Salir del sistema" id="menuSalir" onclick="window.location.href='<?php echo base_url()?>login/logout'" class="salir">Salir</li>
            </ul>
        </li>
        	<li class="ayuda" title="Ayuda">
            
            	<ul>
                    <li id="menuEmail" class="email">Email</li>
                    <!--<li id="menuTutorial" class="tutorial">Tutorial</li>-->
                    <li onclick="window.open('https://redisoftsystems.zendesk.com/hc/es')" id="menuManual" class="manual">Manual</li>
                </ul>
                
            </li>
            <li id="menuRecargar" onclick="recargarPagina()" title="Recargar" class="recargar"></li>
            <li id="menuDesconectado" class="conectado" title="Conectado a internet"></li>
            
            <li class="conectados" title="Usuarios conectados">
            	<?php
                if(isset($conectados))
				{
					$i=0;
					echo '
					<ul>';
						
						foreach($conectados as $row)
						{
							echo' <li '.($i==0?'class="primerUsuario"':'').' title="'.$row->usuario.'">'.substr($row->usuario,0,15).'</li>';
							
							$i++;
						}
					
					echo'</ul>';
				}
				?>
            	
            </li>
            
        </ul>
    </div>
    

	<div  ><!--style="width:100px; height:40px; position:absolute; background-color: rgba(0, 0, 0, 0.3); "-->
    	<?php
        if(file_exists('img/logos/'.$this->session->userdata('logotipo')) and strlen($this->session->userdata('logotipo'))>5)
		{
			echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="width:100px; height:40px; position:absolute; margin-top:2px " />';
		}
		?>
		
    </div>
<div class="titulitoEncabezado" style="padding-bottom:6px; font-weight:100">
Panel de Administración
</div>

<div class="imagenEncabezado">
	<?php #print($nameusuario.'@'.$this->session->userdata('nombreEmpresa'));?> <?php #print($Fecha_actual); ?>
    &nbsp;&nbsp;&nbsp;
    <!--<a title="Salir" href="<?php echo base_url()?>login/logout">
    	<img src="<?php echo base_url()?>img/salir.png" style="width:25px; height:25px; float:right" />
    </a>-->
</div>

</div>

<div class="header"></div>
<div class="main">

<!-- Main -->

<div class="cuerpo">
<!-- cuerpo -->

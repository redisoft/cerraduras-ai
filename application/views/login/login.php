
<!DOCTYPE HTML>
	<html>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.7">
    <head>
    	<title>
    		<?php echo $estilo->nombre?>
    	</title>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login/login.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adminLte.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap/bootstrap.css" />
        <script src="<?php echo base_url()?>js/jsruta.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>js/jquery/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>js/jquery/jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>js/bootstrap/bootstrap.js"></script>
        <script>window.base_url = '<?php echo base_url()?>';</script>
        
        <script type="text/javascript" src="<?php echo base_url()?>js/bibliotecas/jquery.disableAutoFill.js"></script>
        <script>
			
			$(document).ready(function()
			{
				$('#username').focus();
				
			});
			
			
		</script>
        
         </head>
    
    <body style="background-color:#EEE; background-image: url(<?php echo base_url()?>img/bgpagina.jpg);  background-size: cover;">
	
	<div class="container-fluid"  >
    	<form id="acceso" name="acceso" action="<?php echo base_url()?>login/acceso" method="post" >
        
		<div class="row">
    		<div class="col-md-12">
            </div>
    	</div>
        
        <div class="row">
    		<div class="col-md-12 text-center">
            	<img src="<?php echo base_url()?>img/redisoft.png" style="max-width: 20vh; max-height: 20vh;" />
            </div>
    	</div>
        
        <div class="row">
    		<div class="col-md-12">
            </div>
    	</div>
        
        <!--<div class="row">
    		<div class="col-md-12" style="font-size:26px; olor:#FFF; font-weight:normal">
            
            	Si buscas <strong>resultados distintos,</strong> <br />
    			usa <strong>Redisoft Systems</strong>
            </div>
    	</div>-->
        
        <div class="row">
        
        	<div class="col-md-4"></div>
            
            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Acceso al sistema</h3>
                    </div>
               			
                        <div class="col-md-12" style="font-size:1.5vh">
                           <br>
                        </div>

                    <div class="col-md-12" style="font-size:2vh">
                            <?=$estilo->nombre?> | <?=$estacion->nombre?> <span id="estadoPwa" class="estado-pwa"></span>
                        </div>
                      
                      
                      <div class="col-md-12" style="font-size:1.5vh">
                           <br>
                        </div>
                      
                    
                        <div class="col-md-12" style="font-size:1.5vh">
                            Usuario
                        </div>
                    
                    	
                    
                        <div class="col-md-12 text-center">
                        	<input type="hidden"  class="form-control" id="selectSucursal" name="selectSucursal" value="<?=$estilo->idLicencia?>"/>
                            <input type="text"  class="form-control" id="username" name="username"  required="true" tabindex="1"  />
                        </div>
                    
                    
                    	<div class="col-md-12" style="font-size:1.5vh">
                           <br>
                        </div>
                    
                        <div class="col-md-12" style="font-size:1.5vh">
                             Contrase&#241;a:
                        </div>
                    
                        <div class="col-md-12 text-center">
                            <input type="password" class="form-control"  id="password" name="password" required="true"/>
                        </div>
                    
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <br>
                        </div>
                    </div>
                    
                   
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-warning" >Aceptar</button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <br>
                        </div>
                    </div>

                    <div class="barraInstalacion">
                        <button type="button" id="btnInstalarLogin" class="btn-instalar-login">Instalar App</button>
                        <div id="instalacionProceso"><p>Preparando instalador...</p></div>
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger"  onclick="window.location.href='<?php echo base_url().'instalacion'?>'"/>Cambiar estación</button>
                    </div>
					
				<!--div class="row">
                        <div class="col-md-12 text-center">
                            <br>
                        </div>
                    </div>
				
					<div class="col-md-12 text-center">
                        <button type="button" class="btn btn-info"  onclick="window.location.href='http://redisoftserver.com/app/cordobita'"/>Acceso a sistema de Aceros y Metales de Cordobita SA de CV</button>
                    </div>-->
                    
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                             &nbsp;
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-4"></div>
            
            
        </div>
        
        <div class="row">
    		<div class="col-md-12 text-center">
            	<?php
                if(file_exists('img/logos/'.$estilo->id.'_'.$estilo->logotipo) and strlen($estilo->logotipo)>4)
				{
					echo '<img src="'.base_url().'img/logos/'.$estilo->id.'_'.$estilo->logotipo.'" style="max-width: 25vh; max-height: 25vh;" />';
				}
				?>
            </div>
    	</div>
        
        
   <!--<div class="formularioLogin"></div>-->

</form>

<script src="<?php echo base_url()?>js/loginInstall.js?v=<?=time()?>"></script>

    
    </div>
	</body>
</html>

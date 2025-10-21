
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
        <script>
			
			$(document).ready(function()
			{
				$('#username').focus();
			});
		</script>
        
         </head>
    
    <body style="background-color:#EEE; background-image: url(<?php echo base_url()?>img/bgpagina.jpg);  background-size: cover;">
	
	<div class="container-fluid"  >
    	<form id="acceso" name="acceso" action="<?php echo base_url()?>login/acceso" method="post">
        
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
                        
                        <div class="col-md-12" style="font-size:1.5vh">
                            Sucursal
                        </div>
                       <div class="col-md-12 text-center">
                           <select class="form-control" id="selectSucursal" name="selectSucursal"  required="true">
                           	   <?php
                               foreach($licencias as $row)
							   {
								   echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
							   }
							   ?>
                           </select>
                        </div>
                        
                        <div class="col-md-12" style="font-size:1.5vh">
                           <br>
                        </div>
                    
                        <div class="col-md-12" style="font-size:1.5vh">
                            Nombre
                        </div>
                        
                        <div class="col-md-12 text-center">
                            <input type="text"  class="form-control" id="username" name="username"  required="true" tabindex="1"/>
                        </div>
                    
                    
                    	<div class="col-md-12" style="font-size:1.5vh">
                           <br>
                        </div>
                    
                        <div class="col-md-12" style="font-size:1.5vh">
                             Contrase&#241;a:
                        </div>
                    
                        <div class="col-md-12 text-center">
                            <input type="password" class="form-control"  id="password" name="password" required="true" tabindex="2"/>
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

    
    </div>
	</body>
</html>

<!DOCTYPE HTML>
<html>
<meta charset="utf-8">
<head>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login/login.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bibliotecas/notificaciones.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bibliotecas/sha1.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/instalacion/instalacion.js?v=20241024"></script>
<script>window.base_url = '<?php echo base_url()?>';</script>
<script type="text/javascript" src="<?php echo base_url()?>js/loginInstall.js?v=20241024"></script>
<?php
require_once "application/libraries/ReCaptcha.php";
?>

<title>.: Zapaterias México :.</title>

</head>
<body>

<div class="main">

<script>

$(document).ready(function()
{
	instalacion				= '<?php echo $estilo->passwordTiendas?>';
	usuarioInstalacion		= '<?php echo $estilo->usuarioTiendas?>';
	base_url				= '<?php echo base_url()?>';
	
	$('#txtUsuario').focus();
	$('.barraInstalacion').show();
    if(localStorage.getItem('cerradurasPwaInstalada')==='1'){
        $('#instalacionCookieProceso').hide();
        $('#btnSimularInstalacion').hide();
    }
});

</script>

<div class="arriba">
    <div class="titulitoEncabezado" align="center">
    	<img style="margin-left:650px" src="<?php echo base_url()?>img/redisoft.png" />
    </div>
</div>

<div class="top">
</div>

<div id="div_login" align="right">
	<div class="header"  style="position:absolute; margin-left:800px; margin-top:9px; z-index:200">Instalación</div>

	<div style="font-size:26px; width:400px; float:left; color:#FFF; text-align:left; padding-top:130px; padding-left:150px; font-weight:normal">
    Si buscas <strong>resultados distintos,</strong> <br />
    usa <strong>Redisoft Systems</strong>
    </div>
    
    <div style="color:#FFF; font-size:18px; z-index:30; position:absolute; margin-left:823px; margin-top:43px">
    <form id="frmInstalacion" name="frmInstalacion" action="javascript:accesoInstalacion()">
    	 
          
        <table border="0" cellspacing="0" cellpadding="0" class="tablaFormulario">
            <tr>
                <td align="center">&nbsp;</td>
            </tr>
    		
            <tr align="center" class="textosRosa">
                <td>
                    Usuario:
                </td>
            </tr>
            
            <tr style="height:40px">
                <td align="center">
                  <input type="text" class="textosFormulario"  id="txtUsuario" name="txtUsuario" required="true"/>
                </td>
            </tr>
            
            <tr align="center" class="textosRosa">
                <td>
                    Contrase&#241;a:
                </td>
            </tr>
            
            <tr style="height:40px">
                <td align="center">
                  <input type="password" class="textosFormulario"  id="txtPassword" name="txtPassword" required="true"/>
                </td>
            </tr>
    
            <tr>
                <td style="border: solid 1px #666"></td>
            </tr>
            
            
            <tr>
                <td style="border: solid 1px #666">
                	
                    <div class="g-recaptcha" data-sitekey="<?php echo siteKey;?>"></div>
				  <script type="text/javascript"
                      src="https://www.google.com/recaptcha/api.js?hl=es">
                  </script>
                
                </td>
            </tr>
            
            <tr>
                <td align="center">
                  <div style="text-align:left; padding-left:7.0em; font-size:12px; color:#B10808; ">
                  </div>
                  <div style="text-align:center; font-size:12px; color:#B10808;">
                    
                    
                  </div>
                  
                </td>		
            </tr>		
            <tr>
                <td align="center" style="height:55px">
                    <input type="submit" class="aceptar" style="color:#000" id="btnAceptar" value="Aceptar"  form="frmInstalacion" >
                    
                    <?php
					#echo $cookie;
                    if($cookie=='estacion')
					{
						echo '<br /><input type="button" class="aceptar" id="btnCambiarEstacion"  style="color:#000" value="Cancelar" onclick="window.location.href=\''.base_url().'login\'" >';
					}
					
					?>
                    <div class="barraInstalacion" style="margin-top:1.5vh;">
                        <button type="button" id="btnSimularInstalacion" class="btn-instalar-login">Instalar App Cerraduras</button>
                        <div id="instalacionCookieProceso" style="display:none;"></div>
                    </div>
                </td>
            </tr>
    
            <tr>
                <td>
                    <?php
                    if(file_exists('img/logos/'.$estilo->id.'_'.$estilo->logotipo) and strlen($estilo->logotipo)>4)
                    {
                        echo '<img src="'.base_url().'img/logos/'.$estilo->id.'_'.$estilo->logotipo.'" style="margin-top:0px; max-width:187px; max-height:80px"  />';
                    }
                    ?>
                    
                </td>
            </tr>
                    
        </table>
    </form>
    </div>
    
    <div class="formularioLogin" style="float:right; z-index:-10; height:300px; margin-right:129px"></div>
</div>

<div class="footer">
</div>
</div>

</body>
</html>

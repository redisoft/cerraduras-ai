
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login/login.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bibliotecas/notificaciones.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bibliotecas/sha1.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/instalacion/instalacion.js"></script>

<title>.: Zapaterias México :.</title>

</head>
<body>

<div class="main">

<script>

$(document).ready(function()
{
	var es_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
	
	if(!es_firefox)
	{
		$('#btnAceptar').remove()
		$('#frmInstalacion').attr('action',null)
		
		notify('El sistema solo es compatible con Mozilla Firefox',500,6000,"error",55,8);
		
		
	}
	
	base_url	= '<?php echo base_url()?>';
	
	obtenerEstaciones()
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
	<div class="header"  style="position:absolute; margin-left:852px; margin-top:9px; z-index:200">Instalación</div>

	<div style="font-size:26px; width:400px; float:left; 
    color:#FFF; text-align:left; padding-top:130px; padding-left:150px; font-weight:normal">
    Si buscas <strong>resultados distintos,</strong> <br />
    usa <strong>Redisoft Systems</strong>
    </div>
    
    <div style="color:#FFF; font-size:18px; z-index:30; position:absolute; margin-left:870px; margin-top:60px">
    <form id="frmInstalacion" name="frmInstalacion" action="javascript:registrarEstacion()">
        <table border="0" cellspacing="0" cellpadding="0" class="tablaFormulario">
            <tr>
                <td align="center">&nbsp;</td>
            </tr>
    
            <tr align="center" class="textosRosa">
                <td>
                    Tienda
                </td>
            </tr>
            
            <tr style="height:40px">
                <td align="center">
                    <select class="textosFormulario"  id="selectTiendas" name="selectTiendas" style="width:170px" onchange="obtenerEstaciones()">
                    	
                        <option value="0">Matriz</option>
                        
                        <?php
                        foreach($tiendas as $row)
						{
							echo '<option value="'.$row->idTienda.'">'.$row->clave.', '.$row->nombre.'</option>';
						}
						?>
                    </select>
                </td>
            </tr>
            
            <tr align="center" class="textosRosa">
                <td>
                    Estación
                </td>
            </tr>
            
            <tr style="height:40px">
                <td align="center" id="obtenerEstaciones">
                    <select class="textosFormulario"  id="selectEstaciones" name="selectEstaciones" style="width:170px" required="true">
                    	<option value="0">Matriz</option>
                    </select>
                </td>
            </tr>
    
            <tr>
                <td style="border: solid 1px #666"></td>
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
    
    <div class="formularioLogin" style="float:right; z-index:-10; height:355px; margin-right:212px"></div>
</div>

<div class="footer">
</div>
</div>

</body>
</html>

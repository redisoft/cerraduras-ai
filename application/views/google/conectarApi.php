<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >
<div class="seccionDiv">
	Solicitud de permisos
</div>


<div class="listproyectos">
<?php
$cal 						= new Gcal();
$configObj['redirectURI']	= base_url().'principal/conectarApiGoogle';

$arreglo	= $cal->listCalendarList($configObj);

#var_dump($arreglo);
if(isset($arreglo->etag))
{
	if(strlen($arreglo->etag)>2)
	{
		echo '<br /><br />La solicitud ha sido correcta, espere un momento <img src="'.base_url().'img/loader.gif" />';

		echo '
		<script>
		$(document).ready(function()
		{
			window.setTimeout(function() 
			{
				location.href=\''.base_url().'principal/tableroControl\'
			}, 7000);  
		});
		</script>';
	}
}
else
{
	echo '<br /><br />Imposible conectar con el calendario de google en este momento';
}
#echo $arreglo->etag;



?>

</div>
</div>
</div>
</div>
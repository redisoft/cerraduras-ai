<?php
$entrada	="Pendiente";
$salida		="Pendiente";

if($personal!=null)
{
	$completo		='0';
	$configurado	=1;
	
	if($chequeo!=null)
	{
		$entrada	=$chequeo->horaEntrada!=null?$chequeo->horaEntrada:"Pendiente";
		$salida		=$chequeo->horaSalida!=null?$chequeo->horaSalida:"Pendiente";

		if($entrada!='Pendiente' and $salida!='Pendiente')
		{
			$completo="1";
		}
	}
	
	echo '
	<input type="hidden" id="txtCompletado" value="'.$completo.'" />
	<input type="hidden" id="txtIdPersonal" value="'.$personal->idPersonal.'" />';

	echo'
	<table class="admintable" width="100%">
		<tr class="arriba">
			<td align="center">';
				
				if(file_exists('img/personal/'.$personal->idPersonal.'_'.$personal->fotografia) and strlen($personal->fotografia)>3)
				{
					echo '<img src="'.base_url().'img/personal/'.$personal->idPersonal.'_'.$personal->fotografia.'"  style="max-height:150px; max-width: 150px"  />';
				}
				else
				{
					echo '<img src="'.base_url().'img/personal/default.png"  style="max-height:150px; max-width: 150px"  />';
				}
				
			echo'
			</td>
			<td colspan="3">'.$personal->nombre.'<br />
			'.$personal->puesto.'
			</td>
		</tr>
		<tr class="abajo">
			<td align="right">Hora de entrada:</td>
			<td>'.substr(isset($horario->horaInicial)?$horario->horaInicial:'',0,5).'</td>
			<td align="right">Hora de chequeo:</td>
			<td>'.$entrada.'</td>
		</tr>
		
		<tr class="arriba">
			<td align="right">Hora de salida:</td>
			<td>'.substr(isset($horario->horaFinal)?$horario->horaFinal:'',0,5).'</td>
			<td align="right">Hora de chequeo:</td>
			<td>'.$salida.'</td>
		</tr>
	</table>';
	
	if(!isset($horario->horaInicial))
	{
		echo '<div class="Error_validar">El horario de '.$personal->nombre.' no esta configurado</div>';
		$configurado=0;
	}
	
	echo '<input type="hidden" id="txtConfigurado" value="'.$configurado.'" />';
}
else
{
	echo '<input type="hidden" id="txtRegistro" value="0" />';
	echo '<div class="Error_validar">El numero de personal no esta registrado</div>';
}
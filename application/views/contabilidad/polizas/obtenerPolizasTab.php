<?php
$i=$limite;

if($polizas!=null)
{
	echo '
	
	<script>
		$("#txtIdConceptoActivo").val('.$polizas[0]->idConcepto.');
		obtenerPolizaConcepto()
	</script>
	
	<div align="center">
		<ul id="pagination-digg" class="ajax-pagPolizas">'.$this->pagination->create_links().'</ul>
	</div>
	
	<ul class="menuTabs">';
	
	foreach($polizas as $row)
	{
		echo '<li class="tabChico'.($i==$limite?'':'').'" id="poliza'.$row->idConcepto.'" onclick="obtenerPolizaConcepto('.$row->idConcepto.')">'.obtenerTipoPoliza($row->tipo).' | '.$row->numero.' | '.obtenerFechaMesCorto($row->fecha).'</li>';
		
		$i++;
	}
	
	echo '</ul>
	
	<div id="obtenerPolizaConcepto"></div>';
}
else
{
	echo '
	<div class="Error_validar">Aun no se han registrado pólizas</div>';
}
?>
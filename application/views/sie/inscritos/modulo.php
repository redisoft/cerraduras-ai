<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script>
<script src="<?php echo base_url()?>js/sie/inscritos/modulo.js"></script>
<script src="<?php echo base_url()?>js/apexcharts.js"></script>
<link href="<?php echo base_url()?>css/apexcharts.css" rel="stylesheet" type="text/css" />



<div class="row">
	<div class="col-md-4"></div>
    <div class="col-md-4 text-center">Semana <br /><input type="text" class="form-control" style="cursor:pointer" id="txtInicioSieBusqueda"  value="<?php echo $semana->diaInicio?>" onchange="obtenerInscritos()" readonly="readonly" /></div>
    <div class="col-md-3 text-center" style="display:none">a <br /><input type="text" class="form-control" style="cursor:pointer" id="txtFinSieBusqueda"  value="<?php echo $semana->diaFin?>" onchange="obtenerInscritos()" /></div>
    <div class="col-md-4"></div>
</div>

<div id="obtenerInscritos"></div>

<!--
<div id="ventanaDetallesProspectos" title="Detalles de prospectos">
    <div id="obtenerDetallesProspectos"></div>
</div>-->




<script type="text/javascript">
	

</script>


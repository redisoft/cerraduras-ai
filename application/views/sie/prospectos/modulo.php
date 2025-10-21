<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script>
<script src="<?php echo base_url()?>js/sie/prospectos/modulo.js"></script>
<script src="<?php echo base_url()?>js/apexcharts.js"></script>
<link href="<?php echo base_url()?>css/apexcharts.css" rel="stylesheet" type="text/css" />

<div class="row">
	<div class="col-md-3"></div>
    <div class="col-md-6 text-center">Semana <br /><input type="text" class="form-control" style="cursor:pointer" id="txtInicioSieBusqueda"  value="<?php echo $semana->diaInicio?>" onchange="obtenerProspectos()" readonly="readonly" /></div>
    <div class="col-md-3 text-center" style="display:none">a <br /><input type="text" class="form-control" style="cursor:pointer" id="txtFinSieBusqueda"  value="<?php echo $semana->diaFin?>" onchange="obtenerProspectos()" /></div>
    <div class="col-md-3"></div>
</div>
<br><br>
<div id="obtenerProspectos"></div>


<div id="ventanaDetallesProspectos" title="Detalles de prospectos">
    <div id="obtenerDetallesProspectos"></div>
</div>



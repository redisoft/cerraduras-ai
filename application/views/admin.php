<div class="derecha">

<div class="barra">Administración de licencias</div>

<div class="toolbar" id="toolbar" >
  <table class="toolbar" border="0" width="29%">
   <tr>
  <td></td>
      <td class="button" id="">
      <a>
        <span class="icon-option" title="Agregar licencia" id="agregarLicencia">
          <img src="<?php print(base_url()); ?>img/licencia.jpg" width="24px" height="24px"  />
          </span>
         Agregar licencia       
              </a>
         </td>       
         </tr>
         </table>
     </div>


<div class="listproyectos" style=" margin-left:4%; width: 93%;">
 
 <table class="admintable" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <th colspan="4">Lista de licencias</th>
 </tr>
 
 <tr>
     <th>Empresa</th>
     <th>Fecha inicio</th>
     <th>Fecha fin</th>
     <th>Acciones</th>
 </tr>
 
 <?php
 foreach($licencias as $row)
 {
	 ?>
     <tr>
     	<td align="center"><?php echo $row->usuario?></td>
        <td align="center"><?php echo $row->fechaInicio?></td>
        <td align="center"><?php echo $row->fechaFin?></td>
        <td align="center">
        <a onclick="return confirm('¿Realmente desea borrar la licencia?')" 
        href="<?php echo base_url()?>configuracion/borrarLicencia/<?php echo $row->idLicencia?>">
        <img src="<?php echo base_url()?>img/quitar.png" title="Borrar licencia" width="22"/>
        </a><br />
		Borrar
        </td>
     </tr>
     <?php
	 
 }
 ?>
</table>

 <div id="ventanaLicencias" title="Agregar Licencia">
<div style="width:99%;" id="cargandoLicencias"></div>
<table class="admintable" width="99%;">
<tr>
<td class="key">Empresa:</td>
<td>
	<input type="text" name="empresa" id="empresa" class="cajas" style="width:160px;"   /> 
    <input type="hidden" name="paginaActiva" id="paginaActiva" class="cajas" value="<?php echo $this->uri->segment(3)?>" style="width:160px;"   /> 
</td>
</tr>

<tr>
	<td class="key">Fecha inicio:</td>
	<td><input name="FechaDia" style="width:80px;" type="text" class="cajas" id="FechaDia" /> </td>
</tr>

<tr>
	<td class="key">Fecha inicio:</td>
	<td><input name="FechaDia2" style="width:80px;" type="text" class="cajas" id="FechaDia2" /> </td>
</tr>

</table>

</div>

 
</div>
<!-- Termina -->
</div>

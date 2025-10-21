<script type="text/javascript">

function validando()
{
if(document.form.FechaDia.value==""|| document.form.FechaDia2.value=="" )
{
	alert("Debe llenar ambos campos");
	return false;
}
else if(document.form.FechaDia.value > document.form.FechaDia2.value)
{
	alert('La fecha de inicio no puede ser mayor a la final.');
	return false;
} 
	else
	return true;
}
 
 
 function imprSelec(nombre)
{
  var ficha = document.getElementById(nombre);
  var ventimp = window.open(' ', 'popimpr');
  ventimp.document.write( ficha.innerHTML );
  ventimp.document.close();
  ventimp.print( );
  ventimp.close();
}

</script>

<div class="derecha">

<div class="barra">Ventas</div>

<div class="submenu">

</div>
       
<div class="listproyectos" style=" margin-left:4%; width: 93%;">

<form id="form" name="form" onsubmit="return validando()" method="post" action="<? echo base_url().'reportes/reportes_ventas/'?>">

    <table class="admintable" width="99%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <th align="left" scope="col">&nbsp;</th>
              <th align="left" scope="col">Por favor escriba un rango de fechas </th>
            </tr>
            <tr>
              <th align="left" scope="col">&nbsp;</th>
              <th align="left" scope="col">
                De
                <input name="FechaDia" type="text" title="Inicio" style="width:110px" id="FechaDia" class="cajas" />
                a
                <input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:110px" class="cajas" />
                <input type="submit" name="Submit" class="btn" value="Buscar" />  <img src="/img/print.png" onclick="imprSelec('RESPUESTACLIENTE')" style="cursor:pointer;" alt="Imprimir" width="32" height="32"> </th>
              </tr>
          </table>
        </form>
    
<div id="CargandoID" style="width:92%; float:left; margin-top:2px; margin-bottom: 5px;"> </div>


<?php

if(!empty($Clientes)){

?>

<div id="RESPUESTACLIENTE" style="width:98%;float:left;">

    
<table class="admintable" width="900">

    <tr>
      <th colspan="4">Ventas</th>
      </tr>
    <tr>
        <th width="24%">Contacto</th>
        <th width="31%">Empresa</th>
        <th width="11%" align="right">Monto</th>
        <th width="25%">Ejecutivo</th>
        </tr>
    <tr>

        <td> </td>
        <td align="center">        </td>
        <td></td>
        <td> </td>
        </tr>

<?php
foreach ($Clientes as $Cliente){

$Link_Ficha=anchor(base_url()."clientes/ficha/".$Cliente["id_cliente"],'<img src="'.base_url().'img/ficha_info.png" width="16px;" height="16px;" title="Ficha del Cliente">','Ficha del cliente');


$image_Editar = array(
       'src' => base_url().'img/edit.png',
       'alt' => 'Editar Cliente',
       'class' => '',
       'width' => '16',
       'height' => '16',
       'title' => 'Editar Cliente'
  );

$Link_editar=anchor(base_url()."clientes/editar/".$Cliente['id_cliente'],img($image_Editar),'Editar del cliente');
$idcli=$Cliente['id_cliente'];
$ww=$this->db->query("select nombre,id_cliente from clientes_contactos where id_cliente = '$idcli' order by fechadd desc limit 0,1");
foreach ($ww->result() as $row)
{

$contacto=$row->nombre;

}

?>

    
    <tr>
        <td align="center"> <?php print($contacto) ?> </td>
        <td align="center"> <?php print($Cliente['empresa']); ?> </td>
        <td align="right">$<? echo number_format($Cliente['precioventa'],2);?></td>
        <td> <?php print($Cliente['name']); ?> </td>
        </tr>

<?php
 }//Foreach del Cliente
?>
</table>
    
<table class="admintable" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <th colspan="2" align="left" scope="col"><table width="48%"  class="admintable">
      <tr>
        <th scope="col">Total ventas: </th>
        <th scope="col">$
              <?php $query = $this->db->query("select sum(precioventa) as suma from cotiza_detalles_venta");

foreach ($query->result() as $row)
{
    //$float=round($row->suma * 100) / 100; 
//echo '$ '.$float;

echo number_format($row->suma,2);
}
?></th>
      </tr>
    </table></th>
  </tr>
</table>
<p>&nbsp;</p>
</div><!--  RESPUESTACLIENTES -->
<div class="Error_validar" id="registroError" style="display:none; width:90%; margin-top:2px; margin-bottom: 5px;"></div>

<?php

}//Fin del IF de Clientes
else{
?>

    

<div id="container" style="width:93%; float:left; margin-left: 1%;" >
<div class="Error_validar" id="registroError" style="width:90%; margin-top:2px; margin-bottom: 5px;">No hay registros de ventas </div>

    
 <?php
   }
 ?>
</div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
<!-- Termina -->
</div>

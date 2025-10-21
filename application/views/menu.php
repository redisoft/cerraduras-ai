<?php $anio=date("Y");?>
<div class="izquierda">
<div style="width:auto; background-color:#0285C0; border-radius: 3px">
<ul class="menu_color">
    <li id="menu-dashboard" onclick="window.location.href='<?php echo base_url()?>principal/tableroControl/<?php echo date('Y-m-d')?>'">
    <div class="letras">Dashboard</div>
    </li>
    
    <li id="menu-clientes" onclick="window.location.href='<?php echo base_url()?>clientes'">
    <div class="letras">Clientes</div>
    </li>
    
    <li id="menu-proveedores" onclick="window.location.href='<?php echo base_url()?>proveedores'">
    <div class="letras" >Proveedores</div>
    </li>
    
    <li id="menu-compras" onclick="window.location.href='<?php echo base_url()?>compras/administracion'">
    <div class="letras" >Compras</div>
    </li>
    
    <li id="menu-gastos" style="width:170px"  
    onclick="window.location.href='<?php echo base_url()?>produccion/gastos/<?php echo date('m').'/'.date('Y')?>'">
    <div class="letras" >Gastos administrativos</div>
    </li>
    
    <li id="menu-inventarioProductos" onclick="window.location.href='<?php echo base_url()?>inventarioProductos'">
    <div class="letras" >Productos</div>
    </li>
    
    <li>
        <div class="letras" >Producción</div>
        <ul>
            <li id="menu-materiales" onclick="window.location.href='<?php echo base_url()?>materiales'">
            <div class="letras" >Materia prima</div>
            </li>
            <li style="height:40px" id="menu-analisis" onclick="window.location.href='<?php echo base_url()?>produccion'">
            <div class="letras" >Análisis de precios</div>
            </li>
            <li style="height:40px" id="menu-preciosUnitarios" onclick="window.location.href='<?php echo base_url()?>produccion/preciosUnitarios'">
            <div class="letras" >Precios unitarios</div>
            </li>
            <li style="height:40px" id="menu-productoTerminado" onclick="window.location.href='<?php echo base_url()?>produccion/productoTerminado/<?php echo date('m')?>'">
            <div class="letras" >Almacen de producto</div>
            </li>
            <li id="menu-ordenesProduccion" style="border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; height:40px" onclick="window.location.href='<?php echo base_url()?>ordenes'">
            <div class="letras" >Orden de producción</div>
            </li>
        </ul>
    </li>
    
    <li style="width:170px" >
    <div class="letras" >Recursos financieros</div>
    <ul>
        <li style="width:170px" id="menu-ingresos" onclick="window.location.href='<?php echo base_url()?>reportes/ingresos'">
        <div class="letras" >Ingresos</div>
        </li>
        <li id="menu-egresos" style="border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; width:170px" 
        onclick="window.location.href='<?php echo base_url()?>reportes/egresos'">
        <div class="letras" >Egresos</div>
        </li>
    </ul>
    </li>
    
    <li>
    <div class="letras" >Reportes</div>
        <ul>
    
        <li id="menu-reporteVentas" onclick="window.location.href='<?php echo base_url()?>reportes'">
        <div class="letras" >Ventas</div>
        </li>
        
        <li id="menu-reporteCompras" onclick="window.location.href='<?php echo base_url()?>reportes/reportesCompras'">
        <div class="letras" >Compras</div>
        </li>
        
        <li id="menu-prospectos" onclick="window.location.href='<?php echo base_url()?>clientes/prospectos'">
        <div class="letras" >Prospectos</div>
        </li>
        <li id="menu-cobranza" onclick="window.location.href='<?php echo base_url()?>reportes/cobranza/<?php echo date('m')?>'">
        <div class="letras" >Cobranza</div>
        </li>
        </ul>
    </li>
    
    
    <li id="menu-camaras" onclick="window.location.href='<?php echo base_url()?>configuracion/camaras'">
    <div class="letras" >Vigilancia</div>
    </li>
    
    <li id="menu-tiendas"  
    onclick="window.location.href='<?php echo base_url()?>tiendas'">
    <div class="letras" >Tiendas</div>
    </li>
		
</ul>
</div>
</div>




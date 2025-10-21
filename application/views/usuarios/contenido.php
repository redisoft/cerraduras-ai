<div class="derecha">

<div class="barra">Lista de <?php print(ucwords($Categoria)); ?>s</div>

<div class="submenu">Nuevo
<?php print(anchor(base_url()."usuario/add",'<img src="'.base_url().'img/add.png" width="20px;" height="20px;" title="Añadir nuevo usuario">','Nuevo')); ?>
</div>

<div class="listproyectos">

<div id="box_result">
<table id="flex1" style="display:none"></table>
	<script type="text/javascript">
			$("#flex1").flexigrid
			({
			url: '<?php echo base_url()?>usuario/paginacion/',
			dataType: 'xml',
			colModel : [
				{display: 'acciones	', width : 50, sortable : false, align: 'center'},
				{display: '#', name : '#', width : 10, sortable : false, align: 'center'},
				{display: 'Nombre', name : 'name', width : 150, sortable : false, align: 'left'},
				{display: 'Usuario', name : 'username', width :100, sortable :true, align: 'left'},
				{display: 'Role', name : 'role', width :140, sortable :true, align: 'left'},
				{display: 'Correo', name : 'correo', width : 140, sortable : false, align: 'left'},
				{display: 'Creado', name : 'createDate', width : 120, sortable : true, align: 'left'},
				{display: 'Ultima modificación', name : 'modify_fech', width : 130, sortable : true, align: 'left'},
				],
			searchitems : [
				{display: 'Nombre', name : 'name', isdefault: true},
                                {display: 'Usurio', name : 'username', isdefault: true},
				{display: 'Role',    name: 'role'},
				],
			sortname: 'username',
			sortorder: "ASC",
			usepager: true,
			title: '<?php print(ucwords($Categoria)); ?>',
			useRp: true,
			rp: 10,
			showTableToggleBtn: true,
			width:'auto',
			height:320
			}
		);
	</script>
</div>

</div>
<!-- Termina -->
</div>

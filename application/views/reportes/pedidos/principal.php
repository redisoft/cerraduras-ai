<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title><?php echo isset($titulo)?$titulo:'Reportes'?></title>
    	 <link type="text/css" rel="stylesheet"  href="<?php echo base_url()?>css/pedidos.css" />
    </head>
<body>
<?php $this->load->view($reporte)?>
</body>
</html>
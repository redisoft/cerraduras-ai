<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Reportes</title>
    	 <link type="text/css" rel="stylesheet"  href="<?php echo base_url()?>css/adm/style<?php echo $this->session->userdata('estilo')?>.css" />
    </head>
    
    <style>
		body
		{
			padding:37.79px;
			max-width: 529.06px;
		}
	</style>
    <script>
		print();
	</script>
<body>
<?php echo $this->load->view($reporte)?>
</body>
</html>
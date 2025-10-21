<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login/login.css">
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bibliotecas/notificaciones.js"></script>

<title>.: <?php echo $estilo->nombre?> :.</title>

<?php
if(strlen($this->session->userdata('errorNotificacion'))>2)
{
	echo 
	'<script>
	$(document).ready(function()
	{
		notify("'.$this->session->userdata('errorNotificacion').'",500,3000,"error",55,8);
	})
	</script>';
}

$this->session->set_userdata('errorNotificacion','');

?>

</head>
<body>

<div class="main">


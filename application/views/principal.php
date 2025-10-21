<div class="row">
<?php
if($this->session->userdata('usuarioActivo')=='conta')
{
	$this->load->view('principalGlobal');
}
else
{
	$this->load->view('principalDemo');
}
?>
</div>
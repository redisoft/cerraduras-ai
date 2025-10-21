<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?=$titulo?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Beethemes" name="author" />
    <!-- favicon -->
    <!--<link rel="shortcut icon" href="<?=base_url()?>sie/assets/images/favicon.ico">-->
	
	<script src="<?=base_url()?>js/jquery/jquery.js"></script>
    <!--<script src="<?=base_url()?>js/jquery/jquery-ui.js"></script>-->
    <script src="<?php echo base_url()?>js/jsruta.js"></script>
    
   
    
    
    <!--<script src="<?php echo base_url()?>js/bibliotecas/jquery-ui-timepicker-addon.js"></script>
	-->
    <script src="<?php echo base_url()?>js/bibliotecas/jquery-ui-sliderAccess.js"></script>
    <script src="<?php echo base_url()?>js/bibliotecas/notificaciones.js"></script>
    
    <script src="<?php echo base_url()?>js/bibliotecas/sha1.js"></script>
    <script src="<?php echo base_url()?>js/catalogos.js"></script>
    <!--<script src="<?php echo base_url()?>js/bibliotecas/fechas.js"></script>	-->
    <script src="<?php echo base_url()?>js/conexion/offline.js"></script>	
    <script src="<?php echo base_url()?>js/correos/firma.js"></script>	
    
    
    
     <script>
	$(document).ready(function()
	{
		menuSie('<?php echo $menuActivo?>','<?php echo isset($subMenu)?$subMenu:''?>');
		codigoBorrado	= '<?php echo $this->session->userdata('codigoBorrado')?>';
		codigoEditar	= '<?php echo $this->session->userdata('codigoEditar')?>';
		codigoImportar	= '<?php echo $this->session->userdata('codigoImportar')?>';
		codigoCancelar	= '<?php echo $this->session->userdata('codigoCancelar')?>';
		base_url		= '<?php echo base_url()?>';
		img_loader		= '<?php echo base_url().'img/ajax-loader.gif'?>';
		
		precioVentaA	= '<?php echo obtenerNombrePrecio(1)?>';
		precioVentaB	= '<?php echo obtenerNombrePrecio(2)?>';
		precioVentaC	= '<?php echo obtenerNombrePrecio(3)?>';
		precioVentaD	= '<?php echo obtenerNombrePrecio(4)?>';
		precioVentaE	= '<?php echo obtenerNombrePrecio(5)?>';
		sistemaActivo	= '<?php echo sistemaActivo?>';
		
		alertaActiva	= '<?php echo $this->session->userdata('alertaActiva')?>';
		preciosActivo	= '<?php echo $this->session->userdata('precios')?>';
	});
	
	</script>

    <!-- Required css -->
    <link href="<?=base_url()?>sieclases/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url()?>sieclases/assets/css/style.min.css" rel="stylesheet" type="text/css" />


</head>

<body>

    <!-- Begin page -->
    <div class="wrapper">

        <!-- ============================================================= -->
        <!-- ===============    side menu content start  ================= -->
        <!-- ============================================================= -->
        <div class="left-side-menu">
            <div class="slimscroll-menu">
                <!-------------------------------------->
                <!-- Put your logo hear in img tag -->
                <a href="index.html" class="logo">
                    <span class="logo-lg">
                        <?php
						
						if(file_exists('img/logos/'.$configuracion->id.'_'.$configuracion->logotipo) and strlen($configuracion->logotipo)>0)
						{
							echo '<img src="'.base_url().'img/logos/'.$configuracion->id.'_'.$configuracion->logotipo.'" style="max-height: 50px; max-width: 130px" alt="">';
						}
                        
						?>
                    </span>
                    <span class="logo-sm">
                        <img src="<?=base_url()?>img/logos/logo.png" alt="" style="max-height: 55px; max-width: 55px">
                    </span>
                </a>
                <!-- Logo content end -->
                <!-------------------------------------->
                <!-------------------------------------->
                <!-- Menu link start -->
                <ul class="metismenu side-nav">
                    <li class="menu-caption menu-item">SIE</li>
                    
                    <li class="menu-item active" id="finanzas">
                        <a href="javascript: void(0);" class="menu-link">
                            <i class="feather mdi mdi-bank"></i>
                            <span> Finanzas </span>
                            <span class="menu-sub-icon"></span>
                        </a>
                        <ul class="menu-level-second collapse" aria-expanded="false">
                            <li id="escenario0"><a href="<?=base_url()?>sie/index">Saldos</a></li>
                            <li id="escenario1"><a href="<?=base_url()?>sie/index/1">Escenario 1</a></li>
                            <li id="escenario2"><a href="<?=base_url()?>sie/index/2">Escenario 2</a></li>
                            <li id="creditos"><a href="<?=base_url()?>sie/creditos">Créditos</a></li>
                            <li id="egresos"><a href="<?=base_url()?>sie/egresos">Egresos</a></li>
                            <li id="calendarioPagos"><a href="<?=base_url()?>sie/calendarioPagos">Calendario de pagos</a></li>
                        </ul>
                    </li>
                    
                    <li class="menu-item" id="matricula">
                        <a href="<?=base_url()?>matricula" class="menu-link">
                            <i class="feather mdi mdi-calculator"></i>
                            <span> Matrícula </span>
                        </a>
                    </li>
                    
                     <li class="menu-item" id="prospectos">
                        <a href="<?=base_url()?>prospectos" class="menu-link">
                            <i class="feather mdi mdi-cash-usd"></i>
                            <span> Prospectos </span>
                        </a>
                    </li>
                    
                     <li class="menu-item" id="inscritos">
                        <a href="<?=base_url()?>prospectos/inscritos" class="menu-link">
                            <i class="feather mdi mdi-chart-pie"></i>
                            <span> Inscritos </span>
                        </a>
                    </li>
                </ul>
                <!-- Menu link end -->
                <!-------------------------------------->

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- ============================================================= -->
        <!-- ===============     side menu content End   ================= -->
        <!-- ============================================================= -->


        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- ============================================================= -->
                <!-- ===============    Top bar header content start  ================= -->
                <!-- ============================================================= -->
                <div class="navbar-custom">
                    <!------------------------------------->
                    <!-- right side topbar content start -->
                    <ul class="list-unstyled topbar-right-menu float-right mb-0">
                      
                      
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none mx-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    
                                <span class="account-user-avatar">
                                    <img src="https://image.flaticon.com/icons/png/512/55/55089.png" alt="user-image" class="rounded-circle">
                                </span>
                                <span>
                                    <span class="account-user-name feather mdi mdi-account"> <?=$usuario?></span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">

                                <a href="<?=base_url()?>login/logout" class="dropdown-item">
                                    <i class="feather icon-power text-danger"></i> &nbsp; Salir</a>
                            </div>
                        </li>
                    </ul>
                    <!-- right side topbar content end -->
                    <!------------------------------------->

                    <!---------------------------------------------->
                    <!-- Left side topbar content Start -->

                    <!-- Minimenu and mobille toggle button start -->
                    <button class="button-menu-mobile open-left">
                        <i class="feather icon-menu"></i>
                    </button>
                    <!-- Minimenu and mobille toggle button end -->


                    <div class="header-search" style="display:none">
                        <form>
                            <div class="input-group">
                                <span class="feather icon-search"></span>
                                <input type="text" class="form-control" placeholder="Search...">
                            </div>
                        </form>
                    </div>
                    <!-- Left side topbar content end -->
                    <!------------------------------------->
                </div>
                <!-- ================================================================== -->
                <!-- ===============    Top bar header content end  ================= -->
                <!-- ================================================================== -->


                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?=base_url()?>sie/index1"><i class="feather icon-home"></i></a></li>
                                        <?=$breadcumb?>
                                        
                                    </ol>
                                </div>
                                <h4 class="page-title"><?=$titulo?></h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    
                    
                    
                    <?php $this->load->view($pagina)?>
                    

                </div> <!-- container -->

            </div> <!-- content -->
            
            
            

            <!-- ============================================================= -->
            <!-- ===============    footer content start  ================= -->
            <!-- ============================================================= -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            © IEXE
                        </div>
                        <div class="col-md-6">
                            <!--<div class="text-md-right footer-links d-none d-md-block">
                                <a href="javascript: void(0);">Buy Now</a>
                                <a href="https://bit.ly/2wYNera">Rate us</a>
                                <a href="mailto:Beethemes@gmail.com">Support</a>
                            </div>-->
                        </div>
                    </div>
                </div>
            </footer>
            <!-- ============================================================= -->
            <!-- ===============    footer content start  ================= -->
            <!-- ============================================================= -->


        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->





    <!-- Required js -->
    <script src="<?=base_url()?>sieclases/assets/js/app.min.js"></script>
    
    
</body>

</html>
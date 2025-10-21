<?php
$color=$this->session->userdata('estilo');
?>
<style>
	
/* CSS Document */

*{ 
	padding:0; margin:0;
 }

body
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
	color:<?php echo $color?>;
	/*background-color:#FFF;*/
	background-color:#F1F1F1;
}

i
{
	color: <?php echo $color?>;
	font-weight:600;
}

label
{
	color: <?php echo $color?>;
}

a
{
	color:<?php echo $color?>;
	font-size:13px;
	//text-decoration: underline;
}

.ui-state-error, .ui-widget-content .ui-state-error, .ui-widget-header .ui-state-error 
{
	border: 1px solid <?php echo $color?>; 
	width:100%; 
}

form
{
	width:100%
}

a:link
{
    text-decoration: none;
}

a.dercargas:hover
{
    text-decoration: underline;
}

img
{
	border:none;
}

.main
{
	/*width:1200px;*/
	/*background-color:#FFF;*/
	background-color:#F1F1F1;
	margin:0px auto;
	min-height:700px;
}

	
h3.principal
{
	font-size: 14px;
	color:#9a9b9a;
}

.header
{
	width:100%;
	background: color:#000;
	height: 20px;
	border:none;
}

.arriba
{
	width:100%;
}

.titulitoEncabezado
{
	float:left; 
	width:730px; 
	font-size:18px;
	font-weight:600;
	margin-top:15px;
	padding-right:10px;
	color:#666;
	text-align:right;
}

.imagenEncabezado
{
	float:left; 
	width:400px; 
	text-align:right;
	margin-top:10px;
	color:#666;
	font-size:12px;
}

.top
{
	background-color:<?php echo $color?>;
    border-radius: 7px;
	width: 98%;
	height: 50px;
	color:#FFF;
	text-align:left;
	font-size:13px;
	margin-left:1%;
	font-weight:600;
}

div.cuerpo
{
	 width:100%;
	 background-color:transparent;
	/* max-height: 700px;
	 overflow: scroll;
	 overflow-y: auto;
	 overflow-x: hidden;*/
}


div.lineaDegradado
{
	width:97%;
	min-height:3px;
	//-moz-border-radius: 7px;
	//-webkit-border-radius: 7px;
	margin-left:1.5%;
	
	background-image: linear-gradient(left , rgb(255,255,255) 1%, rgb(41,155,204) 36%, rgb(41,155,204) 54%, rgb(255,255,255) 93%);
background-image: -o-linear-gradient(left , rgb(255,255,255) 1%, rgb(41,155,204) 36%, rgb(41,155,204) 54%, rgb(255,255,255) 93%);
background-image: -moz-linear-gradient(left , rgb(255,255,255) 1%, rgb(41,155,204) 36%, rgb(41,155,204) 54%, rgb(255,255,255) 93%);
background-image: -webkit-linear-gradient(left , rgb(255,255,255) 1%, rgb(41,155,204) 36%, rgb(41,155,204) 54%, rgb(255,255,255) 93%);
background-image: -ms-linear-gradient(left , rgb(255,255,255) 1%, rgb(41,155,204) 36%, rgb(41,155,204) 54%, rgb(255,255,255) 93%);

background-image: -webkit-gradient(
	linear,
	left bottom,
	right bottom,
	color-stop(0.01, rgb(255,255,255)),
	color-stop(0.36, rgb(41,155,204)),
	color-stop(0.54, rgb(41,155,204)),
	color-stop(0.93, rgb(255,255,255))
);

}

div.lineaDegradadoPie
{
	width:97%;
	min-height:3px;
	//-moz-border-radius: 7px;
	//-webkit-border-radius: 7px;
	margin-left:1.5%;
	
	background-image: linear-gradient(left , rgb(41,155,204) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(41,155,204) 91%);
background-image: -o-linear-gradient(left , rgb(41,155,204) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(41,155,204) 91%);
background-image: -moz-linear-gradient(left , rgb(41,155,204) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(41,155,204) 91%);
background-image: -webkit-linear-gradient(left , rgb(41,155,204) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(41,155,204) 91%);
background-image: -ms-linear-gradient(left , rgb(41,155,204) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(41,155,204) 91%);

background-image: -webkit-gradient(
	linear,
	left bottom,
	right bottom,
	color-stop(0.01, rgb(41,155,204)),
	color-stop(0.46, rgb(255,255,255)),
	color-stop(0.49, rgb(255,255,255)),
	color-stop(0.91, rgb(41,155,204))
);

}


div.lineadegradadoMenu
{
	width:97%;
	min-height:1px;
	//-moz-border-radius: 7px;
	//-webkit-border-radius: 7px;
	margin-left:1.5%;
	margin-top:4%;
	
	background: rgb(59,103,158); /* Old browsers */
	background: -moz-linear-gradient(left, rgba(125,185,232,1) 0%, rgba(43,136,217,1) 0%, rgba(32,124,202,1) 40%, rgba(125,185,232,1) 100%); /* FF3.6+ */
	
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(59,103,158,1)), color-stop(50%,rgba(43,136,217,1)), color-stop(51%,rgba(32,124,202,1)), color-stop(100%,rgba(125,185,232,1))); /* Chrome,Safari4+ */
	
	background: -webkit-linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* IE10+ */
	background: linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3b679e', endColorstr='#7db9e8',GradientType=1 ); /* IE6-9 */
	);  
}


div.derecha
{
	width:100%;
	/*background-color:#FFF;*/
	background-color:#F1F1F1;
	float:right;
	min-height:650px;
	/*height:auto !important;*/
	
	
	/*max-height: 700px;
	overflow: scroll;
	overflow-y: auto;
	overflow-x: hidden*/
	
}

.desplegable
{
	max-height: 700px;
	overflow: scroll;
	overflow-y: auto;
	overflow-x: hidden
}

.desplegableSeguimentos
{
	max-height: 600px;
	overflow: scroll;
	overflow-y: auto;
	overflow-x: hidden;
	width: 100%;
}

.desplegableAlertas
{
	max-height: 420px;
	overflow: scroll;
	overflow-y: auto;
	overflow-x: hidden;
	width: 100%;
}



div.derecha div.barra
{
	width:100%;
	font-size:15px;
	font-weight:600;
	background-color:<?php echo $color?>;
	text-align:left;
	height:35px;
	color:#FFF;
	border-radius: 9px;
	text-align:center;
	line-height:35px;

	margin-top:1.5%;
}

div.footer
{
	width: 100%;
	text-align: center;
	background-color:<?php echo $color?>;		
	height: 4vh;
	color: #fff;
	font-size: 1.7vh;
	line-height:3.8vh;
}

.menusito
{
	width: 100%;
	//background-color:<?php echo $color?>;		
	height: 60px;
	border-radius: 7px;
	color:#FFF;
	font-size:14px;
}

.menusito:hover
{
	background: rgb(59,103,158); /* Old browsers */
	background: -moz-linear-gradient(left, rgba(125,185,232,1) 50%, rgba(43,136,217,1) 0%, rgba(32,124,202,1) 40%, rgba(125,185,232,1) 50%); /* FF3.6+ */
	
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(59,103,158,1)), color-stop(50%,rgba(43,136,217,1)), color-stop(51%,rgba(32,124,202,1)), color-stop(100%,rgba(125,185,232,1))); /* Chrome,Safari4+ */
	
	background: -webkit-linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* IE10+ */
	background: linear-gradient(left, rgba(59,103,158,1) 0%,rgba(43,136,217,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3b679e', endColorstr='#7db9e8',GradientType=1 ); /* IE6-9 */
	);  

}
	

/* Estilo de Autocompletado */
/*.ui-autocomplete-loading { background: white url('../../img/ui-anim_basic_16x16.gif') right center no-repeat; }
.ui-autocomplete {
		max-height:200px;
		overflow-y:auto;
		     width:175px;
		text-align:justify;
	}
	
.ui-autocomplete a div {
		border-bottom:1px solid #BEBEBE;
		  border-left:none;
		 border-right:none;
		   border-top:none;
	}
	
.ui-autocomplete a label {
		color:#464646;
		font-weight:bold;			
	}	
	

* html .ui-autocomplete {
		height:200px;
		width:175px;
	}

.ui-dialog label{
       color:<?php echo $color?>;
   font-size:12px;
}

.ui-dialog .cajas{
       color:<?php echo $color?>;
   font-size:12px;
}
*/
.header .theader{
    width: 1200px;
    margin: 0 auto;
    color: #000;
}
.title_system{
    width: 700px;
    text-align: center;
}
.title_system h1{
    text-align: center;
}
.theader a{
    color: #000;
}

	
table.header
{
	background-color:#C4B699;
	font-family:"Trebuchet MS";
	font-weight:bold;
	color:#FFF;
}

table.header label
{
	color:<?php echo $color?>;
	font-weight:bold;
}

h1
{
	color:#000;
	font-size:21px;
}

div.izquierda table.menu
{
	width: 100%;
}

div.izquierda table.menu th
{
	font-size:12px;
	text-align:left;
	padding-left:2px;
	font-weight:normal;
}

div.izquierda table.menu a
{
	color:#FFFFFF;
	font-weight:bold;
}

#izquierda ul
{
    border: 1px solid #FFF;
}

div.derecha div.submenu
{
	width:97%;
	margin-top:10px;
	text-align:left;
	margin-left:1.5%;
	font-size:13px;
	height:75px;
}

div.derecha div.submenu a
{
	text-decoration:none;
}

div.derecha div.submenu label
{
	display:inline;
}

div.derecha div.submenu a
{
	text-decoration:none;
}

div.derecha div.listproyectos
{
	width:100%;
	/*float:left;*/
	margin-top:10px;
	
	
}

div.derecha div.listproyectos form{
float:left;
width:100%;
}

div.derecha div.listproyectos form fieldset{
    border: 1px solid #000;
    width: auto;
    height: auto;
}
/*Tables*/

table label
{
  	vertical-align:middle;
     font-size:13px;
}

.sinBordeDerecha
{
	border-right: none !important;
}

.sinBordeIzquierda
{
	border-left: none !important;
}

.sinBordeIzquierdaDerecha
{
	border-left: none !important;
	border-right: none !important;
}

/*Stilo tablas*/
.admintable tbody
{
	background:#fff;
	cursor:pointer;
}

.admintable
{
	background:#fff;
	cursor:pointer;
	border-collapse:collapse;
	margin-top:2px;
}


.admintable td
{
	padding:3px;
	border:1px solid #d5d5d5;
	vertical-align:middle;
}

.admintable td.sinBorde
{
	border: none !important;
	background-color: #f1f1f1 !important;
	padding: 0;
}

.admintable td.vinculos
{
	
}

.admintable td.sinbordeTransparente
{
	border: none !important;
	background-color: #f1f1f1 !important;
		padding: 0;
}

.admintable td.vinculos img
{
	width:22px;
	height:22px;
}

.admintable td.imagenesLinea img
{
	max-width:80px;
	max-height:80px;
}

.admintable td.formularios
{
	
}

.admintable td.formularios img
{
	width:22px;
	height:22px;
}

.admintable img
{
    border:none;
}


.admintable th
{
	padding:3px;
	background-color:#f6f6f6;
	font-weight:normal;
	font-size:12px;
	vertical-align:middle;
	color:#666;
	border:1px solid #d5d5d5;
}

.admintable th.resaltadoIexe
{
	background-color:#066;
	font-weight:bold;
	color:#FFF;
}

.admintable th.encabezadoPrincipal
{
	height:40px;
	background-image: linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -o-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -moz-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -webkit-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -ms-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0.66, #C5D5D6),
		color-stop(0.68, #C5D5D6),
		color-stop(0.92, #E4ECED)
	);
}


.admintable th.encabezadoPrincipalChico
{
	height:22px;
	background-image: linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -o-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -moz-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -webkit-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -ms-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0.66, #C5D5D6),
		color-stop(0.68, #C5D5D6),
		color-stop(0.92, #E4ECED)
	);
}

.admintable th.encabezadoTablero
{
	color:<?php echo $color?>;
	border:none;
	background-color:#FFF;
	text-align:left;
}

.admintable th.encabezadoTablero a
{
	color:<?php echo $color?>;
}

.admintable caption
{
	color:#586e83;
	font-weight:normal;
	text-align:center;
	padding-right:8px;
}

.admintable thead
{
	height:25px;
	color:#265f7a;
	/*vertical-align:top;*/
}
/**ESTILOS PARA TABLAS INFORMACION*/
table.admintable td
{
	padding-top: 3px;
	padding-right: 4px;
	padding-bottom: 3px;
	padding-left: 3px;
	font-size:11px;
	color:#696969;
}

table.admintable td.totales
{
	font-weight:bold;
}

table.admintable td label
{
	font-weight:100;
	font-size:11px;
}

table.admintable tr.sombreado
{
	background-color: #EEE;
	
	/*background-image: linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -o-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -moz-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -webkit-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -ms-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);

	background-image: -webkit-gradient
	(
		linear,
		left bottom,
		right bottom,
		color-stop(0.01, rgb(242,242,242)),
		color-stop(0.46, rgb(255,255,255)),
		color-stop(0.49, rgb(255,255,255)),
		color-stop(0.91, rgb(242,242,242))
	);*/
}

table.admintable tr.sinSombra
{
	/*background-image: linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -o-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -moz-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -webkit-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);
	background-image: -ms-linear-gradient(left , rgb(242,242,242) 1%, rgb(255,255,255) 46%, rgb(255,255,255) 49%, rgb(242,242,242) 91%);

	background-image: -webkit-gradient
	(
		linear,
		left bottom,
		right bottom,
		color-stop(0.01, rgb(242,242,242)),
		color-stop(0.46, rgb(255,255,255)),
		color-stop(0.49, rgb(255,255,255)),
		color-stop(0.91, rgb(242,242,242))
	);*/
}

table.admintable tr.sinSombra:hover
{
	background-color:#D2D6D3;
	
	/*background-image: linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -o-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -moz-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -webkit-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -ms-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	
	background-image: -webkit-gradient
	(
		linear,
		right top,
		left top,
		color-stop(0.09, #D2D6D3),
		color-stop(0.4, #F2F2F2),
		color-stop(0.61, #F2F2F2),
		color-stop(0.89, #D2D6D3)
	);*/
}

table.admintable tr.sombreado:hover
{
	background-color:#D2D6D3;
	
	/*background-image: linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -o-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -moz-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -webkit-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	background-image: -ms-linear-gradient(right , #D2D6D3 9%, #F2F2F2 40%, #F2F2F2 61%, #D2D6D3 89%);
	
	background-image: -webkit-gradient
	(
		linear,
		right top,
		left top,
		color-stop(0.09, #D2D6D3),
		color-stop(0.4, #F2F2F2),
		color-stop(0.61, #F2F2F2),
		color-stop(0.89, #D2D6D3)
	);*/
}

table.admintable tr.selected
{
	background-color:#D2D6D3;
}

table.admintable tr.fuenteNaranja > td
{
	color: #F60 !important;
}

table.admintable tr.seleccionado
{
	background-color:#CCC;
}


table.admintable td img
{
    border:none;
}

table.admintable td a
{
	text-decoration:none;
	color:<?php echo $color?>;
	font-size:11px;
	//font-weight:bold;
}

table.admintable th a
{
	text-decoration:none;
	color:#696969;
	//font-weight:bold;
}


table.admintable tr.devolucion td a, tr.devolucion td
{
	color:red;
}

table.admintable td.key, table.admintable td.paramlist_key
{
	background-color: #f6f6f6;
	text-align: right;
	width: 230px;
	color: #666666;
	font-weight: bold;
	border: 1px solid #e9e9e9;
}

table.admintable td.SubKey
{
	background-color: #f6f6f6;
	color: #666666;
	font-weight: bold;
	border: 1px solid #e9e9e9;
}
table.admintable td:hover{
 /*cursor:pointer;*/
}

.btn{
	border:1px solid #DFD6BE;
     font-size:12px;
    background:#F4F4F4;
	 color:<?php echo $color?>;
   margin-left:5px;
    margin-top:5px;	
         width:100px;
        height:28px;	
}

.TextArea{
border:1px solid #DFD6BE;
font-size:12px;
background:#F4F4F4;
color:<?php echo $color?>;	
margin-left:5px;
width:41%;
}

.cajasColores{
	border:1px solid #DFD6BE;
     font-size:12px;
    background:#F4F4F4;
	 color:<?php echo $color?>;
   margin-left:5px;
    margin-top:5px;	
        height:18px;
         width:5%;		
}

.cajas
{
	border:1px solid #DFD6BE;
	font-size:12px;
	background:#F4F4F4;
	color:<?php echo $color?>;
	margin-left:5px;
	margin-top:5px;	
	height:18px;
	width:41%;		
}

.cajasTransparentes
{
	border: none;
	background:#FFF;
	color:<?php echo $color?>;
	width:100%;
	cursor:none;
	text-align:center;		
}

.cajasDerecha
{
	text-align:right;
}

.cajasNormales
{
	border:1px solid #DFD6BE;
	font-size:12px;
	background:#F4F4F4;
	color:<?php echo $color?>;
	margin-left:5px;
	height:18px;
	width:250px;
}

.busquedas
{
	border:1px solid #DFD6BE;
	font-size:12px;
	background:#F4F4F4;
	color:#999;
	margin-left:5px;
	margin-top:5px;	
	height:18px;
	width:41%;
	font-weight:600;
	border-radius: 4px;
}

.cajasProductos{
	border:1px solid #DFD6BE;
     font-size:12px;
    background:#F4F4F4;
	 color:<?php echo $color?>;
   margin-left:5px;
    margin-top:5px;	
        height:18px;
         width:60%;		
}

.cajasSelect{
	border:1px solid #DFD6BE;
     font-size:12px;
    background:#F4F4F4;
	 color:<?php echo $color?>;
   margin-left:5px;
    margin-top:5px;	
        height:18px;
         width:auto;		
}


.cajasTel{
	border:1px solid #DFD6BE;
     font-size:12px;
    background:#F4F4F4;
	 color:<?php echo $color?>;
   margin-left:5px;
    margin-top:5px;	
        height:18px;
         width:20%;		
}

.cajasFax{
	border:1px solid #DFD6BE;
     font-size:12px;
    background:#F4F4F4;
	 color:<?php echo $color?>;
   margin-left:5px;
    margin-top:5px;	
        height:18px;
         width:10%;		
}

.cajasFile{
	border:1px solid #DFD6BE;
    font-size:12px;
    background:#F4F4F4;
	color:<?php echo $color?>;
    margin-left:5px;
    margin-top:5px;	
    height:25px;
    width:41%;		
}

.box_articulos{
	margin:0 5px 0 5px;
	color:#ff0000;
	background-color:#F3F3F3;
    border:1px solid #DFD6BE;
	font-family:Arial, Helvetica, sans-serif;
	font-size:10pt;
}

.txtmsjadv{
	margin:0 5px 0 5px;
	color:#ff0000;
	font-family:Arial, Helvetica, sans-serif;
	font-size:10pt;
	text-align:center;
}



	
	
div.footer a
{
	color:#999999;
	text-decoration:none;
}
div.footer a:hover{
	text-decoration:underline;
}

/* Facturas */

/*Stilo tablas*/
.factura tbody{
	background:#fff;
}
.factura td{
	padding:3px;
        border:1px solid #d5d5d5;
	/*border-bottom:1px solid #d5d5d5;*/
}

.factura td.sl{
  	padding:3px;
        border:none;
}

.factura td.slb{
  	padding:3px;
        border-top:none;
	border-bottom:none;
        border-left:1px solid #d5d5d5;
        border-right:1px solid #d5d5d5;
}


.factura thead{
	height:25px;
	color:#265f7a;
}

/**ESTILOS PARA TABLAS INFORMACION*/
table.factura td{
	padding-top: 3px;
	padding-right: 3px;
	padding-bottom: 3px;
	padding-left: 3px;
	font-size:11px;
	color:#696969;
	font-size:12px;
	font-family:"Arial";
}

table.factura td.No{
 font-size:17px;
text-align:center;
     color:#D8000C;
}

table.factura input.cajas{
    background-color:#FFF;
               color:#535353;
           font-size:12px;
              border:1px solid #d5d5d5;
    width:60%;
    text-align:center;
}

table.factura input.cajass{
    background-color:#f6f6f6;
               color:#535353;
           font-size:13px;
              border:none;
          text-align:left;
          width:300px;
}


table.factura td.titulos
{
	text-align:center;
	vertical-align:middle;
}

table.factura td.cabe
{
	background-color:#000000;
	color:#FFFFFF;
	text-align:center;
	font-weight:bold;
}

table.factura td.pie
{
	text-align:justify;
	font-size:10px;
	color:#000000;
	font-weight:bold;
}


table.factura td.key, table.admintable td.paramlist_key{
	background-color: #f6f6f6;
	text-align: right;
	width: 230px;
	color: #666666;
	font-weight: bold;
	border: 1px solid #e9e9e9;
}

table.factura td:hover{
 /*cursor:pointer;*/
}

/*
 * Para mensages
*/

.info_system, .success_system, .warning_system, .error_system, .validation_system {
border: 1px solid;
margin: 10px 0px;
padding:15px 10px 15px 50px;
background-repeat: no-repeat;
background-position:10px center;
}
.info_system {
color: #00529B;
background-color: #BDE5F8;
background-image: url('../../images/info.png');
font-family:Verdana;
font-size:12px;
}
.success_system {
color: #4F8A10;
background-color: #DFF2BF;
background-image:url('../../img/success.png');
font-family:Verdana;
font-size:12px;
}
.warning_system {
color: #9F6000;
background-color: #FEEFB3;
background-image: url('../../img/warning.png');
font-family:Verdana;
font-size:12px;
}
.error_system {
color: #D8000C;
background-color: #FFBABA;
background-image: url('../../img/error.png');
font-family:Verdana;
font-size:12px;
}
.validation_system{
color: #D63301;
background-color: #FFCCBA;
background-image: url('../../img/validation.png');
}
.errors_system{
	color:#D8000C;
}

.formulario .error{
	color:#a52a2a;
}


/*  Complemento del Error */

.error_cpt{
  font-size:12px;
       width:91%;
 margin-left:4%;
}

/* Stilo de Tabs*/

.Tabs{
padding: 0;
width: 90%;
border-top: 1px solid #b3b3b3;

voice-family: "\"}\"";
voice-family: inherit;
margin-top: 8px;
margin-left:4%;
}

.Tabs ul{
margin:0;
margin-left:2px;
padding: 0;
list-style: none;
}Documentos

.Tabs li{
display: inline;
margin: 0 2px 0 0;
padding: 0;
text-transform:uppercase;
}

.Tabs a
{
  float: left;
display: block;
   font: bold 12px Arial;
  color: #b3b3b3;
text-decoration: underline;
         margin: 0 4px 0 0; 
        padding: 5px 10px 9px 10px; 

 -moz-border-radius-bottomleft: 5px;
     border-bottom-left-radius: 5px;
-moz-border-radius-bottomright: 5px;
    border-bottom-right-radius: 5px;
}

.Tabs a:hover{
     padding-top: 9px; 
  padding-bottom: 5px;
           color: #000000;
}

.Tabs .current a{ 
background-color: #f6f6f6; 
     padding-top: 9px; 
  padding-bottom: 5px; 
           color: #000000;
           
}

/* == Pagination === */
ul
{
   border:0;
   margin-left:0%;
  padding:0;

}

#pagination-digg
{
	text-align: center;
	margin-top: 6px;
}
	
#pagination-digg ul
{
	display: inline-block;
	margin: 0;
	padding: 0;
}

#pagination-digg li
{
	border:0; margin:0; padding:0;
	font-size:12px;
	list-style:none;
	margin-right:2px;
	display:inline;
}
#pagination-digg a
{
	margin-right:2px;
}

#pagination-digg .previous-off, #pagination-digg .next-off 
{
	color:#265F7A;
	display:block;
	float:left;
	font-weight:bold;
	margin-right:2px;
	padding:3px 4px;
}
#pagination-digg .next a, #pagination-digg .previous a {
	font-weight:bold;
}

#pagination-digg .active
{
	color:<?php echo $color?>;
	font-weight:bold;
	display:block;
	float:left;
	
	padding:3px 4px;
}
#pagination-digg a:link, #pagination-digg a:visited 
{
	color: #666;
	display:block;
	float:left;
	padding:3px 4px;
	text-decoration:none;
	font-size:12px;
	font-weight:bold;
}
#pagination-digg a:hover
{
	//border:solid 1px #265F72;
    background-color:#EBEBEB;
	color:#265F7A;
	/*
	background-color:#265F7A;
	color:#ffffff;
	*/
}


.pagination_detail
{
	text-align:right;
	color:#265F7A;
	padding-right:20px;
	padding-top:2px;
}

.Error_validar
{
	width:95%;
	float:left;
	color: #FFF;
	background-color: <?php echo $color?>;
	font-family:Verdana;
	font-size:12px;	
	border: 1px solid;
	margin: 10px 0px;
	padding:5px 3px 7px 10px;
	background-repeat: no-repeat;
	background-position:10px center;
}

.ExitoRespuesta
{
	width:95%;
	float:left;
	color: #4F8A10;
	background-color:#DFF2BF;
	font-family:Verdana;
	font-size:12px;	
	border: 1px solid;
	margin: 10px 0px;
	padding:5px 3px 7px 10px;
	background-repeat: no-repeat;
	background-position:10px center;
}

.DeudasPagos
{
	color:#ff0000;
    font-size:10pt;
}

.OcultarCapa{
display:none;
}

.MuestraCapa{
display:block;
}

/*Menus*/

div.toolbar
{
	
	padding: 0;
}

table.toolbar 
{
	border-collapse: collapse; 
	padding: 0; 
	margin: 0;
	margin-top:9px;
}

table.toolbar td 
{ 
	padding: 1px 1px 1px 4px; 
	text-align: center; 
	color: <?php echo $color?>; 
	height: 48px;
	font-size:11px;
	font-weight:100;
	cursor:pointer;
}

table.toolbar td.seccion
{
	font-size:16px;
	width:10%;
	text-align:left;
	font-weight:no;
	font-style:italic;
	height:16px;
}

.seccionDiv
{
	font-size:16px;
	width:800px;
	text-align:left;
	font-weight:600;
	font-style:italic;
	height:16px;
	color: <?php echo $color?>; 
}

table.toolbar td.spacer  { width: 10px; }
table.toolbar td.divider { border-right: 1px solid #eee; width: 5px; }

table.toolbar span { float: none; width: 32px; height: 32px; margin: 0 auto; display: block; }

table.toolbar a 
{
    display: block; 
	float: left;
	white-space: nowrap;
	border: 1px solid #fbfbfb;
	color: <?php echo $color?>;
	padding: 1px 5px;
	cursor: pointer;
	border:none;
}

table.toolbar a:hover {
	border-left: 1px solid #eee;
	border-top: 1px solid #eee;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	text-decoration: none;
	color: <?php echo $color?>;
}


table.toolbar span
{
	float: none;
	width: 32px;
	height: 32px;
	margin-top: 0pt;
	margin-right: auto;
	margin-bottom: 0pt;
	margin-left: auto;
	display: block;
	background-repeat:no-repeat;
}


table.cajaHerramienta a 
{
    display: block; 
	float: left;
	white-space: nowrap;
	border: 1px solid #fbfbfb;
	color: <?php echo $color?>;
	padding: 1px 5px;
	cursor: pointer;
	border:none;
}

table.cajaHerramienta a:hover {
	border-left: 1px solid #eee;
	border-top: 1px solid #eee;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	text-decoration: none;
	color: <?php echo $color?>;
}


table.cajaHerramienta span
{
	float: none;
	width: 32px;
	height: 32px;
	margin-top: 0pt;
	margin-right: auto;
	margin-bottom: 0pt;
	margin-left: auto;
	display: block;
	background-repeat:no-repeat;
}


.suggestionsBox 
{
	position: relative;
	left: 30px;
	margin: 10px 0px 0px 0px;
	width: 200px;
	background-color: #212427;
	//background-color: #b3b3b3;
	border-radius: 7px;
	border: 2px solid #000;	
	color: #fff;
}
	
.suggestionList 
{
	margin: 0px;
	padding: 0px;
}

.suggestionList li 
{
	margin: 0px 0px 3px 0px;
	padding: 3px;
	cursor: pointer;
}

.suggestionList li:hover 
{
	background-color: #659CD8;
}
	
	.textos
	{
		font-size:16px;
	}
	
	.textosRosa
	{
		color: #906;
		font-size:16px;
	}
	
	#caja
	{
		//width:70%;
		display: none;
		//padding:5px;
		//border:2px solid #FADDA9;
		//background-color:#FDF4E1;
	}
	
	#mostrar
	{
		display:block;
		//width:70%;
		//padding:5px;
		//border:2px solid #D0E8F4;
		//background-color:#ECF8FD;
	}
	


div.izquierda
{
	/*width: 1200px;*/
	height:30px; 
	//background-color:<?php echo $color?>;
}


/*=================================================================================*/
ul.menu_color
{
	list-style:none;
	margin-left:0px;
	//background-color:<?php echo $color?>;
	
}

.letrasMenu
{
	margin-top:0.1vh;
	text-align:center;
}

ul.menu_color li
{
	display:block;
	position:relative;
	width:100%;
	height:5.2vh;
	color:#FFF;
	font-size:1.8vh;
	/*font-weight:600;*/
	cursor:pointer;
	float:left;
	background-color:<?php echo $color?>;
	z-index:90;
}

ul.menu_color ul
{
	position:absolute;
	top:3vh;
	display:none;
	/*right:0.5px;*/
	list-style:none;
}

ul.menu_color li:hover
{
	background-color:#EEE;
	color:#666;
}

ul.menu_color li.activado
{
	background-color:#EEE;
	color:#666;
}

ul.menu_color li.desactivado
{
	color:#FFF;
	cursor:default;
	background-color:#8A9196;
}

ul.menu_color li:hover > ul
{
	display:block;
	z-index:200;
}

ul.menu_color  ul > li
{
	z-index:200;
	width:100%;
}

ul.menu_color ul > li > ul
{
	/*right: 32.7vh;*/
	top:-4.7vh;
	width:100%;
	right: 19.5vh;
	z-index:500;
	position:relative;
	margin-left: -10vh;
}

ul.menu_color ul > li > ul > li
{
	z-index:500;
}

.sinbordes
{
	-moz-border-radius: 0px
}

.bordesAbajo
{
	-moz-border-radius-bottomleft: 0px;
	-moz-border-radius-topleft: 0px;
}

.bordesArriba
{
	-moz-border-radius-bottomright: 0px;
	-moz-border-radius-topright: 0px;
}


/*SISTEMA DE NOTIFICACIONES PARA EVENTOS IMPORTANTES*/
.notificacionesPago
{
    background-color: #000;
    color: #FFF;
	font-weight:bold;
    width: 390px;
    -moz-border-radius:10px;
    -webkit-border-radius:10px;
    border-radius:10px;
	font-size:12px;
	max-height:400px;
	overflow:auto;
}

.notificacionesPago p
{
    padding: 10px;
    margin: 0;
}

.notificacionesPago.detalles
{
    background-color: #000;
	color:#FFF;
}

.notificaciones
{
    background-color: #000;
    color: #FFF;
	font-weight:bold;
    width: 390px;
    -moz-border-radius:10px;
    -webkit-border-radius:10px;
    border-radius:10px;
	font-size:12px;
	max-height:400px;
	overflow:auto;
}

.notificaciones p
{
    padding: 10px;
    margin: 0;
}

.notificaciones.detalles
{
    background-color: #000;
	color:#FFF;
}

.colorNotificacion
{
	/*color:<?php echo $color?>;*/
	color: #FFF;
}

/*SISTEMA DE NOTIFICACIONES*/
.notify
{
    background-color: #333;
    color: #FFF;
	font-weight:bold;
    width: 350px;
    -moz-border-radius:10px;
    -webkit-border-radius:10px;
    border-radius:10px;
	font-size:12px;
}
.notify p
{
    padding: 10px;
    margin: 0;
}
.notify.error
{
    background-color: <?php echo $color?>;
	color:#FFF;
}

.ui-widget-header 
{ 
	color: <?php echo $color?>/*{fcHeader}*/; 
}

/*BORRAR BUSQUEDAS*/
.borrarBusqueda
{
	width:20px !important;
	height:20px !important;
	cursor:pointer;
}

.custom-input-file 
{
	overflow: hidden;
	position: relative;
	cursor: pointer;
}

.custom-input-file .input-file 
{
	margin: 0;
	padding: 0;
	outline: 0;
	font-size: 10000px;
	border: 10000px solid transparent;
	opacity: 0;
	filter: alpha(opacity=0);
	position: absolute;
	right: -1000px;
	top: -1000px;
	cursor: pointer;
}

.contenedorReportes
{
	border: solid 1px #000; 
	width:208px; 
	border-radius: 7px; 
	background-color:<?php echo $color?>; 
	color:#FFF; height:30px; 
	vertical-align:middle;
	text-align:center;
	font-size:12px;
	line-height:27px;
	float:left;
	margin-right:30px;
	margin-bottom:30px;
	cursor:pointer;
}

.contenedorReportes:hover
{
	background-color:#EEE;
	color:#666;
}

.contenedorReportesDesactivado
{
	color:#FFF;
	cursor:default;
	background-color:#8A9196;
	
	border: solid 1px #000; 
	width:208px;  
	border-radius: 7px; 
	height:30px; 
	vertical-align:middle;
	text-align:center;
	font-size:14px;
	line-height:27px;
	float:left;
	margin-right:30px;
	margin-bottom:30px;
}

/*PUNTO DE VENTA*/
.puntoVenta > section.precio
{
	/*top:63px;
	left:20px;*/
	margin-top:0px;
	margin-left:15px;
	
	background-color:<?php echo $color?>;
	color:#FFF;
	border-radius: 5px;
	height:18px;
	width:65px;	
}

.cajasPrecios
{
	font-size:12px;
	background:<?php echo $color?>;
	color:#FFF;
	width:65px;	
	height:18px;	
	border-radius: 5px;
}

.botonPuntoVenta
{
	background:<?php echo $color?>;
	color:#FFF;
	width:100px;
	height:40px;
	cursor:pointer;
}

.botonPuntoVenta:hover
{
	background:#FFF;
	color:<?php echo $color?>;
	border : solid 1px <?php echo $color?>;
}

/*MENU TABS*/
ul.menuTabs
{
	list-style:none;
}

ul.menuTabs li
{
	display:block;
	width:160px;
	position:relative;
	height:35px;
	color:#FFF;
	font-size:11px;
	font-weight:600;
	cursor:pointer;
	border-top-right-radius: 8px; 
	border-top-left-radius: 8px;
	float:left;
	margin-right:3px;
	text-align:center;
	padding:2px 1px 2px 1px;
	margin-top:60px;
	background-color:<?php echo $color?>;
	line-height:35px;
	
	border-bottom: solid 0.1px <?php echo $color?>;
}

ul.menuTabs li.sinMargen
{
	margin-top:3px;
}

.tabChico
{
	margin-top:10px !important;
	font-size:10px !important;
	width:165px !important;
}

.tabChicoMargenIzquierdo
{
	margin-left:-54px;
}

ul.menuTabs li.texto
{
	line-height:40px;
}

ul.menuTabs li.bordesPrincipal
{
	border: solid 1px #9c9b9c;
	border-bottom:none;
}

ul.menuTabs ul
{
	position:absolute;
	left:226px;
	top:-1px;
	display:none;
	list-style:none;
}

ul.menuTabs li:hover
{
	background-color:#eee;	
	color:#050200;
	border-bottom: solid 0.1px #eee;
}

ul.menuTabs a
{
	text-decoration:none;
	color:#FFF;
}

ul.menuTabs li.activado
{
	background-image: -webkit-gradient( linear, left bottom, left top, color-stop(0.66, #C5D5D6), color-stop(0.68, #C5D5D6), color-stop(0.92, #E4ECED) );	
	color:#050200;
	border: solid 0.1px #d5d5d5;
	border-bottom:none;
	cursor:default;
}

ul.menuTabs li.desactivado
{
	background-image: linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -o-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -moz-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -webkit-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -ms-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	
	background-image: -webkit-gradient
	(
		linear,
		left bottom,
		left top,
		color-stop(0.4, #8A9196),
		color-stop(0.6, #9FA8AD)
	);
	
	color:#FFF;
	cursor:default;
}

ul.menuTabs li.desactivado:hover
{
	color:#FFF;
	cursor:default;
}

img.imgCuentasArbol
{
	width:18px;
	height:18px;
	cursor:pointer;
}


/*PARA LA CONTABILIDAD ELECTRÓNICA*/

/*PARA LAS TABLAS DE LOS MENUS*/
.tablaMenu
{
	color:#000;
	font-weight:100;
	cursor:pointer;
	width:100%;
	border-collapse:collapse;
	margin-top:9px;
}

.tablaMenu th
{
	height:25px;
	font-size:13px;
}

.tablaMenu td
{
	padding: 2px 2px 2px 2px;
	height:28px;
	font-size:16px;
}

.tablaMenu td img
{
	width:40px;
	height:40px;
	cursor:pointer;
	text-decoration:none;
	border:none;
}

/**ESTILOS PARA TABLAS INFORMACION*/
table.admintable td.vinculos
{
	color:<?php echo $color?>;
	text-align:center;
}

table.admintable td.vinculos img
{
	cursor:pointer;
	text-decoration:none;
	border:none;
}

.cajasMes
{
	border:1px solid #DFD6BE;
	font-size:12px;
	background:#F4F4F4;
	color:<?php echo $color?>;
	margin-left:5px;
	margin-top:5px;	
	height:18px;
	width:100px;		
}

a.crm:hover
{
	text-decoration:underline;
}

label.crm:hover
{
	text-decoration:underline;
}

table.admintable td.semanal
{
	z-index:99;
}

table.admintable td.semanal a
{
	z-index:110;
}


/*MENU DE BARRA */

.barraMenu
{
	background-color:<?php echo $color?>; 
}

ul.menuBarra li
{
	border: solid 1px <?php echo $color?>;
	background-color:<?php echo $color?> !important;
}

ul.menuBarra li:hover
{
	background-color:#FFF !important;
	color:<?php echo $color?> !important;
}

ul.menuBarra li.usuarioRegistrado > ul > li.configuracion:after
{
	color:<?php echo $color?> !important;
}

ul.menuBarra li.ayuda > ul > li.email:after
{
	color:<?php echo $color?> !important;
}


/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
/*TABS CLIENTE*/
/*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

/*MENU TABS*/
ul.menuTabsCliente
{
	list-style:none;
}

ul.menuTabsCliente li
{
	display:block;
	width:130px;
	position:relative;
	height:25px;
	color:#FFF;
	font-size:11px;
	font-weight:600;
	cursor:pointer;
	border-top-right-radius: 8px; 
	border-top-left-radius: 8px;
	float:left;
	margin-right:3px;
	text-align:center;
	padding:2px 1px 2px 1px;
	margin-top:10px;
	background-color:<?php echo $color?>;
	line-height:25px;
	
	border-bottom: solid 0.1px <?php echo $color?>;
}

ul.menuTabsCliente li.texto
{
	line-height:40px;
}

ul.menuTabsCliente li.bordesPrincipal
{
	border: solid 1px #9c9b9c;
	border-bottom:none;
}

ul.menuTabsCliente ul
{
	position:absolute;
	left:226px;
	top:-1px;
	display:none;
	list-style:none;
}

ul.menuTabsCliente li:hover
{
	background-color:#eee;	
	color:#050200;
	border-bottom: solid 0.1px #eee;
}

ul.menuTabsCliente a
{
	text-decoration:none;
	color:#FFF;
}

ul.menuTabsCliente li.activado
{
	background-color:#eee;	
	color:#050200;
	border: solid 0.1px #d5d5d5;
	border-bottom:none;
	cursor:default;
}

ul.menuTabsCliente li.desactivado
{
	background-image: linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -o-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -moz-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -webkit-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -ms-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	
	background-image: -webkit-gradient
	(
		linear,
		left bottom,
		left top,
		color-stop(0.4, #8A9196),
		color-stop(0.6, #9FA8AD)
	);
	
	color:#FFF;
	cursor:default;
}

ul.menuTabsCliente li.desactivado:hover
{
	color:#FFF;
	cursor:default;
}

.divCliente
{
	display:none;
}

.divCliente.visible
{
	display:block;
}


/*BOTON UPLOAD*/

.btn-primary 
{
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color:<?php echo $color?>;
}

.btn-primary:hover
{
	color: <?php echo $color?> !important;
	border: solid 1px <?php echo $color?>!important;
	background-color:#D5D5D5 !important;
}


/*CONTABILIDAD*/

div.menuCatalogo
{
	text-align:center;
	width:121px;
	font-size:12px;
	float:left;
	margin-top:5px;
	background-color:<?php echo $color?>;
	color:#FFF;
	height:50px;
	margin-left:5px;
	cursor:pointer;
	padding-top:13px;
	border: solid 1px <?php echo $color?>;
}

div.menuCatalogo:hover
{
	background-color:#FFF;
	color:<?php echo $color?>;
}

div.menuCatalogo.activado
{
	background-color:#FFF;
	color:<?php echo $color?>;
	cursor:default;
}

div.cuentaCatalogoAsociar
{
	background-color:<?php echo $color?>;
	color: #FFF;
	cursor: pointer;
	border: solid 1px <?php echo $color?>;
	width:170px;
	height:35px;
	float:left;
	margin-left:4px;
	margin-top:4px;
	text-align:center;
	font-size:11px;
	line-height:20px;
}

div.cuentaCatalogoAsociar.inicio
{
	
}
	

div.cuentaCatalogoAsociar:hover
{
	background-color: #FFF;
	color: <?php echo $color?>;
	cursor: pointer;
}

.fuenteRoja
{
	color: #F00 !important;
}

/*ESTILO PARA ARCHIVOS*/
.contenidoArchivo
{
	width: 100%;
	height:20px;
	margin-top: 5px;
	padding-left: 20px;
}

.contenidoArchivo img
{
	max-width: 20px;
	max-height: 20px;
	cursor:pointer;
	position:absolute;
	margin-left: -21px;
	margin-top: -5px;
}

.contenidoArchivo a:hover
{
	text-decoration:underline !important;
}

.botonesAlineados30
{
	width: 30%;
	text-align:center;
	float:left;
}

.botonesAlineados34
{
	width: 36%;
	text-align:center;
	float:left;
}

.botonesAlineados25
{
	width: 25%;
	text-align:center;
	float:left;
}

.botonesAlineados20
{
	width: 20%;
	text-align:center;
	float:left;
}

.botonesAlineados16
{
	width: 16%;
	text-align:center;
	float:left;
}

.botonesAlineados18
{
	width: 18%;
	text-align:center;
	float:left;
}



.panelIzquierdo
{
	/*width: 29%;*/
	text-align:center;
	/*float:left;*/
	padding: 5px;
	border: solid 1px;
	border-color: #999;
	min-height: 200px;
	max-height:700px;
	overflow: scroll;
	overflow-x: hidden;
	overflow-y: auto;
}

.panelDerecho
{
	/*width: 67%;*/
	text-align:center;
	/*float:right;*/
	padding: 5px;
	border: solid 1px;
	border-color: #999;
	min-height: 250px;
	max-height:auto;
	background: #FFF;
}

ul.tabSeguimiento
{
	list-style:none;
}

ul.tabSeguimiento li
{
	display:block;
	width:130px;
	position:relative;
	height:25px;
	color:#000;
	font-size:11px;
	font-weight:600;
	cursor:pointer;
	border-top-right-radius: 3px; 
	border-top-left-radius: 3px;
	float:left;
	margin-right:0px;
	text-align:center;
	padding:2px 1px 2px 1px;
	margin-top:0px;
	background-color: #E8E8E8;
	border: solid 1px #d5d5d5;
	
	line-height:22px;
	
	border-bottom: none !important;
	border-bottom: solid 0.1px <?php echo $color?>;
}

ul.tabSeguimiento li:hover
{
	background-color: #FFF;
}

ul.tabSeguimiento li.activado
{
	background-color: #FFF;
}


.margenResponsivoIzquierdo
{
	padding-left:0.1vh !important;
}

.margenResponsivoDerecho
{
	padding-right:0.1vh !important;
}

.margenResponsivo
{
	padding-left:0.1vh !important;
	padding-right:0.1vh !important;
}


/*MENU TABS PROSPECTOS*/
ul.menuTabsProspectos
{
	list-style:none;
}

ul.menuTabsProspectos li
{
	display:block;
	width:16vh;
	position:relative;
	height:3.5vh;
	color:#FFF;
	font-size:1.1vh;
	font-weight:600;
	cursor:pointer;
	border-top-right-radius: 0.8vh; 
	border-top-left-radius: 0.8vh; 
	float:left;
	margin-right:0.3vh;
	text-align:center;
	padding:0.2vh 0.1vh 0.2vh 0.1vh;
	margin-top:6vh;
	background-color:<?php echo $color?>;
	line-height:3.5vh;
	width: 18.7vh;
	
	border-bottom: solid 0.1vh <?php echo $color?>;
}

ul.menuTabsProspectos li.sinMargen
{
	margin-top:0.3px;
}

ul.menuTabsProspectos ul
{
	position:absolute;
	left:226px;
	top:-1px;
	display:none;
	list-style:none;
}

ul.menuTabsProspectos li:hover
{
	background-color:#eee;	
	color:#050200;
	border-bottom: solid 0.1px #eee;
}

ul.menuTabsProspectos a
{
	text-decoration:none;
	color:#FFF;
}

ul.menuTabsProspectos li.activado
{
	background-color:#eee;	
	color:#050200;
	border: solid 0.1px #d5d5d5;
	border-bottom:none;
	cursor:default;
}

ul.menuTabsProspectos li.desactivado
{
	background-image: linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -o-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -moz-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -webkit-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	background-image: -ms-linear-gradient(bottom, #8A9196 40%, #9FA8AD 60%);
	
	background-image: -webkit-gradient
	(
		linear,
		left bottom,
		left top,
		color-stop(0.4, #8A9196),
		color-stop(0.6, #9FA8AD)
	);
	
	color:#FFF;
	cursor:default;
}

ul.menuTabsProspectos li.desactivado:hover
{
	color:#FFF;
	cursor:default;
}

.circuloAlertas
{
    border-radius: 40px;
    height: 33px;
    width: 33px;
	background-color: #F00;
	position: absolute;  
	z-index: 100; 
	cursor: pointer;
	font-size:12px;
	color: #FFF;
	text-align:center;
	font-weight: bold;
	line-height: 32px;
	
	left:370px; margin-top: 42px; display: none
}


/*.circuloAlertas
{
    border-radius: 2.5vh;
    height: 2.5vh;
    width: 2.5vh;
	background-color: #F00;
	position: absolute;  
	margin-top: -6vh;
	z-index: 100; 
	cursor: pointer;
	font-size:1.4vh;
	color: #FFF;
	margin-left: -17vh;
}*/

#audio
{
	display: none
}

#audioAlertas
{
	display: none
}



/*TABLA PARA FIXED*/

/*Stilo tablas*/
.tablaFixed tbody
{
	background:#fff;
	cursor:pointer;
}

.tablaFixed
{
	background:#fff;
	cursor:pointer;
	border-collapse:collapse;
	margin-top:2px;
	width:540px;
}


.tablaFixed td
{
	padding:3px;
	border:1px solid #d5d5d5;
	vertical-align:middle;
}

.tablaFixed td.sinBorde
{
	border: none !important;
	background-color: #f1f1f1 !important;
	padding: 0;
}

.tablaFixed td.vinculos
{
	
}

.tablaFixed td.sinbordeTransparente
{
	border: none !important;
	background-color: #f1f1f1 !important;

}

.tablaFixed td.vinculos img
{
	width:22px;
	height:22px;
}

.tablaFixed td.imagenesLinea img
{
	max-width:80px;
	max-height:80px;
}

.tablaFixed td.formularios
{
	
}

.tablaFixed td.formularios img
{
	width:22px;
	height:22px;
}

.tablaFixed img
{
    border:none;
}


.tablaFixed th
{
	padding:3px;
	background-color:#f6f6f6;
	font-weight:normal;
	font-size:12px;
	vertical-align:middle;
	color:#666;
	border:1px solid #d5d5d5;
}

.tablaFixed th.resaltadoIexe
{
	background-color:#066;
	font-weight:bold;
	color:#FFF;
}

.tablaFixed th.encabezadoPrincipal
{
	height:40px;
	background-image: linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -o-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -moz-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -webkit-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -ms-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0.66, #C5D5D6),
		color-stop(0.68, #C5D5D6),
		color-stop(0.92, #E4ECED)
	);
}


.tablaFixed  thead
{
	display: block;
	color: #fff;
	background: #000;
	height:64px !important;
}

.tablaFixed  tbody
{
	display: block;
	height: 100%;
	overflow: auto;
}

.tablaFixed th.movible
{
	 position: fixed;
	 margin-top: 0;
	 width: 27.3%
}

.tablaFixed th.encabezadoPrincipalChico
{
	height:22px;
	background-image: linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -o-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -moz-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -webkit-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	background-image: -ms-linear-gradient(bottom, #C5D5D6 66%, #C5D5D6 68%, #E4ECED 92%);
	
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0.66, #C5D5D6),
		color-stop(0.68, #C5D5D6),
		color-stop(0.92, #E4ECED)
	);
}

.tablaFixed th.encabezadoTablero
{
	color:<?php echo $color?>;
	border:none;
	background-color:#FFF;
	text-align:left;
}

.tablaFixed th.encabezadoTablero a
{
	color:<?php echo $color?>;
}

.tablaFixed caption
{
	color:#586e83;
	font-weight:normal;
	text-align:center;
	padding-right:8px;
}

.tablaFixed thead
{
	height:25px;
	color:#265f7a;
	/*vertical-align:top;*/
}
/**ESTILOS PARA TABLAS INFORMACION*/
table.tablaFixed td
{
	padding-top: 3px;
	padding-right: 4px;
	padding-bottom: 3px;
	padding-left: 3px;
	font-size:11px;
	color:#696969;
}

table.tablaFixed td.totales
{
	font-weight:bold;
}

table.tablaFixed td label
{
	font-weight:100;
	font-size:11px;
}

table.tablaFixed tr.sombreado
{
	background-color: #EEE;
}

table.tablaFixed tr.sinSombra
{
}

table.tablaFixed tr.sinSombra:hover
{
	background-color:#D2D6D3;
}

table.tablaFixed tr.sombreado:hover
{
	background-color:#D2D6D3;
}

table.tablaFixed tr.fuenteNaranja > td
{
	color: #F60 !important;
}

table.admintable tr.seleccionado
{
	background-color:#CCC;
}


table.admintable td img
{
    border:none;
}

table.tablaFixed td a
{
	text-decoration:none;
	color:<?php echo $color?>;
	font-size:11px;
	//font-weight:bold;
}

table.tablaFixed th a
{
	text-decoration:none;
	color:#696969;
	//font-weight:bold;
}


/*TABLAS PARA EL CALENDARIO*/
.tablaCalendario
{
	color:#000;
	font-weight:100;
	cursor:pointer;
	border-collapse:collapse;
	width:100%;
}

.tablaCalendario th
{
	height:25px;
	background-color:#000;
	font-weight:600;
	font-size:14px;
	text-align:left;
	color:#FFF;
	padding: 2px 2px 2px 2px;
	text-align:center;
}

.tablaCalendario th a
{
	font-size:30px;
}

.tablaCalendario th a:hover
{
	text-decoration:none;
}

.tablaCalendario th.titulos
{
	background-color:#066;
	font-weight:bold;
	color:#FFF;
	font-size:20px;
}

.tablaCalendario th.titulos a
{
	color:#FFF;
}


.tablaCalendario th.titulos img
{
	width:24px;
	height:24px;
}

.tablaCalendario th.datos
{
	height:30px;
	
	background-color: #DBDBDB;
	
	font-weight:100;
	font-size:16px;
	color:#000;
	font-weight:bold;
}

.tablaCalendario th.datosSemanal
{
	height:30px;
	
	background-image: linear-gradient(bottom, rgb(208,221,228) 55%, rgb(239,244,246) 91%);
	background-image: -o-linear-gradient(bottom, rgb(208,221,228) 55%, rgb(239,244,246) 91%);
	background-image: -moz-linear-gradient(bottom, rgb(208,221,228) 55%, rgb(239,244,246) 91%);
	background-image: -webkit-linear-gradient(bottom, rgb(208,221,228) 55%, rgb(239,244,246) 91%);
	background-image: -ms-linear-gradient(bottom, rgb(208,221,228) 55%, rgb(239,244,246) 91%);

	background-image: -webkit-gradient
	(
		linear,
		left bottom,
		left top,
		color-stop(0.55, rgb(208,221,228)),
		color-stop(0.91, rgb(239,244,246))
	);
	
	font-weight:100;
	font-size:16px;
	color:#000;
	font-weight:bold;
	border: solid 1px #CCC;
}

.tablaCalendario th.transparente
{
	background-color:transparent;
}


.tablaCalendario td
{
	height:80px;
	font-size:15px;
	text-align:center;
	/*width:14.28%;*/
}

.tablaCalendario td a
{
	font-weight:bold;
	color:#36C;
	font-size:17px;
	text-align:center;
}

.tablaCalendario td a > div
{
	/*border: solid 2px #e4332d; border-radius: 20px; width:23px ;height:23px; margin-left: 39%*/
}

.tablaCalendario td a:hover
{
	text-decoration:none;
}

.tablaCalendario td.semanal
{
	font-size:14px;
	font-weight:bold;
	z-index:99;
	border: solid 1px #CCC;
	height:60px;
}

.sombreadoCitas
{
	height: 70px; 
	background-color: #000; 
	color: #000; 
	position: absolute; 
	width: 133px;
	margin-top: -30px;
}

.tablaCalendario td.semanal a
{
	font-weight:bold;
	color:#339608;
	font-size:12px;
	z-index:110;
}

.tablaCalendario td.camara img
{
	cursor:pointer; 
	width:50px; 
	height:50px;
}

.tablaCalendario td.etiquetas
{
	font-weight:bold;
	text-align:right;
	font-size:12px;
}

.tablaCalendario td.etiquetasGrandes
{
	font-weight:bold;
	text-align:right;
	font-size:16px;
}

.tablaCalendario .arriba:hover
{
	background-color: #eff4f6;
}

.tablaCalendario .abajo:hover
{
	background-color: #eff4f6;
}

.tablaCalendario .arriba
{
	background-color:#FFF;
}

.tablaCalendario .abajo
{
	background-color:#F9F9F9;
}

.tablaCalendario .abajo a
{
	text-decoration:none;
	color:#000;
	font-weight:600;
}

.tablaCalendario .arriba a
{
	text-decoration:none;
	color:#000;
	font-weight:600;
}

.tablaCalendario td img
{
	width:20px;
	height:20px;
	cursor:pointer;
}

.tablaCalendario td.vinculos img
{
	width:24px;
	height:24px;
	cursor:pointer;
}

.ventasCaja
{
	width: 100%;
	font-size: 25px;
	text-align: center;
}
	
.cajasCaja
{
	border:1px solid #DFD6BE;
	font-size:30px;
	background:#F4F4F4;
	color:<?php echo $color?>;
	height:50px;
	width:30%;		
}

.textoSincronizacion
{
	font-size: 30px;
	text-align:  center;
}



</style>
<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * int    $tipus: Tipo de fecha (0-timestamp, 1-dd/mm/aaaa)
 * */
function convertir_fecha($data, $tipus=1){
  if ($data != '' && $tipus == 0 || $tipus == 1)
  {
    $semana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'); 

    if ($tipus == 1)
    {
     // preg_match('([0-9]{1,2})-([0-9]{1,2})-([0-9]{2,4})', $data, $data);
      $data = preg_split('/-| /', $data);
     
      $data = mktime(0,0,0,$data[1],$data[2],$data[0]);
    } 

    return $semana[date('w', $data)].', '.date('d', $data).' '.$mes[date('m',$data)-1].' del '.date('Y', $data);
  }
  else
  {
    return 0;
  }
}

//************* Semana Año, Mes , Dia *****************************************


function get_NoSemanaYeart($week,$year,$day) {
   $week_en = array('Sunday','Monday','Twesday','Wednesday','Thursday','Friday','Saturday');
   if(empty($year))
       $year = date('Y');
   $first_day_year = strtotime("first $week_en[$day]",mktime(0,0,0,1,1,$year));

   return date('Y-m-d', strtotime('+'.--$week.' week',$first_day_year));
}

//date('z',strtotime('28/11/2008'));  //Numero de dial del año

//strftime("%W",mktime(0,0,0,12,13,2010)) Numero de semana actual 12=mes,13=dia, 2010-> año

/*
 <?php
$week_es = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');

$week = 41;
$year = 2007;
$day  = 6;  // Lunes

$out  = "El primer $week_es[$day] de la semana $week del año $year es: ";
$out .= ''. get_day_week($week,$year,$day) . '
';

$out .= "El primer Lunes de la semana $week del año 2004 es: ";
$out .= ''. get_day_week($week) . '
';

echo $out;

function get_day_week( $week, $year = '' , $day = '1') {
   $week_en = array('Sunday','Monday','Twesday','Wednesday','Thursday','Friday','Saturday');
   if (empty($year)) $year = date('Y');
   $first_day_year = strtotime("first $week_en[$day]",mktime(0,0,0,1,1,$year));
   return date('d.m.Y', strtotime('+'.--$week.' week',$first_day_year));
}
?>


 */




function suma_fechas($fecha,$ndias){
	
  
/*
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$año)=split("/", $fecha);

      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$año)=split("-",$fecha);
*/
               //dia-Mes-Año 0-01-02 
		  $Fecha=explode("-",$fecha);
		    $mes=$Fecha[1];
			$dia=$Fecha[0];
			$Ano=$Fecha[2];
			  

            $nueva = mktime(0,0,0, $mes,$dia,$Ano) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("Y-m-d",$nueva);

      return ($nuevafecha);
}//Termina Fechas

function convertirMonea($Moneda,$Precio,$Dolar,$Euro){

    $PrecioA=0;

  switch($Moneda){
    case "MXN":          $PrecioA=Sprintf("% 01.2f",$Precio);
                          $Moneda="Pesos";
                $TipoCambioMoneda="MXN";
                break;
    case "USD":           $PrecioA=Sprintf("% 01.2f",$Precio*$Dolar);
                           $Moneda="Dolar: $ ".number_format($Dolar,2);
                 $TipoCambioMoneda=$Dolar;
               break;
    case "EUR":           $PrecioA=Sprintf("% 01.2f",$Precio*$Euro);
                           $Moneda="Euro: $ ".number_format($Euro,2);
                 $TipoCambioMoneda=$Euro;
               break;
  }//Switch

 return $PrecioA."^".$Moneda;

}

function ConvertirMonedas($TipoMoneda,$Moneda,$Precio,$Dolar,$Euro,$Factor){		
  $Precios=0;				
  
  $Dolar=$Dolar*$Factor;

	switch($TipoMoneda){
		case "MXN": 		
				  switch($Moneda){
					//case "MXN":$Precios=Sprintf("% 01.2f",$Precio);break;
					//case "USD":$Precios=Sprintf("% 01.2f",$Precio*$Dolar);break;
					case "EUR":$Precios=Sprintf("% 01.2f",$Precio*$Euro);break;
				  }//Switch		            					
	   			   //***************************************
					break;					
		case "USD":
				   switch($Moneda){
					 //case "MXN":$Precios=Sprintf("% 01.2f",$Precio*$Dolar);break;
					 //case "USD":$Precios=$Precio;break;
					 case "EUR":$Precios=Sprintf("% 01.2f",($Precio/($Dolar/$Euro))); break;
				    }//Switch		            					
		            break;
		
		case "EUR":		
				   switch($Moneda){
					 //case "MXN":$Precios=Sprintf("% 01.2f",$Precio*$Euro); break;
					 //case "USD":$Precios=Sprintf("% 01.2f",($Precio/($Euro/$Dolar))); break;
					 case "EUR":$Precios=$Precio;break;
				    }//Switch		    
		            break;
		
	}//switch

 return $Precios;
	
}



//$nuevaFecha= date('Y-m-d', strtotime('-1 year')) ; // resta 1 año

?>
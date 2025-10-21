<?php
class Inventario extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $_Variables;

    function __construct()
	{
		parent::__construct();
        //verificar si el el usuario ha iniciado sesion
    	if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 	  	}

        $this->_fechaActual = mdate("%Y-%m-%d %H:%i:%s",now());
        $this->_iduser = $this->session->userdata('id');
	    $this->config->load('style', TRUE);
        $this->_csstyle = $this->config->item('style');
        $this->config->load('js',TRUE);
  	    $this->_jss=$this->config->item('js');
		
		$this->load->model("modelousuario","modelousuario");
   	    $this->load->model("modeloclientes","modeloclientes");
	    $this->load->model("proveedores_model","modeloproveedores");
		$this->load->model("inventario_model","modeloinventario");
		
        $this->load->model("modelo_configuracion","configuracion");
        $this->_Variables=$this->configuracion->getAllVariables();
  	}

	public function index($Limite=0)
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
		
		$Data['csui']=$this->_csstyle["csui"];
		////$Data['csuidemo']=$this->_csstyle["csuidemo"];
		
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		
		$Data['Jry']=$this->_jss['jquery'];
		$Data['JFuntInventario']=$this->_jss['JFuntInventario'];
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Conte['Categoria']=$this->uri->segment(1);
		
		//Paginar el inventario ///
		
		// First Links
		$Pag['first_link'] = 'Inicio';
		$Pag['first_tag_open'] = '<li>';
		$Pag['first_tag_close'] = '</li>';
		
		// Last Links
		$Pag['last_link'] = 'Fin';
		$Pag['last_tag_open'] = '<li>';
		$Pag['last_tag_close'] = '</li>';
		
		// Next Link
		$Pag['next_link'] = '&raquo;';
		$Pag['next_tag_open'] = '<li>';
		$Pag['next_tag_close'] = '</li>';
		
		// Previous Link
		$Pag['prev_link'] = '&laquo;';
		$Pag['prev_tag_open'] = '<li>';
		$Pag['prev_tag_close'] = '</li>';
		
		// Current Link
		$Pag['cur_tag_open'] = '<li class="active">';
		$Pag['cur_tag_close'] = '</li>';
		
		// Digit Link
		$Pag['num_tag_open'] = '<li>';
		$Pag['num_tag_close'] = '</li>';
		
		$Pag["base_url"]= base_url()."inventario/index/";
		$Pag["total_rows"]=$this->modeloinventario->coutproducto();//Total de Registros
		$Pag["per_page"]=15;
		$Pag["num_links"]=5;
		
		$this->pagination->initialize($Pag);
		
		$Conte['Inventarios'] = $this->modeloinventario->get_AllPag($Pag["per_page"],$Limite);
		
		$Conte['inicio']  = $Limite;
		$num_r = count($Conte['Inventarios']);
		$Conte['per_page']= $num_r;
		
		//***********************************//
		//$Conte['Inventarios']=$this->modeloinventario->getAll();
		
		$this->load->view("inventario/index",$Conte); //principal lista de clientes
		
		$this->load->view("pie",$Data);
	
	}//index
 

	public function add()
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
				
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		
		$Data['Jry']=$this->_jss['jquery'];
		$Data['jvalidate']=$this->_jss['jvalidate'];
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		$Conte['proveedores']=$this->modeloinventario->proveedores();
		$Conte['Categoria']=$this->uri->segment(1);
		//$Conte['categorias']=$this->configuracion->vercategorias();
		// $Conte['lineas']=$this->configuracion->verlineas();
		$this->load->view("inventario/add",$Conte);
		
		$this->load->view("pie",$Data);
	}//Finn del ADD

public function saveNewProducto()
{
			$image = $this->_upload_image();
            if($image != NULL)

if(!empty ($_POST)){
        if($this->modeloinventario->addProducto($image) != NULL){
             $this->session->set_flashdata('message', array('messageType' => 'success','Message' => 'El registro se ha almacenado correctamente.'));
        }else{
             $this->session->set_flashdata('message', array('messageType' => 'error','Message' => 'Ocurrio un error al guardar el registro.'));
           }
     redirect('inventario','refresh');

 }else {
     redirect('inventario/add','refresh');
    }
    //echo $this->input->post('proveedor_0');
    //echo $this->input->post('proveedor_1');
}//saveNewProveedor



	public function editar($id)
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
		
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		
		$Data['Jry']=$this->_jss['jquery'];
		$Data['jvalidate']=$this->_jss['jvalidate'];
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Conte['Categoria']=$this->uri->segment(1);
		$Conte['Inventario']=$this->modeloinventario->getAllProveedor($id);
		// $Conte['categorias']=$this->configuracion->vercategorias();
		$Conte['proveedores_producto']=$this->modeloinventario->Proveedor($id);
		//  $Conte['lineas']=$this->configuracion->verlineas();
		
		$this->load->view("inventario/editar",$Conte);
		
		$this->load->view("pie",$Data);
	}

public function borra_proveedor($id_prod,$id_prov)
{

    $r=$this->modeloinventario->delproveedor($id_prod,$id_prov);
    redirect("inventario/editar/$id_prod","refresh");


}



//***** Guardar Cambios del proveedor

public function saveProducto(){

   if(!empty ($_POST)){
		
       $id = intval($this->input->post('id'));		
		
        if($this->modeloinventario->SaveProducto($id,$this->_upload_image('image', TRUE)) != NULL){
          $this->session->set_flashdata('message', array('messageType' => 'success','Message' => 'El registro se ha almacenado correctamente.'));
        }else{
             $this->session->set_flashdata('message', array('messageType' => 'error','Message' => 'Ocurrio un error al guardar el registro.'));
        }
     redirect('inventario','refresh');
    }else {
     redirect('inventario','refresh');
    }


}//saveProveedor

public function listaproductos($Limite=0){
  // First Links
$Pag['first_link'] = 'Inicio';
$Pag['first_tag_open'] = '<li>';
$Pag['first_tag_close'] = '</li>';

// Last Links
$Pag['last_link'] = 'Fin';
$Pag['last_tag_open'] = '<li>';
$Pag['last_tag_close'] = '</li>';

// Next Link
$Pag['next_link'] = '&raquo;';
$Pag['next_tag_open'] = '<li>';
$Pag['next_tag_close'] = '</li>';

// Previous Link
$Pag['prev_link'] = '&laquo;';
$Pag['prev_tag_open'] = '<li>';
$Pag['prev_tag_close'] = '</li>';

// Current Link
$Pag['cur_tag_open'] = '<li class="active">';
$Pag['cur_tag_close'] = '</li>';

// Digit Link
$Pag['num_tag_open'] = '<li>';
$Pag['num_tag_close'] = '</li>';

if(!empty ($_POST)){

    if($this->input->post('T1')=="all"){

          $Pag["base_url"]= base_url()."inventario/listaproductos";
        $Pag["total_rows"]=$this->modeloinventario->coutproducto();//Total de Registros
          $Pag["per_page"]=50;
         $Pag["num_links"]=5;

        $this->pagination->initialize($Pag);

        $Datos['Registros'] = $this->modeloinventario->get_AllPag($Pag["per_page"],$Limite);

        $Datos['inicio']  = $Limite;
  	           $num_r = count($Datos['Registros']);
	$Datos['per_page']= $num_r;

           $Datos["Serie"]=$this->input->post('serie');

    }//Muestra todos de la lista
    else
       if($this->input->post('T1')=="pag"){

          $Pag["base_url"]= base_url()."inventario/listaproductos";
        $Pag["total_rows"]=$this->modeloinventario->coutproducto();//Total de Registros
          $Pag["per_page"]=50;
         $Pag["num_links"]=5;

        $this->pagination->initialize($Pag);

        $Datos['Registros'] = $this->modeloinventario->get_AllPag($Pag["per_page"],$Limite);

        $Datos['inicio']  = $Limite;
                   $num_r = count($Datos['Registros']);
        $Datos['per_page']= $num_r;

           $Datos["Serie"]=$this->input->post('serie');


     }//Pag


}//Fin del POST

$this->load->view("inventario/paginacion",$Datos);

//***********************
}//Termina listaproductos


public function DeleteProductos(){

  if(!empty($_POST)){

      if($this->modeloinventario->deleteDatosProductosEnsamble($this->input->post('Idp'),$this->input->post('serie'))==TRUE){
		  $ListProductos["TpoMoneda"]=$this->input->post('TpoMoneda');
		          $ListProductos["Dolar"]=$this->input->post('Dolar');
	               $ListProductos["Euro"]=$this->input->post('Euro');
            $ListProductos["Productos"]=$this->modeloinventario->getDatosProductosEnsamble($this->input->post('serie'));
      }//if
  }//empty _POST

$this->load->view("inventario/listaproductos",$ListProductos);

}//DeleteProductosFtosDesc

public function existenProductosEnsambles(){
$BAND=0;
   if($this->modeloinventario->getDatosProductosEnsamble($this->input->post('serie'))!=NULL){
       $BAND=1;
   }
  print($BAND);
}

//********* Convertir Moneda *****************

public function ConvetirMoneda(){

 if(!empty($_POST)){

  $this->load->helper('fecha');
	 
	 $TpoMoneda=$this->input->post('TpoMoneda');
	    $Precio=$this->input->post('Precio');
	    $Moneda=$this->input->post('Moneda');
	
	print(ConvertirMonedas($TpoMoneda,$Moneda,$Precio,$this->_Variables[0]["dolar"],$this->_Variables[0]["euro"])); 
   
 }//Fin del empty
	
	
}

/*
public function ActualizarProveedores(){

      $J=2;
  $Final="2982";

  while($J<=$Final){

        $data_c=array(
        'id_prod' => $J,
        'id_prov' => 1,
          'fecha' => date("Y-m-d")
     );

    $data_c = $this->input->xss_clean($data_c);
    $this->db->insert('proveedores_deproductos', $data_c);
    $J++;
  }//While

}//A

*/

        private function _upload_image($field_name = 'image', $edit = FALSE)
        {

                $config['upload_path'] = './media/images_productos/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']	= '1500';
                $config['max_width'] = '1500';
                $config['max_height'] = '1024';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                $this->upload->initialize($config);

                if($this->upload->do_upload($field_name)){
                    $data = $this->upload->data();
                    $this->_thumbnail($data['file_name']);
                    return $data['file_name'];
                }else{
                    if($edit == FALSE){
                        $this->session->set_flashdata('message', $this->upload->display_errors());
                        redirect('inventario/add', 'refresh');
                    }else{

                        return NULL;
                    }

                }
        }

        private function _thumbnail($image)
        {

            $this->load->library('image_lib');
            $config['image_library']  = 'gd2';
            $config['source_image']   = './media/images_productos/'.$image;
            $config['new_image']      = './media/images_productos/thumbs/'.$image;
            $config['thumb_marker']   = '';
            $config['create_thumb']   = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 20;
            $config['height'] = 20;

            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $this->image_lib->clear();




        }

//Termina la Clase
}
?>

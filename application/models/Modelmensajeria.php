<?php

class CI_Modelmensajeria extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;

    function __construct(){
	parent::__construct();
	$this->config->load('datatables',TRUE);
	$this->_table = $this->config->item('datatables');
	$this->_fecha_actual = mdate("%Y-%m-%d %H:%i:%s",now());
        $this->_user_id = $this->session->userdata('id');

        $this->load->CI_Model("CI_Modelousuario","CI_Modelusuarios");
    }

    /*
     * Public methods
     */

    
 public function ActualizarInventario(){



 $data=array(

           "idct"=>$this->input->post('Idct'),
            "idcl"=>$this->input->post('Idcl'),
       //    "idm"=>$this->input->post('Idm'),
        "fechadd"=>$this->_fecha_actual,
       "cantidad"=>$this->input->post('cantidad'),
"nombre_recibido"=>$this->input->post('Nombre'),
 "fecha_recibido"=>$this->input->post('FechaReal'),
     "created_by"=>$this->_user_id
   );

 

 $this->db->insert($this->_table['historia_inventario'], $data);

 return ($this->db->affected_rows() == 1)? TRUE : NULL ;
     
 }//ActualizarInventario


 public function getHistoriaInventario($Idp,$Idpv){

     $SQL="SELECT * FROM ".$this->_table['historia_inventario']. " WHERE idct='$Idp' AND idcl= '$Idpv'";

     $consulta=$this->db->query($SQL);

     return ($consulta->num_rows() > 0) ?  $consulta->row_array() : NULL;

 }//getHistoriaInventario


    public function getExisteMensaje($idct,$idpv){
        
      $this->db->where('id_cotiza',$idct);
      $this->db->where('id_cliente',$idpv);
     // $this->db->where('id_producto',$idp);

      $query = $this->db->get($this->_table['mensajeria_clientes']);

      return ($query->num_rows() > 0)? $query->row_array() : NULL;
      
    }//fin de getExisteMensaje

    public function getGuardarMensaje(){
      
        $data=array(
            'no_guia'=>$this->input->post('id_nomj'),
     'nombre_paquete'=>$this->input->post('id_nombrep'),
       'fecha_arribo'=>$this->input->post('FechaDia'),
       'nombre_envia'=>$this->input->post('id_nombrev'),
        'Hora_arribo'=>$this->input->post('HoraArriba'),
          'id_cotiza'=>$this->input->post('idct'),
       'id_cliente'=>$this->input->post('idcl'),

        );

      $this->db->insert($this->_table['mensajeria_clientes'], $data);

      return ($this->db->affected_rows() == 1)? $this->db->insert_id() : NULL ;

    }//getGuardarMensaje



}//Class
?>

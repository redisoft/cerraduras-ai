<?php
class Productos_model extends CI_Model
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
    }//contructor

    /*
     * Public methods
     */

    public function getAll()
    {
        $this->db->where('block != ',1);
        $query = $this->db->get($this->_table['productos']);

        return ($query->num_rows() > 0)? $query->result() : NULL;
    }

    public function getAllProductosFamilia(){
     
     $SQL="SELECT A.*,B.id,B.nombre FROM ".$this->_table['productos']." as A,".$this->_table['familias']." as B
           WHERE A.id_familia=B.id ORDER BY A.created";
    
        return ($query->num_rows() > 0)? $query->row_array() : NULL;
        
    }//Fin del getAllProductosFamilia


public function count(){
        $this->db->where('block != ', 1);
        return $this->db->count_all_results($this->_table['productos']);
}//count


    public function create($image = NULL, $ficha_tecnica = NULL){
       
        $data = array('descripcion' => $this->input->post('descripcion'),
                            'clave' => $this->input->post('clave'),
                           'moneda' => $this->input->post('moneda'),
                       'familia_id' => $this->input->post('familia'),
                          'created' => $this->_fecha_actual,
                           'precio' => $this->input->post('precio'),
                  'precio_sugerido' => $this->input->post('precios'),
                        'mano_obra' => $this->input->post('txtMano'),
                           'flete'  => $this->input->post('flete'),
                         'especial' => $this->input->post('especial'),
                       'created_by' => $this->_user_id,
                      'modified_by' => $this->_user_id,
                           'cmxima' => $this->input->post('cmx'),
                          'cminima' => $this->input->post('cmn'),
                         'cingresa' => $this->input->post('cing'),
                       'cexistente' => $this->input->post('cing'),
                         'modified' => $this->_fecha_actual);

        if($image != NULL){
            $data['imagen'] = $image;
        }
        if($ficha_tecnica != NULL){
            $data['ficha_tecnica'] = $ficha_tecnica;
        }

        $this->db->insert($this->_table['productos'], $data);

        if($this->db->affected_rows() == 1){
            $producto_id = $this->db->insert_id();
            if($this->input->post('rows') > 0){
                // Agregar todos los proveedores
                for($x=0; $x <= $this->input->post('rows'); $x++){
                    if($this->input->post('costo_'.$x) != NULL){
                    $ganancia = (($this->input->post('precio')- $this->input->post('costo_'.$x))/$this->input->post('costo_'.$x))*100;
                    $data_ = array('proveedor_id' => $this->input->post('proveedor_'.$x),
                           'producto_id' => $producto_id,
                           'costo' => $this->input->post('costo_'.$x),
                           'modified' => $this->_fecha_actual,
                           'ganancia' => $ganancia);
                    $this->db->insert($this->_table['productos_proveedores'], $data_);
                    }
                }
                // Agregar Fabricantes
                for($x=0; $x <= $this->input->post('rowsF'); $x++){

                  if($this->input->post('val_'.$x)==true){
                     $data_ = array(
                              'fabricante_id' => $this->input->post('fabricante_'.$x),
                                'producto_id' => $producto_id
                             );
                    $this->db->insert($this->_table['fabricante_productos'], $data_);
                  }
                }//For
               


            }//if de rows
            return $producto_id;
                
        }else{
         return  NULL;
        }
}

    public function edit($producto_id, $image = NULL,$Ficha=NULL)
    {
	 $data = array(
  	          "descripcion" => $this->input->post('descripcion'),
				    "clave" => $this->input->post('clave'),                     
				   "moneda" => $this->input->post('moneda'),
			   "familia_id" => $this->input->post('familia'),
				   "precio" => $this->input->post('precio'),
		  "precio_sugerido" => $this->input->post('precios'),
			    "mano_obra" => $this->input->post('txtMano'),					  
				   "flete"  => $this->input->post('flete'),
				 "especial" => $this->input->post('especial'),
		      "modified_by" => $this->_user_id,
			       "cmxima" => $this->input->post('cmx'),
				  "cminima" => $this->input->post('cmn'),
				 "cingresa" => $this->input->post('cing'),
			   "cexistente" => $this->input->post('cing'),
				 "modified" => $this->_fecha_actual);
				 			 
        if($image != NULL){
            $data["imagen"] = $image;
        }
				
	   if($Ficha != NULL){
            $data["ficha_tecnica"] = $Ficha;
        }
		
			
    									
		$this->db->update($this->_table['productos'], $data,array("id"=>$producto_id));
		
        return ($this->db->affected_rows() == 1)? TRUE : FALSE;
    }

    public function delete($producto_id)
    {
        $this->db->where('id', $producto_id);
//        $this->db->delete($this->_table['productos']);
        $this->db->update($this->_table['productos'], array('block' => 1));

        return ($this->db->affected_rows() == 1)? TRUE : FALSE;
    }

    public function getById($producto_id)
    {
        $this->db->select($this->_table['productos'].'.*, '.$this->_table['productos'].'.id as producto_id,'.
                $this->_table['familias'].'.nombre as familia');
        $this->db->where($this->_table['productos'].'.id', $producto_id);
        
        $this->db->join($this->_table['familias'],
                $this->_table['familias'].'.id = '.$this->_table['productos'].'.familia_id');
        $query = $this->db->get($this->_table['productos']);

//        echo '<pre>'.$this->db->last_query().'</pre>';
        return ($query->num_rows() > 0)? $query->row_array() : NULL;
    }

    public function getProveedoresByProducto($producto_id)
    {
        $this->db->select($this->_table['proveedores'].'.nombre as proveedor, '.

                $this->_table['productos_proveedores'].'.*');
        $this->db->join($this->_table['proveedores'],
                $this->_table['proveedores'].'.id = '.$this->_table['productos_proveedores'].'.proveedor_id');
        $this->db->join($this->_table['productos'],
                $this->_table['productos'].'.id = '.$this->_table['productos_proveedores'].'.producto_id');
        $this->db->where($this->_table['productos'].'.id', $producto_id);

        $query = $this->db->get($this->_table['productos_proveedores']);
//        echo '<pre>'.$this->db->last_query().'</pre>';
        return ($query->num_rows() > 0)? $query->result_array() : NULL;

    }

   public function getProductoByProveedor($producto_id){


    }//getProductoByProveedor


    public function countByProductoID($producto_id)
    {
        $this->db->where('producto_id', $producto_id);

        return $this->db->count_all_results($this->_table['productos_proveedores']);
    }

    public function ajaxSaveProveedor($pp_id)
    {
        $producto = $this->getById($this->input->post('producto'));
        $ganancia = (($producto['precio'] - $this->input->post('costo'))/$this->input->post('costo'))*100;
        $data = array('proveedor_id' => $this->input->post('proveedor'),
                      'costo' => $this->input->post('costo'),
                      'ganancia' => $ganancia,
                      'modified' => $this->_fecha_actual);
        $this->db->where('id', $pp_id);

        $this->db->update($this->_table['productos_proveedores'], $data);

        return ($this->db->affected_rows() == 1)? TRUE : FALSE ;

    }

    public function ajaxDeleteProveedor($pp_id){
        $this->db->where('id', $pp_id);
        $this->db->delete($this->_table['productos_proveedores']);

        return ($this->db->affected_rows() == 1)? TRUE : FALSE ;
    }

    public function ajaxAddProveedor()
    {
        $producto = $this->getById($this->input->post('producto'));
        $ganancia = (($producto['precio']- $this->input->post('costo'))/$this->input->post('costo'))*100;
        $data = array('proveedor_id' => $this->input->post('proveedor'),
                      'producto_id' => $this->input->post('producto'),
                      'costo' => $this->input->post('costo'),
                      'ganancia' => $ganancia,
                      'modified' => $this->_fecha_actual);

        $this->db->insert($this->_table['productos_proveedores'], $data);

        return ($this->db->affected_rows() == 1)? $this->db->insert_id() : NULL ;
    }


   public function AddProveedorByProducto(){

       $producto = $this->getById($this->input->post('T2'));
     if($producto!=NULL){

        $data = array('proveedor_id' => $this->input->post('T1'),
                       'producto_id' => $this->input->post('T2'),
                          'modified' => $this->_fecha_actual);

        $this->db->insert($this->_table['productos_proveedores'], $data);

        return ($this->db->affected_rows() == 1)? $this->db->insert_id() : NULL ;
       }//If
     return NULL;
  }//AddProveedorByProducto

    // Fabricante
	
  public function ajaxAddFabricante(){
//fabricante_id	producto_id
        $data = array('fabricante_id' => $this->input->post('idf'),
                      'producto_id' => $this->input->post('idp')
					 );

        $this->db->insert($this->_table['fabricante_productos'], $data);

        return ($this->db->affected_rows() == 1)? $this->db->insert_id() : NULL ;
    }

    public function ajaxDeleteFabricante($f_id){
        $this->db->where('id', $f_id);
        $this->db->delete($this->_table['fabricante_productos']);

        return ($this->db->affected_rows() == 1)? TRUE : FALSE ;
    }		

  public function ajaxSaveFabricante($f_id){
	  	              
        $data = array('fabricante_id' => $this->input->post('id_f'),
                      'producto_id' => $this->input->post('id_p')
					 );
					 
        $this->db->where('id', $f_id);

        $this->db->update($this->_table['fabricante_productos'], $data);

        return ($this->db->affected_rows() == 1)? TRUE : FALSE ;

    }


//Detectar productos para proveedores para realializar la compra....
//**************** Proveedores compras


 public function getCoutProductosProveedores($Serie){
//A.cexistente<A.cminima OR 
$SQL="SELECT  A.id,A.cexistente,A.cminima,A.descripcion,B.serie,B.idp,B.block,B.cantidad
           FROM ".$this->_table['productos']." as A, ".$this->_table['productos_descuentos_fotos']." as B
      WHERE  (A.id=B.idp AND  (A.cexistente<=0)) AND B.serie='".$Serie."'";

$consulta=$this->db->query($SQL);

  return ($consulta->num_rows() > 0) ?  $consulta->result_array() : NULL;

 }//getCoutProductosProveedores
    
// Lista de productos IN

 public function getProductosProveedorIN($Idsps){

  $SQL="SELECT  A.proveedor_id,A.producto_id,A.costo,B.id,B.nombre,B.domicilio,B.email,B.tels,B.RFC
           FROM ".$this->_table['productos_proveedores']." as A, ".$this->_table['proveedores']." as B
        WHERE  (A.producto_id IN (".$Idsps.")) AND A.proveedor_id=B.id ORDER BY A.proveedor_id ";

     $consulta=$this->db->query($SQL);

     return ($consulta->num_rows() > 0) ?  $consulta->result_array() : NULL;
 }//getProductosIN


 public function getProductosProveedorCostoBy($Idpv,$Idp){

     $SQL="SELECT * FROM ".$this->_table['productos_proveedores']." WHERE proveedor_id='".$Idpv."' AND producto_id='".$Idp."'";
   $query=$this->db->query($SQL);

     return ($query->num_rows() > 0) ?  $query->row_array() : NULL;
     
 }//

  public function getProductosIN($Idsps){

     $SQL="SELECT * FROM ".$this->_table['productos']." WHERE id IN(".$Idsps.") AND cexistente<=0";

     $consulta=$this->db->query($SQL);

     return ($consulta->num_rows() > 0) ?  $consulta->result_array() : NULL;
 }//getProductosIN


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

 //"cantidad":No,"Idp":Idp,"Idpv":Idpv,"Nombre":T1,"FechaReal":T2,"Idm":Idm,"Idc":Idc,"NoSerie[]":NoSerie}
  public function ActualizarInventarioProveedores(){

 $data=array(

           "idpv"=>$this->input->post('Idpv'),
           "idp"=>$this->input->post('Idp'),
            "idm"=>$this->input->post('Idm'),
        "fechadd"=>$this->_fecha_actual,
       "cantidad"=>$this->input->post('Cantidad'),
"nombre_recibido"=>$this->input->post('Nombre'),
 "fecha_recibido"=>$this->input->post('FechaReal'),
     "created_by"=>$this->_user_id
   );

 

 $this->db->insert($this->_table['historia_inventario_proveedores'], $data);
 $IdHIP=$this->db->insert_id();
  //Para insertar los nuevos números de serie By producto
 $J=0;
 $Cantidad=$this->input->post('Cantidad');
  $NoSerie=$this->input->post('NoSerie');
 
  while($J<$Cantidad){
    $data_ = array(
	              'idp' => $this->input->post('Idp'),
		 'no_serie' => $NoSerie[$J],
   'id_historia_proveedores'=>$IdHIP,
		  'fechadd' => $this->_fecha_actual,
		    'block' => "1",
                     'idpv' => $this->input->post('Idpv')
          );
          $data_ = $this->input->xss_clean($data_);
      $this->db->insert($this->_table['historiaProductosSerieProveedores'], $data_);
      $IdSeries[]= $this->db->insert_id();
    $J++;
  }
  
 return ($this->db->affected_rows() == 1)? TRUE : NULL ;

 }//ActualizarInventario


 public function getHistoriaInventario($Idm,$Idp,$Idpv){

     $SQL="SELECT * FROM ".$this->_table['historia_inventario']. " WHERE idm='".$Idm."' AND idct='".$Idpv."' AND idcl='".$Idp."'";

     $consulta=$this->db->query($SQL);

     return ($consulta->num_rows() > 0) ?  $consulta->row_array() : NULL;

 }//getHistoriaInventario

 public function getHistoriaInventarioProveedores($Idm,$Idpv,$Idp){

     $SQL="SELECT * FROM ".$this->_table['historia_inventario_proveedores']. " WHERE idm='".$Idm."' AND idpv='".$Idpv."' AND idp ";

     $consulta=$this->db->query($SQL);

     return ($consulta->num_rows() > 0) ?  $consulta->row_array() : NULL;

 }//getHistoriaInventario

 public function getHistoriaInventarioProductos($Id_historia){

     $SQL="SELECT * FROM ".$this->_table["historiaProductosSerieProveedores"]." WHERE id_historia_proveedores='".$Id_historia."'";
     $consulta=$this->db->query($SQL);
     return ($consulta->num_rows() > 0) ?  $consulta->result_array() : NULL;
 }//public getHistoriaInventarioProductos

//**********************
}//Clase del CI_Model...
?>

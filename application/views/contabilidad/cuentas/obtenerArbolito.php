
<div id="borrandoNivel3"></div>
<div id="borrandoNivel4"></div>
<div id="borrandoNivel5"></div>
<div id="borrandoNivel6"></div>

<ul id="dhtmlgoodies_tree2" class="dhtmlgoodies_tree">
    <li id="node0" noDrag="true" noSiblings="true" noDelete="true" noRename="true"><a><label><?php echo $detalle=='0'?'Cuentas':$detalle?></label></a>
        <ul id="cuentas">
            <?php
            foreach($primerNivel as $row)
            {
                echo '
                <li id="node1"><a>'.$row->nombre.'('.$row->codigo.')</a>  <!--<img title="Agregar saldo inicial" id="imgSaldoInicial'.$row->idCuenta.'" onclick="formularioSaldoInicial('.$row->idCuenta.')" src="'.base_url().'img/'.($row->saldoInicial>0?'saldoIniciado':'saldo').'.png" class="imgCuentasArbol" />-->
                    <ul>';
                    
                    foreach($segundoNivel as $segundo)
                    {
                        if($segundo->idCuenta==$row->idCuenta)
                        {
                            echo '<li id="nivel2'.$segundo->idSubCuenta.'" noDelete="true"><a>'.$segundo->nombre.'('.$segundo->codigo.')</a> <img title="Agregar cuenta nivel 3" onclick="formularioNivel3('.$segundo->idSubCuenta.',2)" src="'.base_url().'img/agregar.png" class="imgCuentasArbol" />  <img title="Agregar saldo inicial" id="imgSaldoInicial'.$segundo->idSubCuenta.'" onclick="formularioSaldoInicial('.$segundo->idSubCuenta.')" src="'.base_url().'img/'.($segundo->saldoInicial>0?'saldoIniciado':'saldo').'.png" class="imgCuentasArbol" />';
                            
                            $tercerNivel	= $this->cuentas->obtenerNivel3($segundo->idSubCuenta);
                            
                            if($tercerNivel!=null)
                            {
                                echo '<ul>';
                                
                                foreach($tercerNivel as $tercero)
                                {
                                    echo '<li id="nivel3'.$tercero->idSubCuenta3.'" noDelete="true"><a>'.$tercero->nombre.'('.$tercero->codigo.')</a>
                                    <img title="Agregar cuenta nivel 4" onclick="formularioNivel4('.$tercero->idSubCuenta3.',3)" src="'.base_url().'img/agregar.png" class="imgCuentasArbol" />
                                    <img title="Editar cuenta nivel 3" onclick="obtenerCuentaNivel3('.$tercero->idSubCuenta3.',3)" src="'.base_url().'img/editar.png" class="imgCuentasArbol" />
                                    <img title="Borrar cuenta nivel 3" onclick="borrarNivel3('.$tercero->idSubCuenta3.',3)" src="'.base_url().'img/borrar.png" class="imgCuentasArbol" />';
                                    
                                    $cuartoNivel	= $this->cuentas->obtenerNivel4($tercero->idSubCuenta3);
                                    
                                    if($cuartoNivel!=null)
                                    {
                                        echo '<ul>';
                                            
                                        foreach($cuartoNivel as $cuarto)
                                        {
                                            echo '<li id="nivel4'.$cuarto->idSubCuenta4.'" noDelete="true"><a>'.$cuarto->nombre.'('.$cuarto->codigo.')</a>
                                            <img title="Agregar cuenta nivel 5" onclick="formularioNivel5('.$cuarto->idSubCuenta4.',4)" src="'.base_url().'img/agregar.png" class="imgCuentasArbol" />
                                            <img title="Editar cuenta nivel 4" onclick="obtenerCuentaNivel4('.$cuarto->idSubCuenta4.',4)" src="'.base_url().'img/editar.png" class="imgCuentasArbol" />
                                            <img title="Borrar cuenta nivel 4" onclick="borrarNivel4('.$cuarto->idSubCuenta4.',4)" src="'.base_url().'img/borrar.png" class="imgCuentasArbol" />';
                                            
                                            $quintoNivel	= $this->cuentas->obtenerNivel5($cuarto->idSubCuenta4);
                                    
                                            if($quintoNivel!=null)
                                            {
                                                echo '<ul>';
                                                    
                                                foreach($quintoNivel as $quinto)
                                                {
                                                    echo '<li id="nivel5'.$quinto->idSubCuenta5.'" noDelete="true"><a>'.$quinto->nombre.'('.$quinto->codigo.')</a>
                                                    <img title="Agregar cuenta nivel 6" onclick="formularioNivel6('.$quinto->idSubCuenta5.',5)" src="'.base_url().'img/agregar.png" class="imgCuentasArbol" />
                                                    <img title="Editar cuenta nivel 5" onclick="obtenerCuentaNivel5('.$quinto->idSubCuenta5.',5)" src="'.base_url().'img/editar.png" class="imgCuentasArbol" />
                                                    <img title="Borrar cuenta nivel 5" onclick="borrarNivel5('.$quinto->idSubCuenta5.',5)" src="'.base_url().'img/borrar.png" class="imgCuentasArbol" />';
                                                    
                                                    $sextoNivel	= $this->cuentas->obtenerNivel6($quinto->idSubCuenta5);
                                    
                                                    if($sextoNivel!=null)
                                                    {
                                                        echo '<ul>';
                                                            
                                                        foreach($sextoNivel as $sexto)
                                                        {
                                                            echo '<li id="nivel6'.$sexto->idSubCuenta6.'" noDelete="true"><a>'.$sexto->nombre.'('.$sexto->codigo.')</a>
                                                            <img title="Editar cuenta nivel 6" onclick="obtenerCuentaNivel6('.$sexto->idSubCuenta6.',6)" src="'.base_url().'img/editar.png" class="imgCuentasArbol" />
                                                            <img title="Borrar cuenta nivel 6" onclick="borrarNivel6('.$sexto->idSubCuenta6.',6)" src="'.base_url().'img/borrar.png" class="imgCuentasArbol" />
                                                            </li>';
                                                        }
                                                            
                                                        echo '</ul>';
                                                    }
                                                    
                                                    echo '</li>';	
                                                }
                                                    
                                                echo '</ul>';
                                            }
                                            
                                            echo '</li>';	
                                        }
                                            
                                        echo '</ul>';
                                    }
                                    
                                    echo '</li>';		
                                }
                                
                                echo '</ul>';
                            }
                            
                            echo '</li>';
                        }
                    }
                    
                    echo'
                    </ul>
                </li>';
            }
            ?>
        </ul>
    </li>
</ul>

<script type="text/javascript">	
treeObj = new JSDragDropTree();
treeObj.setTreeId('dhtmlgoodies_tree2');
treeObj.setMaximumDepth(1000);
treeObj.setMessageMaximumDepthReached('Maximum depth reached'); // If you want to show a message when maximum depth is reached, i.e. on drop.
treeObj.initTree();
treeObj.expandAll();
</script>

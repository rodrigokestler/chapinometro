<?php
$args = array(
        'posts_per_page'   => -1,
        'offset'           => 0,
        'orderby'          => 'title',
        'order'            => 'ASC',
        'post_type'        => 'nivel',
        'post_status'      => 'publish',
    );
    $posts_array = get_posts($args);
    $contador=0;
    ?>
    <table style="width:100%;text-align:center;">
    <?php
    foreach($posts_array as $nivel){
        $preguntas_acertadas = get_post_meta($nivel->ID,'preguntas_acertadas',TRUE);
        $url_background="";
        if($preguntas_acertadas==''){
            $texto = '';
            if($nivel->post_title!='1'){
                $class = "nivel_bloqueado";
            }else{
                $class="nivel_juego";
                $imagen = get_post_meta($nivel->ID,'icono',TRUE);
                var_dump(wp_get_attachment_url($imagen));
                $url_background= wp_get_attachment_url($imagen);
            }
        }else{
            $texto = $preguntas_acertadas+'/10';
            $class="nivel_juego";
            $imagen = get_post_meta($nivel->ID,'icono',TRUE);
            $url_background=$imagen->url;
        }
        if($contador==0){ ?>
            <tr style="height:110px;">
        <?php }
        ?>
        
                        
                            <td>
                                <button data-nivelid="<?php echo $nivel->ID; ?>" style="background-image:('<?php echo $url_background; ?>')"class="<?php echo $class;?>">
                                    <div class="texto_niveles font-morado">
                                        <?php  echo $texto;?>
                                    </div>
                                </button>
                            </td>
                       
         
        <?php
            $contador++;
        if($contador==3){ ?>
             </tr>
        <?php 
             $contador=0;
        }
    }
    ?>
    </table>
    <?php
    die();
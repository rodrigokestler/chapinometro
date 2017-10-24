<?php


/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */

function pregunta_columns( $columns ) {

	$columns['nivel'] = 'Nivel';

	return $columns;
}
add_filter( 'manage_edit-pregunta_columns', 'pregunta_columns' ) ;

function my_manage_pregunta_columns( $column, $post_id ) {
    global $post;
    if($column == 'nivel'){
    	$nivel =  get_post_meta($post_id,'nivel',TRUE);
    	echo get_the_title($nivel)." - ".get_post_meta($nivel,'nombre',TRUE);
    }
}
add_action( 'manage_pregunta_posts_custom_column', 'my_manage_pregunta_columns', 10, 2 );
function nivel_columns( $columns ) {

	$columns['nombre'] = 'Nombre';
	$columns['nivel'] = 'Nivel';

	return $columns;
}
add_filter( 'manage_edit-nivel_columns', 'nivel_columns' ) ;

function my_manage_nivel_columns( $column, $post_id ) {
    global $post;
    if($column == 'nombre'){
    	
    	echo get_post_meta($post_id,'nombre',TRUE);
    }else if($column == 'nivel'){
    	echo get_post_meta($post_id,'numero_nivel',TRUE);
    }
}
add_action( 'manage_nivel_posts_custom_column', 'my_manage_nivel_columns', 10, 2 );
function check_user(){
    $user_email = $_POST['user_email'];//$_POST['user_email'];
    $user_pass = $_POST['user_pass'];//$_POST['user_pass'];    
    //$user = get_user_by('email', $user_email); 
    $user = get_user_by('login',$user_email);
    
    if ( $user && (wp_check_password( $user_pass, $user->data->user_pass, $user->ID))){            
        return $user;
    }else{
        return null;
    }   
}

function login(){
    $user_pass = $_POST['user_pass'];    
    $user_email = $_POST['user_email'];    
    $user_id = username_exists( $user_email );
    
    if ( !$user_id && email_exists($user_email) == false ) {
        echo json_encode(array('msj_error'=>'No se encontró a nadie con ese usuario.'));    
        die();
    }
    
    $user = get_user_by('login', $user_email);    
    if ( !$user || !wp_check_password( $user_pass , $user->data->user_pass, $user->ID)){ 
        echo json_encode(array('msj_error'=>'La contraseña es incorrecta.'));    
        die();
    } 
    $user->vidas = get_user_meta($user->ID,'vidas',TRUE);
    die(json_encode($user));   
}

add_action('wp_ajax_nopriv_login','login');
function login_ios(){

}
add_action('wp_ajax_nopriv_login_ios');

function register_user(){
    $user_id = username_exists( $_POST['user_email'] );
    if ( !$user_id and email_exists($_POST['user_email']) == false ) {
        $userid = wp_create_user( $_POST['user_email'], $_POST['user_pass1'], $_POST['user_email'] );
        $nombre = $_POST['user_name'];
        wp_update_user( array( 'ID' => $userid, 'display_name' => $nombre ) );
        update_user_meta($userid,'vidas',5);
        die(json_encode(array('success'=>'1','ID'=>$userid)));
            
    } else {
        die(json_encode(array('msj_error'=>"El correo ingresado ya existe, intente de nuevo")));
    }
}
add_action('wp_ajax_nopriv_register_user','register_user');

function register_user_ios(){
	$user_id = username_exists( $_POST['user_email'] );
    if ( !$user_id and email_exists($_POST['user_email']) == false ) {
        $userid = wp_create_user( $_POST['user_email'], $_POST['user_pass1'], $_POST['user_email'] );
        $nombre = $_POST['user_name'];
        wp_update_user( array( 'ID' => $userid, 'display_name' => $nombre ) );
        update_user_meta($userid,'vidas',5);
        die(json_encode(array('success'=>'1','ID'=>$userid)));
            
    } else {
        die(json_encode(array('msj_error'=>"El correo ingresado ya existe, intente de nuevo")));
    }
}
add_action('wp_ajax_nopriv_register_user_ios');

function fb_login(){
	//user_email
	//user_pass (fb_id)
	//user_login (fb_id)
	//user_name
	$user_email = $_POST['user_email'];
	$user_login = $_POST['user_login'];
	$user_id = username_exists( $user_login );
    
    if ( !$user_id  ) {
        $userid = wp_create_user( $user_login, $user_login, $user_email );
        $nombre = $_POST['user_name'];
        wp_update_user( array( 'ID' => $userid, 'display_name' => $nombre ) );   
        update_user_meta($userid,'vidas',5);
        
    }
    
    $user = get_user_by('login', $user_login); 

    if($user != false){
    	$user->vidas = get_user_meta($user->ID,'vidas',TRUE);
   	 	wp_update_user(array('ID'=>($user->ID),'user_email' => $user_email));
    	die(json_encode($user));  
    }else{
    	die(json_encode(array('msj_error'=>"Hubo un error ingresando con Facebook. Por favor intenta de nuevo")));
    }
    

}
add_action('wp_ajax_nopriv_fb_login','fb_login');
function send_vidas(){
	$user = check_user();
	update_user_meta($user->ID,'vidas',$_POST['vidas']);
	die('1');
}
add_action('wp_ajax_nopriv_send_vidas','send_vidas');

function send_resultado(){
	$user = check_user();
	$id_nivel = $_POST['id_nivel'];
	$respuestas = $_POST['respuestas'];
	if($respuestas >= 7){
		update_user_meta($user->ID,'nivel-'.$id_nivel,'completado');
	}
	update_user_meta($user->ID,$id_nivel.'preguntas_acertadas',$respuestas);
	die('1');

}
add_action('wp_ajax_nopriv_send_resultado','send_resultado');
function print_t($f){
    echo "<pre>";
    print_r($f);
    echo "</pre>";
}
function get_niveles_destacados(){
	$user = check_user();
	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'orderby'          => 'meta_value_num',
		'meta_key'		   => 'prioridad',
		'order'            => 'ASC',
		'post_type'        => 'nivel_destacado',
		'post_status'      => 'publish',
	);
	$posts_array = get_posts($args);
	$contador=0;
	?>
	<table style="width:100%;text-align:center;">
	<?php
	$habilitar_siguiente = false;
	foreach($posts_array as $nivel){
		
		$preguntas_acertadas = get_user_meta($user->ID,$nivel->ID.'preguntas_acertadas',TRUE);
		$numero_nivel = get_post_meta($nivel->ID,'numero_nivel',TRUE);
		$class="nivel_juego";
		$imagen = get_post_meta($nivel->ID,'icono',TRUE);
		$url_background='background-image:url('.wp_get_attachment_url($imagen).');';
		$texto = $preguntas_acertadas != '' ? $preguntas_acertadas.'/10' : '0/10';
		
		
		if($contador==0){ ?>
			<tr style="height:110px;">
		<?php }
			
		?>
		
                        
                            <td>
                                <button data-nivelid="<?php echo $nivel->ID; ?>" style="<?php echo $url_background; ?>" class="nivelBtn <?php echo $class;?>">
                                	<div class="texto_niveles font-morado">
                                		<?php  echo $texto; ?>
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
}
add_action('wp_ajax_nopriv_get_niveles_destacados','get_niveles_destacados');

function get_niveles(){
	$user = check_user();
	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'orderby'          => 'meta_value_num',
		'meta_key'		   => 'numero_nivel',
		'order'            => 'ASC',
		'post_type'        => 'nivel',
		'post_status'      => 'publish',
	);
	$posts_array = get_posts($args);
	$contador=0;
	?>
	<table style="width:100%;text-align:center;">
	<?php
	$habilitar_siguiente = false;
	foreach($posts_array as $nivel){
		
		$preguntas_acertadas = get_user_meta($user->ID,$nivel->ID.'preguntas_acertadas',TRUE);
		$numero_nivel = get_post_meta($nivel->ID,'numero_nivel',TRUE);
		$completado = get_user_meta($user->ID,'nivel-'.$nivel->ID,TRUE);
		//update_user_meta($user->ID,'nivel-'.$id_nivel,'completado');
		$url_background="";
		if($completado == 'completado'){
			$habilitar_siguiente = true;
			$texto = $preguntas_acertadas.'/10';
			$class="nivel_juego";
			$imagen = get_post_meta($nivel->ID,'icono',TRUE);
			$url_background='background-image:url('.wp_get_attachment_url($imagen).');';
			$entro = 'entro if completado';
		}else{

			if($numero_nivel == '1' || $habilitar_siguiente == true){
				$entro = 'entro nivel == 1 o habilitar siguiente == true';
				$class="nivel_juego";
				$imagen = get_post_meta($nivel->ID,'icono',TRUE);
				$url_background='background-image:url('.wp_get_attachment_url($imagen).');';
				$texto = $preguntas_acertadas != '' ? $preguntas_acertadas.'/10' : '0/10';
				$habilitar_siguiente = false;
			}else if($habilitar_siguiente == false){
				$class = "nivel_bloqueado";
				$texto = "";
				$entro = 'entro if habilitar siguiente == false';
			}
		}
		
		if($contador==0){ ?>
			<tr style="height:110px;">
		<?php }
			

		?>
		
                        
                            <td>
                            	<?php
                            		if( $user->ID == 9 || $user->ID == 13){
                            			$ambiente = ["preguntas_acertadas" => $preguntas_acertadas,
                            						 "numero_nivel"		   => $numero_nivel,
                            						 "completado"		   => $completado,
                            						 "nivel_id"			   => $nivel->ID,
                            						 "entro"			   => $entro
                            						];
                            			echo '<pre>';
                            			var_dump($ambiente);
                            			echo '</pre>';
                            		}
                            	?>
                                <button data-nivelid="<?php echo $nivel->ID; ?>" style="<?php echo $url_background; ?>" class="nivelBtn <?php echo $class;?>">
                                	<div class="texto_niveles font-morado">
                                		<?php  echo $texto; ?>
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
}
add_action('wp_ajax_nopriv_get_niveles','get_niveles');

function get_preguntas(){
	$user = check_user();
	$id_nivel = $_POST['id_nivel'];
	$args = array(
		'posts_per_page'   => 10,
		'offset'           => 0,
		'orderby'          => 'rand',
		'post_type'        => 'pregunta',
		'post_status'      => 'publish',
		'meta_key'		   => 'nivel',
		'meta_value'	   => $id_nivel
	);
	$posts_array = get_posts($args);
	$tiempo = get_post_meta($id_nivel,'tiempo',TRUE);
	$socialsharing = get_post_meta($id_nivel,'socialsharing',TRUE);
	$imgSocial = wp_get_attachment_url($socialsharing);
	$imagenes = 0;
	$sources = [];
	?>

	<?php
	foreach($posts_array as $pregunta){
		
		$categories = get_the_category($pregunta->ID);
		if($categories[0]->name=='pregunta-texto'){

		}else if($categories[0]->name=='pregunta-imagen'){
			$imagenes++;
			array_push($sources,wp_get_attachment_url(get_post_meta($pregunta->ID,'pregunta',TRUE)));
		}
		?>

					<div class="pregunta" data-no="<?php echo $contador;?>" style="display:none;d">
						<div class="flecha-arriba"></div>
                    	<div class="flecha-abajo"></div>
                        <div class="preguntaSection">
                        	<?php 
                        		if($categories[0]->name == 'pregunta-texto'){ ?>
                        			<div class="preguntaTexto"><?php echo $pregunta->pregunta;?></div>
                        	<?php }else{ ?>
                        			<div class="preguntaImagen"><img src="<?php echo wp_get_attachment_url(get_post_meta($pregunta->ID,'pregunta',TRUE));?>" ></div>
                        		<?php } ?>
                        
                            
                            
                        </div>
                        <div class="respuestas">
                            <button class="respuestaTexto" data-opcion="respuesta1" data-correcta="<?php echo $pregunta->respuesta_correcta;?>"><?php echo $pregunta->respuesta1; echo $pregunta->respuesta_correcta == 'respuesta1' && ($user->ID == 13 || $user->ID == 9) ? ' <span style="font-weight:bold">correcta</span>' : '';?>
                            </button>
                            <button class="respuestaTexto" data-opcion="respuesta2" data-correcta="<?php echo $pregunta->respuesta_correcta;?>"><?php echo $pregunta->respuesta2; echo $pregunta->respuesta_correcta == 'respuesta2' && ($user->ID == 13 || $user->ID == 9) ? ' <span style="font-weight:bold">correcta</span>' : ''; ?>
                            </button>
                            <button class="respuestaTexto" data-opcion="respuesta3" data-correcta="<?php echo $pregunta->respuesta_correcta;?>"><?php echo $pregunta->respuesta3; echo $pregunta->respuesta_correcta == 'respuesta3' && ($user->ID == 13 || $user->ID == 9) ? ' <span style="font-weight:bold">correcta</span>' : ''; ?>
                            </button>
                            <button class="respuestaTexto" data-opcion="respuesta4" data-correcta="<?php echo $pregunta->respuesta_correcta;?>"><?php echo $pregunta->respuesta4; echo $pregunta->respuesta_correcta == 'respuesta4' && ($user->ID == 13 || $user->ID == 9) ? ' <span style="font-weight:bold">correcta</span>' : '';?>
                            </button>
							<button class="respuestaTexto" data-opcion="respuesta5" data-correcta="<?php echo $pregunta->respuesta_correcta;?>"><?php echo $pregunta->respuesta5; echo $pregunta->respuesta_correcta == 'respuesta5' && ($user->ID == 13 || $user->ID == 9) ? ' <span style="font-weight:bold">correcta</span>' : '';?>
                            </button>
                        </div>
                    </div>

	<?php }

	?>
	<script>
	juego.nombreNivel.html("<?php echo get_post_meta($id_nivel,'nombre',TRUE);?>");
	juego.tiempo_restante = <?php echo $tiempo;?>;
	$('#socialSharingBtn').data('link',"<?php echo $imgSocial;?>");
	juego.imageCount = <?php echo $imagenes ?>;
	<?php 
		if($imagenes >0){
			for($i =0 ; $i < $imagenes; $i++){
				?>
					console.log("new image src <?php echo $sources[$i];?>");
					juego.images[<?php echo $i;?>] = new Image();
					juego.images[<?php echo $i;?>].src = '<?php echo $sources[$i];?>';
					juego.images[<?php echo $i;?>].onload = function(){
						console.log('image loaded');
			        	juego.imagesLoaded++;
				        if(juego.imagesLoaded == juego.imageCount){
				            juego.comenzarJuego(juego.sonido);
				        }
			    	};
				<?php
			}
		
		}else{
			?>
			juego.comenzarJuego(juego.sonido);
			<?php
		}
	?>
	
	
	</script>
	<?php

die();
}
add_action('wp_ajax_nopriv_get_preguntas','get_preguntas');
function get_user_vidas(){
	$user = check_user();
	if($user == null){
		die(json_encode(array('msj_error'=>'usuario null')));
	}else{
		if($_POST['tipo']=='reset'){
			update_user_meta($user->ID,'vidas',5);
		}
		$vidas = get_user_meta($user->ID,'vidas',TRUE);
		die(json_encode(array('success'=>'1','vidas'=>$vidas)));
	}
}
add_action('wp_ajax_nopriv_get_user_vidas','get_user_vidas');

<?php


/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function chapinometro_scripts() {
	
}
add_action( 'wp_enqueue_scripts', 'chapinometro_scripts' );

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
    die(json_encode($user));   
}

add_action('wp_ajax_nopriv_login','login');

function register_user(){
    $user_id = username_exists( $_POST['user_email'] );
    if ( !$user_id and email_exists($_POST['user_email']) == false ) {
        $userid = wp_create_user( $_POST['user_email'], $_POST['user_pass1'], $_POST['user_email'] );
        $nombre = $_POST['user_name'];

        die(json_encode(array('success'=>'1')));
            
    } else {
        die(json_encode(array('msj_error'=>"El correo ingresado ya existe, intente de nuevo")));
    }
}
add_action('wp_ajax_nopriv_register_user','register_user');

function get_niveles(){

	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'orderby'          => 'title',
		'order'            => 'DESC',
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
}
add_action('wp_ajax_nopriv_get_niveles','get_niveles');

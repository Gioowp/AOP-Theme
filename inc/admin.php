<?php


function saveNewObject(){
//	print_r($_POST);

	$data = get_post_meta($_POST['posdId'], 'aopObjects', true);

	$data = json_decode($data, true);


	if(isset($_POST['aopText']) && !empty($_POST['aopText'])){

		$_POST['aopText'] = strip_tags($_POST['aopText']);

		$new['bf']['obType'] = 'text';
		$new['bf']['rotDirect'] = 'right';
		$new['bf']['rotCount'] = '0';
		$new['bf']['zindex'] = '5';
		$new['bf']['animTime'] = '1';
		$new['bf']['fSize'] = '16';
		$new['bf']['fDimension'] = 'em';
		$new['bf']['fAlign'] = 'left';
		$new['bf']['fColor'] = '000';
		$new['bf']['moveLeft'] = '0';
		$new['bf']['moveTop'] = '0';
		$new['bf']['opacity'] = '0';
		$new['bf']['sizeX'] = '100';
		$new['bf']['sizeY'] = '';

		$new['af']['obType'] = 'text';
		$new['af']['rotDirect'] = 'right';
		$new['af']['rotCount'] = '0';
		$new['af']['zindex'] = '5';
		$new['af']['animTime'] = '1';
		$new['af']['fSize'] = '16';
		$new['af']['fDimension'] = 'em';
		$new['af']['fAlign'] = 'left';
		$new['af']['fColor'] = '000';
		$new['af']['moveLeft'] = '300';
		$new['af']['moveTop'] = '0';
		$new['af']['opacity'] = '0';
		$new['af']['sizeX'] = '100';
		$new['af']['sizeY'] = '';

		$new['data'] = $_POST['aopText'];

		$data[] = $new;
	}

	if(isset($_FILES['aopFile'])){

		$uploadedfile = $_FILES['aopFile'];
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		$new['bf']['obType'] = 'picture';
		$new['bf']['rotDirect'] = 'right';
		$new['bf']['rotCount'] = '0';
		$new['bf']['zindex'] = '5';
		$new['bf']['animTime'] = '1';
		$new['bf']['fSize'] = '16';
		$new['bf']['fDimension'] = 'em';
		$new['bf']['fAlign'] = 'left';
		$new['bf']['fColor'] = '000';
		$new['bf']['moveLeft'] = '0';
		$new['bf']['moveTop'] = '0';
		$new['bf']['opacity'] = '0';
		$new['bf']['sizeX'] = '100';
		$new['bf']['sizeY'] = '';

		$new['af']['obType'] = 'picture';
		$new['af']['rotDirect'] = 'right';
		$new['af']['rotCount'] = '0';
		$new['af']['zindex'] = '5';
		$new['af']['animTime'] = '1';
		$new['af']['fSize'] = '16';
		$new['af']['fDimension'] = 'em';
		$new['af']['fAlign'] = 'left';
		$new['af']['fColor'] = '000';
		$new['af']['moveLeft'] = '300';
		$new['af']['moveTop'] = '0';
		$new['af']['opacity'] = '0';
		$new['af']['sizeX'] = '100';
		$new['af']['sizeY'] = '';

		$new['data'] = $movefile['url'];

		$data[] = $new;
	}


	$data = json_encode($data);
	update_post_meta($_POST['posdId'], 'aopObjects', $data);

	return $data;
}





/////// meta box
function myplugin_add_meta_box() {
//add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'myplugin_sectionid',
			__( 'One Pager' ),
			'metaBoxForm',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );




function metaBoxForm(){
	$themeRoot = get_template_directory();

	include_once "{$themeRoot}/inc/_pager-manager.php";
	return;
}






/////////////////////////////////////////////////////
//add_action('wp_ajax_nopriv_aopaa', 'aopaa');
add_action('wp_ajax_aopaa', 'aopaa');

function aopaa() {

	if(isset($_POST['aa']) && function_exists($_POST['aa'])){
		print call_user_func($_POST['aa']);
	}
	exit;
//	die(); // this is required to return a proper result
}


function __scrm_localize(){
	wp_localize_script( "aop-jquery-ui", "dinob", array(
		'home_url' => home_url(),
	));
}


////////////////////////////// Admin file system
function aopAdminScripts() {

	wp_register_script( 'aop-jquery-ui', get_template_directory_uri() . '/js/jquery-ui.min.js', array('jquery'), '20120206', true );
	wp_enqueue_script( 'aop-jquery-ui' );


	__scrm_localize();

}
add_action( 'admin_enqueue_scripts', 'aopAdminScripts' );

function aop_admin_menu() {

	//create admin menu
	$ident = 'aopsettings';

	add_menu_page( 'AOP', 'AOP Theme', 'manage_options', $ident, 'aop_get_page');
//	add_submenu_page( $ident, 'Settings', 'Settings', 'manage_options', 'aopsettings2', 'aop_get_page' );


	return false;
}


add_action('admin_menu', "aop_admin_menu");


function aop_get_page(){
	$themeRoot = get_template_directory();
	$name = 'aop'; // name

	if(!isset($_GET['page']) )return false;
	$var = strip_tags($_GET['page']);

	if( substr($var,0,3)!=$name)return false;
	$page = substr($var,3);

	if(empty($page))return false;

	print "<div class='aopAdminPage {$name} {$page}'>";
	if (function_exists($page)){
		print call_user_func_array($page);
	}elseif(is_file("{$themeRoot}/inc/{$page}.php")){

		include_once "{$themeRoot}/inc/{$page}.php";
	}
	print "</div>";
	return false;
}


function remove_menus(){

	remove_menu_page( 'index.php' );                  //Dashboard
	remove_menu_page( 'edit.php' );                   //Posts
//	remove_menu_page( 'upload.php' );                 //Media
//	remove_menu_page( 'edit.php?post_type=page' );    //Pages
	remove_menu_page( 'edit-comments.php' );          //Comments
//	remove_menu_page( 'themes.php' );                 //Appearance
//	remove_menu_page( 'plugins.php' );                //Plugins
//	remove_menu_page( 'users.php' );                  //Users
	remove_menu_page( 'tools.php' );                  //Tools
//	remove_menu_page( 'options-general.php' );        //Settings

//	remove_submenu_page( 'options-general.php', 'options-reading.php' );
}
add_action( 'admin_menu', 'remove_menus' );
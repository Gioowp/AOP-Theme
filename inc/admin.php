<?php


//add_filter('media_send_to_editor', 'media_to_editor', 1, 3);
add_filter('image_send_to_editor', 'image_to_editor', 1, 8);

function image_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt){
//	print_r($_POST);
	return $ret = "{$html}-{$id}-{$caption}-{$title}-{$align}-{$url}-{$size}-{$alt}";
	return $url;
}

function saveNewObject(){
//	print_r($_POST);

	$data = get_post_meta($_POST['postId'], 'aopObjects', true);
	$data = json_decode($data, true);

///// default values
	$new['bf']['rotDirect'] = 'right';
	$new['bf']['rotCount'] = '0';
	$new['bf']['zindex'] = '5';
	$new['bf']['animTime'] = '1';
	$new['bf']['moveLeft'] = '0';
	$new['bf']['moveTop'] = '0';
	$new['bf']['opacity'] = '1';
	$new['bf']['sizeX'] = '100';
	$new['bf']['sizeY'] = '';
	$new['bf']['animEffect'] = 'easeOutCirc';

	$new['af']['rotDirect'] = 'right';
	$new['af']['rotCount'] = '0';
	$new['af']['zindex'] = '5';
	$new['af']['animTime'] = '1';
	$new['af']['moveLeft'] = '300';
	$new['af']['moveTop'] = '0';
	$new['af']['opacity'] = '1';
	$new['af']['sizeX'] = '100';
	$new['af']['sizeY'] = '';
	$new['af']['animEffect'] = 'easeOutCirc';


	$smartAf = $smartBf = $textData = $file = '';
	if(isset($_POST['aopText']) && !empty($_POST['aopText'])){

		$textData = $_POST['aopText'];

//		$new['data'] = $_POST['aopText'];
		$new['obType'] = 'text';

		$data[] = $new;
		$newId = count($data)-1;
		update_post_meta($_POST['postId'], "aopObjectData_{$newId}", $textData);


		$idd = count($data)-1;
		$smartBf .= aopGetObjectCell($new, 'bf', $idd, $_POST['postId'] );
		$smartAf .= aopGetObjectCell($new, 'af', $idd, $_POST['postId'] );

	}

	if(isset($_FILES['aopFile'])){

		$file = strip_tags($_POST['aopFile']);

//		$new['data'] = $movefile['url'];
		$new['obType'] = 'picture';


		$data[] = $new;

		$newId = count($data)-1;
		update_post_meta($_POST['postId'], "aopObjectData_{$newId}", $file);


		$idd = count($data)-1;
		$smartBf .= aopGetObjectCell($new, 'bf', $idd, $_POST['postId'] );
		$smartAf .= aopGetObjectCell($new, 'af', $idd, $_POST['postId'] );
	}


	$data = json_encode($data);
	update_post_meta($_POST['postId'], 'aopObjects', $data);

	return json_encode( [ 'bf'=>$smartBf,'af'=>$smartAf ] );
}



function updateObject(){
//	print_r($_POST);
	$data = json_decode(stripslashes($_POST['data']),true);
	$textData = json_decode(stripslashes($_POST['textData']),true);
	print_r($_POST);

	if(!is_array($data))return false;

	if(is_array($textData)){
		foreach($textData as $k=>$v){
			update_post_meta($_POST['postId'], 'aopObjectData_'.$k, $v);
		}
	}

	$data = json_encode($data);
	update_post_meta($_POST['postId'], 'aopObjects', $data);


	return;

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



////////////////////////

function aopGetObjectsRaw($postId = 0){

	$data = get_post_meta($postId, 'aopObjects', true);
	$data = json_decode($data, true);

//	print_r($data);

	return $data;

}

function aopGetObjectList($objectsRaw = [], $position='bf', $postId=''){
	$ret = '';
	if(!is_numeric($postId))return false;
	if(!is_array($objectsRaw))return false;

	foreach($objectsRaw as $k=>$v)$ret .= aopGetObjectCell($v, $position, $k,$postId);
	return $ret;

}

function aopGetObjectCell($objectRaw = 0, $position='', $id='', $postId = ''){
//print_r($objectRaw);

	if(!is_numeric($postId))return false;

	$obType = $objectRaw['obType'];
	$obType = $obType == 'picture'?$obType:'text';
//	print_r($objectRaw);
	$objectRaw = $objectRaw[$position];

	$val = get_post_meta($postId, "aopObjectData_{$id}", true);


	$edit = $obType!='picture'?"<div title='Edit above' class='doEditOb acticon'>E</div>":'';
	if($obType=='picture'){
		$val = "<img src='{$val}' style='opacity:{$objectRaw['opacity']};' />";
	}else{
		$val = "<span class='data' style='opacity:{$objectRaw['opacity']};'>{$val}</span>";
	}
	$ret = "<div class='aopObject' obId = '{$id}'
				obType='{$obType}'
				rotDirect='{$objectRaw['rotDirect']}'
				rotCount='{$objectRaw['rotCount']}'
				zindex='{$objectRaw['zindex']}'
				animTime='{$objectRaw['animTime']}'
				moveLeft='{$objectRaw['moveLeft']}'
				moveTop='{$objectRaw['moveTop']}'
				opacity='{$objectRaw['opacity']}'
				sizeX='{$objectRaw['sizeX']}'
				sizeY='{$objectRaw['sizeY']}'
				animEffect='{$objectRaw['animEffect']}'
				style='z-index:{$objectRaw['zindex']};
				 left:{$objectRaw['moveLeft']}px; top:{$objectRaw['moveTop']}px;
				  width:{$objectRaw['sizeX']}px; height:{$objectRaw['sizeY']}px; '

				>{$val}

				<div title='Remove' class='doRemoveOb acticon'>X</div>
				{$edit}

				</div>";
	return $ret;

	//<div title='Edit' class='doEditOb'>E</div>

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
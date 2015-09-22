<?php
/**
 * advancedonepage Theme Customizer.
 *
 * @package advancedonepage
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */



function pagesList(){
	$args = array(
		'order'                  => 'ASC',
		'orderby'                => 'menu_order',
		'post_type'              => 'nav_menu_item',
		'post_status'            => 'publish',
		'output'                 => ARRAY_A,
		'output_key'             => 'menu_order',
		'nopaging'               => true,
		'update_post_term_cache' => false );
	$items = wp_get_nav_menu_items( 'Mainmenu', $args );

//	print '<pre>'; 	print_r($items);

	$ret = ['dom'=>'','css'=>'','js'=>''];

	foreach($items as $v){

//		continue;
		$part = generateComponents($v->object_id);
		$ret['dom'] .= $part['dom'];
		$ret['css'] .= $part['css'];
		$ret['js'] .= $part['js'];
	}

	return $ret;

}

function generateComponents($postId = ''){

	$aopData = get_post_meta($postId, 'aopObjects', true);
	$aopData = json_decode($aopData, true);
	if(!is_array($aopData))$aopData=[];

	return ['dom'=>generateDom($aopData, $postId), 'css'=>generateCss($aopData, $postId), 'js'=>generateJs($aopData, $postId)];
}

function generateDom($aopData=[], $postId = ''){

	$ret = '';
	foreach($aopData as $k=>$v){

		if($v['obType']=='picture'){
			$ret .= "<img class='aopOb{$postId}{$k} aopOb' postId='{$postId}' obId='{$k}' src='{$v['data']}' />";
		}else{
			$ret .= "<div class='aopOb{$postId}{$k} aopOb' postId='{$postId}' obId='{$k}'>{$v['data']}</div> ";
		}

	}

	return "<div class='aopObsWrapper aopObs{$postId}' style='display:none;'>".$ret."</div>";
}


function generateCss($aopData=[], $postId = ''){
	$ret = '';

	foreach($aopData as $k=>$v){
		$bf = $v['bf'];
//		print $bf['sizeX'].'--';
		$width = calcPercent($bf['sizeX'], 'width');
		$height = calcPercent($bf['sizeY'], 'height');
		$left = calcPercent($bf['moveLeft'], 'left');
		$top = calcPercent($bf['moveTop'], 'top');
//		print_r($v);

		$ret .= ".aopObs{$postId} .aopOb{$postId}{$k} { font-size:{$bf['fSize']}{$bf['fDimension']}; z-index:{$bf['zindex']}; text-align:{$bf['fAlign']}; color:{$bf['fColor']}; left:{$left}%; top:{$top}%; width:{$width}%; height:{$height}%; opacity:{$bf['opacity']};  } ";

	}

	return $ret;
}

function generateJs($aopData=[], $postId = ''){
	/// animate forward and backward

	$forward = $backward = '';
	foreach($aopData as $k=>$v) {
		$bf = $v['af'];

		$width = calcPercent($bf['sizeX'], 'width');
		$height = calcPercent($bf['sizeY'], 'height');
		$left = calcPercent($bf['moveLeft'], 'left');
		$top = calcPercent($bf['moveTop'], 'top');
		$deg = $bf['rotCount']*360;
		$rotateDirection = $bf['rotDirect']=='left'?'-':'';
		$duration = $bf['animTime']*1000;

		$forward .= "
		jQuery( '.aopObs{$postId}' ).fadeIn(100);
		jQuery('.aopObs{$postId} .aopOb{$postId}{$k}').fadeIn(300).animate({
			width:'{$width}%',
			height:'{$height}%',
			left:'{$left}%',
			top:'{$top}%',
			opacity:'{$bf['opacity']}',
			deg: {$rotateDirection}{$deg},
		}, { duration: {$duration},
				easing: 'easeOutCirc',
		        complete: function(){
			      //jQuery( this ).fadeOut(100);
			    }
		 }); ";
	}

	foreach($aopData as $k=>$v) {
		$bf = $v['bf'];

		$width = calcPercent($bf['sizeX'], 'width');
		$height = calcPercent($bf['sizeY'], 'height');
		$left = calcPercent($bf['moveLeft'], 'left');
		$top = calcPercent($bf['moveTop'], 'top');
		$deg = $bf['rotCount']*360;
		$rotateDirection = $bf['rotDirect']=='left'?'-':'';
		$duration = $bf['animTime']*300;

		$backward .= "jQuery('.aopObs{$postId} .aopOb{$postId}{$k}').stop().animate({
			width:'{$width}%',
			height:'{$height}%',
			left:'{$left}%',
			top:'{$top}%',
			opacity:'{$bf['opacity']}',
			deg: {$rotateDirection}{$deg},
		}, { duration: {$duration},
				easing: 'easeOutQuint',
		        complete: function(){
			      jQuery( '.aopObs{$postId}' ).fadeOut(300);

			    }
		}); ";
	}




	return "
	function animateObsForward{$postId}(){
		{$forward}
	}

	function animateObsBackward{$postId}(){
		{$backward}

	} ";

}


function calcPercent($val='', $type=''){
	//// width, height, left, top
	//// 500x250

	if(!is_numeric($val))return 0;
	switch($type){
		case 'width': return round(($val*100)/500);
		case 'height': return round(($val*100)/250);
		case 'left': return round(($val*100)/500);
		case 'top': return round(($val*100)/250);
	}

	return 0;
}


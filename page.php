<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package advancedonepage
 */


get_header();


$aopData = generateComponents($post->ID);


//print_r($aopData);

?>
<style>
	.aopConatainer{ width:100%; background:#fafafa; min-height:500px; position:relative; overflow:hidden; }
	.aopOb{ position:absolute; background:#ccc; }
<?=$aopData['css']?>
</style>

<h1><?=$post->post_title?></h1>
<span class="doAnimateBackward"> << Backward </span>
<span class="doAnimateForward"> Forward >> </span>


<div class="aopConatainer">
	<?=$aopData['dom']?>
</div>


<script>


	jQuery(window).load(function(e){


		jQuery('body').on('DOMMouseScroll mousewheel',function(e){
			if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
				// scroll up
				console.log('up');
				doNextAnim();
			}
			else {
				// scroll down
				console.log('down');
				doPrevAnim();
			}

		});

		jQuery('body').on('click','.doAnimateForward',function(){
			animateObsForward5();
		});

		jQuery('body').on('click','.doAnimateBackward',function(){
			animateObsBackward5();
		});

	});

	function doNextAnim(){
		var currentNavIndex = jQuery('nav li.current_page_item').index();
		var navCount = jQuery('nav li').length;
		var nextIndex = 1;

		if(navCount > currentNavIndex)nextIndex = currentNavIndex+1;

		console.log(nextIndex);
		eval('animateObsBackward'+currentNavIndex)();
		eval('animateObsForward'+nextIndex)();


	}

	function doPrevAnim(){
		var currentNavIndex = jQuery('nav li.current_page_item').index();
		var navCount = jQuery('nav li').length;
		var nextIndex = navCount;

		if(currentNavIndex > 0)nextIndex = currentNavIndex-1;

		console.log(nextIndex);
		eval('animateObsBackward'+currentNavIndex)();
		eval('animateObsForward'+nextIndex)();

//		console.log(navCount);
	}


//	function animateObsForward4(){console.log(44444);}
<?=$aopData['js'] ?>

</script>



<?php get_footer();

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
	$items = wp_get_nav_menu_items( $menu, $args );

	print_r($items);

}

function generateComponents($postId = ''){

	$aopData = get_post_meta($postId, 'aopObjects', true);
	$aopData = json_decode($aopData, true);

	return ['dom'=>generateDom($aopData, $postId), 'css'=>generateCss($aopData, $postId), 'js'=>generateJs($aopData, $postId)];
}

function generateDom($aopData=[], $postId = ''){

	$ret = '';
	foreach($aopData as $k=>$v){
		$source = $v['obType']=='picture'?"<img src='{$v['data']}' />":$v['data'];
		$ret .= "<div class='aopOb{$postId}{$k} aopOb' postId='{$postId}' obId='{$k}'>{$source}</div> ";

	}

	return "<div class='aopObsWrapper aopObs{$postId}'>".$ret."</div>";
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

		$forward .= "jQuery('.aopObs{$postId} .aopOb{$postId}{$k}').animate({
			width:'{$width}%',
			height:'{$height}%',
			left:'{$left}%',
			top:'{$top}%',
			opacity:'{$bf['opacity']}',
			deg: {$rotateDirection}{$deg},
		}, { duration: {$duration} }); ";
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

		$backward .= "jQuery('.aopObs{$postId} .aopOb{$postId}{$k}').animate({
			width:'{$width}%',
			height:'{$height}%',
			left:'{$left}%',
			top:'{$top}%',
			opacity:'{$bf['opacity']}',
			deg: {$rotateDirection}{$deg},
		}, { duration: {$duration} }); ";
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
	//// 600x330

	if(!is_numeric($val))return 0;
	switch($type){
		case 'width': return round(($val*100)/600);
		case 'height': return round(($val*100)/330);
		case 'left': return round(($val*100)/600);
		case 'top': return round(($val*100)/330);
	}

	return 0;
}


?>


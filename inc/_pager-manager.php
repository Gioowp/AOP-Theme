<style>
.red{ color:#f00; }
.note{ font-size:0.6em; }
	.row{ width:99%; display:block; overflow:hidden; box-sizing:border-box; padding:10px;  }

	.buildPlace{ width:600px; height:330px; background:#005082; position:relative; overflow:hidden; float:left; box-sizing:border-box; border:solid 1px #ccc; }
	.buildPlace .aopObject{ width:30%; background:#fff; top:0; left:0; position:absolute; overflow:hidden; }
	.buildPlace .aopObject img{ width:100%; }

	.ui-resizable-se { cursor:se-resize; width:8px; height:8px; background:#ccc; position: absolute; bottom: 1px; right: 1px; opacity:0.5;}
	.ui-resizable-e{ cursor:e-resize; height: 100%; right: -5px; top: 0; width: 7px; position: absolute;}
	.ui-resizable-s{ cursor:s-resize; bottom: -5px; height: 7px; left: 0; width: 100%; position: absolute;}
	.aopObject .acticon{ position:absolute; width:12px; height:12px; top:0; left:0; cursor:pointer; background:#ccc; box-sizing:border-box; padding:0; line-height:0.8; text-align:center; font-weight:bold; opacity:0.7; }
	.aopObject .doEditOb{ left:13px; }

	.active{ z-index:111; box-shadow: 0px 0px 12px 0px rgba(0,0,0,0.75); }
	.pull-right{ float:right; }
	.pull-left{ float:left; }

	.aop-fields textarea,
	.aop-fields textarea{ width:100%; min-height:100px; margin-bottom:10px; }
	.aop-fields input{ width:50%; }
	.row h2{ margin-top:0 !important; }

	.aop-object-conf{ float:left; width:100%; }
	.aop-object-conf label{ float:left; width:25%; }
	.aop-object-conf label input, .aop-object-conf label input{ width:80px; }
	.aop-object-conf h3{ width:90%; }

</style>
<div class="row aop-fields">
	<form id="aop-form">

	<h2 class="pull-left">Add text or upload picture <span class="red note" style="display:none;">Editing selected object..........</span></h2>
	<button class="button button-primary  pull-right doAddNewObject">Save</button>

	<label>
		<textarea name="aopText" placeholder="Write some text here ...." title="Write some text here ...." obId=""></textarea>
	</label>


	<label>
		<input name="aopFile" title="Select file" placeholder="Select file" type="file" />
	</label>

	<button class="button button-primary  pull-right doAddNewObject">Save</button>


	</form>
<hr>

</div>
<div class="row">

	<div class="panel panel-default aopParams">
		<h2 class="pull-left">Object configuration</h2>
		<button class="button button-primary  pull-right doSaveObjects">Save</button>

			<div class="object-main-conf aop-object-conf">
			<h3>Main properties</h3>
			<label class="rotateConf">
				Rotate
				<select name="rotDirect">
					<option>left</option>
					<option>right</option>
				</select>

				<input type="text" name="rotCount" placeholder="xx times" value="0" >
			</label>
			<label class="zindexConf">
				Z-index
				<input type="text" name="zindex" placeholder="Layering" value="1" >
			</label>
			<label class="zindexConf">
				Time
				<input type="text" name="animTime" placeholder="Animation time (sec)" value="1" >
			</label>

				<label>
					<select name="fSize" title="Text size">
						<option>4</option>
						<option>5</option>
						<option>6</option>
						<option>7</option>
						<option>8</option>
						<option>9</option>
						<option>10</option>
						<option>11</option>
						<option>12</option>
						<option>13</option>
						<option>14</option>
						<option>15</option>
						<option>16</option>
						<option>18</option>
						<option>20</option>
						<option>22</option>
						<option>24</option>
						<option>26</option>
						<option>28</option>
						<option>32</option>
						<option>36</option>
						<option>40</option>
						<option>44</option>
					</select>
					<select name="fDimension" title="Text size dimension">
						<option>em</option>
						<option>px</option>
						<option>rem</option>
					</select>

					<select name="fAlign" title="Text align">
						<option>left</option>
						<option>center</option>
						<option>right</option>
					</select>
					<input name="fColor" value="#000" type="text" title="Text color" placeholder="Text color">


				</label>
			</div>

			<div class="object-individual-conf aop-object-conf">
				<h3>Individual properties</h3>
				<label class="moveConf">
					Move
					<input type="text" name="moveLeft" placeholder="X axis" >
					<input type="text" name="moveTop" placeholder="Y axis" >
				</label>

				<label class="opacityConf">
					Opacity
					<input type="text" name="opacity" placeholder="Opacity from 0 to 100" value="100" >
				</label>

				<label class="resizeConf">
					Resize
					<input type="text" name="sizeX" placeholder="Width" >
					<input type="text" name="sizeY" placeholder="Height" >
				</label>
			</div>




	</div>


</div>


<div class="row aop-objects">

<?php
$rawData = aopGetObjectsRaw($_GET['post']);

?>

	<div class="objectsBefore buildPlace">
		<?=aopGetObjectList($rawData,'bf')?>
	</div>

	<div class="pull-right objectsAfter buildPlace">
		<?=aopGetObjectList($rawData,'af')?>
	</div>


</div>

<script>
	jQuery(window).load(function(e){


		///////// live text edit
		jQuery('body').on('click','.doEditOb', function(e){

			var ob = jQuery(this).closest('.aopObject');
			console.log( jQuery('span',ob).text() );

			jQuery('.aop-fields textarea[name=aopText]').val(jQuery('span',ob).text()).attr('obId', jQuery(ob).attr('obId')).addClass('liveEdit');
		});

		jQuery('body').on('keyup','.aop-fields .liveEdit[name=aopText]', function(e){
			jQuery(".buildPlace .aopObject[obId="+jQuery(this).attr('obId')+"] span").text( jQuery(this).val() );
		});


////////////////////////
		jQuery('body').on('keyup','.aopParams input, .aopParams select', function(e){
			setObParamsFromFields();
		});

		jQuery('body').on('click','.doAddNewObject', function(e){
				e.preventDefault();


//			document.getElementById('the-form')


				var formData = new FormData();
				formData.append('aopText', jQuery('.aop-fields textarea[name=aopText]').val() );
				formData.append('aopFile', jQuery('.aop-fields input[name=aopFile]')[0].files[0] );
				formData.append('postId', <?=$_GET['post']?> );
				formData.append('action', 'aopaa');
				formData.append('aa', 'saveNewObject');

				jQuery.ajax({
					url: dinob.home_url+'/wp-admin/admin-ajax.php',
					data: formData,
					processData: false,
					contentType: false,
					type: 'POST',
					success: function(data){
//					alert(data);


					}
				});


				console.log(111);
		});



		jQuery('body').on('click','.doSaveObjects',function(e){
			e.preventDefault();

			var aopObject = {};
			var after = {};
			jQuery('.aop-objects .objectsBefore .aopObject').each(function(e){

				var newBefore = { obType: jQuery(this).attr('obType'),
					rotDirect: jQuery(this).attr('rotDirect'),
					rotCount: jQuery(this).attr('rotCount'),
					zindex: jQuery(this).attr('zindex'),
					animTime: jQuery(this).attr('animTime'),
					fSize: jQuery(this).attr('fSize'),
					fDimension: jQuery(this).attr('fDimension'),
					fAlign: jQuery(this).attr('fAlign'),
					fColor: jQuery(this).attr('fColor'),
					moveLeft: jQuery(this).attr('moveLeft'),
					moveTop: jQuery(this).attr('moveTop'),
					opacity: jQuery(this).attr('opacity'),
					sizeX: jQuery(this).attr('sizeX'),
					sizeY: jQuery(this).attr('sizeY')
				};




				var afOb = jQuery(".objectsAfter .aopObject[obId="+jQuery(this).attr('obId')+"]");

				var newAfter = { obType: jQuery(afOb).attr('obType'),
					rotDirect: jQuery(afOb).attr('rotDirect'),
					rotCount: jQuery(afOb).attr('rotCount'),
					zindex: jQuery(afOb).attr('zindex'),
					animTime: jQuery(afOb).attr('animTime'),
					fSize: jQuery(afOb).attr('fSize'),
					fDimension: jQuery(afOb).attr('fDimension'),
					fAlign: jQuery(afOb).attr('fAlign'),
					fColor: jQuery(afOb).attr('fColor'),
					moveLeft: jQuery(afOb).attr('moveLeft'),
					moveTop: jQuery(afOb).attr('moveTop'),
					opacity: jQuery(afOb).attr('opacity'),
					sizeX: jQuery(afOb).attr('sizeX'),
					sizeY: jQuery(afOb).attr('sizeY')
				};


				var source = '';
				if(jQuery(this).attr('obType')=='text'){
					source = jQuery('span.data',this).text();
				}else{
					source = jQuery('img',this).attr('src');
				}

				aopObject[jQuery(this).attr('obId')] = {bf: newBefore, af:newAfter, obType:jQuery(this).attr('obType'), data:source  };
//				console.log(e);
			});

			var asJson = JSON.stringify(aopObject);


			jQuery.ajax({
				url: dinob.home_url+'/wp-admin/admin-ajax.php',
				data: { action:'aopaa', aa:'updateObject', data:asJson, postId:<?=$_GET['post']?> },
				type: 'POST',
				success: function(data){
//					alert(data);
					console.log(data);


				}
			});


			console.log(asJson);

		});





	jQuery('body').on('mousedown','.aopObject',function(){
		if(jQuery(this).hasClass('active'))return getObParams();

		if( jQuery('.aop-fields textarea[name=aopText]').hasClass('liveEdit') ){
			jQuery('.aop-fields textarea[name=aopText]').attr('obId','').val('').removeClass('liveEdit');
		}

		jQuery('.buildPlace .aopObject').removeClass('active')
		jQuery(this).addClass('active');
		getObParams();
	});

	jQuery( ".aopObject" ).draggable({
		stop: function( event, ui ) {
			getObParams();
//            console.log(event);
//            console.log(ui.offset.top);
		}
	}).resizable({
		stop: function( event, ui ) {
			getObParams();
		}
	});



	});


	function getObParams(){
		var ob = jQuery('.buildPlace .aopObject.active');
//    console.log(ob);
		if(!ob)return false;
		var offset = jQuery(ob).position();
		jQuery('.panel.aopParams [name=moveLeft]').val(offset.left);
		jQuery('.panel.aopParams [name=moveTop]').val(offset.top);

		jQuery('.panel.aopParams [name=sizeX]').val(jQuery(ob).width());
		jQuery('.panel.aopParams [name=sizeY]').val(jQuery(ob).height());


		jQuery(ob).attr('moveLeft', offset.left);
		jQuery(ob).attr('moveTop', offset.top);

		jQuery(ob).attr('sizeX', jQuery(ob).width());
		jQuery(ob).attr('sizeY', jQuery(ob).height());

//		console.log(offset);


	}

	function setObParamsFromFields(){
		var ob = jQuery('.buildPlace .aopObject.active');
//    console.log(ob);
		if(!ob)return false;


		var offset = jQuery(ob).position();

		jQuery(ob).attr('rotDirect', jQuery('.aopParams [name=rotDirect]').val());
		jQuery(ob).attr('rotCount', jQuery('.aopParams [name=rotCount]').val());
		jQuery(ob).attr('zindex', jQuery('.aopParams [name=zindex]').val());
		jQuery(ob).attr('animTime', jQuery('.aopParams [name=animTime]').val());
		jQuery(ob).attr('fSize', jQuery('.aopParams [name=fSize]').val());
		jQuery(ob).attr('fDimension', jQuery('.aopParams [name=fDimension]').val());
		jQuery(ob).attr('fAlign', jQuery('.aopParams [name=fAlign]').val());
		jQuery(ob).attr('fColor', jQuery('.aopParams [name=fColor]').val());
		jQuery(ob).attr('moveLeft', jQuery('.aopParams [name=moveLeft]').val());
		jQuery(ob).attr('moveTop', jQuery('.aopParams [name=moveTop]').val());
		jQuery(ob).attr('opacity', jQuery('.aopParams [name=opacity]').val());
		jQuery(ob).attr('sizeX', jQuery('.aopParams [name=sizeX]').val());
		jQuery(ob).attr('sizeY', jQuery('.aopParams [name=sizeY]').val());


//		console.log(offset);


	}

</script>


<?php


function aopGetObjectsRaw($postId = 0){

	$data = get_post_meta($postId, 'aopObjects', true);
	$data = json_decode($data, true);

//	print_r($data);

	return $data;

}

function aopGetObjectList($objectsRaw = [], $position='bf'){
	$ret = '';
	if(!is_array($objectsRaw))return false;

	foreach($objectsRaw as $k=>$v)$ret .= aopGetObjectCell($v, $position, $k);
	return $ret;

}

function aopGetObjectCell($objectRaw = 0, $position='', $id=''){
//print_r($objectRaw);

	$val = $objectRaw['data'];
	$obType = $objectRaw['obType'];
//	print_r($objectRaw);
	$objectRaw = $objectRaw[$position];

	$edit = $obType!='picture'?"<div title='Edit above' class='doEditOb acticon'>E</div>":'';
	if($obType=='picture')$val = "<img src='{$val}' />";
	$ret = "<div class='aopObject' obId = '{$id}'
				obType='{$obType}'
				rotDirect='{$objectRaw['rotDirect']}'
				rotCount='{$objectRaw['rotCount']}'
				zindex='{$objectRaw['zindex']}'
				animTime='{$objectRaw['animate']}'
				fSize='{$objectRaw['fsize']}'
				fDimension='{$objectRaw['fDimension']}'
				fAlign='{$objectRaw['fAlign']}'
				fColor='{$objectRaw['fColor']}'
				moveLeft='{$objectRaw['moveLeft']}'
				moveTop='{$objectRaw['moveTop']}'
				opacity='{$objectRaw['opacity']}'
				sizeX='{$objectRaw['sizeX']}'
				sizeY='{$objectRaw['sizeY']}'
				style='z-index:{$objectRaw['zindex']}; font-size:{$objectRaw['fsize']}{$objectRaw['fDimension']}; text-align:{$objectRaw['fAlign']};
				 color:#{$objectRaw['fColor']}; left:{$objectRaw['moveLeft']}px; top:{$objectRaw['moveTop']}px;
				  width:{$objectRaw['sizeX']}px; height:{$objectRaw['sizeY']}px; '

				><span class='data' style='opacity:{$objectRaw['opacity']};'>{$val}</span>

				<div title='Remove' class='doRemoveOb acticon'>X</div>
				{$edit}

				</div>";
	return $ret;

	//<div title='Edit' class='doEditOb'>E</div>

}

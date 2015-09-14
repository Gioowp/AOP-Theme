<?php
/**
The template name: Drager
 */


get_header(); ?>

<style>
    .buildPlace{ width:800px; height:480px; background:#005082; position:relative; overflow:hidden; }
    .buildPlace .object1{ width:200px; height:100px; background:#fff; }

    .ui-resizable-se { cursor:se-resize; width:8px; height:8px; background:#ccc; position: absolute; bottom: 1px; right: 1px; opacity:0.5;}
    .ui-resizable-e{ cursor:e-resize; height: 100%; right: -5px; top: 0; width: 7px; position: absolute;}
    .ui-resizable-s{ cursor:s-resize; bottom: -5px; height: 7px; left: 0; width: 100%; position: absolute;}

    .active{ z-index:111; -webkit-box-shadow: 0px 0px 12px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 0px 0px 12px 0px rgba(0,0,0,0.75);
        box-shadow: 0px 0px 12px 0px rgba(0,0,0,0.75); }
</style>

<div class="row">

<div class="panel panel-default params">
    <div class="panel-heading">Object configuration</div>
    <div class="panel-body">
<h3>Main properties</h3>
        <div class="rotateConf">
            Rotate
            <select>
                <option>Left</option>
                <option>Right</option>
            </select>

            <input name="rotateTimes" placeholder="xx times" value="0" >
        </div>
        <div class="zindexConf">
            Z-index
            <input name="rotateTimes" placeholder="Layering" value="1" >
        </div>
        <div class="zindexConf">
            Time
            <input name="rotateTimes" placeholder="Animation time (sec)" value="1" >
        </div>

        <h3>Different properties</h3>
        <div class="moveConf">
            Move

            <input name="moveX" placeholder="X axis" >
            <input name="moveY" placeholder="Y axis" >
        </div>

        <div class="opacityConf">
            Opacity
            <input name="opacity" placeholder="Opacity from 0 to 100" value="100" >
        </div>

        <div class="resizeConf">
            Resize

            <input name="resizeWidth" placeholder="Width" >
            <input name="resizeHeight" placeholder="Height" >
        </div>




    </div>
</div>


</div>


<div class="row">


    <div class="col-lg-5 objectsBefore">
        <div class="buildPlace">

            <div class="object1 draggableObject ui-widget-content">first object</div>
            <div class="object1 draggableObject ui-widget-content">first object</div>
            <div class="object1 draggableObject ui-widget-content">first object</div>

        </div>
    </div>

    <div class="col-lg-5 pull-right objectsAfter">
        <div class="buildPlace">

            <div class="object1 draggableObject ui-widget-content">first object</div>
            <div class="object1 draggableObject ui-widget-content">first object</div>
            <div class="object1 draggableObject ui-widget-content">first object</div>

        </div>
    </div>

</div>

<script>

    jQuery('body').on('mousedown','.draggableObject',function(){
        if(jQuery(this).hasClass('active'))return getObParams();

        jQuery('.buildPlace .draggableObject').removeClass('active')
        jQuery(this).addClass('active');
        getObParams();
    });

    jQuery( ".draggableObject" ).draggable({
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


function getObParams(){
    var ob = jQuery('.buildPlace .draggableObject.active');
//    console.log(ob);
    if(!ob)return false;
    var offset = jQuery(ob).position();
        jQuery('.panel.params [name=moveX]').val(offset.left);
        jQuery('.panel.params [name=moveY]').val(offset.top);


    jQuery('.panel.params [name=resizeWidth]').val(jQuery(ob).width());
    jQuery('.panel.params [name=resizeHeight]').val(jQuery(ob).height());


    console.log(offset);



}

</script>





<?php get_footer(); ?>


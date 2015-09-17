/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 */


jQuery(window).load(function(e){

	//aopConatainer

	console.log( jQuery( window ) );

	jQuery('.aopConatainer').height( jQuery( window )[0]['innerHeight']-40 );

	jQuery('body').on('DOMMouseScroll mousewheel',function(e){
		if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
			doPrevAnim();
		} else {
			doNextAnim();
		}

	});


	jQuery('body').on('click','nav a',function(e){
		e.preventDefault();
		doAnimByIndex(this);
	});


});


function doAnimByIndex(thiss){
	var nextLi = jQuery(thiss).closest( 'li' );
		if(jQuery(nextLi).hasClass('current_page_item'))return false;
	var nextPostId = jQuery( thiss ).attr('postId');

	var currentNavIndex = jQuery('nav li.current_page_item').index();
	var currentPostId = jQuery('nav li.current_page_item a').attr('postId');



	//console.log('>> '+navCount+'||'+currentNavIndex+'--'+nextIndex+'///'+currentPostId+'--'+nextPostId);

	jQuery('nav li').removeClass('current_page_item');
	jQuery(nextLi).addClass('current_page_item');

	eval('animateObsBackward'+currentPostId)();
	eval('animateObsForward'+nextPostId)();


}


function doNextAnim(){
	var currentNavIndex = jQuery('nav li.current_page_item').index();
	var navCount = jQuery('nav li').length-1;
	var nextIndex = 0;
	var currentPostId = jQuery('nav li.current_page_item a').attr('postId');

	if(navCount > currentNavIndex)nextIndex = parseInt(currentNavIndex+1);

	var nextLi = jQuery('nav li').eq( nextIndex );
	var nextPostId = jQuery( 'a', nextLi ).attr('postId');

//		console.log( jQuery( nextLi ).html() );

	console.log('>> '+navCount+'||'+currentNavIndex+'--'+nextIndex+'///'+currentPostId+'--'+nextPostId);

	jQuery('nav li').removeClass('current_page_item');
	jQuery(nextLi).addClass('current_page_item');

	eval('animateObsBackward'+currentPostId)();
	eval('animateObsForward'+nextPostId)();


}

function doPrevAnim(){
	var currentNavIndex = jQuery('nav li.current_page_item').index();
	var navCount = jQuery('nav li').length;
	var nextIndex = navCount-1;
	var currentPostId = jQuery('nav li.current_page_item a').attr('postId');

	if(currentNavIndex > 0)nextIndex = parseInt(currentNavIndex-1);

	var nextLi = jQuery('nav li').eq( nextIndex );
	var nextPostId = jQuery( 'a', nextLi ).attr('postId');

	console.log('<< '+navCount+'||'+currentNavIndex+'--'+nextIndex+'///'+currentPostId+'--'+nextPostId);

	jQuery('nav li').removeClass('current_page_item');
	jQuery(nextLi).addClass('current_page_item');

	eval('animateObsBackward'+currentPostId)();
	eval('animateObsForward'+nextPostId)();

//		console.log(navCount);
}



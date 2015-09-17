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


$aopData = pagesList();

//print_r($aopData);


?>
<style>
	<?=$aopData['css']?>
</style>


<div class="aopConatainer">
	<?=$aopData['dom']?>
</div>


<script>
	<?=$aopData['js'] ?>
</script>



<?php get_footer(); ?>


<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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


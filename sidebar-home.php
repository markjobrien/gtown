<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>

			<?php //get_latest_lotto(); ?>

			<a href="https://www.klubfunder.com/Clubs/Garristown_GFC/Membership" style="margin-bottom: 10px; display: block;" target="_blank">
				<img src="<?php echo get_template_directory_uri(); ?>/images/membership.png">
			</a>

			<?php get_latest_uploads(); ?>

		</div><!-- #secondary -->
	<?php endif; ?>





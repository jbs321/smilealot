<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="main_title"><?php the_title(); ?></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content">
					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>


	<div id="cta" class="et_pb_section et_section_regular" style='background-color:#0054a6;'>
				<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_2">
			<img src="http://smilealot.ca/wp-content/uploads/2015/02/cpu.jpg" alt="" class="et-waypoint et_pb_image et_pb_animation_left et_pb_image_sticky" />
		</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_2">
			<div class="et_pb_promo et_pb_bg_layout_dark et_pb_text_align_left et_pb_no_bg">
			<div class="et_pb_promo_description">
				<h2>Did you know you can schedule your appointment online?</h2>
				
			</div>
			<a class="et_pb_promo_button" href="http://smilealot.ca/contact-us/">Click here to get started</a>
		</div>
		</div> <!-- .et_pb_column -->
		</div> <!-- .et_pb_row -->
			
		</div> <!-- .et_pb_section -->

<div id="offer" class="et_pb_section et_section_regular" style='background-color:#00376c;'>
			
			
				
				<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_4_4">
			<div class="et_pb_text et_pb_bg_layout_dark et_pb_text_align_center">
			
<h2><em>Our Proud Associations</em></h2>

		</div> <!-- .et_pb_text -->
		</div> <!-- .et_pb_column -->
		</div> <!-- .et_pb_row --><div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_3">
			<img src="http://smilealot.ca/wp-content/uploads/2015/02/agd1.gif" alt="" class="et-waypoint et_pb_image et_pb_animation_off" />
			
		</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3">
			<img src="http://smilealot.ca/wp-content/uploads/2015/02/ubc.gif" alt="" class="et-waypoint et_pb_image et_pb_animation_off" />
		</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3">
			<img src="http://smilealot.ca/wp-content/uploads/2015/02/aaid.gif" alt="" class="et-waypoint et_pb_image et_pb_animation_off" />
		</div> <!-- .et_pb_column -->
		</div> <!-- .et_pb_row -->
			
		</div> <!-- .et_pb_section -->
					</div> <!-- .entry-content -->


</div> <!-- #main-content -->

<?php get_footer(); ?>
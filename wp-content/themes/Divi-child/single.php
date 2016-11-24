<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>

				<?php
					if ( ! post_password_required() ) :

						$thumb = '';

						$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

						$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
						$classtext = 'et_featured_image';
						$titletext = get_the_title();
						$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
						$thumb = $thumbnail["thumb"];

						$post_format = get_post_format();

						if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
							printf(
								'<div class="et_main_video_container">
									%1$s
								</div>',
								$first_video
							);
						} else if ( ! in_array( $post_format, array( 'gallery', 'link', 'quote' ) ) && 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb ) {
							
						} else if ( 'gallery' === $post_format ) {
							et_gallery_images();
						}
					?>

					<?php
						$text_color_class = et_divi_get_post_text_color();

						$inline_style = et_divi_get_post_bg_inline_style();

						switch ( $post_format ) {
							case 'audio' :
								printf(
									'<div class="et_audio_content%1$s"%2$s>
										%3$s
									</div>',
									esc_attr( $text_color_class ),
									$inline_style,
									et_pb_get_audio_player()
								);

								break;
							case 'quote' :
								printf(
									'<div class="et_quote_content%2$s"%3$s>
										%1$s
									</div> <!-- .et_quote_content -->',
									et_get_blockquote_in_content(),
									esc_attr( $text_color_class ),
									$inline_style
								);

								break;
							case 'link' :
								printf(
									'<div class="et_link_content%3$s"%4$s>
										<a href="%1$s" class="et_link_main_url">%2$s</a>
									</div> <!-- .et_link_content -->',
									esc_url( et_get_link_url() ),
									esc_html( et_get_link_url() ),
									esc_attr( $text_color_class ),
									$inline_style
								);

								break;
						}

					endif;
				?>

<div id="single-post" class="entry-content">
	<div class="et_pb_section et_section_regular">
		<div class="et_pb_row">
			
			<div class="et_pb_column et_pb_column_1_4">
			<div class="et_pb_widget_area et_pb_widget_area_left clearfix et_pb_bg_layout_light">
			<div id="nav_menu-2" class="et_pb_widget widget_nav_menu"><?php get_sidebar(); ?></div>
			</div> <!-- end .et_pb_widget -->
			</div> <!-- .et_pb_column -->
			
			<div class="et_pb_column et_pb_column_1_4">
			<?php if (has_post_thumbnail( $post->ID ) ): ?>
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<a href='<?php echo $image[0]; ?>' class="et_pb_lightbox_image" title=""><?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?></a>
			<?php endif; ?>
			</div> <!-- .et_pb_column -->
			
			<div class="et_pb_column et_pb_column_1_2">
			<div class="et_pb_text et_pb_bg_layout_light et_pb_text_align_left">
				<div id="stcpDiv">
					<h1><?php the_title(); ?></h1>
					<?php et_divi_post_meta(); ?>
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) ); ?>
				</div>
 			</div> <!-- .et_pb_text -->
		</div> <!-- .et_pb_column -->
		
		
		</div> <!-- .et_pb_row -->
	</div> <!-- .et_pb_section -->
</div> <!-- .entry-content -->



					<?php
					if ( et_get_option('divi_468_enable') == 'on' ){
						echo '<div class="et-single-post-ad">';
						if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
						else { ?>
							<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
				<?php 	}
						echo '</div> <!-- .et-single-post-ad -->';
					}
				?>


				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>
			<?php endwhile; ?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
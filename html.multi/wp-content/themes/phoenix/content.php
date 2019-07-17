<?php
	//Get child theme's name
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$site_options = get_option($themename);

	$blog_style = $site_options['blog_style'];

	$width  = 870;
	$height = 272;

	if ( 'a' == $blog_style ) {
		$width  = 424;
		$height = 265;
	}

	$arg_defaults = array(
			'width'              => $width,
			'height'             => $height,
			'crop'               => true,
			'crop_from_position' => 'center,center',
			'resize'             => true,
			'cache'              => true,
			'default'            => null,
			'jpeg_quality'       => 70,
			'resize_animations'  => false,
			'return'             => 'url',
			'background_fill'    => null
	);

	global $post;
	global $blog_engine_host;
	global $blog_id;

	$blog_post_key_group = get_transient( 'blog_post_key_group' );
	if ( false === $blog_post_key_group ) {
		$blog_post_key_group = array();
	}

	$blog_detail = get_transient( 'get_blog_details'. $blog_id . $post->ID );
	if ( false === $blog_detail ) {
		$blog_detail = get_site( get_current_blog_id() );
		set_transient( 'get_blog_details' . $blog_id . $post->ID, $blog_detail );

		if ( ! in_array( 'get_blog_details' . $blog_id . $post->ID, $blog_post_key_group ) ) {
			$blog_post_key_group[] = 'get_blog_details' . $blog_id . $post->ID;
			set_transient( 'blog_post_key_group', $blog_post_key_group );
		}
	}

	$blog_engine_link = get_transient( 'get_link_url' . $blog_id.$post->ID );
	if ( false === $blog_engine_link ) {
		$blog_engine_link = get_link_url( $post, $blog_engine_host, $blog_id );
		set_transient( 'get_link_url' . $blog_id . $post->ID, $blog_engine_link );

		if ( ! in_array( 'get_link_url' . $blog_id . $post->ID, $blog_post_key_group ) ) {
			$blog_post_key_group[] = 'get_link_url' . $blog_id . $post->ID;
			set_transient( 'blog_post_key_group', $blog_post_key_group );
		}
	}

	if ( false !== strpos( $blog_engine_link, '/post/' ) ) {
		$blog_engine_link = $blog_engine_link . '?site=' . $blog_detail->domain;
		$blog_engine_content = get_transient( 'get_content_from_link' . $blog_id . $post->ID );
			if ( false === $blog_engine_content ) {
				$blog_engine_content = get_content_from_link( $blog_engine_link );
				set_transient( 'get_content_from_link' . $blog_id . $post->ID, $blog_engine_content );

				if ( ! in_array( 'get_content_from_link' . $blog_id . $post->ID, $blog_post_key_group ) ) {
					$blog_post_key_group[] = 'get_content_from_link' . $blog_id . $post->ID;
					set_transient( 'blog_post_key_group', $blog_post_key_group );
				}
			}
	} else {
		$blog_engine_link = "";
	}
	$image_src = get_transient( 'wp_get_attachment_image_src' . $blog_id . $post->ID );
	if ( false === $image_src ) {
		$image_src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		set_transient( 'wp_get_attachment_image_src' . $blog_id . $post->ID, $image_src );

		if ( ! in_array( 'wp_get_attachment_image_src' . $blog_id . $post->ID, $blog_post_key_group ) ) {
			$blog_post_key_group[] = 'wp_get_attachment_image_src'.$blog_id.$post->ID;
			set_transient( 'blog_post_key_group', $blog_post_key_group );
		}
	}
	$syndicate_image = get_transient( 'get_post_custom_values' . $blog_id . $post->ID );
			if ( false === $syndicate_image ) {
				$syndicate_image = get_post_custom_values( 'syndicates_image_1', $post->ID );
				set_transient( 'get_post_custom_values' . $blog_id . $post->ID, $syndicate_image );

				if ( ! in_array( 'get_post_custom_values' . $blog_id . $post->ID, $blog_post_key_group ) ) {
					$blog_post_key_group[] = 'get_post_custom_values' . $blog_id . $post->ID;
					set_transient( 'blog_post_key_group', $blog_post_key_group );
				}
			}

	$post_date                = new DateTime( $post->post_date );
	$current_date             = new DateTime( 'now' );
	$interval                 = date_diff( $current_date, $post_date );
	$blog_engine_custom_field = get_post_meta( $post->ID, 'icon', true );

	$featured_image   = '';
	$meta_content_img = '';

	if ( $interval->y <= 2 or $blog_engine_custom_field != 'http://www.techadvisory.org/favicon.ico' ) {
		if ( '' != $image_src ) {
			$featured_image   = '<img src="' .wpthumb(  $image_src[0], $arg_defaults) . '" class="img-responsive img-thumbnail margin-10" width="' . $width . '" height="' . $height . '" alt="' . get_the_title() . '" itemprop="url" />';
			$meta_content_img = '<meta content="'. $image_src[0] .'" itemprop="image">';
		} elseif ( NULL != $syndicate_image ) {
			$featured_image   = '<img src="' . wpthumb( $syndicate_image[0], $arg_defaults ) . '" class="img-responsive" alt="' . get_the_title() . '" />';
			$meta_content_img = '<meta content="'. $syndicate_image[0] .'" itemprop="image">';
		}
	}
?>

<?php
$post_meta_date = $site_options['post_meta_date'];

$blog_style = $site_options['blog_style'];
if ( 'c' == $blog_style ) { ?>
	<article class="grid col-md-4 blog-list" itemtype="http://schema.org/BlogPosting" itemscope="">
		<figure class="clearfix">
			<a href="<?php the_permalink(); ?>" class="media-object"><?php echo $featured_image; ?></a>
			<?php echo $meta_content_img; ?>
		</figure>
		<meta content="<?php the_time('Y-m-j'); ?>" itemprop="datePublished">
		<footer>
			<?php
				if ( $post_meta_date ) {
					echo '<time class="date" datetime="2018-02-14"><i class="fa fa-calendar"></i> ';
					the_time( 'F jS, Y' );
					echo '</time> ';
				}
			?>
		</footer>
		<h3 itemprop="headline"><a href="<?php the_permalink(); ?>"><span itemprop="name"><?php echo get_the_title(); ?></span></a>
		</h3>
		<div itemprop="articleBody">
			<?php the_excerpt(); ?>
		</div>
		<p><a class="btn btn-default btn-primary" href="<?php the_permalink(); ?>">Read more</a></p>
	</article>
<?php
} else {
?>
	<article class="clearfix" itemtype="http://schema.org/BlogPosting" itemscope="">
		<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><span itemprop="name"><?php echo get_the_title(); ?></span></a></h2>
		<meta content="<?php the_time( 'Y-m-j' ); ?>" itemprop="datePublished">
		<footer>
		<?php
			$post_meta_author     = $site_options['post_meta_author'];
			$post_meta_categories = $site_options['post_meta_categories'];
			$post_meta_tags       = $site_options['post_meta_tags'];

			if ( $post_meta_date ) {
				echo '<span class="date"><i class="fa fa-calendar"></i> ';
				the_time( 'F jS, Y' );
				echo '</span> ';
			}
			if ( $post_meta_author ) {
				echo '<span class="user"><i class="fa fa-user"></i> <span itemprop="author">';
				the_author_posts_link();
				echo '</span></span> ';
			}
			if ( $post_meta_categories ) {
				echo '<span class="category"><i class="fa fa-file"></i> <span itemprop="genre">';
				the_category( ', ' );
				echo '</span></span> ';
			}
			if ( $post_meta_tags ) {
				the_tags( '<span class="tag"><i class="fa fa-tag"></i> <span itemprop="keywords">', '</span>, <span itemprop="keywords">', '</span></span>' );
			}
		?>
		</footer>
	<?php
		// Blog Style A
		if ( 'a' == $blog_style ):
			if ( '' != $featured_image ):
		?>
				<div class="row">
					<figure class="col-md-6">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
							<?php echo $featured_image; ?>
						</a>
						<?php echo $meta_content_img; ?>
					</figure>
		<?php endif; ?>
					<div class="detail<?php echo $featured_image ? ' col-md-6' : ''; ?>" itemprop="articleBody">
						<?php
						if ( ! empty( $post->post_excerpt ) or ! empty( $blog_engine_content ) ) {
							the_excerpt();
						} else {
							the_content();
						}
						?>
						<div class="additional">
							<a class="more-link btn btn-primary pronto-baby" href="<?php the_permalink(); ?>">Read more</a>
							<?php
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
							if ( is_plugin_active( 'disqus-comment-system/disqus.php' ) ) {
								echo '';
							} elseif ( get_comments_number() == 0 ) {
								echo '';
							} else {
								echo '<span class="comment pull-right"><i class="fa fa-comment"></i>' . comments_number( ' ', '1 Comment', '% Comments' ) . '</span>';
							}
							?>
						</div>
					</div>
		<?php
			if ( '' != $featured_image ):
		?>
				</div>
		<?php
			endif;
		// Blog Style B
		elseif ( 'b' == $blog_style ):
			if ( '' != $featured_image ):
	?>
				<figure class="clearfix">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
						<?php echo $featured_image; ?>
					</a>
					<?php echo $meta_content_img; ?>
				</figure>
			<?php endif; ?>
			<div itemprop="articleBody">
				<?php
				if ( ! empty( $post->post_excerpt ) or ! empty( $blog_engine_content ) ) {
					the_excerpt();
				} else {
					$content = strip_tags( the_content(), '<p>' );
					$content = clip_sentence( $content, 250 );
					echo '<p>' . do_shortcode( $content ) . '</p>';
				}
				?>
			</div>
			<div class="additional">
				<a class="more-link btn btn-primary pronto-baby" href="<?php the_permalink(); ?>">Read more</a>
				<?php
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				if ( is_plugin_active( 'disqus-comment-system/disqus.php' ) ) {
					echo '';
				} elseif ( get_comments_number() == 0 ) {
					echo '';
				} else {
					echo '<span class="comment pull-right"><i class="fa fa-comment"></i>' . comments_number( ' ', '1 Comment', '% Comments' ) . '</span>';
				}
				?>
			</div>
	<?php endif; ?>
</article>
<?php
}
?>

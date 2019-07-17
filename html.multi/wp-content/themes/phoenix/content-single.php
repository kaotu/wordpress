<?php if ( has_post_format( 'quote' ) ) { ?>
    <article class="content format-quote">
<?php } else { ?>
    <article class="content" itemtype="http://schema.org/BlogPosting" itemscope="">
<?php } ?>
    <h1><span itemprop="name"><?php echo get_the_title(); ?></span></h1>
    <meta content="<?php the_time('Y-m-j') ?>" itemprop="datePublished">
    <footer>
    <?php
        //Get child theme's name
        $themename = get_option( 'stylesheet' );
        $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
        $site_options = get_option($themename);

        $post_meta_date = $site_options['post_meta_date'];
        $post_meta_author = $site_options['post_meta_author'];
        $post_meta_categories = $site_options['post_meta_categories'];
        $post_meta_tags = $site_options['post_meta_tags'];

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
    $arg_defaults = array(
        'width'              => 870,
        'height'             => 272,
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
    $syndicate_image = get_post_custom_values('syndicates_image_1', get_the_ID());
    global $post;
    $post_date = new DateTime($post->post_date);
    $current_date = new DateTime('now');
    $interval = date_diff($current_date, $post_date);
    if($interval->y <= 2) {
        if(has_post_thumbnail()) {
            $image_src  = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full');
            echo '<figure class="clearfix">';
            echo '<img src="' . wpthumb( $image_src[0], $arg_defaults ) . '" class="img-responsive wp-post-image alignnone" alt="' . get_the_title() . '">';
            echo '</figure>';
        }
        else if($syndicate_image[0] != NULL) {
            echo '<figure class="clearfix">';
            echo '<img src="' . wpthumb( $syndicate_image[0], $arg_defaults ) . '" class="img-responsive wp-post-image alignnone" alt="' . get_the_title() . '">';
            echo '</figure>';
        }
    }
    else {
        $blog_engine_custom_field = get_post_meta($post->ID, 'icon', true);
        if ($blog_engine_custom_field == 'http://www.techadvisory.org/favicon.ico') {
            add_filter('the_content', 'remove_img');
        }
    }
    ?>
    <div itemprop="articleBody">
        <?php the_content(); ?>
    </div>
</article>
<hr>

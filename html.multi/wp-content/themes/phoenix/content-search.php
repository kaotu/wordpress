<article class="clearfix" itemtype="http://schema.org/BlogPosting" itemscope="">
    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><span itemprop="name"><?php echo search_highlight( get_the_title(), get_search_query() ); ?></span></a></h2>
    <meta content="<?php the_time('Y-m-j'); ?>" itemprop="datePublished" />
<?php
    global $post;
    global $blog_engine_host;
    global $blog_id;

    $blog_detail      = get_site( get_current_blog_id() );
    $blog_engine_link = get_link_url( $post, $blog_engine_host, $blog_id );

    if ( false !== strpos( $blog_engine_link, '/post/' ) ) {
        $blog_engine_link    = $blog_engine_link . '?site=' . $blog_detail->domain;
        $blog_engine_content = get_content_from_link( $blog_engine_link );
    }
    else {
        $blog_engine_link = "";
    }
?>
    <div class="detail<?php echo $featured_image ? ' col-md-6' : ''; ?>" itemprop="articleBody">
<?php
        if ( ! empty( $post->post_excerpt ) or !empty( $blog_engine_content ) ) {
            $content = get_the_excerpt();
            echo '<p>' . search_highlight( wp_strip_all_tags( do_shortcode( $content ), true ), get_search_query() ) . '</p>';
        }
        else {
            $content = strip_tags( get_the_content(), '<p>' );

            //add wp_strip_all_tags to remove js and html tags from the search results page
            $content = search_highlight( wp_strip_all_tags( do_shortcode( $content ), true ), get_search_query() );
            $content = clip_sentence( $content, 250 );
            echo '<p>' . $content . '</p>';
        }
?>
        <div class="additional">
            <a class="more-link btn btn-primary" href="<?php the_permalink(); ?>">Read more</a>
        </div>
    </div>
</article>

<?php
/**
 * The Footer for our theme.
 *
 * @package Phoenix
 */
/* Check if page template is not 'template-fullwidth.php' or post type is not Landing Page */
do_action('normal_page_start_footer');
?>
<!--Footer-->
<?php
//Close div for page-wrap
echo '</div>';
/* hook landing page header group */
do_action('landing_page_footer_group');

?>
    </div>
  </div>
<?php wp_footer(); ?>
<?php if( extension_loaded('newrelic') ) { echo newrelic_get_browser_timing_footer( true ); } ?>
<!--Javascript-->
<script src="<?php echo get_template_directory_uri(); ?>/bootstrap/js/bootstrap.min.js"></script>
<?php $mainjsmodify = filemtime(WP_CONTENT_DIR.'/themes/phoenix/js/main.js');?>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js?<?php echo $mainjsmodify ?>"></script>
</body>
</html>
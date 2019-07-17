<?php
/**
 * Header for our Phoenix theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Phoenix
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes"/>
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php if ( true !== constant( 'IS_PRODUCTION' ) ) { ?><meta name="robots" content="noindex, nofollow" /><?php } ?>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php echo embed_google_fonts(); ?>
    <link href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/fontawesome/css/v4-shims.min.css" rel="stylesheet" />
    <!--[if IE 7]>
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome-ie7.min.css" rel="stylesheet" />
    <![endif]-->
    <?php if( extension_loaded('newrelic') ) { echo newrelic_get_browser_timing_header( true ); } ?>
    <?php echo get_fav_icon(). "\n"; ?>
    <?php wp_head(); ?>
    <!--[if lt IE 9]>
        <script src="<?php echo home_url(); ?>/assets/crossdomain/respond.min.js"></script>
        <link href="<?php echo home_url(); ?>/assets/crossdomain/respond-proxy.html" id="respond-proxy" rel="respond-proxy" />
        <link href="<?php echo home_url(); ?>/assets/nocdn/crossite/respond.proxy.gif" id="respond-redirect" rel="respond-redirect" />
        <script src="<?php echo home_url(); ?>/assets/nocdn/crossite/respond.proxy.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->
<?php
    global $post;

    /* hook custom landing page css*/
    do_action('custom_landing_page_css');
?>
<?php if ( has_post_format( 'quote' ) ) { ?>
    <style>
        .format-quote blockquote::before {
            content: "";
            display: block;
            font-family: fontawesome;
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .format-quote blockquote {
            background: #fff none repeat scroll 0 0;
            border: 0 none;
            padding: 40px;
        }

        .format-quote blockquote cite {
            border-top: 1px solid #ccc;
            display: block;
            font-size: 0.85em;
            padding-top: 1.5em;
        }

        @media  (max-width: 991px) {
            .format-quote blockquote {
                background: rgba(0, 0, 0, 0.02) none repeat scroll 0 0;
            }
        }
    </style>
<?php } ?>
</head>

<body <?php body_class(); ?>>
  <div>
    <div>
      <div class="page-wrap">
<?php
        /* hook landing page header group */
        do_action( 'landing_page_header_group' );

        /* breadcrump */
        do_action( 'breadcrumb_template' );

        /* Masthead Jumpdown */
        do_action( 'masthead_jumpdown_hook' );

        /* Landing Page Templates Masthead Hook*/
        do_action( 'landing_page_masthead_hook' );

        /* Location Template Masthead Hook */
        do_action( 'location_masthead_hook' );

        /** Showcase Template Masthead Hook */
        do_action( 'showcase_masthead_hook' );

        /* Check if page template is not 'template-fullwidth.php'
                 * or post type is not Landing Page */
        do_action( 'normal_page_end_header' );

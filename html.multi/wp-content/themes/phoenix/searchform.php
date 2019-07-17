<?php
/**
 * The template for displaying search forms in Phoenix
 *
 * @package Phoenix
 */
?>
<form id="searchform" role="search" method="get" class="navbar-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input class="search-query" type="text" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'phoenix' ); ?>" style="width: 100%;">
    <button class="btn btn-default" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'phoenix' ); ?>">
        <i class="fa fa-search"></i>
    </button>
</form>

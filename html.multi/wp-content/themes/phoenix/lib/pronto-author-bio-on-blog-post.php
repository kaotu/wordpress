<?php
add_action( 'show_user_profile', 'add_extra_social_links' );
add_action( 'edit_user_profile', 'add_extra_social_links' );

function add_extra_social_links( $user )
{
    if (esc_attr( get_the_author_meta( "author_box", $user->ID )) == "yes") 
        $check_author_box = "checked";

    if (esc_attr( get_the_author_meta( "author_email", $user->ID )) == "yes") 
        $check_author_email = "checked";

    $html =  '<table class="form-table">
        	<tr>
                <th><label for="position">Position</label></th>
                <td><input type="text" name="position" value="' . esc_attr(get_the_author_meta( "position", $user->ID )) . '" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="linkedin_profile">Linkedin ID</label></th>
                <td><input type="text" name="linkedin_profile" value="' . esc_attr(get_the_author_meta( "linkedin_profile", $user->ID )) . '" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="author_box">Enable Author Bio on Posts</label></th>
                <td><input type="checkbox" name="author_box" value="yes" ' . $check_author_box . ' class="regular-checkbox" /></td>
            </tr>
            <tr>
                <th><label for="author_email">Enable Email in Author Bio</label></th>
                <td><input type="checkbox" name="author_email" value="yes" ' . $check_author_email . ' class="regular-checkbox" /></td>
            </tr>
        </table>';
    echo $html;
    return $html;
}

add_action( 'personal_options_update', 'save_extra_social_links' );
add_action( 'edit_user_profile_update', 'save_extra_social_links' );

function save_extra_social_links( $user_id )
{
    update_user_meta( $user_id,'position', sanitize_text_field( $_POST['position'] ) );
    update_user_meta( $user_id,'linkedin_profile', sanitize_text_field( $_POST['linkedin_profile'] ) );
    update_user_meta( $user_id,'author_box', $_POST['author_box'] );
    update_user_meta( $user_id,'author_email', $_POST['author_email'] );
}

add_filter('get_avatar','change_avatar_css');

function change_avatar_css($class) {
    $class = str_replace("avatar-249 photo", "avatar-249 photo img-responsive img-circle media-object pull-left col-xs-6 col-sm-4 col-md-3' itemprop='image", $class) ;
    return $class;
}
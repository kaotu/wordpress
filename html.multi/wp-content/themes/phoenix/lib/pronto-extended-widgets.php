<?php
class Pronto_Widget_Archives extends WP_Widget_Archives {

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $new_instance = wp_parse_args( 
            (array) $new_instance, 
            array( 
                'title'    => '', 
                'count'    => 0, 
                'dropdown' => '',
            ) 
        );

        $instance['title']    = strip_tags($new_instance['title']);
        $instance['count']    = $new_instance['count'] ? 1 : 0;
        $instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;
        $instance['tag']      = $new_instance['tag'] ? 1 : 0;

        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title'    => '',
                'count'    => 0,
                'dropdown' => '',
                'tag'      => '',
            )
        );

        $title    = strip_tags( $instance['title'] );
        $count    = $instance['count'] ? 'checked="checked"' : '';
        $dropdown = $instance['dropdown'] ? 'checked="checked"' : '';
        $tag      = $instance['tag'] ? 'checked="checked"' : '';
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p>
            <input class="checkbox" type="checkbox" <?php echo $dropdown; ?> id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" /> <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label>
            <br/>
            <input class="checkbox" type="checkbox" <?php echo $count; ?> id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" /> <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label>
            <br/>
            <input class="checkbox" type="checkbox" <?php echo $tag; ?> id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" /> <label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('Show as tags'); ?></label>
        </p>
<?php
    }
}

class Pronto_Widget_Categories extends WP_Widget_Categories {

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']        = strip_tags($new_instance['title']);
        $instance['count']        = ! empty($new_instance['count']) ? 1 : 0;
        $instance['hierarchical'] = ! empty($new_instance['hierarchical']) ? 1 : 0;
        $instance['dropdown']     = ! empty($new_instance['dropdown']) ? 1 : 0;
        $instance['tag']          = ! empty($new_instance['tag']) ? 1 : 0;

        return $instance;
    }

    function form( $instance ) {
        //Defaults
        $instance     = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title        = esc_attr( $instance['title'] );
        $count        = isset($instance['count']) ? (bool) $instance['count'] :false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
        $dropdown     = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
        $tag          = isset( $instance['tag'] ) ? (bool) $instance['tag'] : false;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
        <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
        <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
        <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>"<?php checked( $tag ); ?> />
            <label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e( 'Show as tags' ); ?></label>
        </p>
<?php
    }
}

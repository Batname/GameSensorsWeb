<?php
$el_clans_title = $el_clans_number = $el_class = '';
global $post;
extract( shortcode_atts( array(
    'el_clans_title' => '',
    'el_clans_number'  => '',
    'el_class' => '',
), $atts ) );
if(empty($css)) $css = '';
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_text_column wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>

<div id="buddypress" class="<?php echo esc_attr($css_class); if(!empty($el_class)) echo esc_attr($el_class); ?> clans-block">
    <div class="wpb_wrapper" id="members">
        <div class="title-wrapper">
            <h3 class="widget-title"><i class="fa fa-group"></i> <?php if(!empty($el_clans_title)) echo esc_attr($el_clans_title); ?></h3>
            <div class="clear"></div>
        </div>
       <?php

        $args = array(
              'post_type' => 'clan',
              'orderby' => 'name',
              'order' => 'ASC',
              'showposts' => $el_clans_number
              );
        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) { ?>

     <ul id="members-list" class="item-list" >

   <?php while ( $the_query->have_posts() ) { $the_query->the_post();  ?>
        <li>
                <div class="clan-list-wrapper">
                    <div class="item-avatar">
                       <a href="<?php echo get_permalink($post->ID); ?> ">
                         <?php $img = get_post_meta( $post->ID, 'clan_photo', true );
                               $image = blackfyre_aq_resize( $img, 50, 50, true, true, true );
                               if(!$image){
                                   $image = get_template_directory_uri().'/img/defaults/default-clan-50x50.jpg';
                               }
                               ?>
                        <img alt="img" src="<?php echo esc_url($image); ?>" class="avatar" >
                       </a>
                    </div>

                    <div class="item">
                        <div class="item-title">
                            <a href="<?php echo get_permalink($post->ID); ?> "> <?php echo esc_attr($post->post_title); ?></a>
                            <div class="item-meta"><span class="activity"> <?php $members = blackyfyre_return_number_of_members($post->ID); ?>
            <?php if($members == 1){ echo $members; ?>&nbsp;<?php _e('Member','blackfyre'); }else{ echo esc_attr($members); ?>&nbsp;<?php _e('Members','blackfyre'); } ?></span></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

            </li>
   <?php } ?>

      </ul>
      <div class="clear"></div>

<?php } else { ?>
 <div class="error_msg"><span><?php  _e('There are no clans at the moment!', 'blackfyre'); ?> </span></div>
<?php }
/* Restore original Post Data */
wp_reset_postdata(); ?>


    </div>
</div>
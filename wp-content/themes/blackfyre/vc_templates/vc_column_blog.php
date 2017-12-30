<?php
$el_blog_number_posts = $el_blog_categories =  $el_blog_title = $el_class = '';
$posts = array();
extract( shortcode_atts( array(
    'el_blog_title' => '',
    'el_blog_number_posts' => '',
    'el_class' => '',
    'el_blog_categories' => ''
), $atts ) );
if(empty($css)) $css = '';
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_text_column wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>

<div class="<?php echo esc_attr($css_class); if(!empty($el_class)) echo esc_attr($el_class); ?>">
    <div class="wpb_wrapper">
        <div class="title-wrapper">
            <h3 class="widget-title"><i class="fa fa-newspaper-o"></i> <?php if(!empty($el_blog_title)) echo esc_attr($el_blog_title); ?></h3>
            <div class="clear"></div>
        </div>

           <?php

                    $ct = array();
                    if ( $el_blog_categories != '' ) {
                        $el_blog_categories = explode( ",", $el_blog_categories );
                        foreach ( $el_blog_categories as $category ) {
                            array_push( $ct, $category );
                        }
                    }

        $posts = new WP_Query(array(
                        'showposts' => $el_blog_number_posts,
                        'category__in' => $ct
                    ));

       ?>
        <?php if ( $posts->have_posts() ) : while ( $posts->have_posts() ) : $posts->the_post(); ?>
    <div class="blog-post">

        <div class="blog-twrapper">
            <div class="blog-image right">
                 <?php
                    global $post;
                     $key_1_value = get_post_meta($post->ID, '_smartmeta_my-awesome-field77', true);
                    if($key_1_value != '') {
                    echo $key_1_value;
                    }elseif ( has_post_thumbnail() ) { ?>
                      <a href="<?php the_permalink(); ?>"> <?php
                       $thumb = get_post_thumbnail_id();
                       $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
                       $image = blackfyre_aq_resize( $img_url, 817, 320, true, '', true ); //resize & crop img
                       ?><img alt="img" src="<?php echo $image[0]; ?>" /></a>
                 <?php } ?>
                 <?php if ( has_post_thumbnail() or  $key_1_value != '') { ?>
                 <div class="blog-date">
                 <?php }else{?>
                 <div class="blog-date-noimg">
                 <?php } ?>
                    <span class="date"><?php the_time('M'); ?><br /><?php the_time('d'); ?></span>
                    <div class="plove"><?php if( function_exists('heart_love') ) heart_love(); ?></div>
                 </div>

                        <div class="blog-rating">
                        <?php
                        // overall stars
                        $overall_rating_1 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_1!="0" && $overall_rating_1=="0.5"){ ?>
                        <div class="overall-score"><div class="rating r-05"></div></div>
                        <?php } ?>

                        <?php $overall_rating_2 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_2!="0" && $overall_rating_2=="1"){ ?>
                        <div class="overall-score"><div class="rating r-1"></div></div>
                        <?php } ?>

                        <?php $overall_rating_3 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_3!="0" && $overall_rating_3=="1.5"){ ?>
                        <div class="overall-score"><div class="rating r-15"></div></div>
                        <?php } ?>

                        <?php $overall_rating_4 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_4!="0" && $overall_rating_4=="2"){ ?>
                        <div class="overall-score"><div class="rating r-2"></div></div>
                        <?php } ?>

                        <?php $overall_rating_5 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_5!="0" && $overall_rating_5=="2.5"){ ?>
                        <div class="overall-score"><div class="rating r-25"></div></div>
                        <?php } ?>

                        <?php $overall_rating_6 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_6!="0" && $overall_rating_6=="3"){ ?>
                        <div class="overall-score"><div class="rating r-3"></div></div>
                        <?php } ?>

                        <?php $overall_rating_7 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_7!="0" && $overall_rating_7=="3.5"){ ?>
                        <div class="overall-score"><div class="rating r-35"></div></div>
                        <?php } ?>

                        <?php $overall_rating_8 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_8!="0" && $overall_rating_8=="4"){ ?>
                        <div class="overall-score"><div class="rating r-4"></div></div>
                        <?php } ?>

                        <?php $overall_rating_9 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_9!="0" && $overall_rating_9=="4.5"){ ?>
                        <div class="overall-score"><div class="rating r-45"></div></div>
                        <?php } ?>

                        <?php $overall_rating_10 = get_post_meta(get_the_ID(), 'overall_rating', true);
                        if($overall_rating_10!="0" && $overall_rating_10=="5"){ ?>
                        <div class="overall-score"><div class="rating r-5"></div></div>

                        <?php } ?>
                         </div><!-- blog-rating -->

            </div><!-- blog-image -->

              <div class="blog-content <?php if ( has_post_thumbnail() or  $key_1_value != '') {  }else{?> blog-content-no-img <?php } ?>">
                        <h2><a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a></h2>
                        <?php the_excerpt(10); ?>
              </div><!-- blog-content -->
          </div>


         <div class="blog-info">


                    <div class="post-pinfo">
                        <span class="fa fa-user"></span> <a data-original-title="<?php _e("View all posts by ", 'blackfyre'); ?><?php echo get_the_author(); ?>" data-toggle="tooltip" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author(); ?></a> &nbsp;
                        <span class="fa fa-comments-o"></span>

                        <?php if ( is_plugin_active( 'disqus-comment-system/disqus.php' )){ ?>
                        <a  href="<?php echo the_permalink(); ?>#comments" >
                        <?php comments_number( __('No comments', 'blackfyre'), __('One comment', 'blackfyre'), __('% comments', 'blackfyre')); ?></a> &nbsp;
                       <?php }else{ ?>
                        <a data-original-title="<?php comments_number( __('No comments in this post', 'blackfyre'), __('One comment in this post', 'blackfyre'), __('% comments in this post', 'blackfyre')); ?>" href="<?php echo the_permalink(); ?>#comments" data-toggle="tooltip">
                        <?php comments_number( __('No comments', 'blackfyre'), __('One comment', 'blackfyre'), __('% comments', 'blackfyre')); ?></a> &nbsp;

                       <?php } ?>
                        <?php $posttags = get_the_tags();if ($posttags) {?>  <span class="fa fa-tags"></span>  <?php $i = 0; $len = count($posttags); foreach($posttags as $tag) { ?>  <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"> <?php echo esc_attr($tag->name); if($i != $len - 1) echo ', '; ?> </a><?php  $i++; } }?>
                    </div><!-- post-pinfo -->

                    <a href="<?php the_permalink(); ?>" class="button-small"><?php _e("Read more", 'blackfyre'); ?></a>

                    <div class="clear"></div>

         </div><!-- blog-info -->
        </div><!-- /.blog-post -->

     <div class="block-divider"></div>
        <?php endwhile; endif; ?>
        <div class="clear"></div>

    </div><!--wpb_wrapper -->
</div>
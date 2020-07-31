
<?php get_header(); ?>
<?php get_sidebar(); ?>
    <section>
        custompage
        <?php if(have_posts()): while(have_posts()):the_post(); ?>

        <div class="cat">
            <?php //echo get_the_term_list($post->ID, 'hoge_custom_post', '', ' / ', ''); ?>
        </div>
        <div class="tag">
            <?php //echo get_the_term_list($post->ID,'works_tag'); ?>
        </div>
        <h2><?php the_title(); ?></h2>
        <div class="thumnail">
            <?php the_post_thumbnail('large', array('class' => 'w-100')); ?>
        </div>
        <?php the_content(); ?>
        <?php
            echo '<pre>';
            var_dump(get_post_meta($post->ID , '' ,true));
            echo '</pre>';
        ?>
        <?php
            date_default_timezone_set('Asia/Tokyo');
            echo date('Y-m-d H:i', filemtime(__FILE__));
        ?>

        <?php endwhile; endif; ?>
    </section>
<?php get_footer(); ?>
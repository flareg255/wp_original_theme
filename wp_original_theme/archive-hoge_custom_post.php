<?php
    get_header();
    get_sidebar();
?>

    <section>
        <h1><?php post_type_archive_title(); ?></h1>
        <!--
            <ul class="categoryWrap">
                <?php wp_list_categories( array('title_li' => '', 'post_type' => 'hoge_custom_post', 'show_count' => 0 ) ); ?>
            </ul>
        -->

        <div class="listWrap container">
            <div class="row">
                <?php if(have_posts()): while(have_posts()):the_post(); ?>

                    <div class="col-4">
                        <a href="<?php the_permalink(); ?>" >
                            <div class="listBox">
                                <div class="thumnail"><?php the_post_thumbnail('medium', array('class' => 'w-100')); ?></div>
                                <h2><?php the_title(); ?></h2>
                                <div class="data"><?php the_time('Y.m.d'); ?></div>
                            </div>
                        </a>
                    </div>

                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
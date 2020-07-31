<?php get_header(); ?>
<?php get_sidebar(); ?>
            <div id="rightSide" class="col-9">
                <article>
                    <?php 
                        if ( have_posts() ) :
                            while ( have_posts() ) : the_post();
                    ?>
                        <h2><?php the_title(); ?></h2>
                        <section>
                            single
                            <?php the_content(); ?>
                        </section>
                    <?php 
                        endwhile;
                    endif;
                    ?>
                </article>
            </div>
<?php get_footer(); ?>
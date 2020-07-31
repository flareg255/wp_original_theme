            <div id="leftSide" class="col-3">
                <section>
                    <h1>
                        <?php
                            global $theme_func;
                            $theme_func->set_logo();
                        ?>
                    </h1>
                </section>
                <section>
                    <?php
                        $defaults = array(
                            'menu'            => '',
                            'menu_class'      => 'menu',
                            'menu_id'         => '',
                            'container'       => 'nav',
                            'container_class' => '',
                            'container_id'    => '',
                            'fallback_cb'     => 'wp_page_menu',
                            'before'          => '',
                            'after'           => '',
                            'link_before'     => '',
                            'link_after'      => '',
                            'echo'            => true,
                            'depth'           => 0,
                            'walker'          => '',
                            'theme_location'  => '',
                            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        );

                        wp_nav_menu( $defaults );
                    ?>
                </section>
            </div>
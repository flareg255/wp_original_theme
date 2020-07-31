<?php
class Theme_func {
    public function set_logo(){
        $logo = '';

        if( has_custom_logo() ){
            $logo = get_custom_logo();
        }else{
            $logo = '<a href="'.home_url().'">'.get_bloginfo( 'name' ).'</a>';
        }

        echo '<h1 class="logo">'.$logo.'</h1>';
    }
}
?>
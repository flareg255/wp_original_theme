<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Post_type {

    const POST_TYPE_PROP = array(
        'label' => '自作', //表示名
        'public' => true, //公開状態
        'exclude_from_search' => true, //検索対象に含めるか
        'show_ui' => true, //管理画面に表示するか
        'show_in_menu' => true, //管理画面のメニューに表示するか
        'menu_position' => 5, //管理メニューの表示位置を指定
        'hierarchical' => true, //階層構造を持たせるか
        'has_archive' => true, //この投稿タイプのアーカイブを作成するか
        'supports' => array( 'title', 'thumbnail', 'custom-fields' )
    );

    // const TAXONOMY_PROP_CAT = array(
    //     'label' => 'お知らせカテゴリ',
    //     'singular_label' => 'お知らせカテゴリ',
    //     'labels' => array(
    //         'all_items' => 'お知らせカテゴリ一覧',
    //         'add_new_item' => 'お知らせカテゴリを追加'
    //     ),
    //     'public' => true,
    //     'show_ui' => true,
    //     'show_in_nav_menus' => true,
    //     'hierarchical' => true
    // );

    // const TAXONOMY_PROP_TAG =array(
    //     'label' => 'お知らせのタグ',
    //     'singular_label' => 'お知らせのタグ',
    //     'labels' => array(
    //         'add_new_item' => 'お知らせのタグを追加'
    //     ),
    //     'public' => true,
    //     'show_ui' => true,
    //     'show_in_nav_menus' => true,
    //     'hierarchical' => false
    // );



    public function init() {
        add_action( 'init', array( $this, 'create_post_type' ) );
        add_action( 'admin_menu', array( $this, 'add_custom_fields' ) );
        add_action( 'post_edit_form_tag', array( $this, 'custom_metabox_edit_form_tag' ) );
        add_action( 'save_post', array( $this, 'save_custom_fields' ) );

        // add_action( 'init' , array( $this, 'my_remove_post_editor_support' ) ); 通常の投稿入力欄非表示
    }

    // public function my_remove_post_editor_support() {
    //     remove_post_type_support( 'post', 'editor' );
    // }

    public function create_post_type() {
        register_post_type('hoge_custom_post', self::POST_TYPE_PROP);

        // //お知らせカテゴリ
        // register_taxonomy(
        //     'info-cat',
        //     'hoge_custom_post',
        //     self::TAXONOMY_PROP_CAT
        // );
        //     //お知らせタグ
        // register_taxonomy(
        //     'info-tag',
        //     'hoge_custom_post',
        //     self::TAXONOMY_PROP_TAG
        // );

        //カテゴリを投稿と共通設定にする
        // register_taxonomy_for_object_type('category', 'hoge_custom_post');
        //タグを投稿と共通設定にする
        // register_taxonomy_for_object_type('post_tag', 'hoge_custom_post');
    }

    public function add_custom_fields() {
        add_meta_box(
            'custom_setting', //編集画面セクションのHTML ID
            'カスタム情報', //編集画面セクションのタイトル、画面上に表示される
            array( $this, 'insert_custom_fields'), //編集画面セクションにHTML出力する関数
            'hoge_custom_post', //投稿タイプ名
            'normal' //編集画面セクションが表示される部分
        );
    }

    public function custom_metabox_edit_form_tag(){
        echo ' enctype="multipart/form-data"';
    }

    public function insert_custom_fields(){
        global $post;
        //get_post_meta関数を使ってpostmeta情報を取得
        wp_nonce_field('wp-nonce-key', '_wp_nonce');

        echo '<div class="photos">';
        for($i = 1; $i < 5; $i++) {
            $imageName = get_post_meta(
                $post->ID, //投稿ID
                'image' . $i, //キー名
                true //戻り値を文字列にする場合true(falseの場合は配列)
            );
            echo '<div class="photoItem">';
            $image_thumbnail = get_post_meta($post->ID,'image_thumbnail' . $i,true);
            echo '名前' . $i . '： <input type="text" name="image' . $i . '" value="' . $imageName .'" /><br>';
            echo '画像' . $i . '： <input type="file" name="image_thumbnail' . $i . '" accept="image/*" /><br>';
            if(isset($image_thumbnail) && strlen($image_thumbnail) > 0){
            //hoge_thumbnailキーのpostmeta情報がある場合は、画像を表示
            //$hoge_thumbnailには、後述するattach_idが格納されているので、wp_get_attachment_url関数にそのattach_idを渡して画像のURLを取得する
                $img_src_str = wp_get_attachment_image_src($image_thumbnail, 'org_thumb150');
                echo '<img src="'.$img_src_str[0].'"><br>';
                echo '<input type="hidden" name="del_id' . $i . '" value="' . $image_thumbnail . '">';
                echo '<label><input type="checkbox" name="del' . $i . '[]" value="' . $image_thumbnail . '">' . 'この画像を削除する' . '</label><br>';
            }
            echo '</div>';
        }
        echo '</div>';

        $get_tools = get_post_meta( $post->ID,'tools',true );
        $tools = $get_tools ? $get_tools : array();
        $data = array("不明", "鉛筆", "紙", "CLIP STUDIO PAINT", "ペンタブ", "液タブ");
        echo '<div class="customTitle">タイプ</div>';
        foreach ( $data as $d ) {
            //取得したデータをin_array検索 $toolsの値の中に$dataと同じ物があればcheckdを付与
            if ( in_array($d, $tools) ) { $check = "checked"; } else { $check = ""; }
            //複数選択の場合は、name=toolsではなくname=tools[]とする
            echo '<label><input type="checkbox" name="tools[]" value="' . esc_attr($d) . '" ' . $check . '>' . esc_html($d) . '</label><br>';
        }
    }

    public function save_custom_fields( $post_id ) {
        // echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>';
        if ( isset($_POST['_wp_nonce']) && $_POST['_wp_nonce'] ) {
            for($i = 1; $i < 5; $i++) {
                if(isset($_POST['image'. $i])){
                    //hoge_nameキーで、$_POST['hoge_name']を保存
                    update_post_meta($post_id, 'image'. $i, $_POST['image'. $i]);
                }else{
                    //hoge_nameキーの情報を削除
                    delete_post_meta($post_id, 'image'. $i);
                }
                if(isset($_POST['del'. $i]) && $_POST['del'. $i][0]){
                    // echo '<pre>';
                    // echo 'del_id'. $i;
                    // var_dump( $i );
                    // var_dump($_POST['del_id'. $i]);
                    // var_dump(wp_get_attachment_metadata( $_POST['del_id'. $i] ));
                    // echo '</pre>';
                    wp_delete_attachment( $_POST['del_id'. $i] );
                }else{
                    if(isset($_FILES['image_thumbnail' . $i]) && $_FILES['image_thumbnail' . $i]['size'] !== 0){
                        $file_name = basename($_FILES['image_thumbnail' . $i]['name']);
                        $file_name = trim($file_name);
                        $file_name = str_replace(" ", "-", $file_name);

                        $wp_upload_dir = wp_upload_dir(); //現在のuploadディクレトリのパスとURLを入れた配列
                        $upload_file = $_FILES['image_thumbnail' . $i]['tmp_name'];
                        $upload_path = $wp_upload_dir['path'].'/'.$file_name; //uploadsディレクトリ以下などに配置する場合は$wp_upload_dir['basedir']を利用する
                        //画像ファイルをuploadディクレトリに移動させる
                        move_uploaded_file($upload_file,$upload_path);

                        $file_type = $_FILES['image_thumbnail' . $i]['type'];
                        //正規表現で拡張子なしのスラッグ名を生成
                        $slug_name = preg_replace('/\.[^.]+$/', '', basename($upload_path));

                        if(file_exists($upload_path)){
                            //保存に成功してファイルが存在する場合は、wp_postsテーブルなどに情報を追加
                            $attachment = array(
                                'guid'           => $wp_upload_dir['url'].'/'.basename($file_name),
                                'post_mime_type' => $file_type,
                                'post_title' => $slug_name,
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            //添付ファイルを追加
                            $attach_id = wp_insert_attachment($attachment,$upload_path,$post_id);
                            if(!function_exists('wp_generate_attachment_metadata')){
                                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                            }
                            //添付ファイルのメタデータを生成し、wp_postsテーブルに情報を保存
                            $attach_data = wp_generate_attachment_metadata($attach_id,$upload_path);
                            wp_update_attachment_metadata($attach_id,$attach_data);
                            //wp_postmetaテーブルに画像のattachment_id(wp_postsテーブルのレコードのID)を保存
                            update_post_meta($post_id, 'image_thumbnail' . $i, $attach_id);
                        }else{
                            //保存失敗
                            echo '画像保存に失敗しました';
                            exit;
                        }
                    }
                }
            }

            if ( check_admin_referer('wp-nonce-key', '_wp_nonce') ) {
                if ( isset($_POST['tools']) && $_POST['tools'] ) {
                    update_post_meta( $post_id, 'tools', $_POST['tools'] );
                } else {
                    delete_post_meta( $post_id, 'tools', get_post_meta($post_id, 'tools', true) );
                }
            }
        }
    }

}
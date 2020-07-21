<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" >
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes">
    <meta name="format-detection" content="telephone=no" >

    <?php if ( is_home() && !is_paged() ): ?>
        <meta name="robots" content="index,follow">
    <?php elseif ( is_search() or is_404() ): ?>
        <meta name="robots" content="noindex,follow">
    <?php elseif ( !is_category() && is_archive() ): ?>
        <meta name="robots" content="noindex,follow">
    <?php elseif ( is_paged() ): ?>
        <meta name="robots" content="noindex,follow">
    <?php endif; ?>

    <?php wp_head(); ?>
</head>
<body>
    <div id="wrap" class="container-fluid">
        <div class="row">
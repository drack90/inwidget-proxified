<?php

/**
 * Project:     inWidget: show pictures from instagram.com on your site!
 * File:        template.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of MIT license
 * https://inwidget.ru/MIT-license.txt
 *
 * @link https://inwidget.ru
 * @copyright 2014-2020 Alexandr Kazarmshchikov
 * @author Alexandr Kazarmshchikov
 * @package inWidget
 *
 */

if (!$inWidget instanceof \InWidget\Core) {
    throw new \Exception('inWidget object was not initialised.');
}

?>
<!DOCTYPE html>
<html lang="<?= $inWidget->langName ?>">
<head>
    <title>inWidget - free Instagram widget for your site!</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="content-language" content="<?= $inWidget->langName ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" type="text/css" href="<?= $inWidget->skinPath . $inWidget->skinName ?>.css?r7" media="all"/>
    <?php if ($inWidget->color): ?>
        <style type='text/css'>
        .widget {
            border-color: #<?= $inWidget->color ?> !important;
        }
        .widget a.title:link, .widget a.title:visited {
            background-color: #<?= $inWidget->color ?> !important;
        }
        </style>
    <?php endif; ?>
    <?php if ($inWidget->adaptive === false) : ?>
        <style type='text/css'>
            .widget {
                width: <?= $inWidget->width ?>px;
            }
            .widget .data a.image:link, .widget .data a.image:visited {
                width: <?= $inWidget->imgWidth ?>px;
                height: <?= $inWidget->imgWidth ?>px;
            }
            .widget .data .image span {
                width: <?= $inWidget->imgWidth ?>px;
                height: <?= $inWidget->imgWidth ?>px;
            }
            .copyright, .cacheError {
                width: <?= $inWidget->width ?>px;
            }
        </style>
        <?php
    else :
        require_once 'plugins/adaptive.php';
    endif;
    ?>
</head>
<body>
<div id="widget" class="widget">
    <a href="https://instagram.com/<?= $inWidget->data->username ?>" target="_blank" class="title">
        <div class="icon">&nbsp;</div>
        <div class="text"><?= $inWidget->lang['title']; ?></div>
        <div class="clear">&nbsp;</div>
    </a>
    <?php if ($inWidget->toolbar == true) : ?>
        <table class="profile">
            <tr>
                <td rowspan="2" class="avatar">
                    <a href="https://instagram.com/<?= $inWidget->data->username ?>" target="_blank"><img src="<?= $inWidget->rewriteImgUrl($inWidget->data->avatar) ?>"></a>
                </td>
                <td class="value">
                    <?= $inWidget->humanNumber($inWidget->data->posts); ?>
                    <span><?= $inWidget->lang['statPosts'] ?></span>
                </td>
                <td class="value">
                    <?= $inWidget->humanNumber($inWidget->data->followers) ?>
                    <span><?= $inWidget->lang['statFollowers'] ?></span>
                </td>
                <td class="value" style="border-right:none !important;">
                    <?= $inWidget->humanNumber($inWidget->data->following) ?>
                    <span><?= $inWidget->lang['statFollowing'] ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-right:none !important;">
                    <a href="https://instagram.com/<?= $inWidget->data->username ?>" class="follow" target="_blank"><?= $inWidget->lang['buttonFollow'] ?> &#9658;</a>
                </td>
            </tr>
        </table>
    <?php endif;
    $i = 0;
    $count = $inWidget->countAvailableImages($inWidget->data->images);
    if ($count > 0) {
        if ($inWidget->config['imgRandom'] === true) {
            shuffle($inWidget->data->images);
        }
        echo '<div id="widgetData" class="data">';
        foreach ($inWidget->data->images as $key => $item) {
            if ($inWidget->isBannedUserId($item->authorId) === true) {
                continue;
            }
            switch ($inWidget->preview) {
                case 'large':
                    $thumbnail = $item->large;
                    break;
                case 'fullsize':
                    $thumbnail = $item->fullsize;
                    break;
                default:
                    $thumbnail = $item->small;
            }

            $thumbnail = $inWidget->rewriteImgUrl($thumbnail);

            echo '<a href="' . $item->link . '" class="image" target="_blank"><span style="background-image:url(' . $thumbnail . ');">&nbsp;</span></a>';
            $i++;
            if ($i >= $inWidget->view) {
                break;
            }
        }
        echo '<div class="clear">&nbsp;</div>';
        echo '</div>';
    } else {
        if (!empty($inWidget->config['HASHTAG'])) {
            $inWidget->lang['imgEmptyByHash'] = str_replace(
                '{$hashtag}',
                $inWidget->config['HASHTAG'],
                $inWidget->lang['imgEmptyByHash']
            );
            echo '<div class="empty">' . $inWidget->lang['imgEmptyByHash'] . '</div>';
        } else {
            echo '<div class="empty">' . $inWidget->lang['imgEmpty'] . '</div>';
        }
    }
    ?>
</div>
<div class='copyright'>
    Powered by <a href='https://inwidget.apiroad.net/' target='_blank' title='Free Instagram widget for your website!'>inwidget.apiroad.net</a>
</div>
<?php if (isset($inWidget->data->isBackup)) : ?>
    <div class='cacheError'>
        <?= $inWidget->lang['errorCache'] . ' ' . date(
            'Y-m-d H:i:s',
            $inWidget->data->lastupdate
        ) . ' <br /> ' . $inWidget->lang['updateNeeded'] ?>
    </div>
<?php endif; ?>
</body>
</html>
<!-- 
    inWidget - free Instagram widget for your site!
    https://inwidget.apiroad.net , proxified fork of https://inwidget.ru
    ?? Alexandr Kazarmshchikov
    ?? restyler
-->

<?php
/** @var yii\web\View $this */
/** @var app\models\Sites $websiteModel */

use yii\helpers\HtmlPurifier;

$this->title = HtmlPurifier::process("Сайт $websiteModel->url работает сегодня?");

$this->registerMetaTag([
    'name' => 'description',
    'content' => HtmlPurifier::process("Узнайте, доступен ли сегодня сайт $websiteModel->url в России?")
]);

echo $websiteModel->url;
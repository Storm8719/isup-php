<?php
/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">

<head>
    <title><?php echo Html::encode($this->title) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php //$this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>


<header>
    <nav class="container">
        <a href="/" class="header-logo">LOGO</a>
        <div class="header-menu">
            <form class="search-box">
                <input type="text" placeholder="Search..." id="liveSearch"/>
                <input type="submit" value="Search">
            </form>
            <div class="results-box">
                <div class="results" id="resultBox"></div>
            </div>
        </div>
    </nav>
</header>

<main>
    <div class="container main-content">
        <?php echo $content ?>
    </div>

</main>

<footer>
    <div class="container">
        Footer
    </div>
</footer>


<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>


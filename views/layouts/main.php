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
<!--    --><?php //$this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>


<header>
    <nav>
        Navbar
    </nav>
</header>

<main class="main-content">
    <?php echo $content ?>
</main>

<footer>
    Footer
</footer>


<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>


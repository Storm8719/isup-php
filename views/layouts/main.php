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
<script>
    const websitesInfoPathDynamic = "<?php echo \yii\helpers\Url::to(['main/site'])?>/";
</script>
<script src="https://cdn.tailwindcss.com"></script>

<header>
    <nav class="container">
        <a href="/" class="header-logo">LOGO</a>
        <a href="<?php echo \yii\helpers\Url::to(['main/add-site']) ?>">Add site</a>
        <div class="header-menu">
            <form class="search-box">

                <!--                <input type="text" placeholder="Search..." id="liveSearch"/>-->
                <!--                <input type="submit" value="Search">-->
            </form>

        </div>
    </nav>


    <nav class="bg-white/80
            backdrop-blur-md shadow-md w-full
            fixed top-0 left-0 right-0 z-10">
        <div class="container flex justify-between py-4">
            <div class="flex items-center">
                <a class="cursor-pointer" href="/">
                    <h3 class="text-2xl font-medium text-blue-500">
                        <img class="h-10 object-cover"
                             src="/favicon.ico" alt="Store Logo">
                    </h3>
                </a>
            </div>
            <div class="items-center hidden space-x-8 lg:flex">

                <div class="relative">
                    <input id="liveSearch" type="text"
                           class="p-2 pl-8 rounded border border-gray-200 bg-gray-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                           placeholder="Search site">
                    <div class="results-box">
                        <div class="results" id="resultBox"></div>
                    </div>
                    <svg class="w-4 h-4 absolute left-2.5 top-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>



            </div>
            <div class="flex items-center space-x-5">
                <a class="flex text-gray-600 hover:text-blue-500
                    cursor-pointer transition-colors duration-300 <?php if (\yii\helpers\Url::current() == \yii\helpers\Url::to(['/'])) echo "font-semibold text-blue-600 transition-colors" ?>"
                   href="/">
                    Homepika pik
                </a>
                <a class="flex text-gray-600 hover:text-blue-500
                    cursor-pointer transition-colors duration-300 <?php if (\yii\helpers\Url::current() == \yii\helpers\Url::to(['main/add-site'])) echo "font-semibold text-blue-600 transition-colors" ?>"
                   href="<?php echo \yii\helpers\Url::to(['main/add-site']) ?>">
                    Add website
                </a>
            </div>
        </div>

    </nav>


</header>

<main>

    <?php echo $content ?>

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


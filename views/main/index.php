<?php
/** @var array $sites */


$this->title = "Site dont working today?";
$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Check any website everyday'
]);

function iconFilter($imgUrl)
{
    if ($imgUrl)
        return $imgUrl;
    return '/images/link-ico.png';
}

function statusCodeFilter($code)
{
    if ($code == 200)
        return "<b class='s_200'>$code OK</b>";
    if ($code == 0)
        return "<b class='s_empty'>No response</b>";
    return "<b class='s_other'>$code</b>";
}

?>

<!--<input aria-label="search" type="search" id="search" placeholder="Search" class="w-full bg-gray-900 text-white transition border border-transparent focus:outline-none focus:border-gray-400 rounded py-3 px-2 pl-10 appearance-none leading-normal">-->
<!---->
<!---->
<!---->
<!--<input x-on:click="open = !open" type="search" x-model="search" placeholder="Search Here..." class="py-3 px-4 w-1/2 rounded shadow font-thin focus:outline-none focus:shadow-lg focus:shadow-slate-200 duration-100 shadow-gray-100">-->

<svg version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 w-full"
     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1440 126"
     style="transform: rotate(180deg); position: relative" xml:space="preserve">
                <style type="text/css">
                    .wave-svg-light {
                        fill: #ffffff;
                    }
                </style>
    <g id="wave" transform="translate(720.000000, 75.000000) scale(1, -1) translate(-720.000000, -75.000000) "
       fill-rule="nonzero">
        <path class="wave-svg-light"
              d="M694,94.437587 C327,161.381336 194,153.298248 0,143.434189 L2.01616501e-13,44.1765618 L1440,27 L1440,121 C1244,94.437587 999.43006,38.7246898 694,94.437587 Z"
              id="Shape" fill="#0069FF" opacity="0.519587054"></path>
        <path class="wave-svg-light"
              d="M686.868924,95.4364002 C416,151.323752 170.73341,134.021565 1.35713663e-12,119.957876 L0,25.1467017 L1440,8 L1440,107.854321 C1252.11022,92.2972893 1034.37894,23.7359827 686.868924,95.4364002 Z"
              id="Shape" fill="#0069FF" opacity="0.347991071"></path>
        <path class="wave-svg-light"
              d="M685.6,30.8323303 C418.7,-19.0491687 170.2,1.94304528 0,22.035593 L0,118 L1440,118 L1440,22.035593 C1252.7,44.2273621 1010,91.4098622 685.6,30.8323303 Z"
              id="Shape" fill="url(#linearGradient-1)"
              transform="translate(720.000000, 59.000000) scale(1, -1) translate(-720.000000, -59.000000) "></path>
    </g>
</svg>


<div class="container main-content">

    <h1 class="w-full my-8 text-5xl font-bold leading-tight text-center text-gray-800">Most popular on this week</h1>

    <div class="popular-list">
        <?php
        foreach ($sites as $site) {
            ?>
            <div class="site-card-box">
                <a href="<?php echo \yii\helpers\Url::to(['main/site']) . '/' . $site['url'] ?>"
                   class="flex flex-row-reverse items-center justify-between w-full col-span-1 p-6 bg-white rounded-lg shadow sm:flex-row">
                    <div class="site-box">
                        <div>
                            <div class="site-name"><?php echo $site['url'] ?></div>
                            <div class="fav-box">
                                <img src="<?php echo iconFilter($site['image_url']) ?>" class="site-fav"
                                     alt="<?php echo $site['url'] ?> logo"/>
                            </div>
                        </div>
                        <div>
                            <div><?php echo statusCodeFilter($site['last_http_code']) ?></div>
                            <div><?php echo date('H:i:s d/m', $site['updated_at']) ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>

</div>
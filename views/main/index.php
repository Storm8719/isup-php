<?php
/** @var array $sites */

function iconFilter($imgUrl)
{
    if ($imgUrl)
        return $imgUrl;
    return '/images/link-ico.png';
}

function statusCodeFilter($code){
    if($code == 200)
        return "<b class='s_200'>$code OK</b>";
    if($code == 0)
        return "<b class='s_empty'>No response</b>";
    return "<b class='s_other'>$code</b>";
}

?>

<div class="popular-list">
    <?php
    foreach ($sites as $site) {
        ?>
        <div class="site-card-box">
            <a href="<?php echo \yii\helpers\Url::to(['main/site']) . '/' . $site['url'] ?>" class="site-card">
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

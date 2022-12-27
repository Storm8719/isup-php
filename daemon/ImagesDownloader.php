<?php


namespace app\daemon;


class ImagesDownloader
{
    private static function saveImage($url, $pathToSave, $imageName, $imageType)
    {
        //\Yii::getAlias('@app').'/web/images/favicons/'.$websiteModel->url.'.ico'
        try {
            return copy($url, "$pathToSave/$imageName.$imageType");
        }catch (\Exception $e){
            \Yii::$app->l->log(json_encode($e));
        }
        return false;
    }

    public static function saveSiteFavicon($url, $siteUrl = null){
        $pathToSave = \Yii::getAlias('@app').'/web/images/favicons';
        $imageName = $siteUrl ? $siteUrl : \Yii::$app->urlHelper->getUrlHost($url);;
        $imageType = \Yii::$app->urlHelper->getImageFormat($url);
        return self::saveImage($url, $pathToSave, $imageName, $imageType);
    }
}
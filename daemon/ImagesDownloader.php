<?php


namespace app\daemon;


class ImagesDownloader
{
    private function saveImage($url, $pathToSave, $imageName, $imageType)
    {
        //\Yii::getAlias('@app').'/web/images/favicons/'.$websiteModel->url.'.ico'
        try {
            copy($url, "$pathToSave/$imageName.$imageType");
        }catch (\Exception $e){
            \Yii::$app->l->log(json_encode($e));
        }
    }

    public function saveSiteFavicon($url, $sitename){
        $pathToSave = \Yii::getAlias('@app').'/web/images/favicons';
        $imageName = '';
        $imageType = '';
        $this->saveImage($url, $pathToSave, $imageName, $imageType);
    }
}
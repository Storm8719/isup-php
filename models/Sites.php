<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sites".
 *
 * @property int $id
 * @property string $url
 * @property string $redirect_to
 * @property string|null $scheme
 * @property int $status
 * @property string|null $header
 * @property string|null $title
 * @property string|null $description
 * @property int|null $last_http_code
 * @property int|null $ttfb
 * @property int|null $pagesize
 * @property string|null $info
 * @property string|null $image_url
 * @property string|null $is_image_setted
 * @property string|null $image_url_options
 * @property string|null $additional_content
 * @property int|null $is_need_check_flag
 * @property int|null $created_by
 * @property int $created_at
 * @property int|null $updated_at
 * @property string|null $screenshot_url
 * @property int|null $screenshot_last_start_to_update
 * @property int|null $screenshot_updated_at
 */
class Sites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'status', 'created_at'], 'required'],
            [['status', 'last_http_code', 'pagesize', 'created_by', 'created_at', 'updated_at', 'screenshot_last_start_to_update', 'screenshot_updated_at'], 'integer'],
            [['header', 'description', 'info', 'additional_content', 'image_url', 'scheme', 'title', 'image_url_options', 'redirect_to'], 'string'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'redirect_to' => Yii::t('app', 'Redirect To'),
            'scheme' => Yii::t('app', 'Scheme'),
            'status' => Yii::t('app', 'Status'),
            'header' => Yii::t('app', 'Header'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'last_http_code' => Yii::t('app', 'Last Http Code'),
            'ttfb' => Yii::t('app', 'Ttfb'),
            'pagesize' => Yii::t('app', 'Pagesize'),
            'info' => Yii::t('app', 'Info'),
            'is_image_setted' => Yii::t('app', 'Is Image Setted'),
            'image_url_options' => Yii::t('app', 'Image Url Options'),
            'image_url' => Yii::t('app', 'Image Url'),
            'additional_content' => Yii::t('app', 'Additional Content'),
            'is_need_check_flag' => Yii::t('app', 'Is Need Check Flag'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'screenshot_url' => Yii::t('app', 'screenshot_url'),
            'screenshot_last_start_to_update' => Yii::t('app', 'screenshot_last_start_to_update'),
            'screenshot_updated_at' => Yii::t('app', 'screenshot_updated_at'),
        ];
    }
    /*
    status
        -1 - invalid
        0 - new
        1 - checked, valid
        2 -
    */
    public function createRandomWebsite(){
        $this->url = 'random';
        $this->status = 0;
        $this->created_at = time();
        $this->save();
    }

    public function setUrl($websiteUrl){
        $this->url = Yii::$app->urlHelper->getUrlHost($websiteUrl);;
        $this->status = 0;
        $this->created_at = time();
        $this->is_need_check_flag = 1;
        return $this;
    }
}

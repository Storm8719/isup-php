<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sites".
 *
 * @property int $id
 * @property string $url
 * @property int $status
 * @property string|null $header
 * @property string|null $description
 * @property int|null $last_http_code
 * @property int|null $ttfb
 * @property int|null $pagesize
 * @property string|null $info
 * @property string|null $image_url
 * @property string|null $additional_content
 * @property int|null $is_need_check_flag
 * @property int|null $created_by
 * @property int $created_at
 * @property int|null $updated_at
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
            [['status', 'last_http_code', 'ttfb', 'pagesize', 'is_need_check_flag', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['header', 'description', 'info', 'additional_content', 'image_url'], 'string'],
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
            'status' => Yii::t('app', 'Status'),
            'header' => Yii::t('app', 'Header'),
            'description' => Yii::t('app', 'Description'),
            'last_http_code' => Yii::t('app', 'Last Http Code'),
            'ttfb' => Yii::t('app', 'Ttfb'),
            'pagesize' => Yii::t('app', 'Pagesize'),
            'info' => Yii::t('app', 'Info'),
            'image_url' => Yii::t('app', 'Image Url'),
            'additional_content' => Yii::t('app', 'Additional Content'),
            'is_need_check_flag' => Yii::t('app', 'Is Need Check Flag'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function createRandomWebsite(){
        $this->url = 'random';
        $this->status = 0;
        $this->created_at = time();
        $this->save();
    }
}

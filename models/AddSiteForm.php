<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class AddSiteForm extends Model
{
    public $url;
//    public $password;
//    public $rememberMe = true;

    private $_site = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
//            // username and password are both required
//            [['url'], 'required'],
//            // rememberMe must be a boolean value
//            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['url', 'validateUrl'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'url' => Yii::t('app', 'Website url'),
        ];
    }

    /**
     * Validates the url.
     * This method serves as the inline validation for url.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUrl($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $host = Yii::$app->urlHelper->getUrlHost($this->url);
            if(!$host)
                $this->addError($attribute, 'Invalid url');
        }
    }


    /**
     * @return Sites|false
     */
    public function addSite()
    {
        if ($this->validate()) {
            $siteModel = new Sites();
            $this->_site = $siteModel;
            return $siteModel->setUrl($this->url)->save();
        }
        return false;
    }

    public function getSiteModel(){
        return $this->_site;
    }

//    /**
//     * Finds user by [[username]]
//     *
//     * @return User|null
//     */
//    public function getUser()
//    {
//        if ($this->_user === false) {
//            $this->_user = User::findByUsername($this->username);
//        }
//
//        return $this->_user;
//    }
}

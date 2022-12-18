<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */
/** @var boolean $from_404 */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Add website';
?>
<div class="add-website-block">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($from_404):?>
        <p>Website not found, but you can add it right now</p>
    <?php endif; ?>

    <p>Please add url:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($model, 'url')->textInput(['autofocus' => true]) ?>

    <div>
        <div>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

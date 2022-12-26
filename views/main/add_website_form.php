<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */
/** @var boolean $from_404 */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Add website';
?>

<div class="container flex justify-center p-5 sm:p-10">

        <?php if ($from_404):?>
            <p>Website not found, but you can add it right now</p>
        <?php endif; ?>

        <div class="w-full sm:w-full lg:w-6/12 mx-auto md:mx-0">

            <div class="bg-white p-10 flex flex-col w-full shadow-xl rounded-xl">
                <h2 class="text-2xl font-bold text-gray-800 text-left mb-5">
                    Add website
                </h2>
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "<div id='input' class='flex flex-col w-full my-5'>{label}\n{input}\n{error}</div>",
                        'labelOptions' => ['class' => 'text-gray-500 mb-2'],
                        'inputOptions' => ['class' => 'appearance-none border-2 border-gray-100 rounded-lg px-4 py-3 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:shadow-lg'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>

                <?= $form->field($model, 'url')->textInput(['autofocus' => true, 'placeholder' => "Please enter url"]) ?>

                <div>
                    <div>
                        <?= Html::submitButton('Submit', ['class' => 'w-full py-4 bg-blue-600 rounded-lg text-blue-100 font-bold', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

</div>

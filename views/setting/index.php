<?php

use voskobovich\admin\bootstrap\ActiveForm;
use voskobovich\alert\widgets\Alert;
use voskobovich\base\helpers\Html;
use voskobovich\base\helpers\Param;
use yii\widgets\Menu;


/**
 * @var \yii\web\View $this
 * @var \voskobovich\admin\setting\models\Setting $settingModel
 * @var \voskobovich\admin\setting\forms\IndexForm $settingForm
 */

$this->title = Yii::t('backend', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-7">
        <?php Alert::widget(); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="nav-tabs-custom">
            <?= Menu::widget([
                'options' => ['class' => 'nav nav-tabs'],
                'items' => Param::get('settings.menu'),
            ]) ?>
            <div class="tab-content">
                <div class="tab-pane active">
                    <?php $form = ActiveForm::begin([
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-10">{input}{error}{hint}</div>',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                    ]); ?>

                    <?= $this->render('@vendor/voskobovich/yii2-admin-setting-toolkit/views/_fields.php', [
                        'form' => $form,
                        'settingModel' => $settingModel,
                        'settingForm' => $settingForm
                    ]); ?>

                    <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-primary']) ?>
                    <?php $form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
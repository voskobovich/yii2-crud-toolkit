<?php

namespace voskobovich\admin\bootstrap;

/**
 * Class ActiveForm
 * @package app\widgets
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{
    public $fieldClass = 'voskobovich\admin\bootstrap\ActiveField';
}
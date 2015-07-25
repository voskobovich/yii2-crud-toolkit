<?php

namespace voskobovich\admin\controllers;

use voskobovich\admin\setting\actions\IndexAction;
use Yii;


/**
 * Class SettingController
 * @package voskobovich\admin\controllers
 */
class SettingController extends AccessController
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
            ],
        ];
    }
}
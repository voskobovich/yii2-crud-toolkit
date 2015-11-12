<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use Yii;
use yii\base\Action;


/**
 * Class BaseAction
 * @package voskobovich\admin\actions
 */
abstract class BaseAction extends Action
{
    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectRoute;

    /**
     * @var ActiveRecord $model
     */
    protected function redirect($model)
    {
        if ($this->redirectRoute) {
            if (($pos = strpos($this->redirectRoute, ':')) !== false) {
                $route = substr($this->redirectRoute, 0, $pos - 1);
                $params = [$route];

                $attributeName = substr($this->redirectRoute, $pos + 1);
                if ($attributeName && $model->hasAttribute($attributeName)) {
                    $params[$attributeName] = $model->getAttribute($attributeName);
                }
            } else {
                $params = [$this->redirectRoute];
            }

            /** @var BackendController $controller */
            $controller = $this->controller;
            $controller->redirect($params);
        }
    }
}
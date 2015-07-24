<?php

namespace voskobovich\adminToolkit\actions;

use voskobovich\adminToolkit\Backend;
use voskobovich\baseToolkit\db\ActiveRecord;
use voskobovich\baseToolkit\helpers\AlertHelper;
use voskobovich\baseToolkit\helpers\HttpError;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class UpdateAction
 * @package voskobovich\adminToolkit\actions
 */
class UpdateAction extends Action
{
    /**
     * Class to use to locate the supplied data ids
     * @var string
     */
    public $modelClass;

    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectRoute = 'index';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (null == $this->modelClass) {
            throw new InvalidConfigException('Param "modelClass" must be contain model name with namespace.');
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $model = $model::findByPk($id);

        if (empty($model)) {
            HttpError::the404();
        }

        $params = Yii::$app->request->post();

        /** @var Backend $controller */
        $controller = $this->controller;

        if ($model->load($params)) {
            if ($model->save()) {
                AlertHelper::success(Yii::t('backend', 'Saved successfully!'));
                if ($this->redirectRoute) {
                    $controller->redirect([$this->redirectRoute]);
                }
            } else {
                AlertHelper::error(Yii::t('backend', 'Error saving!'));
            }
        }

        return $controller->render('update', [
            'model' => $model
        ]);
    }
}
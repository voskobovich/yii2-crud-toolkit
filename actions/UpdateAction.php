<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\alert\helpers\AlertHelper;
use voskobovich\base\db\ActiveRecord;
use voskobovich\base\helpers\HttpError;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class UpdateAction
 * @package voskobovich\admin\actions
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
        parent::init();

        if ($this->modelClass == null) {
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

        /** @var BackendController $controller */
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
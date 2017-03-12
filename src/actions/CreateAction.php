<?php

namespace voskobovich\crud\actions;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class CreateAction.
 */
class CreateAction extends BaseAction
{
    /**
     * The route which will be redirected after the user action.
     *
     * @var string|array|callable
     */
    public $redirectUrl = ['update', 'id' => ':primaryKey'];

    /**
     * View name.
     *
     * @var string
     */
    public $viewFile = 'create';

    /**
     * Enable or disable ajax validation handler.
     *
     * @var bool
     */
    public $enableAjaxValidation = true;

    /**
     * The flash key for success flash message.
     *
     * @var string
     */
    public $flashSuccessKey = 'create:success';

    /**
     * The flash key for error flash message.
     *
     * @var string
     */
    public $flashErrorKey = 'create:error';

    /**
     * @return string
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = Yii::createObject($this->modelClass);
        $model->scenario = $this->scenario;

        $params = Yii::$app->getRequest()->getBodyParams();
        if ($model->load($params)) {
            if ($this->enableAjaxValidation && Yii::$app->request->isAjax && !empty($params['ajax'])) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                if (is_callable($this->successCallback)) {
                    call_user_func($this->successCallback, $model, $this);
                } elseif (false !== $this->successCallback) {
                    Yii::$app->session->setFlash($this->flashSuccessKey);
                }

                if ($this->redirectUrl) {
                    return $this->redirect($model);
                }
            } else {
                if (is_callable($this->errorCallback)) {
                    call_user_func($this->errorCallback, $model, $this);
                } elseif (false !== $this->errorCallback) {
                    Yii::$app->session->setFlash($this->flashErrorKey);
                }
            }
        }

        return $this->render([
            'model' => $model,
        ]);
    }
}

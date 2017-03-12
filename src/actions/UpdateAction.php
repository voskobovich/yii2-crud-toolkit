<?php

namespace voskobovich\crud\actions;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class UpdateAction.
 */
class UpdateAction extends BaseAction
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
    public $viewFile = 'update';

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
    public $flashSuccessKey = 'update:success';

    /**
     * The flash key for error flash message.
     *
     * @var string
     */
    public $flashErrorKey = 'update:error';

    /**
     * @throws \yii\web\NotFoundHttpException
     *
     * @return string
     */
    public function run()
    {
        $model = $this->getLoadedModel();

        if (null === $model) {
            $pk = $this->getPrimaryKey();

            /** @var ActiveRecord $model */
            $model = $this->findModel($pk);
        }
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

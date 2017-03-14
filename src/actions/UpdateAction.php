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
     * {@inheritdoc}
     */
    public $redirectUrl = ['update', 'id' => ':primaryKey'];

    /**
     * {@inheritdoc}
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
     * @throws \yii\base\InvalidParamException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
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
            if ($this->enableAjaxValidation && Yii::$app->request->isAjax && false === empty($params['ajax'])) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                $this->runSuccessHandler($model);

                if (null !== $this->redirectUrl) {
                    return $this->redirect($model);
                }
            } elseif (false === $model->hasErrors()) {
                $this->runErrorHandler($model);
            }
        }

        return $this->render([
            'model' => $model,
        ]);
    }
}

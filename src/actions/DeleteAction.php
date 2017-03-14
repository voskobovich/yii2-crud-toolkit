<?php

namespace voskobovich\crud\actions;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Class DeleteAction.
 */
class DeleteAction extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    public $redirectUrl = ['index'];

    /**
     * {@inheritdoc}
     */
    public $viewFile = false;

    /**
     * A callback which defines the logic of the removal of the object.
     *
     * @var callable;
     */
    public $handler;

    /**
     * Is called when a throw exception.
     *
     * @var callable|bool;
     */
    public $exceptionCallback;

    /**
     * The flash key for success flash message.
     *
     * @var string
     */
    public $flashSuccessKey = 'delete:success';

    /**
     * The flash key for error flash message.
     *
     * @var string
     */
    public $flashErrorKey = 'delete:error';

    /**
     * The flash key for exception flash message.
     *
     * @var string
     */
    public $flashExceptionKey = 'delete:exception';

    /**
     * {@inheritdoc}
     *
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     * @throws \yii\base\InvalidParamException
     */
    public function run()
    {
        $model = $this->getLoadedModel();

        if (null === $model) {
            $primaryKey = $this->getPrimaryKey();

            /** @var ActiveRecord $model */
            $model = $this->findModel($primaryKey, false);
        }

        if (null !== $model) {
            $model->scenario = $this->scenario;
            try {
                if (is_callable($this->handler)) {
                    $result = (bool) call_user_func($this->handler, $model, $this);
                } else {
                    $result = (bool) $model->delete();
                }

                if ($result) {
                    $this->runSuccessHandler($model);
                } else {
                    $this->runErrorHandler($model);
                }
            } catch (Exception $ex) {
                if (is_callable($this->exceptionCallback)) {
                    call_user_func($this->exceptionCallback, $model, $this, $ex);
                } elseif (false !== $this->exceptionCallback) {
                    Yii::$app->session->setFlash($this->flashExceptionKey);
                }
            }
        }

        if (false === $this->viewFile) {
            return $this->redirect($model);
        }

        return $this->render([
            'model' => $model,
        ]);
    }
}

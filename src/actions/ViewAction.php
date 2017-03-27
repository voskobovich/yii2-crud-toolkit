<?php

namespace voskobovich\crud\actions;

use yii\db\ActiveRecord;

/**
 * Class ViewAction.
 */
class ViewAction extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    public $viewFile = 'view';

    /**
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\base\InvalidParamException
     *
     * @return string
     * @throws \yii\base\InvalidParamException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $model = $this->getLoadedModel();

        if (null === $model) {
            $primaryKey = $this->getPrimaryKey();

            /** @var ActiveRecord $model */
            $model = $this->findModel($primaryKey);
        }

        return $this->render([
            'model' => $model,
        ]);
    }
}

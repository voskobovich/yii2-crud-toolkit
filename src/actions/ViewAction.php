<?php

namespace voskobovich\crud\actions;

use yii\db\ActiveRecord;

/**
 * Class ViewAction.
 */
class ViewAction extends BaseAction
{
    /**
     * View name.
     *
     * @var string
     */
    public $viewFile = 'view';

    /**
     * @throws \yii\web\NotFoundHttpException
     *
     * @return string
     */
    public function run()
    {
        $model = $this->getLoadedModel();

        if (empty($model)) {
            $pk = $this->getPrimaryKey();

            /** @var ActiveRecord $model */
            $model = $this->findModel($pk);
        }

        return $this->render([
            'model' => $model,
        ]);
    }
}

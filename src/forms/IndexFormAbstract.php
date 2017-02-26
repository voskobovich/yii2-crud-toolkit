<?php

namespace voskobovich\crud\forms;

use voskobovich\base\interfaces\ModelInterface;
use voskobovich\base\traits\ModelTrait;
use yii\base\Model;

/**
 * Class IndexFormAbstract.
 */
abstract class IndexFormAbstract extends Model implements ModelInterface
{
    use ModelTrait;

    /**
     * Query building.
     *
     * @param $params
     *
     * @return \yii\data\ActiveDataProvider
     */
    abstract public function search($params);
}

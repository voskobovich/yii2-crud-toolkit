<?php

namespace voskobovich\admin\forms;

use voskobovich\base\traits\ModelTrait;
use yii\base\Model;


/**
 * Class IndexFormAbstract
 * @package voskobovich\admin\forms
 */
abstract class IndexFormAbstract extends Model
{
    use ModelTrait;

    /**
     * Query building
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */
    abstract public function search($params);
}
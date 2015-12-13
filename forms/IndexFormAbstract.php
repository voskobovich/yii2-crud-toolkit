<?php

namespace voskobovich\admin\forms;

use yii\base\Model;


/**
 * Class IndexFormAbstract
 * @package voskobovich\admin\forms
 */
abstract class IndexFormAbstract extends Model
{
    /**
     * Query building
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */
    abstract public function search($params);
}
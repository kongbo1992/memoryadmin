<?php

namespace backend\models;

use Yii;

class LookUp extends \common\models\LookUp
{
	private static $_items=array();

	/**
     * Returns the items for the specified type.
     * @param string item type (e.g. 'PostStatus').
     * @return array item names indexed by item code. The items are order by their position values.
     * An empty array is returned if the item type does not exist.
     */
    public static function items($type,$city_id = 0)
    {
        if(!isset(self::$_items[$type]))
            self::loadItems($type);
        return self::$_items[$type][$city_id];
    }

    /**
     * Returns the item name for the specified type and code.
     * @param string the item type (e.g. 'PostStatus').
     * @param integer the item code (corresponding to the 'code' column value)
     * @return string the item name for the specified the code. False is returned if the item type or code does not exist.
     */
    public static function item($type,$code,$city_id=0)
    {
        if(!isset(self::$_items[$type]))
            self::loadItems($type);
        return isset(self::$_items[$type][$city_id][$code]) ? self::$_items[$type][$city_id][$code] : false;
    }

    /**
     * Loads the lookup items for the specified type from the database.
     * @param string the item type
     */
    private static function loadItems($type)
    {
        self::$_items[$type]=array();
        $models=static::find()->orderBy(['order'=>SORT_ASC])->asArray()->all();
        foreach($models as $model)
            self::$_items[$model['type']][$model['city_id']][$model['code']]=$model['name'];
    }

}
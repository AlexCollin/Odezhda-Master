<?php

/**
 * This is the model class for table "{{categories}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $categories_id
=
 *
 */
class CatalogLegacy extends CActiveRecord
{
    public $products_id;


    public $primaryKey='products_id';

    // здесь мы храним ВСЮ информацию, пришедшую на сохранение
    // и для основной таблице и для связанных
    protected $_allData = [];

    /**
     * Name of the database table associated with this ActiveRecord
     * @return string
     */
    public function tableName()
    {
        return 'products';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function relations()
    {
        return array(
            'description'=>array(self::HAS_ONE, 'CatalogDescriptionLegacy', 'products_id'),
//            'categories'=>array(self::HAS_ONE, 'ShopCategoriesDescriptionLegacy', 'categories_id'),
        );
    }

    protected function afterSave() {
        parent::afterSave();

        $id = $this->_allData['products_id'] = $this->products_id;

        foreach($this->relations() as $value){
            // имя класса АР
            $r_class = $value[1];
            $r_relation_id = $value[2];

            if (!$r_model = parent::model($r_class)->find($r_relation_id.'=:products_id', [':products_id' => $id])){
                // пример как делали раньше
//                $description = new ShopCategoriesDescriptionLegacy();

                // todo: может быть проблемой! (после проверки, удалить заметку и комментарий)
                // если выкидывает ошибку или странно сохраняет - переписать под
                // создание нового экземпляра класса АР

//                $r_model = parent::model($r_class);
                $r_model = new $r_class('add');
            }

            if ($r_model){
                $r_model->setAttributes($this->_allData, false);
                $r_model->save();
            }
        }
//        if(!empty($this->_allData)){
//            $id = $this->categories_id;
//
//            //$is_new = ShopCategoriesDescriptionLegacy::model()->find('categories_id=:categories_id', array(':categories_id' => $id));
//            if (!$description = ShopCategoriesDescriptionLegacy::model()->find('categories_id=:categories_id', array(':categories_id' => $id))){
//                $description = new ShopCategoriesDescriptionLegacy();
//            }
//
////            $description = ($this->isNewRecord ? new ShopCategoriesDescriptionLegacy() : ShopCategoriesDescriptionLegacy::model()->find('categories_id=:categories_id', array(':categories_id' => $id)));
//            $this->_allData['categories_id'] = $id;
////            print_r($this->isNewRecord);exit;
//            if($description){
//                $description->setAttributes($this->_allData, false);
//                $description->save();
//            }
//        }
    }

    /**
     * Закидываем все параметры для сохранения, как для основной таблицы, так и для связанных
     * @param array $data массив данных
     * @param bool $safe использовать безопасные данные
     */
    public function setAllData($data,$safe = true) {
        if(!is_array($data))
            return;

        $this->setAttributes($data,$safe);
        $this->_allData=$data;
    }
}

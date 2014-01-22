<?php

/**
 * This is the model class for table "{{categories}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $categories_id
 * @property integer $parent_id
 * @property string $categories_image
 * @property integer $sort_order
 * @property integer $date_added
 * @property integer $last_modified
 * @property integer $categories_status
 * @property integer $default_manufacturers
 * @property float $markup
 * @property boolean $xml_flag
 *
 */
class ShopCategoriesLegacy extends CActiveRecord
{
    public $primaryKey='categories_id';

    /**
     * Name of the database table associated with this ActiveRecord
     * @return string
     */
    public function tableName()
    {
        return 'categories';
    }

    /**
     * Behaviors associated with this ActiveRecord.
     *
     * We are using the APasswordBehavior because it allows neat things
     * like changing the password hashing methods without rebuilding the whole user database.
     *
     * @see https://github.com/phpnode/YiiPassword
     *
     * @return array Configuration for the behavior classes.
     */
//	public function behaviors()
//	{
//		Yii::import('common.extensions.behaviors.password.*');
//		return array(
//			// Password behavior strategy
//			"APasswordBehavior" => array(
//				"class" => "APasswordBehavior",
//				"defaultStrategyName" => "legacy",
//                "passwordAttribute"=>"admin_password",
//				"strategies" => array(
//					"legacy" => array(
//						"class" => "ALegacyPasswordStrategy"
////						"workFactor" => 14,
////						"minLength" => 8
//					)
//				),
//			)
//		);
//	}
    /**
     * Validation rules for model attributes.
     *
     * @see http://www.yiiframework.com/wiki/56/
     * @return array
     */
//    public function rules()
//    {
//        // NOTE: you should only define rules for those attributes that
//        // will receive user inputs.
//        return array(
//            array('admin_email_address', 'email','message' => Yii::t('validation', "Некорректный E-mail")),
//            array('admin_email_address', 'unique', 'message' => Yii::t('validation', "E-mail должен быть уникальным")),
//            array('admin_email_address', 'required', 'message'=>Yii::t('validation', 'E-mail является обязательным')),
//            array('admin_firstname', 'required', 'message'=>Yii::t('validation', 'Имя является обязательным')),
//            array('admin_groups_id', 'required', 'message'=>Yii::t('validation', 'Группа является обязательной')),
//            array('admin_password', 'required', 'on'=>'add', 'message'=>Yii::t('validation', 'Пароль является обязательным')),
//
//           // array('passwordConfirm', 'compare', 'compareAttribute' => 'newPassword', 'message' => Yii::t('validation', "Passwords don't match")),
//           // array('newPassword, password_strategy ', 'length', 'max' => 50, 'min' => 8),
//           // array('email, password, salt', 'length', 'max' => 255),
//          //  array('requires_new_password, login_attempts', 'numerical', 'integerOnly' => true),
//         //   // The following rule is used by search().
//            // Please remove those attributes that should not be searched.
//         //   array('id, username, email', 'safe', 'on' => 'search'),
//        );
//    }

    /**
     * Customized attribute labels (attr=>label)
     *
     * @return array
     */
//    public function attributeLabels()
//    {
//        return array(
//            'id' => 'ID',
//            'username' => Yii::t('labels', 'Username'),
//            'password' => Yii::t('labels', 'Password'),
//            'newPassword' => Yii::t('labels', 'Password'),
//            'passwordConfirm' => Yii::t('labels', 'Confirm password'),
//            'email' => Yii::t('labels', 'Email'),
//        );
//    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $PARTIAL = true;

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, $PARTIAL);
        $criteria->compare('email', $this->email, $PARTIAL);

        return new CActiveDataProvider(get_class($this), compact('criteria'));
    }

    /**
     * Returns the static model of the specified AR class.
     * Mandatory method for ActiveRecord descendants.
     *
     * @param string $className
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}

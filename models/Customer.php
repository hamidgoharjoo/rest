<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
class Customer extends ActiveRecord
{
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'family', 'mobile', 'home', 'work'], 'required'],
            [['name', 'family'], 'string', 'max' => 20],
            [['mobile', 'home', 'work'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'family' => 'Family',
            'mobile' => 'Mobile',
            'home' => 'Home',
            'work' => 'Work',
        ];
    }
   
}
?>

<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\imagine\Image;
use common\models\User;

/**
 * This is the model class for table "test_profile".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $about
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['about'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['imageFile'], 'file','skipOnEmpty'=>true,'extensions'=>'jpg,png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'about' => 'About',
            'imageFile' => 'Image File'
        ];
    }

    public function upload()
    {
        if ($this->imageFile) {
            $path = Url::to('@webroot/images/profile_photos/');
            $filename = strtolower($this->user->username) . '.jpg';
            //$this->imageFile->saveAs($path . $filename);
            Image::frame($this->imageFile->tempName, 20, '00FF00',100)
                ->save($path.$filename,['quality'=>90]);
        }
        return true;
    }

    public function getPhotoInfo()
    {
        $path = Url::to('@webroot/images/profile_photos/');
        $url = Url::to('@web/images/profile_photos/');
        $filename1 = strtolower($this->user->username) . '.jpg';
        $filename2 = strtolower($this->user->username) . '.png';
        $alt = $this->user->username . "'s Profile Picture";

        $imageInfo = ['alt'=> $alt];

        if (Yii::$app->authManager->getRolesByUser($this->id) == "admin"){
            $imageInfo['url'] = $url.'admin_avatar.jpg';
        } else {
            if (file_exists($path . $filename1)) {
                $imageInfo['url'] = $url.$filename1;
            } elseif (file_exists($path . $filename2) ){
                $imageInfo['url'] = $url.$filename2;
            } else {
                $imageInfo['url'] = $url.'default.png';
            }
        }
        return $imageInfo;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
//    public static function findProfileModel($user_id) {
//        if (($model = Profile::find()->where(['id' => $user_id])->one()) !== null) {
//            return $model;
//        }
//    }

    public function beforeSave($insert)
    {
       if (parent::beforeSave($insert)){
           $this->upload();
       }
        return true;
    }
}

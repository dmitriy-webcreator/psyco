<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 29.06.2017
 * Time: 17:55
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $certificates;
    public $avatar;

    public function rules(){
        return [
            [['certificates'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 4],
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($user_id){
        if ($this->validate()) {
            foreach ($this->certificates as $key => $file) {
                $dir = 'uploads/certificates/'.$user_id;
                if (!is_dir($dir)) {
                    mkdir($dir);
                }

                $file->saveAs($dir.'/certificate_'.$key.'.'.$file->extension);
            }

            $dir = 'uploads/avatars/'.$user_id;
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $this->avatar->saveAs('uploads/avatars/'.$user_id.'/avatar.jpg');
            return true;
        } else {
            return false;
        }
    }

}
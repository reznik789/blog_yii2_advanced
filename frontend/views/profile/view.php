<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */

$this->title = $model->user->username . " profile";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= Html::img($model->getPhotoInfo()['url'], ['alt' => $model->getPhotoInfo()['alt'], "class" => "user_profile_photo" ])?>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--        <?//= Html::a('Delete', ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Are you sure you want to delete this item?',
//                'method' => 'post',
//            ],
//        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            [
                'label' => 'Username',
                'value' => $model->user->username
            ],
            [
                'label' => 'Email',
                'value' => $model->user->email
            ],
            'first_name',
            'last_name',
            'about:ntext',
        ],
    ]) ?>

</div>

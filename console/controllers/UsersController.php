<?php
/**
 * Created by PhpStorm.
 * User: dev30
 * Date: 7/6/16
 * Time: 9:50 AM
 */

namespace console\controllers;



use common\rbac\PostRule;
use common\rbac\ProfileRule;
use yii\console\Controller;
use \yii;

class UsersController extends Controller
{
    public function actionPermissionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $rule = new ProfileRule();
        $auth->add($rule);
        $editOwnProfile = $auth->createPermission('editOwnProfile');
        $editOwnProfile->description = "Can edit own profile";
        $editOwnProfile->ruleName = $rule->name;
        $auth->add($editOwnProfile);
        $rule = new PostRule();
        $auth->add($rule);
        $createPost = $auth->createPermission('createFifePost');
        $createPost->description = "Can create only 5 posts";
        $createPost->ruleName = $rule->name;
        $auth->add($createPost);

        $user = $auth->createRole("user");
        $user->description = "Just a slave";
        $auth->add($user);
        $auth->addChild($user, $editOwnProfile);
        $auth->addChild($user, $createPost);

        $editor = $auth->createRole('editor');
        $editor->description = "Good guy";
        $auth->add($editor);

        $admin = $auth->createRole('admin');
        $admin->description = "The God of this blog";
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $editor);
    }
}
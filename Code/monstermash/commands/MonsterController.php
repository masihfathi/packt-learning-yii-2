<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Monster;
use \Yii;
use app\rbac\ProfileRule;

class MonsterController extends Controller
{
    public function actionLoadMonsters()
    {

        Monster::deleteAll();

        $monsterData = [
            [
                'name' => 'Dracula',
                'age' => 999,
                'gender' => 'm',
                'username' => 'fangman999',
                'password' => 'yummyblood'
            ],
            [
                'name' => 'Frankenstein',
                'age' => 2,
                'gender' => 'm',
                'username' => 'stitchedtogether',
                'password' => 'boltneck'
            ],
            [
                'name' => 'Medusa',
                'age' => 34,
                'gender' => 'f',
                'username' => 'snakehairgirl',
                'password' => 'dontlooknow'
            ],
            [
                'name' => 'Mummy',
                'age' => 86,
                'gender' => 'm',
                'username' => 'dirtyragdude',
                'password' => 'wrappedtight'
            ],
            [
                'name' => 'Wicked Witch',
                'age' => 40,
                'gender' => 'f',
                'username' => 'broomrider4eva',
                'password' => 'getyoumypretty'
            ],

        ];

        foreach ($monsterData as $data) {
            $monster = new Monster($data);
            $monster->hashPassword = true;
            $monster->save();
        }
    }

    public function actionPermissions()
    {
        $auth = Yii::$app->authManager;

        $updateMonster = $auth->createPermission('updateMonster');
        $updateMonster->description = 'Update a monster';
        $auth->add($updateMonster);

        $deleteMonster = $auth->createPermission('deleteMonster');
        $deleteMonster->description = 'Delete a monster';
        $auth->add($deleteMonster);
    }

    public function actionRoles()
    {
        $auth = Yii::$app->authManager;

        $updateMonster = $auth->getPermission('updateMonster');
        $deleteMonster = $auth->getPermission(('deleteMonster'));

        $member = $auth->createRole('member');
        $auth->add($member);
        $auth->addChild($member, $updateMonster);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $deleteMonster);
        $auth->addChild($admin, $member);

    }

    public function actionRules()
    {
        $auth = Yii::$app->authManager;

        $rule = new ProfileRule();
        $auth->add($rule);

        $updateMonster = $auth->getPermission('updateMonster');
        $updateMonster->ruleName = $rule->name;
        $auth->update('updateMonster', $updateMonster);
    }
}
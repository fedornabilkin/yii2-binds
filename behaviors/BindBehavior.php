<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 12:58
 */

namespace fedornabilkin\behaviors;

use fedornabilkin\models\Bind;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class BindBehavior
 * @package fedornabilkin\behaviors
 */
class BindBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate'
        ];
    }

    public $settings = [
        'nicknames' => [],
    ];


    public function getSettings() {
        $settings = [];
        $nicknames = [];
        foreach ($this->settings['nicknames'] as $nn => $pars){
            $nicknames[$nn] = $pars;
            if (!isset($pars['tree'])){
                $nicknames[$nn]['tree'] = false;
            }
            if (isset($pars['data']) && $pars['data']) {
                $nicknames[$nn]['data'] = $pars['data']->all();
            }
        }
        $settings['nicknames'] = $nicknames;
        return $settings;
    }

    public function beforeUpdate() {
        $this->saveBinds();
    }

    public function saveBinds()
    {
        if ($this->settings['nicknames']) {
            $r = \Yii::$app->request;

            $binds = [];
            foreach ($this->settings['nicknames'] as $nn => $pars) {
                $binds = array_merge($binds, $r->post($nn) ?? []);
            }

            $binds = array_filter($binds);
            Bind::setBinds($this->owner->uid, $binds);
        }
    }
}
<?php

namespace fedornabilkin\binds\models;

use creocoder\nestedsets\NestedSetsBehavior;
use fedornabilkin\binds\models\base\BindModel;
use kartik\tree\models\TreeTrait;
use kartik\tree\TreeView;

/**
 * This is the model class for table "bind_catalog".
 *
 * @property int $id
 * @property string $uid
 * @property string $alias
 * @property string $nickname
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
 * @property string $icon
 * @property int $icon_type
 * @property int $active
 * @property int $selected
 * @property int $disabled
 * @property int $readonly
 * @property int $visible
 * @property int $collapsed
 * @property int $movable_u
 * @property int $movable_d
 * @property int $movable_l
 * @property int $movable_r
 * @property int $removable
 * @property int $removable_all
 */
class Catalog extends BindModel
{
    use TreeTrait;


    /**
     * @var string the classname for the TreeQuery that implements the NestedSetQueryBehavior.
     * If not set this will default to `kartik	ree\models\TreeQuery`.
     */
    public static $treeQueryClass; // change if you need to set your own TreeQuery

    /**
     * @var bool whether to HTML encode the tree node names. Defaults to `true`.
     */
    public $encodeNodeNames = true;

    /**
     * @var bool whether to HTML purify the tree node icon content before saving.
     * Defaults to `true`.
     */
    public $purifyNodeIcons = true;

    /**
     * @var array activation errors for the node
     */
    public $nodeActivationErrors = [];

    /**
     * @var array node removal errors
     */
    public $nodeRemovalErrors = [];

    /**
     * @var bool attribute to cache the `active` state before a model update. Defaults to `true`.
     */
    public $activeOrig = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bind_catalog';
    }

    /**
     * Необходимо переопределить, чтобы добавить родительские поведения
     *
     * @return array
     */
    public function behaviors()
    {
        $module = TreeView::module();
        $settings = ['class' => NestedSetsBehavior::className()] + $module->treeStructure;
        $thisBehavior = empty($module->treeBehaviorName) ? [$settings] : [$module->treeBehaviorName => $settings];

        return array_merge_recursive(parent::behaviors(), $thisBehavior);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'root', 'lft', 'rgt', 'lvl', 'icon_type'], 'integer'],
            [['alias', 'nickname', 'name'], 'string', 'max' => 60],
            [['icon'], 'string', 'max' => 255],
            [['active', 'selected', 'disabled', 'readonly', 'visible', 'collapsed', 'movable_u', 'movable_d', 'movable_l', 'movable_r', 'removable', 'removable_all'], 'string', 'max' => 1],
            [['alias', 'uid'], 'unique'],
        ];
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'root']);
    }
}

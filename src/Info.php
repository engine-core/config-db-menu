<?php
/**
 * @link      https://github.com/engine-core/config-db-menu
 * @copyright Copyright (c) 2021 E-Kevin
 * @license   BSD 3-Clause License
 */

declare(strict_types=1);

namespace EngineCore\config\db\menu;

use EngineCore\Ec;
use EngineCore\extension\repository\info\ConfigInfo;
use Yii;
use yii\web\Application;

class Info extends ConfigInfo
{
    
    const EXT_RAND_CODE = 'sUM5Gf_';
    
    protected $id = 'config-db-menu';
    
    /**
     * @inheritdoc
     */
    public function getConfig(): array
    {
        return [
            'container' => [
                'definitions' => [
                    'MenuProvider' => [
                        'class'     => 'EngineCore\extension\menu\DbProvider',
                        'tableName' => '{{%' . parent::EXT_RAND_CODE . 'menu}}',
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getMigrationTable(): string
    {
        return '{{%' . static::EXT_RAND_CODE . 'migration}}';
    }
    
    /**
     * @inheritdoc
     */
    public function getMigrationPath(): array
    {
        return ['@EngineCore/extension/menu/migrations'];
    }
    
    /**
     * @inheritdoc
     */
    public function install(): bool
    {
        if (false === parent::install()) {
            return false;
        }

        return Ec::$service->getMigration()->table($this->getMigrationTable())
                           ->interactive(false)
                           ->path($this->getMigrationPath())
                           ->compact(Yii::$app instanceof Application)
                           ->up(0);
    }

    /**
     * @inheritdoc
     */
    public function uninstall(): bool
    {
        if (false === parent::uninstall()) {
            return false;
        }

        $res = Ec::$service->getMigration()->table($this->getMigrationTable())
                           ->interactive(false)
                           ->path($this->getMigrationPath())
                           ->compact(Yii::$app instanceof Application)
                           ->down('all');
        if ($res) {
            Ec::$service->getMigration()->getMigrate()->db->createCommand()->dropTable($this->getMigrationTable())->execute();
        }

        return $res;
    }
    
}
<?

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

/**
 * @property \Bitrix\Main\HttpRequest $request
 * @property \Bitrix\Main\DB\Connection $connection
 */

Loc::loadMessages(__FILE__);

class pgk_maps extends CModule
{
    protected $tables = [
        \PGK\Maps\MapsTable::class,
        \PGK\Maps\IconsTable::class,
    ];

    public function __construct()
    {
        $arModuleVersion = array();

        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_ID = 'pgk.maps';
        $this->MODULE_NAME = 'ПГК: Карты';
        $this->MODULE_DESCRIPTION = 'Модуль для создания интерактивных карт';
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = 'ПГК';
        $this->PARTNER_URI = 'https://pgk.ru/';
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
    //region events
    public function InstallEvents()
    {
        foreach ($this->getEventList() as $arEvent) {
            if (empty($arEvent)) {
                continue;
            }

            RegisterModuleDependences($arEvent['FROM_MODULE_ID'], $arEvent['EVENT_ID'], $arEvent['TO_MODULE_ID'], $arEvent['TO_CLASS'], $arEvent['TO_METHOD']);
        }
    }

    public function UnInstallEvents()
    {
        foreach ($this->getEventList() as $arEvent) {
            if (empty($arEvent)) {
                continue;
            }

            UnRegisterModuleDependences($arEvent['FROM_MODULE_ID'], $arEvent['EVENT_ID'], $arEvent['TO_MODULE_ID'], $arEvent['TO_CLASS'], $arEvent['TO_METHOD']);
        }
    }

    public function getEventList()
    {
        return [
            [
                'FROM_MODULE_ID' => 'main',
                'EVENT_ID' => 'OnEpilog',
                'TO_MODULE_ID' => $this->MODULE_ID,
                'TO_CLASS' => PGK\Maps\Events\Main::class,
                'TO_METHOD' => 'onEpilogHandler'
            ]
        ];
    }
    //endregion;

    //region db
    function InstallDB()
    {
        global $APPLICATION;
        Loader::includeModule($this->MODULE_ID);

        try {
            /**
             * @var \Bitrix\Main\ORM\Data\DataManager $table
             */
            foreach ($this->tables as $table) {
                $entity = $table::getEntity();
                $tableName = $entity->getDBTableName();

                $connection = \Bitrix\Main\Application::getConnection();

                if (!$connection->isTableExists($tableName)) {
                    $entity->createDbTable();
                }
            }
            return true;
        } catch (Throwable $e) {
            $APPLICATION->ThrowException($e->getMessage());
        }
        return false;
    }

    function UnInstallDB()
    {
        global $APPLICATION;
        Loader::includeModule($this->MODULE_ID);
        try {
            /**
             * @var \Bitrix\Main\ORM\Data\DataManager $table
             */
            foreach ($this->tables as $table) {
                $entity = $table::getEntity();
                $tableName = $entity->getDBTableName();

                $connection = \Bitrix\Main\Application::getConnection();

                if($connection->isTableExists($tableName)) {
                    $connection->dropTable($tableName);
                }
            }
            return true;
        } catch (Throwable $e) {
            $APPLICATION->ThrowException($e->getMessage());
        }
        return false;
    }
    //endregion;

    //region files
    function InstallFiles()
    {
        copyDirFiles(
            __DIR__.'/components',
            Application::getDocumentRoot().'/local/components',
            true, true
        );

        return copyDirFiles(
            __DIR__.'/js',
            Application::getDocumentRoot().'/local/js/',
            true, true
        );
    }
    function UnInstallFiles()
    {
        $folders = [
            Application::getDocumentRoot().'/local/components/pgk/map',
            Application::getDocumentRoot().'/local/js/vue/components/map',
        ];

        foreach ($folders as $folder) {
            Bitrix\Main\IO\Directory::deleteDirectory($folder);
        }
        return true;
    }
    //endregion;
}

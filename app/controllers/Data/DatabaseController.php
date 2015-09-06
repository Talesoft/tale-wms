<?php

namespace Tale\Wms\Controller\Data;

use Tale\App\ControllerBase;

class DatabaseController extends ControllerBase
{

    /**
     * @var \Tale\Data\Source
     */
    public $src;

    public function initDb() {

        $this->src = $this->getDataSource('common');
    }

    public function indexAction()
    {

        $databases = [];
        foreach ($this->src->getDatabaseNames() as $name) {
            $databases[] = [
                'name' => $name,
                'getUrl' => $this->getUrl("data/database/get/$name.{{format}}"),
                'tableIndexUrl' => $this->getUrl("data/table.{{format}}", ['database' => $name])
            ];
        }

        return [
            'title'     => 'Databases in Data Source',
            'databases' => $databases
        ];
    }

    public function getAction($name = null)
    {

        if (!$name)
            return $this->dispatchError('not-found');

        $db = $this->src->getDatabase($name);
        $dbName = $db->getName();
        $exists = $db->exists();

        $tables = [];
        if ($exists) {

            foreach ($this->src->getTableNames($db) as $name) {

                $tables[] = [
                    'name' => $name,
                    'getUrl' => $this->getUrl("data/table/get/$name.{{format}}", ['database' => $dbName])
                ];
            }
        }


        return [
            'title' => "Database $dbName Info",
            'name' => $dbName,
            'exists' => $exists,
            'indexUrl' => $this->getUrl('data/database.{{format}}'),
            'createUrl' => $this->getUrl("data/database/create/$dbName.{{format}}"),
            'removeUrl' => $this->getUrl("data/database/remove/$dbName.{{format}}"),
            'tableIndexUrl' => $this->getUrl("data/table.{{format}}", ['database' => $dbName]),
            'tables' => $tables
        ];
    }

    public function createAction($name = null)
    {

        if (!$name)
            return $this->dispatchError('not-found');

        $db = $this->src->getDatabase($name);
        $dbName = $db->getName();

        $created = false;
        if (!$db->exists()) {

            $db->create();
            $created = true;
        }

        return [
            'title' => "Create Database $dbName",
            'name' => $dbName,
            'created' => $created,
            'indexUrl' => $this->getUrl('data/database.{{format}}'),
            'getUrl' => $this->getUrl("data/database/get/$dbName.{{format}}"),
        ];
    }

    public function removeAction($name = null)
    {

        if (!$name)
            return $this->dispatchError('not-found');

        $db = $this->src->getDatabase($name);
        $dbName = $db->getName();

        $removed = false;
        if ($db->exists()) {

            $db->remove();
            $removed = true;
        }

        return [
            'title' => "Remove Database $dbName",
            'name' => $dbName,
            'removed' => $removed,
            'indexUrl' => $this->getUrl('data/database.{{format}}'),
            'getUrl' => $this->getUrl("data/database/get/$dbName.{{format}}"),
        ];
    }
}
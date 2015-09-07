<?php

namespace Tale\Wms\Controller\Data;

use Tale\App\ControllerBase;
use Tale\Net\Http\Method;

class TableController extends ControllerBase
{

    /**
     * @var \Tale\Data\Source
     */
    public $src;

    public function initDb() {

        $this->src = $this->getDataSource('common');

        $this->addForm('database', [
            'database' => 'text'
        ]);

        $form = $this->getFilledForm('database', Method::GET);
        $this->dbName = $form->database->getValue();

        if (!$this->dbName)
            return $this->dispatchError('not-found');

        $this->db = $this->src->getDatabase($this->dbName);

        if (!$this->db->exists())
            return $this->dispatchError('not-found');
    }

    public function indexAction()
    {

        $tables = [];
        foreach ($this->src->getTableNames($this->db) as $name) {
            $tables[] = [
                'name' => $name,
                'getUrl' => $this->getUrl("data/table/get/$name.{{format}}", null, true),
                'columnIndexUrl' => $this->getUrl("data/column.{{format}}", ['table' => $name], true)
            ];
        }

        return $this->view([
            'title'     => "Table Index of {$this->dbName}",
            'databaseName' => $this->dbName,
            'createUrl' => $this->getUrl("data/table/create/{{name}}.{{format}}", ['database' => $this->dbName]),
            'databaseGetUrl' => $this->getUrl("data/database/get/{$this->dbName}.{{format}}"),
            'databaseIndexUrl' => $this->getUrl("data/database.{{format}}"),
            'tables' => $tables
        ]);
    }

    public function getAction($name = null)
    {

        if (!$name)
            return $this->dispatchError('not-found');

        $tbl = $this->db->getTable($name);
        $tblName = $tbl->getName();
        $exists = $tbl->exists();

        $columns = [];
        if ($exists) {

            foreach ($tbl->getColumns()->loadAll() as $col) {

                $name = $col->getName();
                $columns[] = [
                    'name' => $name,
                    'type' => $col->getType(),
                    'getUrl' => $this->getUrl("data/column/get/$name.{{format}}", ['table' => $tblName], true)
                ];
            }
        }

        return [
            'name' => $tblName,
            'exists' => $exists,
            'indexUrl' => $this->getUrl('data/table.{{format}}', null, true),
            'createUrl' => $this->getUrl("data/table/create/$tblName.{{format}}", ["id" => "id"], true),
            'removeUrl' => $this->getUrl("data/table/remove/$tblName.{{format}}", null, true),
            'tableIndexUrl' => $this->getUrl("data/column.{{format}}", ['table' => $tblName], true),
            'columns' => $columns
        ];
    }

    public function createAction($name = null)
    {

        if (!$name)
            return $this->dispatchError('not-found');

        $tbl = $this->db->getTable($name);
        $tblName = $tbl->getName();
        $args = $this->webRequest->getUrlArgs();

        $created = false;
        if (!$tbl->exists() && !empty($args)) {

            foreach ($args as $name => $typeString) {

                if ($name === 'database')
                    continue;

                $tbl->setColumn($name, $typeString);
            }

            $tbl->create();
            $created = true;
        }

        return [
            'title' => "Create Table $tblName",
            'name' => $tblName,
            'created' => $created,
            'getUrl' => $this->getUrl("data/table/get/$tblName.{{format}}", ['database' => $this->dbName]),
            'indexUrl' => $this->getUrl("data/table.{{format}}", ['database' => $this->dbName])
        ];
    }

    public function removeAction($name = null)
    {

        if (!$name)
            return $this->dispatchError('not-found');

        $tbl = $this->db->getTable($name);
        $tblName = $tbl->getName();

        $removed = false;
        if ($tbl->exists()) {

            $tbl->remove();
            $removed = true;
        }

        return [
            'name' => $tblName,
            'removed' => $removed,
            'getUrl' => $this->getUrl("data/table/get/$tblName.{{format}}", null, true),
        ];
    }
}
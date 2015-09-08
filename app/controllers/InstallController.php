<?php

namespace Tale\Wms\Controller;

use Tale\Wms\ControllerBase;

class InstallController extends ControllerBase
{

    public function indexAction()
    {

        if (!$this->db->exists())
            $this->db->create();

        $this->db->persons->migrate();
        $this->db->storages->migrate();
        $this->db->products->migrate();
        $this->db->storageProducts->migrate();
        $this->db->transfers->migrate();

        return 'Installed';
    }

    public function fullAction()
    {

        $this->db->removeIfExists();
        $this->indexAction();
        $this->seedStoragesAction();
        $this->seedPersonsAction();
        $this->seedProductsAction();
        $this->seedStorageProductsActon();

        return 'Installed fully';
    }

    public function seedStoragesAction()
    {

        $storages = $this->db->storages;
        $storages->insertRow(['name' => 'Wareneingang', 'allowIn' => false]);
        $storages->insertRow(['name' => 'Warenausgang', 'allowOut' => false]);
        $storages->insertRow(['name' => 'Hauptlager']);
        $storages->insertRow(['name' => 'Projekt 1']);
        $storages->insertRow(['name' => 'Projekt 2']);

        return 'Storages seeded';
    }

    public function seedPersonsAction()
    {

        $persons = $this->db->persons;
        $persons->insertRow(['firstName' => 'Administrator', 'loginName' => 'admin', 'role' => 'admin'], false)->setPassword('admin')->create();
        $persons->insertRow(['firstName' => 'Reader', 'loginName' => 'reader', 'role' => 'reader'], false)->setPassword('reader')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 1', 'loginName' => 'mitarbeiter-1', 'role' => 'user'], false)->setPassword('ma')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 2', 'loginName' => 'mitarbeiter-2', 'role' => 'user'], false)->setPassword('ma')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 3', 'loginName' => 'mitarbeiter-3', 'role' => 'user'], false)->setPassword('ma')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 4', 'loginName' => 'mitarbeiter-4', 'role' => 'user'], false)->setPassword('ma')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 5', 'loginName' => 'mitarbeiter-5', 'role' => 'guest'], false)->setPassword('ma')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 6', 'loginName' => 'mitarbeiter-6', 'role' => 'guest'], false)->setPassword('ma')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 7', 'loginName' => 'mitarbeiter-7', 'role' => 'guest'], false)->setPassword('ma')->create();
        $persons->insertRow(['firstName' => 'Mitarbeiter 8', 'loginName' => 'mitarbeiter-8', 'role' => 'guest'], false)->setPassword('ma')->create();

        return 'Persons seeded';
    }

    public function seedProductsAction()
    {

        $products = $this->db->products;
        $products->insertRow(['name' => 'Test Produkt 1', 'description' => 'Beschreibung zum Test Produkt 1']);
        $products->insertRow(['name' => 'Test Produkt 2', 'description' => 'Beschreibung zum Test Produkt 2']);
        $products->insertRow(['name' => 'Test Produkt 3', 'description' => 'Beschreibung zum Test Produkt 3']);
        $products->insertRow(['name' => 'Test Produkt 4', 'description' => 'Beschreibung zum Test Produkt 4']);
        $products->insertRow(['name' => 'Test Produkt 5', 'description' => 'Beschreibung zum Test Produkt 5']);
        $products->insertRow(['name' => 'Test Produkt 6', 'description' => 'Beschreibung zum Test Produkt 6']);
        $products->insertRow(['name' => 'Test Produkt 7', 'description' => 'Beschreibung zum Test Produkt 7']);
    }

    public function seedStorageProductsActon()
    {

        foreach ($this->db->products->select() as $product) {

            foreach ($this->db->storages->select() as $storage) {

                if (mt_rand(0, 100) <= 90) {

                    $rand = mt_rand(5, 100);
                    $req = max(0, $rand + mt_rand(-10, 10));
                    $this->db->storageProducts->insertRow([
                        'storageId' => $storage->id,
                        'productId' => $product->id,
                        'amount' => $rand,
                        'requiredAmount' => $req
                    ]);
                }
            }
        }
    }
}
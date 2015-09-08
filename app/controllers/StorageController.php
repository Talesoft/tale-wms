<?php

namespace Tale\Wms\Controller;

use Tale\Wms\ControllerBase;

class StorageController extends ControllerBase
{

    public function initStorages()
    {

        $sourceStorages = [];
        $targetStorages = [];

        foreach ($this->db->storages->select() as $storage) {

            if ($storage->allowOut)
                $sourceStorages[] = $storage;

            if ($storage->allowIn)
                $targetStorages[] = $storage;
        }

        if (!count($sourceStorages) || !count($targetStorages))
            return ['No storages'];

        if ($this->request->getAction() !== 'set-source-storage')
            if (!isset($this->session->sourceStorageId))
                return $this->redirectReturn('storage/set-source-storage/'.($sourceStorages[0]->id));

        if ($this->request->getAction() !== 'set-target-storage')
            if (!isset($this->session->targetStorageId))
               return $this->redirectReturn('storage/set-target-storage/'.($targetStorages[count($targetStorages) - 1]->id));

        var_dump('BLEH', $this->session->sourceStorageId);
        $currentSourceStorageId = intval($this->session->sourceStorageId);
        $currentTargetStorageId = intval($this->session->targetStorageId);

        $this->usedStorageIds = $usedIds = [$currentSourceStorageId, $currentTargetStorageId];

        $this->sourceStorages = array_filter($sourceStorages, function ($s) use ($usedIds) {

            return !in_array($s->id, $usedIds);
        });
        $this->targetStorages = array_filter($targetStorages, function ($s) use ($usedIds) {

            return !in_array($s->id, $usedIds);
        });;

        $this->currentSourceStorage = $this->db->storages->where(['id' => $currentSourceStorageId])->selectOne();
        $this->currentTargetStorage = $this->db->storages->where(['id' => $currentTargetStorageId])->selectOne();

        $this->codeInputValue = '';
    }

    public function indexAction()
    {

        return $this->view();
    }

    public function searchAction($scanCode = null)
    {

        if (!$scanCode)
            return ['No ScanCode given'];

        $exists = $this->db->hasProductByScanCode($scanCode);

        if (!$exists) {

            return $this->redirect('storage/create-product/'.$scanCode);
        }

        return $this->redirect('storage/view-product/'.$scanCode);
    }

    public function viewProductAction($scanCode = null)
    {

        if (!$scanCode)
            return ['No ScanCode given'];

        if (!$this->db->hasProductByScanCode($scanCode))
            return ['Product not found'];

        $this->codeInputValue = $scanCode;

        $this->product = $this->db->getProductByScanCode($scanCode);

        $this->storageProducts = $this->db->getStorageProductsByProductId($this->product->id);
        foreach ($this->storageProducts as $sp) {

            $sp->background_class = 'success';

            if ($sp->required_amount > $sp->amount)
                $sp->background_class = 'danger';
            else if (($sp->amount - $sp->required_amount) < 5)
                $sp->background_class = 'warning';
        }


        $sp = null;
        if ($this->db->hasStorageProduct($this->currentSourceStorage->id, $this->product->id)) {

            $sp = $this->db->getStorageProduct($this->currentSourceStorage->id, $this->product->id);
        }

        $this->sourceStorageProduct = $sp;

        $this->backgroundClass = 'info';
        $sp = null;
        if ($this->db->hasStorageProduct($this->currentTargetStorage->id, $this->product->id)) {

            $sp = $this->db->getStorageProduct($this->currentTargetStorage->id, $this->product->id);

            if ($sp->required_amount > $sp->amount)
                $this->backgroundClass = 'danger';
            else if (($sp->amount - $sp->required_amount) < 5)
                $this->backgroundClass = 'warning';
        }

        $this->targetStorageProduct = $sp;
    }

    public function setSourceStorageAction($id = null)
    {

        if (!$id)
            return ['No ID given'];

        if (!$this->db->storages->where(['id' => $id])->count())
            return ['Storage not found'];

        $this->session->sourceStorageId = intval($id);

        return $this->redirectWithReturn('storage');
    }

    public function setTargetStorageAction($id = null)
    {

        if (!$id)
            return ['No ID given'];

        if (!$this->db->storages->where(['id' => $id])->count())
            return ['Storage not found'];

        $this->session->targetStorageId = intval($id);

        return $this->redirectWithReturn('storage');
    }

    public function addProductAction($scanCode = null)
    {

        if (!$scanCode)
            return ['No ScanCode given'];

        if (!$this->db->hasProductByScanCode($scanCode))
            return ['Product not found'];

        $product = $this->db->getProductByScanCode($scanCode);

        $currentAmount = $this->db->getStorageProductAmount($this->current);
    }

    public function setProductAmountAction($scanCode = null, $amount = null)
    {

        if (!$scanCode)
            return ['No ScanCode given'];

        if (!$this->db->hasProductByScanCode($scanCode))
            return ['Product not found'];

        $product = $this->db->getProductByScanCode($scanCode);
        $amount = abs($amount ? intval($amount) : $amount);

        $source = $this->currentSourceStorage;
        $target = $this->currentTargetStorage;


        $currentAmount = $this->db->getStorageProductAmount($product->id, $this->currentTargetStorage->id);
    }
}
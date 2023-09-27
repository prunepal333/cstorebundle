<?php
declare(strict_types = 1);

namespace Purush\CstoreBundle\Service;
use Pimcore\Model\DataObject\Classificationstore\CollectionConfig;
use Pimcore\Model\DataObject\Classificationstore\CollectionGroupRelation;
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\Key;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyGroupRelation;
use Pimcore\Model\DataObject\Classificationstore\StoreConfig;

class ClassificationStoreService
{
    public function exportTo($filepath)
    {
        file_put_contents($filepath, $this->export());
    }
    public function export()
    {
        $listing = new StoreConfig\Listing();
        $result = [];
        foreach ($listing as $config) {
            $result['storeconfig'][]= [
                'name' => $config->getName(),
                'description' => $config->getDescription(),
            ];
        }
        $listing = new CollectionConfig\Listing();
        foreach ($listing as $config) {
            $result['collectionconfig'][] = [
                'name' => $config->getName(),
                'description' => $config->getDescription(),
                'store_name' => StoreConfig::getById($config->getStoreId())?->getName(),
            ];
        }
        $listing = new GroupConfig\Listing();
        foreach ($listing as $config) {
            $result['groupconfig'][] = [
                'name' => $config->getName(),
                'description' => $config->getDescription(),
                'store_name' => StoreConfig::getById($config->getStoreId())?->getName(),
            ];
        }
        $listing = new KeyConfig\Listing();
        foreach ($listing as $config) {
            $result['keyconfig'][] = [
                'name' => $config->getName(),
                'title' => $config->getTitle(),
                'description' => $config->getDescription(),
                'definition' => $config->getDefinition(),
                'store_name' => StoreConfig::getById($config->getStoreId())?->getName(),
            ];
        }
        //relation tables
        $listing = new CollectionGroupRelation\Listing();
        foreach ($listing as $config) {
            $result['collectiongroup'][] = [
                'name' => $config->getName(),
                'description' => $config->getDescription(),
                'group_name' => GroupConfig::getById($config->getGroupId())?->getName(),
                'collection_name' => GroupConfig::getById($config->getGroupId())?->getName(),
                'sorter' => $config->getSorter(),
            ];
        }

        $listing = new KeyGroupRelation\Listing();
        foreach ($listing as $config) {
            $result['keygroup'][] = [
                'name' => $config->getName(),
                'description' => $config->getDescription(),
                'group_name' => GroupConfig::getById($config->getGroupId())?->getName(),
                'key_name' => KeyConfig::getById($config->getKeyId())?->getName(),
                'type' => $config->getType(),
                'definition' => $config->getDefinition(),
                'sorter' => $config->getSorter(),
                'enabled' => (int)$config->isEnabled(),
                'mandatory' => $config->isMandatory(),
            ];
        }
        return json_encode($result, JSON_PRETTY_PRINT);
    }
    public function importFrom(string $filepath, $override = false)
    {
        $this->import(file_get_contents($filepath), $override);
    }
    public function import(string $json, $override = false)
    {
        $content = json_decode($json, true);

        if (array_key_exists('storeconfig', $content)) {
            foreach ($content['storeconfig'] as $storeconfig) {
                if ($override && StoreConfig::getByName($storeconfig['name'])) {
                    $storeConfig = new StoreConfig();
                    $storeConfig->setName($storeconfig['name']);
                    $storeConfig->setDescription($storeconfig['description']);
                    $storeConfig->save();
                }
            }
        }

        if (array_key_exists('collectionconfig', $content)) {
            foreach ($content['collectionconfig'] as $collectionconfig) {
                if ($override && CollectionConfig::getByName($collectionconfig['name'])) {
                    $collectionConfig = new CollectionConfig();
                    $collectionConfig->setName($collectionconfig['name']);
                    $collectionConfig->setDescription($collectionconfig['description']);
                    $collectionConfig->setStoreId(StoreConfig::getByName($collectionconfig['store_name'])?->getId());
                    $collectionConfig->save();
                }
            }
        }

        if (array_key_exists('groupconfig', $content)) {
            foreach ($content['groupconfig'] as $groupconfig) {
                if ($override && GroupConfig::getByName($groupconfig['name'])) {
                    $groupConfig = new GroupConfig();
                    $groupConfig->setName($groupconfig['name']);
                    $groupConfig->setDescription($groupconfig['description']);
                    $groupConfig->setStoreId(StoreConfig::getByName($groupconfig['store_name'])?->getId());
                    $groupConfig->save();
                }
            }
        }

        if (array_key_exists('keyconfig', $content)) {
            foreach ($content['keyconfig'] as $keyconfig) {
                if ($override && KeyConfig::getByName($keyconfig['name'])) {
                    $keyConfig = new KeyConfig();
                    $keyConfig->setName($keyconfig['name']);
                    $keyConfig->setTitle($keyconfig['title']);
                    $keyConfig->setDescription($keyconfig['description']);
                    $keyConfig->setDefinition($keyconfig['definition']);
                    $keyConfig->setStoreId(StoreConfig::getByName($keyconfig['store_name'])?->getId());
                    $keyConfig->save();
                }
            }
        }

        if (array_key_exists('collectiongroup', $content)) {
            foreach ($content['collectiongroup'] as $collectiongroup) {
                $collectionGroupRelation = new CollectionGroupRelation();
                $collectionGroupRelation->setName($collectiongroup['name']);
                $collectionGroupRelation->setDescription($collectiongroup['description']);
                $collectionGroupRelation->setGroupId(GroupConfig::getByName($collectiongroup['group_name'])?->getId());
                $collectionGroupRelation->setColId(CollectionConfig::getByName($collectiongroup['collection_name'])?->getId());
                $collectionGroupRelation->setSorter($collectiongroup['sorter']);
                $collectionGroupRelation->save();
            }
        }

        if (array_key_exists('keygroup', $content)) {
            foreach ($content['keygroup'] as $keygroup) {
                $keyGroupRelation = new KeyGroupRelation();
                $keyGroupRelation->setName($keygroup['name']);
                $keyGroupRelation->setDescription($keygroup['description']);
                $keyGroupRelation->setGroupId(GroupConfig::getByName($keygroup['group_name'])?->getId());
                $keyGroupRelation->setKeyId(KeyConfig::getByName($keygroup['key_name'])?->getId());
                $keyGroupRelation->setDefinition($keygroup['definition']);
                $keyGroupRelation->setType($keygroup['type']);
                $keyGroupRelation->setMandatory($keygroup['mandatory']);
                $keyGroupRelation->setSorter($keygroup['sorter']);
                $keyGroupRelation->setEnabled($keygroup['enabled']);
                $keyGroupRelation->save();
            }
        }
    }
}
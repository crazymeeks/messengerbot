<?php

namespace App\Models\Data;

use Data\DataObject;
use App\Models\Api\SystemConfigurationInterface;

class SystemConfiguration implements SystemConfigurationInterface
{

    protected $dataObject;

    /**
     * Constructor
     *
     * @param \Data\DataObject $do
     */
    public function __construct(DataObject $do)
    {
        $this->dataObject = $do;
    }

    /**
     * @inheritDoc
     */
    public function setId(int $id)
    {
        $this->dataObject->setId($id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfigType(string $config_type = self::DEFAULT_CONFIG_TYPE)
    {
        $this->dataObject->setConfigType($config_type);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfigName(string $config_name)
    {
        $this->dataObject->setConfigName($config_name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfigValue($config_value)
    {
        $this->dataObject->setConfigValue($config_value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->dataObject->getId();
    }

    /**
     * @inheritDoc
     */
    public function getConfigType()
    {
        $config_type = $this->dataObject->getConfigType() ?? self::DEFAULT_CONFIG_TYPE;

        return $config_type;
    }

    /**
     * @inheritDoc
     */
    public function getConfigName()
    {
        return $this->dataObject->getConfigName();
    }

    /**
     * @inheritDoc
     */
    public function getConfigValue()
    {
        return $this->dataObject->getConfigValue();
    }
}
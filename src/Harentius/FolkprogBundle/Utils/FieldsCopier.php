<?php

namespace Harentius\FolkprogBundle\Utils;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class FieldsCopier
{
    /**
     * @var bool
     */
    private $ignoreMissingSourceFields;

    /**
     * @var bool
     */
    private $ignoreMissingTargetFields;

    /**
     * @var
     */
    private $skipNullValues;

    /**
     * @var array
     */
    private $prototypeFieldsCache;

    /**
     *
     */
    public function __construct()
    {
        $this->ignoreMissingSourceFields = false;
        $this->ignoreMissingTargetFields = false;
        $this->skipNullValues = true;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setIgnoreMissingSourceFields($value)
    {
        $this->ignoreMissingSourceFields = (bool) $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIgnoreMissingSourceFields()
    {
        return $this->ignoreMissingSourceFields;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setIgnoreMissingTargetFields($value)
    {
        $this->ignoreMissingTargetFields = (bool) $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIgnoreMissingTargetFields()
    {
        return $this->ignoreMissingTargetFields;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setSkipNullValues($value)
    {
        $this->skipNullValues = (bool) $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSkipNullValues()
    {
        return $this->skipNullValues;
    }

    /**
     * @param array $fields
     * @param object|array $source
     * @param object|array $target
     */
    public function copy(array $fields, $source, &$target)
    {
        $sourceIsArray = is_array($source);
        $targetIsArray = is_array($target);
        $arrayFields = null;

        if ($sourceIsArray || $targetIsArray) {
            $arrayFields = array_map(function ($field) {
                return "[{$field}]";
            }, $fields);
        }

        $sourceFields = $sourceIsArray ? $arrayFields : $fields;
        $targetFields = $targetIsArray ? $arrayFields : $fields;
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($fields as $fieldKey => $fieldName) {
            if ($this->ignoreMissingSourceFields) {
                try {
                    $value = $accessor->getValue($source, $sourceFields[$fieldKey]);
                } catch (NoSuchPropertyException $e) {
                    continue;
                }
            } else {
                $value = $accessor->getValue($source, $sourceFields[$fieldKey]);
            }

            if ($value !== null || !$this->skipNullValues) {
                if ($this->ignoreMissingTargetFields) {
                    try {
                        $accessor->setValue($target, $targetFields[$fieldKey], $value);
                    } catch (NoSuchPropertyException $e) {
                        continue;
                    }
                } else {
                    $accessor->setValue($target, $targetFields[$fieldKey], $value);
                }
            }
        }
    }

    /**
     * @param object|string $prototype
     * @param object|array $source
     * @param object|array $target
     */
    public function copyUsingPrototype($prototype, $source, &$target)
    {
        $this->copy($this->getPrototypeFields($prototype), $source, $target);
    }

    /**
     * @param object|string $prototype
     * @return array
     */
    private function getPrototypeFields($prototype)
    {
        if (is_array($prototype)) {
            return array_keys($prototype);
        }

        if (is_object($prototype)) {
            $prototype = get_class($prototype);
        } else {
            $prototype = (string) $prototype;
        }

        if (!isset($this->prototypeFieldsCache[$prototype])) {
            $reflect = new \ReflectionClass($prototype);
            $this->prototypeFieldsCache[$prototype] = array_map(function ($reflectField) {
                /** @var \ReflectionProperty $reflectField */
                return $reflectField->getName();
            }, array_filter($reflect->getProperties(), function ($reflectField) {
                /** @var \ReflectionProperty $reflectField */
                return !$reflectField->isStatic();
            }));
        }

        return $this->prototypeFieldsCache[$prototype];
    }
}

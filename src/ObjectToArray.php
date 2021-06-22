<?php

namespace L5Dto;

/**
*/
class ObjectToArray
{

    /**
     * @var boolean
     */
    protected $isUcFirst;

    /**
     * @var boolean
     */
    protected $withEmptyArrays;

    /**
     * ObjectToArray constructor.
     */
    public function __construct()
    {
        $this->isUcFirst = false;
        $this->withEmptyArrays = false;
    }

    /**
     * @return bool
     */
    public function isUcFirst(): bool
    {
        return $this->isUcFirst;
    }

    /**
     * @param bool $isUcFirst
     */
    public function setIsUcFirst(bool $isUcFirst)
    {
        $this->isUcFirst = $isUcFirst;
    }

    /**
     * @return bool
     */
    public function isWithEmptyArrays(): bool
    {
        return $this->withEmptyArrays;
    }

    /**
     * @param bool $withEmptyArrays
     */
    public function setWithEmptyArrays(bool $withEmptyArrays)
    {
        $this->withEmptyArrays = $withEmptyArrays;
    }

    /**
     * Returns an array of all object's PUBLIC and PROTECTED properties and values.
     *
     * @param object $object
     *
     * @return array
     */
    public function toArray(object $object): array
    {
        $reflectionObject = new \ReflectionObject($object);
        $result = [];

        foreach ($reflectionObject->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC) as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($object);
            $isArray = false;

            if (isset($propertyValue)) {
                if (is_array($propertyValue)){
                    $tempPropertyValue = [];
                    foreach ($propertyValue as $key => $item){
                        if ($this->isUcFirst){
                            $key = ucfirst($key);
                        }
                        $tempPropertyValue[$key] = is_object($item) ? $this->toArray($item) : $item;
                    }
                    $propertyValue = $tempPropertyValue;
                    $isArray = true;
                } elseif (is_object($propertyValue)){
                    $propertyValue = $this->toArray($propertyValue);
                }
            }

            if (isset($propertyValue) && (($this->withEmptyArrays && $isArray) || !(is_array($propertyValue) && empty($propertyValue)))) {
                if ($this->isUcFirst){
                    $propertyName = ucfirst($propertyName);
                }
                $result[$propertyName] = $propertyValue;
            }
        }

        return $result;
    }
}

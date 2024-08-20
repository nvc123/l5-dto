<?php


namespace L5Dto;


use Illuminate\Support\Arr;

class DtoGenerator
{

    /**
     * @param string $class
     * @param array $data
     * @return object
     */
    public function createFromArray(string $class, array $data): object
    {
        return $this->fillFromArray(new $class(), $data);
    }

    /**
     * @param object $object
     * @param array $data
     * @return object
     */
    public function fillFromArray(object $object, array $data): object
    {
        $reflectionObject = new \ReflectionObject($object);

        foreach ($reflectionObject->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC) as $property) {
            $this->setPropertyValue($object, $property, $this->getPropertyFromArray($object, $property, $data));
        }

        return $object;
    }

    /**
     * @param object $object
     * @param \ReflectionProperty $property
     * @param array $data
     * @return mixed
     */
    protected function getPropertyFromArray(object $object, \ReflectionProperty $property, array $data)
    {
        $propertyName = $property->getName();
        $propertyValue = Arr::get($data, $propertyName);

        $property->setAccessible(true);

        if (!isset($propertyValue)) {
            try {
                $propertyValue = $property->getValue($object);
            } catch (\Throwable $throwable) {
            }
        }

        return $propertyValue;
    }

    /**
     * @param object $object
     * @param \ReflectionProperty $property
     * @param $value
     */
    protected function setPropertyValue(object $object, \ReflectionProperty $property, $value): void
    {
        if (method_exists($object, '__set')) {
            $object->__set($property->getName(), $value);
        } else {
            $type = $property->getType();
            if (is_null($value) && $type && !$type->allowsNull()){
                return;
            }
            
            $property->setValue($object, $value);
        }
    }

}

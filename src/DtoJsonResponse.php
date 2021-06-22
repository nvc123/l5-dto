<?php


namespace L5Dto;


class DtoJsonResponse implements \JsonSerializable
{

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        /** @var ObjectToArray $converter */
        $converter = app(ObjectToArray::class);

        $this->onPreSerialize($converter);

        return $converter->toArray($this);
    }

    protected function onPreSerialize(ObjectToArray $converter): void
    {

    }
}
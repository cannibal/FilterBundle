<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Expected;
use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;

class ExpectedFilter
{
    private $name;
    private $type;
    private $config;

    public function __construct($name)
    {
        $this->config = array();
        $this->name = $name;
        $this->type = FilterInterface::TYPE_STRING;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setConfig($name, $value)
    {
        $this->config[$name] = $value;
    }

    public function getConfig($name)
    {
        return $this->config[$name];
    }

    public function hasConfig($name)
    {
        return isset($this->config[$name]);
    }
}
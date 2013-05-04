<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Expected;

interface ExpectedFilterInterface
{
    public function getName();

    public function getType();

    public function setConfig($name, $value);

    public function getConfig($name);

    public function hasConfig($name);
}
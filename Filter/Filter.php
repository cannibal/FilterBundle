<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;

/**
 * This class represents a filter against some list resource
 */
class Filter implements FilterInterface
{
    private $name;
    private $comparison;
    private $criteria;
    private $isNot;

    public function __construct()
    {
        $this->name = null;
        $this->comparison = null;
        $this->criteria = null;
        $this->isNot = false;
    }

    public function setIsNot($isNot)
    {
        $this->isNot = $isNot;
    }

    public function isNot()
    {
        return $this->isNot;
    }

    public function getComparisons()
    {
        return array(
            FilterInterface::EQ,
            FilterInterface::LIKE,
            FilterInterface::ILIKE,
            FilterInterface::LT,
            FilterInterface::LTE,
            FilterInterface::GT,
            FilterInterface::GTE,
            FilterInterface::IN
        );
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function setComparison($comparison)
    {
        $this->comparison = $comparison;
    }

    public function getComparison()
    {
        return $this->comparison;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateValidator;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\ExecutionContext;

/**
 * This class represents a filter against some list resource
 */
class Filter implements FilterInterface
{
    private $name;
    private $comparison;
    private $criteria;
    private $isNot;
    private $type;

    public function __construct()
    {
        $this->name = null;
        $this->comparison = null;
        $this->criteria = null;
        $this->isNot = false;
        $this->type = null;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setIsNot($isNot)
    {
        $this->isNot = $isNot;
    }

    public function isNot()
    {
        return $this->isNot;
    }

    public function containsExpectedCriteriaType(ExecutionContext $context)
    {
        $constraint = null;
        $validator = null;

        switch($this->getType()){
            case FilterInterface::TYPE_DATE:
                $constraint = new Date();
                $validator = new DateValidator();
                break;
            case FilterInterface::TYPE_INT:
                $constraint = new Regex(array('pattern'=>'/^\d+$/'));
                $validator = new RegexValidator();
                break;
            default:
                $constraint = new Date();
                $validator = new DateValidator();
        }

        $validator->initialize($context);
        $validator->validate($this->getCriteria(), $constraint);
    }

    public static function getComparisons()
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

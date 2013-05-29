<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;
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
        $this->not = false;
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

    public function getIsNot()
    {
        return $this->isNot;
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
        $value = $this->getCriteria();

        switch($this->getType()){
            case FilterInterface::TYPE_DATETIME:
                    $out = \DateTime::createFromFormat(\DateTime::ISO8601, $value);
                    if($out == false){
                        $context->addViolationAt('criteria', 'Criteria is not a valid date time');
                    }
                break;
            case FilterInterface::TYPE_INT:
                if(preg_match('/^\d+$/', $value) == 0){
                    $context->addViolationAt('criteria', 'Criteria is not a valid integer');
                }
                break;
            case FilterInterface::TYPE_FLOAT:
                if(preg_match('/^\d+\.\d+$/', $value) == 0){
                    $context->addViolationAt('criteria', 'Criteria is not a valid integer');
                }
                break;
            case FilterInterface::TYPE_BOOL:
                if($value != 'true' && $value != 'false'){
                    $context->addViolationAt('criteria', 'Criteria is not a valid boolean');
                }
                break;
            default:
        }
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
            FilterInterface::IN,
            FilterInterface::NULLABLE_EQ
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

    public function getCastCriteria()
    {
        $out = null;
        $value = $this->getCriteria();
        $type = $this->getType();

        switch($type){
            case FilterInterface::TYPE_INT:
                $out = filter_var($value, \FILTER_VALIDATE_INT);
                if($out == false){
                    $out = null;
                }
                break;
            case FilterInterface::TYPE_FLOAT:
                $out = filter_var($value, \FILTER_VALIDATE_FLOAT);
                if($out == false){
                    $out = null;
                }
                break;
            case FilterInterface::TYPE_DATETIME:
                $out = \DateTime::createFromFormat(\DateTime::ISO8601, $out);
                if($out == false){
                    $out = null;
                }
                break;
            case FilterInterface::TYPE_BOOL:
                $out = filter_var($out, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE);
                break;
            default:
                $out = $value;
        }

        return $out;
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

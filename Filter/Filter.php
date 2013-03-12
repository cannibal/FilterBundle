<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateValidator;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\TypeValidator;

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
        $value = $this->getCriteria();

        switch($this->getType()){
            case FilterInterface::TYPE_DATE:
                $constraint = new Date();
                $validator = new DateValidator();
                break;
            case FilterInterface::TYPE_INT:
                $constraint = new Regex(array('pattern'=>'/^\d+$/'));
                $validator = new RegexValidator();
                break;
            case FilterInterface::TYPE_BOOL:
                if($value != 'true' && $value != 'false'){
                    $context->addViolationAtPath('criteria', 'Criteria is not a valid boolean');
                }
                break;
            default:
        }

        if(null != $constraint && null != $validator){
            $validator->initialize($context);
            $validator->validate($value, $constraint);
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

    public function getCastCriteria()
    {
        $value = $this->getCriteria();
        $out = null;
        $type = $this->getType();

        switch($this->getType()){
            case FilterInterface::TYPE_INT:
                $out = filter_var($value, \FILTER_VALIDATE_INT);
                break;
            case FilterInterface::TYPE_DATE:
                $out = \DateTime::createFromFormat(\DateTime::ISO8601, $out);
                if($out == false){
                    throw new \Cannibal\Bundle\FilterBundle\Filter\Exception\FilterCastException(sprintf('Failed to cast %s as a %s',$value, $type));
                }

                break;
            case FilterInterface::TYPE_BOOL:
                $out = filter_var($out, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE);
                if(null == $out){
                    throw new \Cannibal\Bundle\FilterBundle\Filter\Exception\FilterCastException(sprintf('Failed to cast %s as a %s', $value, $type));
                }
                break;
        }
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

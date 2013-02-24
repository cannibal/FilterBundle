<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

/**
 * This class represents a filter
 */
interface FilterInterface
{
    const EQ = 'eq';
    const LIKE = 'like';
    const ILIKE = 'ilike';
    const LT = 'lt';
    const LTE = 'lte';
    const GT = 'gt';
    const GTE = 'gte';
    const IN = 'in';

    /**
     * @return string
     */
    public function getCriteria();

    /**
     * @return string
     */
    public function getComparison();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return boolean
     */
    public function isNot();
}

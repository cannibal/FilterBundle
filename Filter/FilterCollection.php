<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ExecutionContext;
use Cannibal\Bundle\FilterBundle\Filter\FilterCollectionInterface;
use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface;

/**
 * This class represents a collection of filters
 */
class FilterCollection implements FilterCollectionInterface
{
    private $filters;
    private $expectedFilters;

    public function __construct()
    {
        $this->filters = new ArrayCollection();
        $this->expectedFilters = array();
    }

    public function setExpectedFilters(ExpectedFiltersInterface $expectedFilters)
    {
        $this->expectedFilters = $expectedFilters;
    }

    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface
     */
    public function getExpectedFilters()
    {
        return $this->expectedFilters;
    }

    /**
     * @return ArrayCollection
     */
    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters(ArrayCollection $filters)
    {
        $this->filters = $filters;
    }

    public function removeFilter($filterName)
    {
        $this->getFilters()->remove($filterName);
    }

    public function addFilter(FilterInterface $filter)
    {
        $this->getFilters()->set($filter->getName(), $filter);
    }

    public function hasFilter($filterName)
    {
        return $this->getFilters()->containsKey($filterName);
    }

    /**
     * @param $filterName
     * @return \Cannibal\Bundle\FilterBundle\Filter\FilterInterface
     */
    public function getFilter($filterName)
    {
        return $this->getFilters()->get($filterName);
    }

    public function containsExpectedFilters(ExecutionContext $context)
    {
        $filters = $this->getFilters();
        $expectedFilters = $this->getExpectedFilters();

        foreach($filters as $filter){
            /** @var FilterInterface $filter */
            $name = $filter->getName();
            if(!$this->hasFilter($name)){
                $context->addViolationAt('filters', sprintf('Filter %s is not expected', $name));
            }
        }
    }
}

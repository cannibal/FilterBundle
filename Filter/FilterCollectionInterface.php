<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface;

interface FilterCollectionInterface
{
    public function setExpectedFilters(ExpectedFiltersInterface $expected);

    /**
     * @return array
     */
    public function getExpectedFilters();

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFilters();

    /**
     * @param $filterName
     * @return \Cannibal\Bundle\FilterBundle\Filter\FilterInterface
     */
    public function getFilter($filterName);

    /**
     * @param $filterName
     * @return bool
     */
    public function hasFilter($filterName);

    /**
     * This function removes a filter with the name provided
     *
     * @param $filterName
     * @return mixed
     */
    public function removeFilter($filterName);

    /**
     * This function adds a filter to the collection
     *
     * @param FilterInterface $filter
     * @return mixed
     */
    public function addFilter(FilterInterface $filter);
}

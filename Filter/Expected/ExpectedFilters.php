<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Expected;

use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterInterface;
use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface;

class ExpectedFilters implements ExpectedFiltersInterface
{
    private $filters;

    public function __construct()
    {
        $this->filters = array();
    }

    public function add(ExpectedFilterInterface $expected)
    {
        $this->filters[$expected->getName()] = $expected;
    }

    public function has($name)
    {
        return isset($this->filters[$name]);
    }

    /**
     * @param $name
     * @return \Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterInterface
     */
    public function get($name)
    {
        return $this->filters[$name];
    }

    /**
     * @return array
     */
    public function getExpectedFilters()
    {
        return $this->filters;
    }
}
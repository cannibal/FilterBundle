<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mv
 * Date: 04/05/13
 * Time: 21:27
 * To change this template use File | Settings | File Templates.
 */

namespace Cannibal\Bundle\FilterBundle\Filter\Expected;

use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterInterface;

class ExpectedFilters
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
}
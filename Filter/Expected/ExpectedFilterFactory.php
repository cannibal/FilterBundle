<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mv
 * Date: 04/05/13
 * Time: 22:15
 * To change this template use File | Settings | File Templates.
 */

namespace Cannibal\Bundle\FilterBundle\Filter\Expected;

use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterInterface;
use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilter;
use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface;

class ExpectedFilterFactory
{
    /**
     * @param $name
     * @return \Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterInterface|\Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilter
     */
    public function createExpectedFilter($name)
    {
        return new ExpectedFilter($name);
    }

    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface|\Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilters
     */
    public function createExpectedFilterCollection()
    {
        return new ExpectedFilters();
    }
}
<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Expected;

use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterBuilder;
use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface;

interface ExpectedFilterBuildContextInterface
{

    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterBuildContextInterface
     */
    public function add($name, $type, array $config = array());

    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface
     */
    public function end();
}
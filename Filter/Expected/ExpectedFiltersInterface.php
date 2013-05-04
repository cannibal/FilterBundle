<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Expected;

interface ExpectedFiltersInterface
{
    public function add(ExpectedFilterInterface $expected);

    public function has($name);

    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterInterface
     */
    public function get($name);
}
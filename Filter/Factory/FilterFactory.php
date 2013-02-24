<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Factory;

use Cannibal\Bundle\FilterBundle\Filter\Filter;

/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 21/02/2013
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */
class FilterFactory
{
    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Filter
     */
    public function createFilter()
    {
        return new Filter();
    }
}

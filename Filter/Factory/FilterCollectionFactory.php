<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Factory;

use Cannibal\Bundle\FilterBundle\Filter\FilterCollection;

/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 21/02/2013
 * Time: 14:49
 * To change this template use File | Settings | File Templates.
 */
class FilterCollectionFactory
{
    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\FilterCollection
     */
    public function createFilterCollection()
    {
        return new FilterCollection();
    }
}

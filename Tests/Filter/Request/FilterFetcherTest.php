<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mv
 * Date: 20/02/13
 * Time: 22:55
 * To change this template use File | Settings | File Templates.
 */

namespace Cannibal\Bundle\FilterBundle\Tests\Filter\Request;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Cannibal\Bundle\FilterBundle\Filter\Request\Fetcher\FilterFetcher;
use PHPUnit_Framework_TestCase;



class FilterFetcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Request\Fetcher\FilterFetcher
     */
    public function createFilterFetcher()
    {
        return new FilterFetcher();
    }

    public function dataProviderTestExtraction()
    {
        return array(
            array(
                array('filterName'=>array('comparison'=>'criteria')),
                array(
                    'filters'=>array(
                        array(
                            'name'=>'filterName',
                            'comparison'=>'comparison',
                            'criteria'=>'criteria',
                            'type'=>'type'
                        )
                    )
                )
            ),
            array(
                array('filterName'=>'criteria'),
                array(
                    'filters'=>array(
                        array(
                            'name'=>'filterName',
                            'comparison'=>'eq',
                            'criteria'=>'criteria',
                            'type'=>'type'
                        )
                    )
                )
            ),
        );
    }

    /**
     * @dataProvider dataProviderTestExtraction
     */
    public function testExtraction($input, $expected)
    {
        $test = $this->createFilterFetcher();

        $actual = $test->fetchFilters($input, array('filterName'=>'type'));

        $this->assertEquals($expected, $actual);
    }

    public function testNoFilters()
    {
        $test = $this->createFilterFetcher();

        $actual = $test->fetchFilters(array(), array('filterName'=>'type'));

        $this->assertEquals(array(), $actual);
    }
}
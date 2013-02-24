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
    public function createFilterFetcher($request)
    {
        return new FilterFetcher($request);
    }

    /**
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Request
     */
    public function createRequestMock(array $methods = array())
    {
        return $this->getMock('Symfony\\Component\\HttpFoundation\\Request', $methods);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface
     */
    public function createParameterBagMock()
    {
        return $this->getMock('Symfony\\Component\\DependencyInjection\\ParameterBag\\ParameterBagInterface');
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
                            'criteria'=>'criteria'
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
                            'criteria'=>'criteria'
                        )
                    )
                )
            ),
        );
    }

    public function testDefaults()
    {
        $request = $this->createRequestMock();
        $test = $this->createFilterFetcher($request);

        $this->assertEquals($request, $test->getRequest());
    }

    /**
     * @dataProvider dataProviderTestExtraction
     */
    public function testExtraction($input, $expected)
    {
        $request = $this->createRequestMock();
        $test = $this->createFilterFetcher($request);

        $bag = $this->createParameterBagMock();
        $bag->expects($this->once())->method('has')->with('filterName')->will($this->returnValue(true));
        $bag->expects($this->once())->method('get')->with('filterName')->will($this->returnValue($input));
        $request->query = $bag;

        $test->setRequest($request);
        $actual = $test->fetchFilters(array('filterName'));

        $this->assertEquals($expected, $actual);
    }

    public function testNoFilters()
    {
        $request = $this->createRequestMock();
        $test = $this->createFilterFetcher($request);

        $bag = $this->createParameterBagMock();
        $bag->expects($this->once())->method('has')->with('filterName')->will($this->returnValue(false));
        $request->query = $bag;

        $test->setRequest($request);
        $actual = $test->fetchFilters(array('filterName'));

        $this->assertEquals(array(), $actual);
    }
}
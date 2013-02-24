<?php
namespace Cannibal\Bundle\FilterBundle\Tests\Filter;

use Cannibal\Bundle\FilterBundle\Filter\FilterCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;

use PHPUnit_Framework_TestCase;


/**
 * Test class for the filter collection
 */
class FilterCollectionTest extends PHPUnit_Framework_TestCase
{
    public function createFilterCollection()
    {
        return new FilterCollection();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Validator\ExecutionContext
     */
    public function createExecutionContext($methods = array())
    {
        return $this->getMock('Symfony\\Component\\Validator\\ExecutionContext', $methods, array(), '', false);
    }

    public function testDefaults()
    {
        $test = $this->createFilterCollection();

        $this->assertInstanceOf('Doctrine\\Common\\Collections\\ArrayCollection', $test->getFilters());
        $this->assertCount(0, $test->getFilters());
    }

    /**
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createFilterInterfaceMock()
    {
        return $this->getMock('Cannibal\\Bundle\\FilterBundle\\Filter\\FilterInterface');
    }

    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\FilterCollection
     */
    public function testAddFilter()
    {
        $test = $this->createFilterCollection();

        $mock = $this->createFilterInterfaceMock(array('getName'));
        $mock->expects($this->once())->method('getName')->will($this->returnValue('testName'));

        $test->addFilter($mock);

        return $test;
    }

    public function testRemoveFilter()
    {
        $test = $this->testAddFilter();

        $this->assertCount(1, $test->getFilters());
        $test->removeFilter('testName');
        $this->assertCount(0, $test->getFilters());
    }

    public function testContainsExpectedFiltersNotValid()
    {
        $context = $this->createExecutionContext(array('addViolationAtPath'));
        $context->expects($this->once())->method('addViolationAtPath');

        $test = $this->createFilterCollection();

        $mock = $this->createFilterInterfaceMock(array('getName'));
        $mock->expects($this->atLeastOnce())->method('getName')->will($this->returnValue('testName'));

        $test->addFilter($mock);
        $this->assertCount(1, $test->getFilters());

        $test->setExpectedFilters(array('otherFilter'));

        $test->containsExpectedFilters($context);
    }
}

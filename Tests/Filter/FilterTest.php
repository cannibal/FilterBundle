<?php
namespace Cannibal\Bundle\FilterBundle\Tests\Filter;

use PHPUnit_Framework_TestCase;

use Cannibal\Bundle\FilterBundle\Filter\Filter;

/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 20/02/2013
 * Time: 15:00
 * To change this template use File | Settings | File Templates.
 */
class FilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Filter
     */
    public function createFilter()
    {
        return new Filter();
    }

    public function testDefaults()
    {
        $test = $this->createFilter();

        $this->assertNull($test->getCriteria());
        $this->assertNull($test->getComparison());
        $this->assertNull($test->getName());

        $this->assertFalse($test->isNot());
    }

    public function testSetGetters()
    {
        $test = $this->createFilter();

        $test->setName('testName');
        $this->assertEquals('testName', $test->getName());

        $test->setComparison('comparison');
        $this->assertEquals('comparison', $test->getComparison());

        $test->setCriteria('criteria');
        $this->assertEquals('criteria', $test->getCriteria());

        $test->setIsNot(true);
        $this->assertTrue($test->isNot());
    }
}

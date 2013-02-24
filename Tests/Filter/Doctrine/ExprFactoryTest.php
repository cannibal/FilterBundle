<?php
namespace Cannibal\Bundle\FilterBundle\Tests\Filter\Doctrine;

use PHPUnit_Framework_TestCase;

use Cannibal\Bundle\FilterBundle\Filter\Doctrine\ExprFactory;
use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;
use Doctrine\Common\Collections\Expr\Comparison,
    Doctrine\ORM\Query\Expr\Func;

class ExprFactoryTest extends PHPUnit_Framework_TestCase
{
    const MEMBERNAME = 'm.name';

    public function getFilterMock($methods = array('getComparison', 'getCriteria', 'isNot', 'getName'))
    {
        return $this->getMock('Cannibal\\Bundle\\FilterBundle\\Filter\\FilterInterface', $methods);
    }

    public function getExprFactory()
    {
        return new ExprFactory();
    }

    public function dataProviderCreateExpr()
    {
        return array(
            array(FilterInterface::LIKE, false, 'LIKE', 'testValue'),
            array(FilterInterface::ILIKE, false, 'ILIKE', 'testValue'),
            array(FilterInterface::EQ, false, '=', 'testValue'),
            array(FilterInterface::EQ, true, '<>', 'testValue'),
            array(FilterInterface::GT, false, '>', 'testValue'),
            array(FilterInterface::LT, false, '<', 'testValue'),
            array(FilterInterface::LTE, false, '<=', 'testValue'),
            array(FilterInterface::GTE, false, '>=', 'testValue'),
            array(FilterInterface::IN, false, 'IN', array('fuck', 'shit')),
            array(FilterInterface::IN, true, 'NIN', array('fuck', 'shit')),
        );
    }

    /**
     * @dataProvider dataProviderCreateExpr
     */
    public function testCreateExpr($modifierIn, $notModifier, $expectedOperator, $value)
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue($modifierIn));
        $filter->expects($this->once())->method('getCriteria')->will($this->returnValue($value));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue($notModifier));

        $expr = $test->createExpr(self::MEMBERNAME, $filter);

        if($modifierIn == FilterInterface::LIKE){
            $this->assertEquals($expr->getOperator(), $expectedOperator);
            $field = $expr->getField();
            $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $field);
            $this->assertEquals('lower', $field->getName());
            $this->assertEquals(1, count($field->getArguments()));

        }
        else{
            $this->assertEquals($expr->getField(), self::MEMBERNAME);
            $this->assertEquals($expr->getOperator(), $expectedOperator);
            $this->assertEquals($expr->getValue()->getValue(), $value);
        }
    }

    public function testCreateExprLike()
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue(FilterInterface::LIKE));
        $filter->expects($this->once())->method('getCriteria')->will($this->returnValue('testNotLike'));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue(true));

        /** @var Func $expr  */
        $expr = $test->createExpr(self::MEMBERNAME, $filter);

        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $expr);
        $this->assertEquals('NOT', $expr->getName());
        $this->assertEquals(1, count($expr->getArguments()));

        /** @var Comparison $like  */
        $like = $expr->getArguments();
        $like = $like[0];

        $this->assertEquals($like->getOperator(), 'LIKE');
        $this->assertEquals($like->getValue()->getValue(), 'testNotLike');

        $field = $like->getField();
        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $field);
        $this->assertEquals('lower', $field->getName());
        $this->assertEquals(1, count($field->getArguments()));

    }

    public function testCreateExprILike()
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue(FilterInterface::ILIKE));
        $filter->expects($this->once())->method('getCriteria')->will($this->returnValue('testNotiLike'));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue(true));

        /** @var Func $expr  */
        $expr = $test->createExpr(self::MEMBERNAME, $filter);

        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $expr);
        $this->assertEquals('NOT', $expr->getName());
        $this->assertEquals(1, count($expr->getArguments()));

        /** @var Comparison $like  */
        $like = $expr->getArguments();
        $like = $like[0];

        $this->assertEquals($like->getField(), self::MEMBERNAME);
        $this->assertEquals($like->getOperator(), 'ILIKE');
        $this->assertEquals($like->getValue()->getValue(), 'testNotiLike');
    }

    public function testUnknownFilterModifier()
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue('???'));
        $filter->expects($this->once())->method('getCriteria')->will($this->returnValue('testValue'));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue(false));


        $this->setExpectedException('Cannibal\\Bundle\\FilterBundle\\Filter\\Doctrine\\Exception\\ExprFactoryException');
        $expr = $test->createExpr(self::MEMBERNAME, $filter);

    }
}

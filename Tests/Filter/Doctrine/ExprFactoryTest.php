<?php
namespace Cannibal\Bundle\FilterBundle\Tests\Filter\Doctrine;

use PHPUnit_Framework_TestCase;

use Cannibal\Bundle\FilterBundle\Filter\Doctrine\ExprFactory;
use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;
use Doctrine\ORM\Query\Expr\Comparison,
    Doctrine\ORM\Query\Expr\Func,
    Doctrine\ORM\Query\Expr\Orx;

class ExprFactoryTest extends PHPUnit_Framework_TestCase
{
    const MEMBERNAME = 'm.name';
    const BINDNAME = ':bindname';

    public function getFilterMock($methods = array('getComparison', 'isNot', 'getName', 'getCriteria'))
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
            array(FilterInterface::EQ, false, '='),
            array(FilterInterface::GT, false, '>'),
            array(FilterInterface::LT, false, '<'),
            array(FilterInterface::LTE, false, '<='),
            array(FilterInterface::GTE, false, '>='),
            array(FilterInterface::IN, false, 'IN'),
            array(FilterInterface::IN, true, 'IN'),
        );
    }

    /**
     * @dataProvider dataProviderCreateExpr
     */
    public function testCreateExpr($modifierIn, $notModifier, $expectedOperator)
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue($modifierIn));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue($notModifier));

        $expr = $test->createExpr(self::MEMBERNAME, $filter, self::BINDNAME);

        if($notModifier){
            $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $expr);
            $this->assertCount(1, $expr->getArguments());
            $expr = $expr->getArguments();
            $expr = $expr[0];
        }


        if($modifierIn == FilterInterface::IN){
            $this->assertEquals(self::MEMBERNAME.' IN', $expr->getName());
            $args = $expr->getArguments();
            $this->assertEquals(self::BINDNAME, $args[0]);
        }
        else{
            $this->assertEquals($expr->getLeftExpr(), self::MEMBERNAME);
            $this->assertEquals($expr->getOperator(), $expectedOperator);
            $this->assertEquals($expr->getRightExpr(), self::BINDNAME);
        }
    }

    public function testCreateExprLike()
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue(FilterInterface::LIKE));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue(true));

        /** @var Func $expr  */
        $expr = $test->createExpr(self::MEMBERNAME, $filter, self::BINDNAME);

        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $expr);
        $this->assertCount(1, $expr->getArguments());
        $expr = $expr->getArguments();

        /** @var Comparison $expr  */
        $expr = $expr[0];

        $this->assertEquals($expr->getLeftExpr(), self::MEMBERNAME);
        $this->assertEquals($expr->getOperator(), 'LIKE');
        $this->assertEquals($expr->getRightExpr(), self::BINDNAME);

    }

    public function dataProviderNullableEQ()
    {
        return array(
            array(null),
            array(''),
        );
    }

    /**
     * @dataProvider dataProviderNullableEQ
     */
    public function testCreateExprNullableEQ($value)
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue(FilterInterface::NULLABLE_EQ));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue(false));
        $filter->expects($this->once())->method('getCriteria')->will($this->returnValue(null));

        /** @var Orx $expr  */
        $expr = $test->createExpr(self::MEMBERNAME, $filter, self::BINDNAME);

        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Orx', $expr);
        $parts = $expr->getParts();
        $this->assertCount(2, $expr->getParts());

        /** @var Comparison $expr  */
        $expr = $parts[0];

        $this->assertEquals(self::MEMBERNAME.' IS NULL', $parts[0]);

        /** @var Comparison $expr  */
        $expr = $parts[1];

        $this->assertEquals($expr->getLeftExpr(), self::MEMBERNAME);
        $this->assertEquals($expr->getOperator(), '=');
        $this->assertEquals($expr->getRightExpr(), '');
    }

    public function testCreateExprILike()
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue(FilterInterface::ILIKE));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue(true));

        /** @var Func $expr  */
        $expr = $test->createExpr(self::MEMBERNAME, $filter, self::BINDNAME);

        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $expr);
        $this->assertCount(1, $expr->getArguments());
        $expr = $expr->getArguments();

        /** @var Comparison $expr  */
        $expr = $expr[0];

        $field = $expr->getLeftExpr();
        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $field);
        $this->assertEquals('lower', $field->getName());
        $this->assertEquals(1, count($field->getArguments()));

        $this->assertEquals($expr->getOperator(), 'LIKE');

        $field = $expr->getRightExpr();
        $this->assertInstanceOf('Doctrine\\Orm\\Query\\Expr\\Func', $field);
        $this->assertEquals('lower', $field->getName());
        $this->assertEquals(1, count($field->getArguments()));


        $this->assertEquals($field->getArguments(), array(self::BINDNAME));
    }

    public function testUnknownFilterModifier()
    {
        $test = $this->getExprFactory();

        $filter = $this->getFilterMock();
        $filter->expects($this->once())->method('getComparison')->will($this->returnValue('???'));
        $filter->expects($this->once())->method('isNot')->will($this->returnValue(false));


        $this->setExpectedException('Cannibal\\Bundle\\FilterBundle\\Filter\\Doctrine\\Exception\\ExprFactoryException');
        $expr = $test->createExpr(self::MEMBERNAME, $filter, self::BINDNAME);

    }
}

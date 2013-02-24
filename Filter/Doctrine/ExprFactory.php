<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Doctrine;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface,
    Cannibal\Bundle\FilterBundle\Filter\Doctrine\ExprFactoryInterface;

use Cannibal\Bundle\FilterBundle\Filter\Doctrine\Exception\ExprFactoryException;

use Doctrine\Common\Collections\Expr\Comparison,
    Doctrine\ORM\Query\Expr\Func,
    Doctrine\ORM\Query\Expr;

class ExprFactory implements ExprFactoryInterface
{
    /**
     * @return \Doctrine\ORM\Query\Expr
     */
    public function createDoctrineExpr()
    {
        return new Expr();
    }

    /**
     * @param $memberName
     * @param $paramName
     * @param \Cannibal\Bundle\FilterBundle\Filter\FilterInterface $filter
     *
     * @return \Doctrine\Common\Collections\Expr\Comparison|\Doctrine\ORM\Query\Expr\Func|null
     *
     * @throws Exception\ExprFactoryException
     */
    public function createExpr($memberName, FilterInterface $filter, $paramName = null)
    {
        $modifier = $filter->getComparison();
        $value = $paramName != null ? sprintf(':%s', $paramName) : $filter->getCriteria();

        $isNot = $filter->isNot();

        $out = null;

        $expr = $this->createDoctrineExpr();

        switch($modifier){
            case FilterInterface::LIKE:
                $out = new Comparison(new Func('lower', array($memberName)), 'LIKE', $value);
                if ($isNot) {
                    $out = $expr->not($out);
                }
                break;

            case FilterInterface::ILIKE:
                $out = new Comparison($memberName, 'ILIKE', $value);
                if ($isNot) {
                    $out = $expr->not($out);
                }
                break;

            case FilterInterface::GT:
                $out = new Comparison($memberName, Comparison::GT, $value);
                break;
            case FilterInterface::GTE:
                $out = new Comparison($memberName, Comparison::GTE, $value);
                break;
            case FilterInterface::LT:
                $out = new Comparison($memberName, Comparison::LT, $value);
                break;
            case FilterInterface::LTE:
                $out = new Comparison($memberName, Comparison::LTE, $value);
                break;
            case FilterInterface::EQ:
            case null:
                switch($isNot){
                    case true:
                        $out = new Comparison($memberName, Comparison::NEQ, $value);
                        break;
                    case false:
                        $out = new Comparison($memberName, Comparison::EQ, $value);
                        break;
                }
                break;
            case FilterInterface::IN:
                switch($isNot){
                    case true:
                        $out = new Comparison($memberName, Comparison::NIN, $value);
                        break;
                    case false:
                        $out = new Comparison($memberName, Comparison::IN, $value);
                        break;
                }
                break;
            default:
            throw new ExprFactoryException(sprintf('Could not resolve FilterExpression expression %s', $modifier));
        }

        return $out;
    }

}

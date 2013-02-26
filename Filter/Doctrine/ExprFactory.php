<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Doctrine;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface,
    Cannibal\Bundle\FilterBundle\Filter\Doctrine\ExprFactoryInterface;

use Cannibal\Bundle\FilterBundle\Filter\Doctrine\Exception\ExprFactoryException;

use Doctrine\Common\Collections,
    Doctrine\ORM\Query\Expr,
    Doctrine\ORM\Query\Expr\Func,
    Doctrine\ORM\Query\Expr\Comparison;

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
     * @return \Doctrine\ORM\Query\Expr\Comparison|\Doctrine\ORM\Query\Expr\Func|null
     *
     * @throws Exception\ExprFactoryException
     */
    public function createExpr($memberName, FilterInterface $filter, $bindName)
    {
        $modifier = $filter->getComparison();
        $value = $bindName;

        $isNot = $filter->isNot();

        $out = null;

        $expr = $this->createDoctrineExpr();

        switch($modifier){
            case FilterInterface::LIKE:
                $out = $expr->like($memberName, $value);
                if ($isNot) {
                    $out = $expr->not($out);
                }
                break;

            case FilterInterface::ILIKE:
                $out = new Comparison(new Func('lower', array($memberName)), 'LIKE', $value);
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
                $out = $expr->in($memberName, $value);

                if($isNot){
                    $out = $expr->not($out);
                }
                break;
            default:
            throw new ExprFactoryException(sprintf('Could not resolve FilterExpression expression %s', $modifier));
        }

        return $out;
    }

}

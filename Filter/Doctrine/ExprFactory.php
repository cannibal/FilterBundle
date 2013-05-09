<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Doctrine;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface,
    Cannibal\Bundle\FilterBundle\Filter\Doctrine\ExprFactoryInterface;

use Cannibal\Bundle\FilterBundle\Filter\Doctrine\Exception\ExprFactoryException;

use Doctrine\Common\Collections,
    Doctrine\ORM\Query\Expr,
    Doctrine\ORM\Query\Expr\Func,
    Doctrine\ORM\Query\Expr\Comparison;

use Doctrine\ORM\QueryBuilder;

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

        $value = $filter->getCriteria();

        $isNot = $filter->isNot();

        $out = null;

        $expr = $this->createDoctrineExpr();

        switch($modifier){
            case FilterInterface::LIKE:
                $out = $expr->like($memberName, $bindName);
                break;
            case FilterInterface::ILIKE:
                $out = new Comparison(new Func('lower', array($memberName)), 'LIKE', new Func('lower', $bindName));
                break;
            case FilterInterface::GT:
                $out = new Comparison($memberName, Comparison::GT, $bindName);
                break;
            case FilterInterface::GTE:
                $out = new Comparison($memberName, Comparison::GTE, $bindName);
                break;
            case FilterInterface::LT:
                $out = new Comparison($memberName, Comparison::LT, $bindName);
                break;
            case FilterInterface::LTE:
                $out = new Comparison($memberName, Comparison::LTE, $bindName);
                break;
            case FilterInterface::NULLABLE_EQ:
                if(empty($value)){
                    $out = $expr->orX($expr->isNull($memberName), $expr->eq($memberName, ''));
                }
                else{
                    $out = new Comparison($memberName, Comparison::EQ, $bindName);
                }
                break;
            case FilterInterface::EQ:
                $out = new Comparison($memberName, Comparison::EQ, $bindName);
                break;
            case FilterInterface::IN:
                $out = $expr->in($memberName, $bindName);
                break;
            default:
            throw new ExprFactoryException(sprintf('Could not resolve FilterExpression expression %s', $modifier));
        }

        if($isNot){
            $out = $expr->not($out);
        }

        return $out;
    }

    public function createAndBind(QueryBuilder $builder, $memberName, FilterInterface $filter)
    {
        $bindName = sprintf(':%s',$filter->getName());

        $expr = $this->createExpr($memberName, $filter, $bindName);
        $builder->andWhere($expr)->setParameter($bindName, $filter->getCastCriteria());
    }
}

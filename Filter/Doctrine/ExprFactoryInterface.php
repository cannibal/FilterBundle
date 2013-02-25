<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Doctrine;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;

interface ExprFactoryInterface
{
    /**
     * @param $memberName
     * @param \Cannibal\Bundle\FilterBundle\Filter\FilterInterface $filter
     * @param $paramName
     *
     * @return \Doctrine\Common\Collections\Expr\Comparison|\Doctrine\ORM\Query\Expr\Func|null
     * @throws \Exception
     */
    public function createExpr($memberName, FilterInterface $filter, $paramName);
}

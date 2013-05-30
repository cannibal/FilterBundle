<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Request\Fetcher;

use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;
use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFiltersInterface;
use Cannibal\Bundle\FilterBundle\Filter\Expected\ExpectedFilterInterface;
/**
 * Class FilterFetcher
 *
 * FilterFetcher transforms the request value if present in to something that is compatible with an SF form
 */
class FilterFetcher
{
    public function fetchFilters(array $data, ExpectedFiltersInterface $expectedFilters = null)
    {
        $out = array();
        if($expectedFilters == null){
            return $out;
        }

        /** @var ExpectedFilterInterface $expectedFilter */
        foreach ($expectedFilters->getExpectedFilters() as $expectedFilter) {
            $filterName = $expectedFilter->getName();
            $type = $expectedFilter->getType();

            $notKey = sprintf('%s!', $filterName);
            if (isset($data[$filterName]) || isset($data[$notKey])) {
                $not =  isset($data[$notKey]) ? true : false;
                $filterParam = $not == true ? $data[$notKey] : $data[$filterName];

                if (is_array($filterParam) && $expectedFilter->getType() != FilterInterface::TYPE_ARRAY) {
                    //family[like]test = family like test

                    $keys = array_keys($filterParam);
                    $comparison = isset($keys[0]) ? $keys[0] : null;
                    $criteria = isset($filterParam[$comparison]) ? $filterParam[$comparison] : null;

                    $new = array(
                        'name' => $filterName,
                        'comparison' => $comparison,
                        'criteria' => $criteria,
                        'type'=>$type,

                    );

                    if($not){
                        $new['isNot'] = 'true';
                    }

                    $out['filters'][] = $new;
                }
                else {
                    //family=test = family eq test

                    $new = array(
                        'name' => $filterName,
                        'comparison' => FilterInterface::NULLABLE_EQ,
                        'criteria' => $filterParam,
                        'type'=>$type,
                    );

                    if($not){
                        $new['isNot'] = 'true';
                    }

                    $out['filters'][] = $new;
                }

            }
        }

        return $out;
    }
}
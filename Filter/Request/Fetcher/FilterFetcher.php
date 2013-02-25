<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Request\Fetcher;

/**
 * Class FilterFetcher
 *
 * FilterFetcher transforms the request value if present in to something that is compatible with an SF form
 */
class FilterFetcher
{
    public function fetchFilters(array $data, array $expectedFilters = array())
    {
        $out = array();
        foreach ($expectedFilters as $filter) {
            if (isset($data[$filter])) {
                $filterParam = $data[$filter];

                if (is_array($filterParam)) {
                    //family[like]test = family like test

                    $keys = array_keys($filterParam);
                    $comparison = isset($keys[0]) ? $keys[0] : null;
                    $criteria = isset($filterParam[$comparison]) ? $filterParam[$comparison] : null;

                    $new = array(
                        'name' => $filter,
                        'comparison' => $comparison,
                        'criteria' => $criteria
                    );

                    $out['filters'][] = $new;
                }
                else {
                    //family=test = family eq test

                    $new = array(
                        'name' => $filter,
                        'comparison' => 'eq',
                        'criteria' => $filterParam
                    );

                    $out['filters'][] = $new;
                }

            }
        }

        return $out;
    }
}
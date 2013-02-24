<?php
namespace Cannibal\Bundle\FilterBundle\Filter\Request\Fetcher;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class FilterFetcher
 *
 * FilterFetcher transforms the request value if present in to something that is compatible with an SF form
 */
class FilterFetcher
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function fetchFilters(array $expectedFilters = array())
    {
        $request = $this->getRequest();

        $query = $request->query;

        $out = array();
        foreach ($expectedFilters as $filter) {
            if ($query->has($filter)) {
                $filterParam = $query->get($filter);
                if (isset($filterParam[$filter])) {
                    $filterValues = $filterParam[$filter];
                    if (is_array($filterValues)) {
                        //family[like]test = family like test

                        $keys = array_keys($filterValues);
                        $comparison = isset($keys[0]) ? $keys[0] : null;
                        $criteria = isset($filterValues[$comparison]) ? $filterValues[$comparison] : null;

                        $new = array(
                            'name' => $filter,
                            'comparison' => $comparison,
                            'criteria' => $criteria
                        );

                        $out['filters'][] = $new;

                    } else {
                        //family=test = family eq test

                        $new = array(
                            'name' => $filter,
                            'comparison' => 'eq',
                            'criteria' => $filterValues
                        );

                        $out['filters'][] = $new;
                    }
                }
            }
        }

        return $out;
    }
}
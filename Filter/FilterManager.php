<?php
namespace Cannibal\Bundle\FilterBundle\Filter;

use Cannibal\Bundle\FilterBundle\Filter\Request\Fetcher\FilterFetcher;
use Cannibal\Bundle\FilterBundle\Forms\FilterCollectionType;
use Cannibal\Bundle\FilterBundle\Filter\Factory\FilterCollectionFactory;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;


/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 21/02/2013
 * Time: 14:31
 * To change this template use File | Settings | File Templates.
 */
class FilterManager
{
    private $request;
    private $fetcher;
    private $formFactory;
    private $filterCollectionFactory;

    public function __construct(Request $request, FormFactoryInterface $formFactory, FilterFetcher $fetcher, FilterCollectionFactory $filterFactory)
    {
        $this->fetcher = $fetcher;
        $this->request = $request;
        $this->formFactory = $formFactory;
        $this->filterCollectionFactory = $filterFactory;
    }

    public function setFilterCollectionFactory(FilterCollectionFactory $filterCollectionFactory)
    {
        $this->filterCollectionFactory = $filterCollectionFactory;
    }

    /**
     * @return \Cannibal\Bundle\FilterBundle\Filter\Factory\FilterCollectionFactory
     */
    public function getFilterCollectionFactory()
    {
        return $this->filterCollectionFactory;
    }

    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    public function setFetcher(FilterFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function getFetcher()
    {
        return $this->fetcher;
    }

    public function setRequest($request)
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

    /**
     * @return \Cannibal\Bundle\FilterBundle\Forms\FilterCollectionType
     */
    public function createFilterCollectionType()
    {
        return new FilterCollectionType();
    }


    /**
     * This function gets a filter collection from the request
     *
     * @param array $expectedFilters
     * @return \Cannibal\Bundle\FilterBundle\Filter\FilterCollectionInterface
     */
    public function getFilters(array $expectedFilters)
    {
        $fetcher = $this->getFetcher();
        $formFactory = $this->getFormFactory();

        $filters = $fetcher->fetchFilters($expectedFilters);

        $type = $this->createFilterCollectionType();

        $filterSet = $this->getFilterCollectionFactory()->createFilterCollection();

        $form = $formFactory->create($type, $filterSet);

        $form->bind($filters);

        return $form->getData();
    }
}

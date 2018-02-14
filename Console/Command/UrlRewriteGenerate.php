<?php

namespace Boraso\Toolkit\Console\Command;

use Boraso\Toolkit\Model\Abstracts\PathItems;
use Magento\Store\Model\StoreRepository;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UrlRewriteGenerate extends Command
{

    protected $storeRepository;
    protected $urlRewriteFactory;
    protected $paths;

    public function __construct(
        StoreRepository $storeRepository,
        UrlRewriteFactory $urlRewriteFactory,
        PathItems $paths
    ) {
        $this->storeRepository   = $storeRepository;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->paths      = $paths;

        $this->paths->addItems(array());

        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName('boraso:url_rewrite_generate')->setDescription('Regenerate URL rewrite of affiliates modules.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('START generiting rewrite rules');

        $output->writeln('paths: ');
        $output->writeln(print_r($this->paths->getPaths(), true));

        $stores = $this->storeRepository->getList();
        foreach ($stores as $store) {
            /** @var  $path \Boraso\Toolkit\Model\Abstracts\PathItem */
            foreach ($this->paths->getPaths() as $key => $path) {
                $urlRewriteModel      = $this->urlRewriteFactory->create();
                $urlRewriteCollection = $urlRewriteModel->getResourceCollection()
                                                        ->addFieldToFilter('request_path', strtolower($path->getRequest()))
                                                        ->addFieldToFilter('store_id', $store->getId());
                if ($urlRewriteCollection->count() == 0) {
                    $urlRewriteModel->setStoreId($store->getId());
                    $urlRewriteModel->setIsSystem(0);
                    $urlRewriteModel->setIdPath(rand(1, 100000));
                    $urlRewriteModel->setTargetPath($path->getTarget());
                    $urlRewriteModel->setRequestPath(strtolower($path->getRequest()));
                    $urlRewriteModel->save();

                    $output->writeln('item: ' . $key);
                    $output->writeln('target path: ' . $path->getTarget());
                    $output->writeln('request path: ' . $path->getRequest());
                }
            }
        }

        $output->writeln('STOP generiting rewrite rules');
    }
}
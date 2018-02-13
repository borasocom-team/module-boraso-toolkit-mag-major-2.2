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
        $name = null,
        StoreRepository $storeRepository,
        UrlRewriteFactory $urlRewriteFactory,
        PathItems $paths
    ) {
        $this->storeRepository   = $storeRepository;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->paths      = $paths;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('boraso:url_rewrite_generate')->setDescription('Regenerate URL rewrite of affiliates modules.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('START generiting rewrite rules');

        $output->writeln('paths: ');
        $output->writeln(print_r($this->paths, true));

        $stores = $this->storeRepository->getList();
        if (isset($this->targetPaths) && isset($this->requestPaths)) {
            foreach ($stores as $store) {
                /** @var  $path \Boraso\Toolkit\Model\Abstracts\PathItem */
                foreach ($this->paths->getPaths() as $key => $path) {
                    $urlRewriteModel      = $this->urlRewriteFactory->create();
                    $urlRewriteCollection = $urlRewriteModel->getResourceCollection()
                                                            ->addFieldToFilter('request_path', strtolower(__($path->getTarget())))
                                                            ->addFieldToFilter('store_id', $store->getId());
                    if ($urlRewriteCollection->count() == 0) {
                        $urlRewriteModel->setStoreId($store->getId());
                        $urlRewriteModel->setIsSystem(0);
                        $urlRewriteModel->setIdPath(rand(1, 100000));
                        $urlRewriteModel->setTargetPath($path->getRequest());
                        $urlRewriteModel->setRequestPath(strtolower(__($path->getTarget())));
                        $urlRewriteModel->save();

                        $output->writeln('item: ' . $key);
                        $output->writeln('target path: ' . $path->getTarget());
                        $output->writeln('request path: ' . $path->getRequest());
                    }
                }
            }
        }
        
        
        $output->writeln('target paths: ');
        $output->writeln(print_r($this->targetPaths, true));
        $output->writeln('request paths: ');
        $output->writeln(print_r($this->requestPaths, true));

        $stores = $this->storeRepository->getList();
        if (isset($this->targetPaths) && isset($this->requestPaths)) {
            foreach ($stores as $store) {
                foreach ($this->targetPaths as $key => $targetPath) {
                    $urlRewriteModel      = $this->urlRewriteFactory->create();
                    $urlRewriteCollection = $urlRewriteModel->getResourceCollection()
                                                            ->addFieldToFilter('request_path', strtolower(__($targetPath)))
                                                            ->addFieldToFilter('store_id', $store->getId());
                    if ($urlRewriteCollection->count() == 0 && ! empty($this->requestPaths[$key])) {
                        $urlRewriteModel->setStoreId($store->getId());
                        $urlRewriteModel->setIsSystem(0);
                        $urlRewriteModel->setIdPath(rand(1, 100000));
                        $urlRewriteModel->setTargetPath($this->requestPaths[$key]);
                        $urlRewriteModel->setRequestPath(strtolower(__($targetPath)));
                        $urlRewriteModel->save();

                        $output->writeln('item: ' . $key);
                        $output->writeln('target path: ' . $targetPath);
                        $output->writeln('request path: ' . $this->requestPaths[$key]);
                    }
                }
            }
        }

        $output->writeln('STOP generiting rewrite rules');
    }
}
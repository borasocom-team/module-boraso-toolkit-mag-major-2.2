<?php

namespace Boraso\Toolkit\Console\Command;

use Boraso\Toolkit\Logger\Logger;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SaveAllProducts extends Command
{

    protected $productFactory;
    protected $product;
    protected $logger;
    protected $state;
    protected $storeManager;

    public function __construct(
        ProductFactory $productFactory,
        Product $product,
        Logger $logger,
        State $state,
        StoreManagerInterface $storeManager
    ) {
        $this->productFactory = $productFactory;
        $this->product        = $product;
        $this->logger         = $logger;
        $this->state          = $state;
        $this->storeManager   = $storeManager;

        $this->logger->setLogFileNamePrepend('Boraso_Toolkit_SaveAllProducts');

        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName('boraso:save_all_products')->setDescription('Save all products.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $productsCollection = $this->productFactory->create()->getCollection();

        $this->state->setAreaCode('admin');
        $this->storeManager->setCurrentStore(0);

        $output->writeln(PHP_EOL . 'START updating products with special filters');
        $this->logger->info('START updating products with special filters');

        foreach ($productsCollection as $product) {
            /** @var Product $productToSave */
            $productToSave = $this->productFactory->create()->load($product->getId());
            $productToSave->setStoreId(0);
            try {
                $productToSave->save();
                $output->writeln(PHP_EOL . 'Updated product ' . $product->getSku());
                $this->logger->info('Updated product ' . $product->getSku());
            } catch (\Exception $exception) {
                $this->logger->error('Error saving product ' . $product->getSku());
                $this->logger->debug($exception->getMessage());
            }
        }

        $output->writeln(PHP_EOL . 'STOP updating products with special filters');
        $this->logger->info('Updated product ' . $product->getSku());

        $output->writeln(PHP_EOL);
    }
}
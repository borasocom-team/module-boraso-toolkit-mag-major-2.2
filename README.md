# Boraso Toolkit
Boraso Toolkit consists in a Magento2 collection of usefull tools all grouped in a single module.

This Magento2 module does nothing itself but give to every Magento2 developer a lot of usefull tools and libraries for work better and faster.

## How to install
Simply require this module on your Magento2 composer file
```bash
composer require boraso/module-toolkit
```
and then install the module as usual
```bash
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

## What you have

### Personalizable logger
Simply inject \Boraso\Toolkit\Logger\Logger class on your class and then call this method in your class constructor (or everywhere you want):
```PHP
//We assume you injected the logger class as $logger
$this->logger->setLogFileNamePrepend('Vendor_Module_SomethingElse');
```
This will create:

- Vendor_Module_SomethingElse.log
- Vendor_Module_SomethingElse_debug.log
- Vendor_Module_SomethingElse_system.log

files under var/log directory of your Magento2 installation depending on the log level you use.

### Various functions in helper class

Toolkit give you, trought injecting \Boraso\Toolkit\Helper\Data class some various usefull functions:

- getCssClassFromString: 
  - input: a string
  - output: CSS class sanitazed version of the string

### Better file handler

At the moment the Toolkit file handler is a wrapper of \Magento\Framework\Filesystem\Io\File with this functions:

- archive: 

  - input: file full path to archive and optional directory path where you want to create the file archive
  - output: archived file full path
  - what do: this function archive a file creating a directory with file same name and moving the file to archive inside with date and hour renamed

  ​
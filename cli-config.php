<?php

/** @var $EntityManager \Doctrine\ORM\EntityManager */

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Classes\Helper\EntityManagerFactory;

// Autoload do Projeto
require_once __DIR__ . '/vendor/autoload.php';


// Gerenciador de Entidades
$EntityManagerFactory = new EntityManagerFactory();
$EntityManager = $EntityManagerFactory->getEntityManager();


return ConsoleRunner::createHelperSet($EntityManager);

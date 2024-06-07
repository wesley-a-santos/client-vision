<?php

namespace Classes\Helper;

/**
 * Description of EntityManagerFactory
 *
 * @author c128454
 */
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

class EntityManagerExcel {

    /**
     * @return EntityManagerInterface
     * @throws \Doctrine\ORM\ORMException
     */
    public function getEntityManager(): EntityManagerInterface {
        $DiretorioRaiz = __DIR__ . '/../..';

        $Configuracao = Setup::createAnnotationMetadataConfiguration(
                        [$DiretorioRaiz . '/Classes'],
                        true
        );

        $Conexao = [
            'driver' => 'pdo_sqlsrv',
            'host' => 'bdd5517wn001',
            'dbname' => 'Externo',
            'user' => 'usrExterno',
            'password' => '3zt3rn0',
        ];

        return EntityManager::create($Conexao, $Configuracao);
    }

}
<?php

namespace Classes\Helper;

/**
 * Description of EntityManagerFactory
 *
 * @author c068442
 */
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory {

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
            'host' => SERVIDOR,
            'dbname' => BASE,
            'user' => USUARIO,
            'password' => SENHA,
        ];

        return EntityManager::create($Conexao, $Configuracao);
    }

}

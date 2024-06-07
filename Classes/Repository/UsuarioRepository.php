<?php

/*
 * Copyright (C) 2020 Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Classes\Repository;

use Classes\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Description of UsuarioRepository
 *
 * @author Wesley Alves da Silva Santos <wesley.a.santos@caixa.gov.br em http://www.cemob.sp.caixa/>
 */
class UsuarioRepository extends EntityRepository {

    private $Usuario;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->Usuario = Usuario::class;
    }

    public function cadastrarUsuario(array $Usuario): ?Usuario
    {

        $EntityUsuario = $this->pesquisarUsuario($Usuario['CodigoUsuario']);

        if (!is_null($EntityUsuario)) {
            
            return $this->atualizarUsuario($Usuario, $EntityUsuario);
            
        }
        
            $EntityUsuario = new Usuario();

            $EntityUsuario->setCodigoUsuario($Usuario['CodigoUsuario'])
                    ->setNome($Usuario['Nome'])
                    ->setEmail($Usuario['Email'])
                    ->setFuncao($Usuario['Funcao'])
                    ->setUnidadeCaixa($Usuario['UnidadeCaixa']);

            $this->getEntityManager()->persist($EntityUsuario);
            $this->getEntityManager()->flush();
        

        return $EntityUsuario;
    }

    public function pesquisarUsuario(string $CodigoUsuario): ?Usuario
    {

        return $this->getEntityManager()
                ->getRepository($this->Usuario)
                ->findOneBy(['CodigoUsuario' => $CodigoUsuario]);

    }
    
    
    public function atualizarUsuario(array $Usuario, Usuario $EntityUsuario): ?Usuario
    {

            $EntityUsuario->setCodigoUsuario($Usuario['CodigoUsuario'])
                    ->setNome($Usuario['Nome'])
                    ->setEmail($Usuario['Email'])
                    ->setFuncao($Usuario['Funcao'])
                    ->setUnidadeCaixa($Usuario['UnidadeCaixa']);

            $this->getEntityManager()->flush();

        return $EntityUsuario;
    }
    

}

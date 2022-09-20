<?php

namespace App\Repository\Common;

use App\Entity\Common\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }
    
    /**
     * Get all except English.
     */
    public function exceptEnglish()
    {
        
        return $this->createQueryBuilder('l')
            ->where('l.lang_code != :code')
            ->setParameter(':code', 'en')
            ->getQuery()
            ->execute();
        
    }
    
}

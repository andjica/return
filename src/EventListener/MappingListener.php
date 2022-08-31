<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Replaces a 'common' schema parameter with the common database name. 
 */

class MappingListener {
    
    private ParameterBagInterface $params;
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {

        if($this->params->has('common_data')) {
            
            $commonData = $this->params->get('common_data');
            
            if(isset($commonData['path']) && !empty($commonData['path'])) {
                
                $commonDbName = $commonData['path'];
                
                $classMetadata = $eventArgs->getClassMetadata();
                
                $table = $classMetadata->table;
                
                if(isset($table['schema']) && $table['schema'] == 'common') {
                    
                    $table['schema'] = $commonDbName;
                    
                    $classMetadata->setPrimaryTable($table);
                    
                }
                
                $associationTables = $classMetadata->getAssociationMappings();
                
                if(!empty($associationTables)) {
                    
                    foreach($associationTables as $table) {
                        
                        if(isset($table['joinTable']) && isset($table['joinTable']['schema']) && $table['joinTable']['schema'] == 'common') {
                            $classMetadata->associationMappings[$table['fieldName']]['joinTable']['schema'] = $commonDbName;
                        }
                        
                    }

                }
            
            }
            
        }

        if($this->params->has('reseller_data')) {

            $resellerData = $this->params->get('reseller_data');

            if(isset($resellerData['path']) && !empty($resellerData['path'])) {

                $resellerDbName = $resellerData['path'];

                $classMetadata = $eventArgs->getClassMetadata();

                $table = $classMetadata->table;

                if(isset($table['schema']) && $table['schema'] == 'reseller') {

                    $table['schema'] = $resellerDbName;

                    $classMetadata->setPrimaryTable($table);

                }

                $associationTables = $classMetadata->getAssociationMappings();

                if(!empty($associationTables)) {

                    foreach($associationTables as $table) {

                        if(isset($table['joinTable']) && isset($table['joinTable']['schema']) && $table['joinTable']['schema'] == 'reseller') {
                            $classMetadata->associationMappings[$table['fieldName']]['joinTable']['schema'] = $resellerDbName;
                        }

                    }

                }

            }

        }
        
    }
    
}

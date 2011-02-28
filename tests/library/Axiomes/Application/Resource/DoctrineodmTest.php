<?php
/**
 * 
 */
namespace AxiomesTest\Application\Resource;
use \Axiomes\Application\Resource\Doctrineodm as OdmResource;

class DoctrineodmTest extends \PHPUnit_Framework_TestCase{


    public function testIsDmIsNullByDefault(){
        $resource = new OdmResource();
        $this->assertNull($resource->getDocumentManager());
    }

    public function testConnectionSettingsLoading(){
        $params = array(
            'connection' => array(
                'server' => 'testserver',
                'options' => array(
                    'option_1' => 'value_1',
                    'option_2' => 'value_2'
                )
            )
        );

        $resource = new OdmResource($params);
        $this->assertSame($resource->getConnection(), $params['connection']);
    }

    public function testConnectionInstance(){

        $resource = new OdmResource();
        $this->assertInstanceOf('Doctrine\MongoDB\Connection', $resource->getConnectionInstance());
    }

    public function testConfigurationSettingsLoading(){
        $params = array(
            'configuration' => array(
                'proxyNamespace' => 'Proxies'
            )
        );

        $resource = new OdmResource($params);
        $this->assertSame($resource->getConfiguration(), $params['configuration']);
    }

     public function testConfigurationInstanceEmptySettings(){
        $resource = new OdmResource();
        $this->assertInstanceOf('Doctrine\ODM\MongoDB\Configuration', $resource->getConfigurationInstance());
     }

     public function testBadConfigurationOptionsThrowsException(){

         $this->setExpectedException('Axiomes\Application\Resource\Exception');
         $params = array(
             'configuration' => array(
                 'stupidOption' => 'stubit value'
             )
         );
         $resource = new OdmResource($params);
         $resource->getConfigurationInstance();
     }

     public function testConfigurationSettingsAreApplied(){
         //all options except metadata driver and cache
         $params = array(
             'configuration' => array(
                 'proxyNamespace' => 'CustomProxiesNS',
                 'proxyDir' => '/proxy_dir',
                 'autoGenerateProxyClasses' => 1,
                 'hydratorNamespace' => 'CustomHydratorsNS',
                 'hydratorDir' => '/hydrator_dir',
                 'autoGenerateHydratorClasses' => 1,
                 'defaultDB' => 'customDefaultDb',
                 'environment' => 'customEnvironnement',
                 'databasePrefix' => 'customPrefix',
                 'databaseSuffix' => 'customSuffix',
                 'classMetadataFactoryName' => 'CustomMetadataFactory'
             )
         );

         $resource = new OdmResource($params);
         $config = $resource->getConfigurationInstance();

         $this->assertEquals($config->getProxyNamespace(), $params['configuration']['proxyNamespace']);
         $this->assertEquals($config->getProxyDir(), $params['configuration']['proxyDir']);
         $this->assertEquals($config->getHydratorNamespace(), $params['configuration']['hydratorNamespace']);
         $this->assertEquals($config->getHydratorDir(), $params['configuration']['hydratorDir']);
         $this->assertEquals($config->getAutoGenerateProxyClasses(), $params['configuration']['autoGenerateProxyClasses']);
         $this->assertEquals($config->getAutoGenerateHydratorClasses(), $params['configuration']['autoGenerateHydratorClasses']);
         $this->assertEquals($config->getDefaultDB(), $params['configuration']['defaultDB']);
         $this->assertEquals($config->getEnvironment(), $params['configuration']['environment']);
         $this->assertEquals($config->getDatabasePrefix(), $params['configuration']['databasePrefix']);
         $this->assertEquals($config->getDatabaseSuffix(), $params['configuration']['databaseSuffix']);
         $this->assertEquals($config->getClassMetadataFactoryName(), $params['configuration']['classMetadataFactoryName']);

     }
    public function testConfigurationAnnotationMetadataDriver(){
        $params = array(
            'configuration' => array(
                'metadataDriverImpl' => array(
                    'type' => 'annotation',
                    'readerParams' => array(
                        'defaultAnnotationNamespace' => 'Doctrine\ODM\MongoDB\Mapping\\'
                    ),
                    'path' => array(
                        '/path1',
                        '/path2'
                    )
                )
            )
        );

        $resource = new OdmResource($params);
        $config = $resource->getConfigurationInstance();
        $driver = $config->getMetadataDriverImpl();

        $this->assertInstanceof('Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',$driver);
        $this->assertSame($driver->getPaths(),array('/path1','/path2'));
    }
    public function testConfigurationXmlMetadataDriver(){
        $params = array(
            'configuration' => array(
                'metadataDriverImpl' => array(
                    'type' => 'xml',
                    'path' => array(
                        '/path1',
                        '/path2'
                    ),
                    'params' => array(
                        'fileExtension' => '.custom.extension'
                    )
                )
            )
        );

        $resource = new OdmResource($params);
        $config = $resource->getConfigurationInstance();
        $driver = $config->getMetadataDriverImpl();

        $this->assertInstanceof('Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver',$driver);
        $this->assertSame($driver->getPaths(),array('/path1','/path2'));
        $this->assertSame($driver->getFileExtension(),'.custom.extension');
    }
     public function testConfigurationYamlMetadataDriver(){
        $params = array(
            'configuration' => array(
                'metadataDriverImpl' => array(
                    'type' => 'yaml',
                    'path' => array(
                        '/path1',
                        '/path2'
                    ),
                    'params' => array(
                        'FileExtension' => '.custom.extension'
                    )
                )
            )
        );

        $resource = new OdmResource($params);
        $config = $resource->getConfigurationInstance();
        $driver = $config->getMetadataDriverImpl();

        $this->assertInstanceof('Doctrine\ODM\MongoDB\Mapping\Driver\YamlDriver',$driver);
        $this->assertSame($driver->getPaths(),array('/path1','/path2'));
        $this->assertSame($driver->getFileExtension(),'.custom.extension');
     }

     public function testConfigurationChainMetadataDriver(){
        $params = array(
            'configuration' => array(
                'metadataDriverImpl' => array(
                    'type' => 'chain',
                    'drivers' => array(
                        'ns1' => array('type' => 'annotation'),
                        'ns2' => array('type' => 'xml'),
                        'ns3' => array('type' => 'yaml')
                    )
                )
            )
        );

        $resource = new OdmResource($params);
        $config = $resource->getConfigurationInstance();
        $driver = $config->getMetadataDriverImpl();
        $drivers = $driver->getDrivers();

        $this->assertInstanceof('Doctrine\ODM\MongoDB\Mapping\Driver\DriverChain',$driver);
        $this->assertInstanceof('Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',$drivers['ns1']);
        $this->assertInstanceof('Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver',$drivers['ns2']);
        $this->assertInstanceof('Doctrine\ODM\MongoDB\Mapping\Driver\YamlDriver',$drivers['ns3']);
     }

    public function testConfigurationMetadataCacheFromCachemanager(){
        $config = array(
            'pluginpaths' => array(
                'Axiomes\Application\Resource\\' => BASE_PATH.'/library/Axiomes/Application/Resource'
            ),
            'resources' => array(
                'CacheManager' => array(
                    'metadata' => array(
                        'frontend' => array(
                            'name' => 'Axiomes_Cache_DoctrineCompatible',
                            'customFrontendNaming' => true,
                            'options' => array(
                                'lifetime' => 120,
                                'automatic_serialization' => true
                            )
                        ),
                        'backend' => array(
                            'name' => 'Test'
                        ),
                        'frontendBackendAutoload' => true
                    )
                )
            )
        );

        $application = new \Zend_Application(
            APPLICATION_ENV,
            $config
        );
        $application->bootstrap();
        
        $params = array(
            'bootstrap' => $application->getBootstrap(),
            'configuration' => array(
                'metadataCacheImpl' => 'metadata'
            )
        );

        $resource = new OdmResource($params);
        $config = $resource->getConfigurationInstance();

        $cache = $config->getMetadataCacheImpl();

        $this->assertInstanceof('Doctrine\Common\Cache\Cache', $cache);
    }

    public function testConfigurationMetadataCacheImplZendCacheFactory(){
        $params = array(
            'configuration' => array(
                'metadataCacheImpl' => array(
                    'frontend' => array(
                        'name' => 'Axiomes_Cache_DoctrineCompatible',
                        'customFrontendNaming' => true,
                    ),
                    'backend' => array(
                        'name' => 'Test'
                    )
                )
            )
        );

        $resource = new OdmResource($params);
        $config = $resource->getConfigurationInstance();
        $cache = $config->getMetadataCacheImpl();

        $this->assertInstanceof('Doctrine\Common\Cache\Cache', $cache);
        $this->assertInstanceof('Axiomes_Cache_DoctrineCompatible', $cache);
    }
}

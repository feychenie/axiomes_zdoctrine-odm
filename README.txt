Zend_Application Resource plugin for Doctrine ODM
___________________________________________________

Configuration directives in application.ini:

(look at doctrine ODM documentation)

//dont forget to specify the custom pluginpath(s) before resources definition
pluginpaths.Axiomes\Application\Resource\ = APPLICATION_PATH "/../library/Axiomes/Application/Resource/"

//You can optionally configure the mongoDb connection
resources.doctrineodm.connection.server = ..
resources.doctrineodm.connection.options.connect = false
--other options are explained on php.net's MongoDB documentation ---

// Document manager configuration
// These directives are strictly the same as in procedural configuration (and have no default value) :
resources.doctrineodm.configuration.proxyNamespace
resources.doctrineodm.configuration.proxyDir
resources.doctrineodm.configuration.autoGenerateProxyClasses
resources.doctrineodm.configuration.hydratorNamespace
resources.doctrineodm.configuration.hydratorDir
resources.doctrineodm.configuration.autoGenerateHydratorClasses
resources.doctrineodm.configuration.defaultDB
resources.doctrineodm.configuration.databasePrefix //optional
resources.doctrineodm.configuration.databaseSuffix //optional
resources.doctrineodm.configuration.environment //optional
resources.doctrineodm.configuration.classMetadataFactoryName //optional


// These have special definition rules :
=> configuration.metadataDriverImpl

annotation :
resources.doctrineodm.configuration.metadataDriverImpl.type = "annotation"
resources.doctrineodm.configuration.metadataDriverImpl.readerParams.defaultAnnotationNamespace = "Doctrine\ODM\MongoDB\Mapping\"
resources.doctrineodm.configuration.metadataDriverImpl.path.1 = "/path/to/documents" //optional if you have nice autoloader
resources.doctrineodm.configuration.metadataDriverImpl.path.2 = "/other/path/to/documents" //optional if you have nice autoloader

xml or yaml:
resources.doctrineodm.configuration.metadataDriverImpl.type = "xml" //or yaml
resources.doctrineodm.configuration.metadataDriverImpl.path.1 = "/path/to/mappings"
resources.doctrineodm.configuration.metadataDriverImpl.path.2 = "/other/path/to/mappings"
resources.doctrineodm.configuration.metadataDriverImpl.param.fileExtension = ".my.extension" //if you want to overrid defaults

chain :
resources.doctrineodm.configuration.metadataDriverImpl.type = "chain"
resources.doctrineodm.configuration.metadataDriverImpl.drivers.my_namespace.type = //one of the above drivers types
resources.doctrineodm.configuration.metadataDriverImpl.drivers.my_namespace.path = //just configure it as you would do it above
resources.doctrineodm.configuration.metadataDriverImpl.drivers.my_second_namespace.type = //and add other drivers...

=> configuration.metadataCacheImpl

You can use a cache from your cachemanager plugin, or build your own cache instance.
But it must implement \Doctrine\common\Cache\Cache interface.
I provide \Axiomes_Cache_DoctrineCompatible which extends Zend_Cache_Core, but you can use your own implementation of course

//if you have a cachemanager :
resources.cacheManager.metadata.frontend.name = "Axiomes_Cache_DoctrineCompatible"
resources.cacheManager.metadata.frontend.customBackendNaming = true
--other frontend options and backend options--
resources.doctrineodm.configuration.metadataCacheImpl = "myCacheManagerEntry"

//or you can build directly your own instance :
resources.doctrineodm.configuration.metadataCacheImpl.frontend.name = "Axiomes_Cache_DoctrineCompatible"
resources.doctrineodm.configuration.metadataCacheImpl.customBackendNaming = true
--other frontend options and backend options--

NOTES
______

If you have a look at the demo app, you can see that its easy to have documents in modules, you have just to configure the resource autoloader in the module's bootstrap.
If you use the annotation driver, you don't have to specify the documents path(s) in application.ini, as long as your autoloading is well configured.

I hope my test are covering all configuration options

Feel free to fork, pulls are welcome

I didn't implement the event manager nor the logger, i will try to to it soon
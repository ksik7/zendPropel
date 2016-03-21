<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    const CONFIG_KEY = 'appConfig';

    protected function _initAutoload()
    {

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Dfi_');
        $autoloader->registerNamespace('application_');
        $autoloader->registerNamespace('EasyBib_');
        $autoloader->registerNamespace('Ks_');


        $this->_acl = new Ks_LibraryAcl_LibraryAcl();
        $this->_auth  = Zend_Auth::getInstance();
        $fc =  Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Ks_Plugin_AccessPlugin($this->_acl, $this->_auth));

    }

    protected function _initEnvironment()
    {

        date_default_timezone_set('Europe/Warsaw');
        setlocale(LC_ALL, 'pl_PL.utf8');


        $constPath = APPLICATION_PATH . '/configs/constants.php';

        if (Zend_Loader::isReadable($constPath)) {
            include_once $constPath;
        } else {
            throw new Exception('app config read failed');
        }
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            gc_enable();
        }
    }

    protected function _initLoggers()
    {

        try {
            $files = array(
                _APP_LOG_FILE,
                _AD_LOG_FILE,
                _ERROR_LOG_FILE,
                _DEBUG_LOG_FILE,
                _SHUTDOWN_LOG_FILE,
                _PROPEL_LOG_FILE
            );
            foreach ($files as $file) {
                if (!file_exists($file)) {
                    file_put_contents($file, '');
                    chmod($file, 0666);
                }
            }

            $writer = new Zend_Log_Writer_Stream(_APP_LOG_FILE);
            $logger = new Zend_Log($writer);
            Zend_Registry::set('appLogger', $logger);

            $writerAD = new Zend_Log_Writer_Stream(_AD_LOG_FILE);
            $loggerAD = new Zend_Log($writerAD);
            Zend_Registry::set('ADLogger', $loggerAD);

            $writerError = new Zend_Log_Writer_Stream(_ERROR_LOG_FILE);
            $loggerError = new Zend_Log($writerError);
            Zend_Registry::set('errorLogger', $loggerError);

            $writerDebug = new Zend_Log_Writer_Stream(_DEBUG_LOG_FILE);
            $loggerDebug = new Zend_Log($writerDebug);
            Zend_Registry::set('debugLogger', $loggerDebug);

            $writerShutDown = new Zend_Log_Writer_Stream(_SHUTDOWN_LOG_FILE);
            $loggerShutDown = new Zend_Log($writerShutDown);
            Zend_Registry::set('shutdownLogger', $loggerShutDown);

            $writerPropel = new Zend_Log_Writer_Stream(_PROPEL_LOG_FILE);
            $loggerPropel = new Zend_Log($writerPropel);
            Zend_Registry::set('propelLogger', $loggerPropel);


        } catch (Exception $e) {
            $logFiles = array(_APP_LOG_FILE, _ERROR_LOG_FILE, _DEBUG_LOG_FILE, _SHUTDOWN_LOG_FILE, _PROPEL_LOG_FILE);
            foreach ($logFiles as $logFile) {
                if (!Dfi_File::isWriteable($logFile)) {
                    throw new Exception('file ' . $logFile . ' is not readeable');
                }
            }
        }
    }

    protected function _initErrorsHandlers()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('log_errors', 1);
        ini_set('display_errors', 0);
        ini_set('error_log', _LOG_PATH . 'php.errors.log');

        set_error_handler(array('Dfi_Error_Handler', 'errorHandler'), E_ALL);
        register_shutdown_function(array('Dfi_Error_Handler', 'shutdown'));

        if (Dfi_App_Config::get('main.showDebug')) {
            Zend_Controller_Front::getInstance()->throwExceptions(true);
            ini_set('display_errors', 1);
        }


        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Dfi_Controller_Plugin_Error());

    }

    protected function _initConfig()
    {
        $options = $this->getApplication()->getOptions();
        $config = new Zend_Config($options);
        Zend_Registry::set(self::CONFIG_KEY, $config);

    }


    protected function _initDatabase()
    {
        if (Zend_Loader::isReadable(Dfi_App_Config::get('db.config'))) {

            try {
                $logger = Zend_Registry::get('propelLogger');
                $adapter = new Dfi_Log_Adapter_Propel2Zend($logger);

                /** @noinspection PhpIncludeInspection */
                require_once 'Propel.php';

                Propel::setLogger($adapter);
                Propel::init(Dfi_App_Config::get('db.config'));
            } catch (Exception $e) {
                throw new Exception('Can\'t setup database: ' . $e->getMessage());
            }
        } else {
            throw new Exception('database config read failed');
        }

    }


    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Dfi_Controller_Action_Helper');

        Zend_Controller_Action_HelperBroker::addHelper(Dfi_Controller_Action_Helper_Messages::getInstance());
        Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
    }

    protected function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $file = APPLICATION_PATH . '/configs/routes.ini';

        if (Zend_Loader::isReadable($file)) {
            $config = new Zend_Config_Ini($file, 'production');

            if ($config->count()) {
                $router->addConfig($config, 'routes');
            }
        }
    }

    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('HTML5');


        $view->addHelperPath(array(_BASE_PATH . 'library/Dfi/View/Helper', 'Dfi/View/Helper/'), 'Dfi_View_Helper_');
        $view->addBasePath(APPLICATION_PATH . '/layouts/partials/');


        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        //Zend_Form::setDefaultTranslator(new Zend_Translate_Adapter_Array(include(APPLICATION_PATH . '/configs/messages/validation.php'), 'pl'));

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    protected function _initSidebar()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');

        $view->placeholder('sidebar')
            // "prefix" -> markup to emit once before all items in collection
            ->setPrefix("<div class=\"sidebar\">\n    <div class=\"block\">\n")
            // "separator" -> markup to emit between items in a collection
            ->setSeparator("</div>\n    <div class=\"block\">\n")
            // "postfix" -> markup to emit once after all items in a collection
            ->setPostfix("</div>\n</div>");
    }

}
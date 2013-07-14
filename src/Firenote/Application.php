<?php

namespace Firenote;

use Silex\Provider\MonologServiceProvider;

use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class Application extends \Silex\Application
{
    private
        $configuration;
    
    public function __construct($configurationFile)
    {
        parent::__construct();

        $this['startTime'] = microtime(true);
        $this['logs.path'] = __DIR__ . '/../../logs/';
        $this['cache.path'] = __DIR__ . '/../../cache/';
        
        $this->loadConfiguration($configurationFile);
        $this->initializeDatabase();
        $this->initializeBuiltInServices();
        $this->initializeTemplateEngine();
        $this->initializeSpecificServices();
    }
    
    private function loadConfiguration($configurationFile)
    {
        if(! is_file($configurationFile))
        {
            throw new \Exception("Configuration not found at location [$configurationFile]");
        }
        
        $this->configuration = Yaml::parse($configurationFile);
    }
    
    private function initializeDatabase()
    {
        if(! isset($this->configuration['db']['user'])
        || ! isset($this->configuration['db']['password']))
        {
            throw new \Exception('Missing database configuration (expecting db/user and db/password');
        }
        
        $this->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_mysql',
                'host'     => 'localhost',
                'dbname'   => 'ludo',
                'user'     => $this->configuration['db']['user'],
                'password' => $this->configuration['db']['password'],
                'charset'  => 'utf8'
            ),
        ));
    }
    
    private function initializeBuiltInServices()
    {
        $this->register(new MonologServiceProvider(), array(
            'monolog.logfile' => $this['logs.path'] . 'app.log',
        ));
        $this->register(new ServiceControllerServiceProvider());
        $this->register(new UrlGeneratorServiceProvider());
        $this->register(new FormServiceProvider());
        $this->register(new ValidatorServiceProvider());
        $this->register(new SessionServiceProvider());
        $this->register(new SecurityServiceProvider(), array(
            'security.firewalls' => $this->configureACL(),
            'security.access_rules' => array(
                array('^.*$', 'ROLE_ADMIN'),
            ),
        ));
        $this->register(new RememberMeServiceProvider());
    }
    
    private function configureACL()
    {
        $app = $this;
        $this->get('/login', function(Request $request) use ($app) {
            return $app['twig']->render('pages/login.twig', array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));
        });
        
        return array(
            'login' => array(
                'pattern' => '^/login$',
            ),
            'secured' => array(
                'pattern' => '^.*$',
                'form' => array(
                    'login_path' => '/login',
                    'check_path' => '/admin/login_check'
                ),
                'logout' => array('logout_path' => '/logout'),
                'remember_me' => array(),
                'users' => $this->share(function() use($app){
                    return new UserProvider($app['db']);
                }),
            ),
        );
    }
    
    private function initializeTemplateEngine()
    {
        $this->register(new TwigServiceProvider(), array(
            'twig.path'    => array(__DIR__ . '/../../views'),
            'twig.options' => array('cache' => $this['cache.path'] . 'twig'),
        ));
    }
    
    private function initializeSpecificServices()
    {
        $app = $this;
        
        $this['searchEngine'] = function() use($app) {
            return new Search\Engine($app['db'], $app['games']);
        };
        
        $this['games'] = function() use($app) {
            return new Model\Games($app['db'], $app->configuration['domain']);
        };
        
    }
    
    public function enableDebug()
    {
        $this['debug'] = true;
        
        $this->register($p = new WebProfilerServiceProvider(), array(
            'profiler.cache_dir' => $this['cache.path'] . 'profiler',
        ));
        
        $this->mount('/_profiler', $p);
        
        return $this;
    }
    
    public function enableProfiling()
    {
        $startTime = $this['startTime'];
        
        $this->after(function (Request $request, Response $response) use($startTime){
            $response->headers->set('X-Generation-Time', microtime(true) - $startTime);
        });
        
        return $this;
    }
}

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
    
    public function __construct(Configuration $configuration)
    {
        parent::__construct();
        
        $this['startTime'] = microtime(true);
        
        $rootDir = __DIR__ . '/../../';
        $this['logs.path']  = $rootDir . 'logs/';
        $this['cache.path'] = $rootDir . 'cache/';
        
        $this->configuration = $configuration;
        
        $this->initializeDatabase();
        $this->initializeBuiltInServices();
        $this->initializeTemplateEngine();
        $this->initializeApplicationServices();
    }
    
    private function initializeDatabase()
    {
        $this->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => $this->configuration->read('db/server/driver', 'pdo_mysql'),
                'host'     => $this->configuration->read('db/server/host', 'localhost'),
                'port'     => $this->configuration->read('db/server/port', 3306),
                'dbname'   => $this->configuration->readRequired('db/server/database'),
                'user'     => $this->configuration->readRequired('db/server/user'),
                'password' => $this->configuration->readRequired('db/server/password'),
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
            'security.firewalls' => $this->getSecurityFirewalls(),
            'security.access_rules' => $this->getAccessRules(),
        ));
        $this->register(new RememberMeServiceProvider());
    }
    
    private function getSecurityFirewalls()
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
                'pattern' => '^/admin/',
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
    
    private function getAccessRules()
    {
        return array(
            array('^/admin/', 'ROLE_ADMIN'),
        );
    }
    
    private function initializeTemplateEngine()
    {
        $this->register(new TwigServiceProvider(), array(
            'twig.path'    => array(__DIR__ . '/../../views'),
            'twig.options' => array('cache' => $this['cache.path'] . 'twig'),
        ));
    }
    
    protected function initializeApplicationServices()
    {
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
    
    public function mountProviders()
    {
        $this->mount('/', new Controllers\Admin\Provider());
    }
}

<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\ModuleManager\ModuleManager;
use Locale;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    // The "init" method is called on application start-up and
    // allows to register an event listener.
    public function init(ModuleManager $manager)
    {
        // Get event manager.
        $eventManager = $manager->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(__NAMESPACE__, 'dispatch',
            [$this, 'onDispatch'], 100);
    }

    // Event listener method.
    public function onDispatch(MvcEvent $event)
    {
        $default   = 'ru';
        $supported = array('en', 'ru', 'de');

        $request = $event->getRequest();

        //retrieve GET parameter
        $lang = $request->getQuery('lang', null);

        if (!!($match = Locale::lookup($supported, $lang))) {
            // The locale is one of our supported list
            $locale = $match;
        } else {
            // Nothing from the supported list is a match
            $locale = $default;
        }

        Locale::setDefault(Locale::canonicalize($locale));
    }
}

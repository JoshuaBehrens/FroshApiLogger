<?php

namespace FroshApiLogger\Subscribers;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;
use Enlight_Event_EventArgs;
use Shopware\Components\Logger;
use Shopware_Components_Auth;

class RestApi implements SubscriberInterface
{
    /** @var Logger */
    private $logger;

    /** @var Shopware_Components_Auth */
    private $auth;

    private $logRestApi = false;

    public function __construct(Logger $logger, Shopware_Components_Auth $auth, $logRestApi)
    {
        $this->logger = $logger;
        $this->auth = $auth;
        $this->logRestApi = $logRestApi;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Api' => [['onPreDispatchApi', PHP_INT_MIN]],
        ];
    }

    public function onPreDispatchApi(Enlight_Event_EventArgs $args)
    {
        if (!$this->logRestApi) {
            return;
        }

        /** @var Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $action = $controller->Request()->getActionName();

        if ($action === 'post' || $action === 'put' || $action === 'delete') {
            $this->logger->info('{body}', [
                'user' => $this->auth->getIdentity()->username,
                'method' => strtoupper($action),
                'uri' => $controller->Request()->getRequestUri(),
                'http' => $this->getHttpVersion(),
                'headers' => $this->getAllHeaders(),
                'body' => $controller->Request()->getRawBody(),
            ]);
        }
    }

    protected function getAllHeaders()
    {
        // TODO might not work with ppm. Need this information from the request but Enlight Request hasn't

        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $keyWithoutPrefix = substr($key, 5);
                $normalizedKey = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower($keyWithoutPrefix))));
                $headers[] = $normalizedKey . ': ' . $value;
            }
        }

        return implode(PHP_EOL, $headers);
    }

    protected function getHttpVersion()
    {
        // TODO might not work with ppm. Need this information from the request but Enlight Request hasn't

        return $_SERVER['SERVER_PROTOCOL'];
    }
}

<?php declare(strict_types=1);

namespace FroshApiLogger\Subscribers;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;
use Enlight_Event_EventArgs;
use FroshApiLogger\Interfaces\RuleInterface;
use FroshApiLogger\Services\RuleEvaluationService;
use FroshApiLogger\Services\RuleRepository;
use Shopware\Components\Logger;
use Shopware_Components_Auth;

class RestApi implements SubscriberInterface
{
    /** @var Logger */
    private $logger;

    /** @var Shopware_Components_Auth */
    private $auth;

    private $logRestApi = false;

    /** @var RuleRepository */
    private $rules;

    /** @var RuleEvaluationService */
    private $eval;

    public function __construct(
        Logger $logger,
        Shopware_Components_Auth $auth,
        bool $logRestApi,
        RuleRepository $rules,
        RuleEvaluationService $eval
    ) {
        $this->logger = $logger;
        $this->auth = $auth;
        $this->logRestApi = $logRestApi;
        $this->rules = $rules;
        $this->eval = $eval;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Api' => [['onPreDispatchApi', PHP_INT_MIN]],
        ];
    }

    public function onPreDispatchApi(Enlight_Event_EventArgs $args): void
    {
        if (!$this->logRestApi) {
            return;
        }

        /** @var Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $action = $controller->Request()->getActionName();

        $rule = $this->getAllowedRule($controller);

        if (is_null($rule)) {
            return;
        }

        if ($action === 'post' || $action === 'put' || $action === 'delete') {
            $this->logger->info('{body}', [
                'ruleId' => $rule->getId(),
                'user' => $this->auth->getIdentity()->username,
                'method' => strtoupper($action),
                'uri' => $controller->Request()->getRequestUri(),
                'http' => $this->getHttpVersion(),
                'headers' => $this->getAllHeaders(),
                'body' => $controller->Request()->getRawBody(),
            ]);
        }
    }

    protected function getAllHeaders(): string
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

    protected function getHttpVersion(): string
    {
        // TODO might not work with ppm. Need this information from the request but Enlight Request hasn't

        return strval($_SERVER['SERVER_PROTOCOL']);
    }

    protected function getAllowedRule(Enlight_Controller_Action $controller): ?RuleInterface
    {
        foreach ($this->rules->listActiveIds() as $ruleId) {
            $rule = $this->rules->read($ruleId);

            if ($this->eval->evaluate($rule, $controller->Request()) === true) {
                return $rule;
            }
        }

        return null;
    }
}

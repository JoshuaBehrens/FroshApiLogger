<?php declare(strict_types=1);

namespace FroshApiLogger\Services;

use Shopware\Components\DependencyInjection\Container;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Models\Shop\Shop;

/**
 * @property $logRestApi bool
 */
class Configuration
{
    /** @var ConfigReader */
    private $configReader;

    private $pluginName = '';

    /** @var Shop|null */
    private $shop;

    /** @var array|null */
    private $config = null;

    public function __construct(ConfigReader $configReader, string $pluginName, Shop $shop = null)
    {
        $this->configReader = $configReader;
        $this->pluginName = $pluginName;
        $this->shop = $shop;
    }

    public function __get($name)
    {
        $this->config = $this->config ?: $this->configReader->getByPluginName($this->pluginName, $this->shop);
        $name = Container::underscore($name);
        return array_key_exists($name, $this->config) ? $this->config[$name] : null;
    }
}

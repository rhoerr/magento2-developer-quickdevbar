<?php


namespace ADM\QuickDevBar\Service;


use ADM\QuickDevBar\Api\ServiceInterface;
use Magento\Framework\Interception\DefinitionInterface;
use Magento\Framework\Interception\PluginList\PluginList;

class Plugin implements ServiceInterface
{
    private $pluginsByTypes;
    /**
     * @var PluginList
     */
    private $pluginList;

    public function __construct(PluginList $pluginList)
    {
        $this->pluginList = $pluginList;
    }

    public function pullData()
    {
        if ($this->pluginsByTypes === null) {
            $this->pluginsByTypes =  [];

            try {
                $reflection = new \ReflectionClass($this->pluginList);
                $processed = $reflection->getProperty('_processed');
                $processed->setAccessible(true);
                $processedDefinitions = $processed->getValue($this->pluginList);

                $inherited = $reflection->getProperty('_inherited');
                $inherited->setAccessible(true);
                $inheritedDefinitions = $inherited->getValue($this->pluginList);
            } catch (\ReflectionException $e) {
                $this->pluginsByTypes = [];
                return $this->pluginsByTypes;
            }

            $processed = $processedDefinitions;
            $inherited = $inheritedDefinitions;


            $types = [DefinitionInterface::LISTENER_BEFORE=>'before',
                DefinitionInterface::LISTENER_AROUND=>'around',
                DefinitionInterface::LISTENER_AFTER=>'after'];

            /**
             * @see: Magento/Framework/Interception/PluginList/PluginList::_inheritPlugins($type)
             */
            foreach ($processed as $currentKey => $processDef) {
                if (preg_match('/^(.*)_(.*)___self$/', $currentKey, $matches) or preg_match('/^(.*?)_(.*?)_(.*)$/', $currentKey, $matches)) {
                    $type= $matches[1];
                    $method= $matches[2];
                    if (!empty($inherited[$type])) {
                        foreach ($processDef as $keyType => $pluginsNames) {
                            if (!is_array($pluginsNames)) {
                                $pluginsNames = [$pluginsNames];
                            }

                            foreach ($pluginsNames as $pluginName) {
                                if (!empty($inherited[$type][$pluginName])) {
                                    $this->pluginsByTypes[] = ['type'=>$type, 'plugin'=>$inherited[$type][$pluginName]['instance'], 'plugin_name'=>$pluginName, 'sort_order'=> $inherited[$type][$pluginName]['sortOrder'], 'method'=>$types[$keyType].ucfirst($method)];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->pluginsByTypes;
    }
}

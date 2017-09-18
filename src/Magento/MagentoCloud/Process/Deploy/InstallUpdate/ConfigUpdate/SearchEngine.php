<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MagentoCloud\Process\Deploy\InstallUpdate\ConfigUpdate;

use Magento\MagentoCloud\Config\Environment;
use Magento\MagentoCloud\Process\ProcessInterface;
use Magento\MagentoCloud\Config\Deploy as DeployConfig;
use Psr\Log\LoggerInterface;

class SearchEngine implements ProcessInterface
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DeployConfig
     */
    private $deployConfig;

    /**
     * @param Environment $environment
     * @param LoggerInterface $logger
     * @param DeployConfig $deployConfig
     */
    public function __construct(
        Environment $environment,
        LoggerInterface $logger,
        DeployConfig $deployConfig
    ) {
        $this->environment = $environment;
        $this->logger = $logger;
        $this->deployConfig = $deployConfig;
    }

    /**
     * Executes the process.
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->info('Updating search engine configuration.');

        $relationships = $this->environment->getRelationships();

        if (isset($relationships['elasticsearch'])) {
            $searchConfig = $this->getElasticSearchConfiguration($relationships['elasticsearch'][0]);
        } else if (isset($relationships['solr'])) {
            $searchConfig = $this->getSolrConfiguration($relationships['solr'][0]);
        } else {
            $searchConfig = ['engine' => 'mysql'];
        }

        $this->logger->info("Set search engine to: " . $searchConfig['engine']);

        $this->deployConfig->update($searchConfig);
    }

    /**
     * Returns SOLR configuration
     *
     * @param array $config Solr connection configuration
     * @return array
     */
    private function getSolrConfiguration(array $config)
    {
        $this->logger->info("Updating SOLR configuration.");
        return [
            'engine' => 'solr',
            'solr_server_hostname' => $config['host'],
            'solr_server_port' => $config['port'],
            'solr_server_username' => $config['scheme'],
            'solr_server_path' => $config['path'],
        ];
    }

    /**
     * Returns ElasticSearch configuration
     *
     * @param array $config Elasticsearch connection configuration
     * @return array
     */
    private function getElasticSearchConfiguration(array $config)
    {
        return [
            'engine' => 'elasticsearch',
            'elasticsearch_server_hostname' => $config['host'],
            'elasticsearch_server_port' => $config['port'],
        ];
    }
}

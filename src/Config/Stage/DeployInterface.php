<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\MagentoCloud\Config\Stage;

use Magento\MagentoCloud\Config\StageConfigInterface;

/**
 * Provides access to configuration of deploy stage.
 *
 * @api
 */
interface DeployInterface extends StageConfigInterface
{
    const VAR_QUEUE_CONFIGURATION = 'QUEUE_CONFIGURATION';
    const VAR_SEARCH_CONFIGURATION = 'SEARCH_CONFIGURATION';
    const VAR_ELASTICSUITE_CONFIGURATION = 'ELASTICSUITE_CONFIGURATION';
    const VAR_CACHE_CONFIGURATION = 'CACHE_CONFIGURATION';
    const VAR_SESSION_CONFIGURATION = 'SESSION_CONFIGURATION';
    const VAR_DATABASE_CONFIGURATION = 'DATABASE_CONFIGURATION';
    const VAR_RESOURCE_CONFIGURATION = 'RESOURCE_CONFIGURATION';
    const VAR_CRON_CONSUMERS_RUNNER = 'CRON_CONSUMERS_RUNNER';
    const VAR_CONSUMERS_WAIT_FOR_MAX_MESSAGES = 'CONSUMERS_WAIT_FOR_MAX_MESSAGES';
    const VAR_CLEAN_STATIC_FILES = 'CLEAN_STATIC_FILES';
    const VAR_UPDATE_URLS = 'UPDATE_URLS';
    const VAR_FORCE_UPDATE_URLS = 'FORCE_UPDATE_URLS';

    /**
     * The variable responsible to set lock provider for Magento 2.2.5 and higher.
     */
    const VAR_LOCK_PROVIDER = 'LOCK_PROVIDER';

    /**
     * The variable responsible to set Redis slave connection when it has true value.
     */
    const VAR_REDIS_USE_SLAVE_CONNECTION = 'REDIS_USE_SLAVE_CONNECTION';

    /**
     * The variable responsible to set mysql slave connection when it has true value.
     */
    const VAR_MYSQL_USE_SLAVE_CONNECTION = 'MYSQL_USE_SLAVE_CONNECTION';

    /**
     * The variable responsible to use split database.
     */
    const VAR_SPLIT_DB = 'SPLIT_DB';

    /**
     * The possible value of var SPLIT_DB
     */
    const VAL_SPLIT_DB_QUOTE = 'quote';
    /**
     * The possible value of var SPLIT_DB
     */
    const VAL_SPLIT_DB_SALE = 'sales';

    /**
     * The possible values for var SPLIT_DB
     */
    const VAL_SPLIT_DB = [
        self::VAL_SPLIT_DB_QUOTE,
        self::VAL_SPLIT_DB_SALE
    ];

    /**
     * @deprecated 2.1 specific variable.
     */
    const VAR_GENERATED_CODE_SYMLINK = 'GENERATED_CODE_SYMLINK';

    /**
     * The variable responsible for enabling google analytics in environments other than prod.
     */
    const VAR_ENABLE_GOOGLE_ANALYTICS = 'ENABLE_GOOGLE_ANALYTICS';
}

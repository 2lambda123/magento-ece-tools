<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\MagentoCloud\Test\Unit\DB\Data;

use Magento\MagentoCloud\Config\Database\DbConfig;
use Magento\MagentoCloud\DB\Data\ConnectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @inheritdoc
 */
class ConnectionFactoryTest extends TestCase
{
    /**
     * @var ConnectionFactory
     */
    private $factory;

    /**
     * @var DbConfig|MockObject
     */
    private $mergedConfigMock;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->mergedConfigMock = $this->createMock(DbConfig::class);

        $this->factory = new ConnectionFactory($this->mergedConfigMock);
    }

    public function testCreateMain()
    {
        $this->mergedConfigMock->expects($this->once())
            ->method('get')
            ->willReturn(['connection' => ['default' => ['test' => 'test']]]);

        $this->factory->create(ConnectionFactory::CONNECTION_MAIN);
    }

    public function testCreateSlave()
    {
        $this->mergedConfigMock->expects($this->once())
            ->method('get')
            ->willReturn(['slave_connection' => ['default' => ['test' => 'test']]]);

        $this->factory->create(ConnectionFactory::CONNECTION_SLAVE);
    }

    public function testCreateSlaveAsMain()
    {
        $this->mergedConfigMock->expects($this->exactly(2))
            ->method('get')
            ->willReturn(['connection' => ['default' => ['test' => 'test']]]);

        $this->factory->create(ConnectionFactory::CONNECTION_SLAVE);
    }

    public function testCreateWithException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Connection with type dummy doesn\'t exist');
        $this->factory->create('dummy');
    }
}

<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\MagentoCloud\Test\Unit\Config\Validator\Deploy;

use Magento\MagentoCloud\Config\Stage\DeployInterface;
use Magento\MagentoCloud\Config\Validator\Deploy\DatabaseSplitConnection;
use Magento\MagentoCloud\Config\Validator\Result\Error;
use Magento\MagentoCloud\Config\Validator\Result\Success;
use Magento\MagentoCloud\Config\Validator\ResultFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject as Mock;

/**
 * @inheritdoc
 */
class DatabaseSplitConnectionTest extends TestCase
{
    /**
     * @var DatabaseSplitConnection
     */
    private $validator;

    /**
     * @var ResultFactory|Mock
     */
    private $resultFactoryMock;

    /**
     * @var DeployInterface|Mock
     */
    private $stageConfigMock;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->resultFactoryMock = $this->createConfiguredMock(ResultFactory::class, [
            'success' => $this->createMock(Success::class),
            'error' => $this->createMock(Error::class)
        ]);
        $this->stageConfigMock = $this->getMockForAbstractClass(DeployInterface::class);

        $this->validator = new DatabaseSplitConnection(
            $this->resultFactoryMock,
            $this->stageConfigMock
        );
    }

    /**
     * The test of validator messages
     */
    public function testMessageValidate()
    {
        $dbConfiguration = [
            'connection' => [
                'default' => [],
                'indexer' => [],
                'checkout' => [],
                'sale' => [],
            ],
            'slave_connection' => [
                'default' => [],
                'indexer' => [],
                'checkout' => [],
                'sale' => [],
            ],
        ];
        $expectedMessage = 'Split database configuration was detected in the property DATABASE_CONFIGURATION of the'
            . ' file .magento.env.yaml:' . PHP_EOL
            . '- connection: checkout, sale' . PHP_EOL
            . '- slave_connection: checkout, sale' . PHP_EOL
            . 'Custom split database configuration will be ignored in during current deploy phase.' . PHP_EOL
            . 'Magento Cloud not support a custom split database configuration';
        $this->stageConfigMock->expects($this->once())
            ->method('get')
            ->with(DeployInterface::VAR_DATABASE_CONFIGURATION)
            ->willReturn($dbConfiguration);
        $this->resultFactoryMock->expects($this->once())
            ->method('error')
            ->with($expectedMessage);

        $this->assertInstanceOf(Error::class, $this->validator->validate());
    }

    /**
     * @param array $dbConfiguration
     * @param string $expectedResultClass
     * @dataProvider validateDataProvider
     */
    public function testValidate(array $dbConfiguration, string $expectedResultClass)
    {
        $this->stageConfigMock->expects($this->once())
            ->method('get')
            ->with(DeployInterface::VAR_DATABASE_CONFIGURATION)
            ->willReturn($dbConfiguration);

        $this->assertInstanceOf($expectedResultClass, $this->validator->validate());
    }

    /**
     * @return array
     */
    public function validateDataProvider(): array
    {
        return [
            [
                [],
                Success::class,
            ],
            [
                [
                    '_merge' => true,
                    'connection' => [
                        'default' => [],
                        'indexer' => [],
                    ],
                    'slave_connection' => [
                        'default' => [],
                        'indexer' => [],
                    ],
                ],
                Success::class,
            ],
            [
                [
                    'connection' => [
                        'default' => [],
                        'indexer' => [],
                        'sale' => [],
                    ],
                    'slave_connection' => [
                        'default' => [],
                        'indexer' => [],
                    ],
                ],
                Error::class,
            ],
            [
                [
                    'connection' => [
                        'default' => [],
                        'indexer' => [],
                    ],
                    'slave_connection' => [
                        'default' => [],
                        'indexer' => [],
                        'sale' => [],
                    ],
                ],
                Error::class,
            ],
            [
                [
                    'connection' => [
                        'default' => [],
                        'indexer' => [],
                        'checkout' => [],
                    ],
                    'slave_connection' => [
                        'default' => [],
                        'indexer' => [],
                    ],
                ],
                Error::class,
            ],
            [
                [
                    'connection' => [
                        'default' => [],
                        'indexer' => [],
                    ],
                    'slave_connection' => [
                        'default' => [],
                        'indexer' => [],
                        'checkout' => [],
                    ],
                ],
                Error::class,
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace Olieinikov\OrderData\Model\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class CustomLogger extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/custom_log.log';
}

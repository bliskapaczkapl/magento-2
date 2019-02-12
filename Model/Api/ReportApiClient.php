<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 04.02.19
 * Time: 09:24
 */

namespace Sendit\Bliskapaczka\Model\Api;

use Bliskapaczka\ApiClient\Bliskapaczka\Report;

class ReportApiClient
{
    const OPERATORS = [
        'RUCH' => 'Ruch',
        'POCZTA' => 'Poczta',
        'INPOST' => 'Inpost',
        'DPD' => 'DPD',
        'UPS' => 'UPS',
        'FEDEX' => 'FedEx',
        'GLS' => 'GLS',
        'XPRESS' => 'X-press Couriers'
    ];

    /** @var Report */
    protected $apiClient;
    private function __construct()
    {
    }
    public static function fromConfiguration(Configuration $configuration)
    {
        $apiClient = new ReportApiClient();
        $apiClient->apiClient = new Report(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );
        return $apiClient;
    }

    public function get(string $operator, ?string $startDate = null, ?string $endDate = null)
    {
        if (!empty($startDate)) {
            $this->apiClient->setStartPeriod($startDate);
        }
        if (!empty($endDate)) {
            $this->apiClient->setEndPeriod($endDate);
        }
        $this->apiClient->setOperator($operator);
        $response = $this->apiClient->get();
        return $response;
    }
}

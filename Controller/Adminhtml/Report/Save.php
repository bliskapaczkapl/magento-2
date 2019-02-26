<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 04.02.19
 * Time: 10:55
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Report;

use Magento\Framework\App\ResponseInterface;
use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Report;

class Save extends \Magento\Framework\App\Action\Action
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

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $isPost = $this->getRequest()->getPost();
        if ($isPost) {
            $formData = $this->getRequest()->getParam('report');
        }
        $apiClient = $this->getReportApiclient();
        $zip = $this->createZipFile();

        foreach (self::OPERATORS as $key => $value) {
            $startPeriod = $formData[$key . 'from'];
            $stopPeriod = $formData[$key . 'to'];
            if (!empty($startPeriod)) {
                $startPeriod = new \DateTime($startPeriod);
                $startPeriod = $startPeriod->format('c');
                $apiClient->setStartPeriod($startPeriod);
            }
            if (!empty($stopPeriod)) {
                $stopPeriod = new \DateTime($stopPeriod);
                $stopPeriod = $stopPeriod->format('c');
                $apiClient->setEndPeriod($stopPeriod);
            }
            $apiClient->setOperator($key);
            try {
                $zip->addFromString(sprintf('%s.pdf', $value), $apiClient->get());
            } catch (\Exception $exception) {
                continue;
            }
        }

        $fileName = $zip->filename;
        $zip->close();
        if (is_file($fileName)) {
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length: ".filesize($fileName));
            header("Content-Disposition: attachment; filename=\"".basename($fileName)."\"");
            readfile($fileName);
            exit;
        }
    }

    /**
     * @return \ZipArchive
     */
    private function createZipFile(): \ZipArchive
    {
        $directory = $this->_objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
        $tmpDirectory = $directory->getPath('tmp');
        $zipName = sprintf('%d.zip', time());
        $path = sprintf('%s/%s', $tmpDirectory, $zipName);
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE);
        return $zip;
    }

    private function getReportApiclient()
    {
        $configuration = Configuration::fromStoreConfiguration();
        $apiClient = new Report(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );

        return $apiClient;
    }
}

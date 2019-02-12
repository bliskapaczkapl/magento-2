<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 04.02.19
 * Time: 10:55
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Report;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Sendit\Bliskapaczka\Model\Api\Configuration;
use Sendit\Bliskapaczka\Model\Api\ReportApiClient;

class Save extends \Magento\Framework\App\Action\Action
{

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
        $conf = Configuration::fromStoreConfiguration();
        $apiClient = ReportApiClient::fromConfiguration($conf);
        $zip = $this->createZipFile();

        foreach (ReportApiClient::OPERATORS as $key => $value) {
            $startPeriod = $formData[$key.'from'];
            $stopPeriod = $formData[$key.'to'];
            if (!empty($startPeriod)) {
                $startPeriod = new \DateTime($startPeriod);
                $startPeriod = $startPeriod->format('c');
            } else {
                $startPeriod = null;
            }
            if (!empty($stopPeriod)) {
                $stopPeriod = new \DateTime($stopPeriod);
                $stopPeriod = $stopPeriod->format('c');
            } else {
                $stopPeriod = null;
            }
            $pdf = $apiClient->get($key, $startPeriod, $stopPeriod);
            if (strpos($pdf, 'error') === false) {
                $zip->addFromString($value, $pdf);
            }
        }

        $fileName = $zip->filename;
        $zip->close();
        return $this->_fileFactory->create(
            //File name you would like to download it by
            $fileName,
            [
                'type'  => "filename", //type has to be "filename"
                'value' => "folder/{$fileName}", // path will append to the
                // base dir
                'rm'    => true, // add this only if you would like the file to be
                // deleted after being downloaded from server
            ],
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
        );
    }

    /**
     * @return \ZipArchive
     */
    private function createZipFile() :\ZipArchive
    {
        $directory = $this->_objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
        $tmpDirectory = $directory->getPath('tmp');
        $zipName = sprintf('%d.zip', time());
        $path = sprintf('%s/%s', $tmpDirectory, $zipName);
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE);
        return $zip;
    }
}

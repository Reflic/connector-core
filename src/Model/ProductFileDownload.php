<?php
/**
 * @copyright 2010-2015 JTL-Software GmbH
 * @package Jtl\Connector\Core\Model
 * @subpackage Product
 */

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * @access public
 * @package Jtl\Connector\Core\Model
 * @subpackage Product
 * @Serializer\AccessType("public_method")
 */
class ProductFileDownload extends AbstractModel
{
    /**
     * @var \DateTimeInterface
     * @Serializer\Type("DateTimeInterface")
     * @Serializer\SerializedName("creationDate")
     * @Serializer\Accessor(getter="getCreationDate",setter="setCreationDate")
     */
    protected $creationDate = null;
    
    /**
     * @var integer
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("maxDays")
     * @Serializer\Accessor(getter="getMaxDays",setter="setMaxDays")
     */
    protected $maxDays = 0;
    
    /**
     * @var integer
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("maxDownloads")
     * @Serializer\Accessor(getter="getMaxDownloads",setter="setMaxDownloads")
     */
    protected $maxDownloads = 0;
    
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("path")
     * @Serializer\Accessor(getter="getPath",setter="setPath")
     */
    protected $path = '';
    
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("previewPath")
     * @Serializer\Accessor(getter="getPreviewPath",setter="setPreviewPath")
     */
    protected $previewPath = '';
    
    /**
     * @var integer
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("sort")
     * @Serializer\Accessor(getter="getSort",setter="setSort")
     */
    protected $sort = 0;
    
    /**
     * @var ProductFileDownloadI18n[]
     * @Serializer\Type("array<Jtl\Connector\Core\Model\ProductFileDownloadI18n>")
     * @Serializer\SerializedName("i18ns")
     * @Serializer\AccessType("reflection")
     */
    protected $i18ns = [];

    /**
     * @param \DateTimeInterface $creationDate
     * @return ProductFileDownload
     */
    public function setCreationDate(\DateTimeInterface $creationDate = null): ProductFileDownload
    {
        $this->creationDate = $creationDate;
        
        return $this;
    }
    
    /**
     * @return \DateTimeInterface
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }
    
    /**
     * @param integer $maxDays
     * @return ProductFileDownload
     */
    public function setMaxDays(int $maxDays): ProductFileDownload
    {
        $this->maxDays = $maxDays;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getMaxDays(): int
    {
        return $this->maxDays;
    }
    
    /**
     * @param integer $maxDownloads
     * @return ProductFileDownload
     */
    public function setMaxDownloads(int $maxDownloads): ProductFileDownload
    {
        $this->maxDownloads = $maxDownloads;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getMaxDownloads(): int
    {
        return $this->maxDownloads;
    }
    
    /**
     * @param string $path
     * @return ProductFileDownload
     */
    public function setPath(string $path): ProductFileDownload
    {
        $this->path = $path;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
    
    /**
     * @param string $previewPath
     * @return ProductFileDownload
     */
    public function setPreviewPath(string $previewPath): ProductFileDownload
    {
        $this->previewPath = $previewPath;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPreviewPath(): string
    {
        return $this->previewPath;
    }
    
    /**
     * @param integer $sort
     * @return ProductFileDownload
     */
    public function setSort(int $sort): ProductFileDownload
    {
        $this->sort = $sort;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getSort(): int
    {
        return $this->sort;
    }
    
    /**
     * @param ProductFileDownloadI18n $i18n
     * @return ProductFileDownload
     */
    public function addI18n(ProductFileDownloadI18n $i18n): ProductFileDownload
    {
        $this->i18ns[] = $i18n;
        
        return $this;
    }

    /**
     * @param ProductFileDownloadI18n ...$i18ns
     * @return ProductFileDownload
     */
    public function setI18ns(ProductFileDownloadI18n ...$i18ns): ProductFileDownload
    {
        $this->i18ns = $i18ns;
        
        return $this;
    }
    
    /**
     * @return ProductFileDownloadI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }
    
    /**
     * @return ProductFileDownload
     */
    public function clearI18ns(): ProductFileDownload
    {
        $this->i18ns = [];
        
        return $this;
    }
}

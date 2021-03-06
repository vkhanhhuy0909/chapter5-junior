<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\Chapter5\Model\Product\Attribute\Backend;

use Magento\Catalog\Model\ProductRepository;
use \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Model\Attribute\ScopeOverriddenValue;
$GLOBALS = [];
/**
 * Backend model for set of EAV attributes with 'frontend_input' equals 'price'.
 *
 * @api
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 * @since 100.0.2
 */
class Save extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * Catalog helper
     *
     * @var \Magento\Catalog\Helper\Data
     */
    protected $_helper;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Currency factory
     *
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * Core config model
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_config;

    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    protected $localeFormat;

    /**
     * @var \Magento\Catalog\Model\Attribute\ScopeOverriddenValue
     */
    private $scopeOverriddenValue;

    /**
     * @var ProductRepository
     */
    protected $_productRepository;

    /**
     * Save constructor.
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Helper\Data $catalogData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param ScopeOverriddenValue|null $scopeOverriddenValue
     * @param ProductRepository $productRepository
     */
    public function __construct(
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        ScopeOverriddenValue $scopeOverriddenValue = null,
        ProductRepository $productRepository
    ) {
        $this->_productRepository = $productRepository;
        $this->_currencyFactory = $currencyFactory;
        $this->_storeManager = $storeManager;
        $this->_helper = $catalogData;
        $this->_config = $config;
        $this->localeFormat = $localeFormat;
        $this->scopeOverriddenValue = $scopeOverriddenValue
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(ScopeOverriddenValue::class);
    }

    /**
     * Set Attribute instance
     * Rewrite for redefine attribute scope
     *
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @return $this
     */
    public function setAttribute($attribute)
    {
        parent::setAttribute($attribute);
        $this->setScope($attribute);
        return $this;
    }

    /**
     * Redefine Attribute scope
     *
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @return $this
     */
    public function setScope($attribute)
    {
        if ($this->_helper->isPriceGlobal()) {
            $attribute->setIsGlobal(ScopedAttributeInterface::SCOPE_GLOBAL);
        } else {
            $attribute->setIsGlobal(ScopedAttributeInterface::SCOPE_WEBSITE);
        }

        return $this;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
     * @throws \Exception
     */
    public function afterSave($object)
    {
        $value = $object->getData('sample_save');
        $newValue = $value . " varchar(" . strlen($value) . ")";
        $object->setData('sample_save',$newValue);
        $this->getAttribute()->getEntity()->saveAttribute($object, 'sample_save');
        return parent::afterSave($object); // TODO: Change the autogenerated stub
    }

}

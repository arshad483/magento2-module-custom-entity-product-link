<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\CustomEntityProductLink
 * @author    Maxime LECLERCQ <maxime.leclercq@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\CustomEntityProductLink\Observer\Adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Registry;
use Smile\CustomEntity\Model\CustomEntity\AttributeSet;
use Magento\Framework\Module\Manager;

/**
 * Add custom_entity_attribute_set_id field into base fieldset.
 *
 * @category Smile
 * @package  Smile\CustomEntityProductLink
 * @author   Maxime LECLERCQ <maxime.leclercq@smile.fr>
 */
class AddFieldsToAttributeObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var AttributeSet\Options
     */
    private $attributeSetOptions;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param Manager              $moduleManager       Module manager.
     * @param AttributeSet\Options $attributeSetOptions Attribute set options.
     * @param Registry             $registry            Registry
     */
    public function __construct(
        Manager $moduleManager,
        AttributeSet\Options $attributeSetOptions,
        Registry $registry
    ) {
        $this->moduleManager = $moduleManager;
        $this->attributeSetOptions = $attributeSetOptions;
        $this->registry = $registry;
    }

    /**
     * Append custom_entity_attribute_set_id field.
     *
     * @param \Magento\Framework\Event\Observer $observer Observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->moduleManager->isOutputEnabled('Smile_CustomEntityProductLink')) {
            return;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $observer->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $fieldset->addField(
            'custom_entity_attribute_set_id',
            'select',
            [
                'name'  => 'custom_entity_attribute_set_id',
                'label'  => __('Custom entity type'),
                'title'  => __('Custom entity type'),
                'values' => $this->attributeSetOptions->toOptionArray(),
            ]
        );
        if ($this->getAttributeObject() && $this->getAttributeObject()->getAttributeId()) {
            $form->getElement('custom_entity_attribute_set_id')->setDisabled(1);
        }
    }

    /**
     * @return \Magento\Eav\Api\Data\AttributeInterface|null
     */
    private function getAttributeObject()
    {
        return $this->registry->registry('entity_attribute');
    }
}

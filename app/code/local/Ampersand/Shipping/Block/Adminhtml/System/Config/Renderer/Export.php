<?php

class Ampersand_Shipping_Block_Adminhtml_System_Config_Renderer_Export
    extends Mage_Adminhtml_Block_System_Config_Form_Field
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     * @author Asif Ali <aa@amp.co>
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $buttonBlock = $this->getForm()->getLayout()->createBlock('adminhtml/widget_button');

        $linkUrl = Mage::helper('adminhtml')
            ->getUrl('ampersand_shipping_admin/standard/export');

        $data = array(
            'label' => Mage::helper('adminhtml')->__('Export CSV'),
            'onclick' => "setLocation('{$linkUrl}');",
            'class' => '',
        );

        $html = $buttonBlock->setData($data)->toHtml();

        return $html;
    }
}
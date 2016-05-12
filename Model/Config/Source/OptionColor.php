<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\PDFInvoice\Model\Config\Source;

class OptionColor implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {

        return [

            ['value' => '1', 'label' => __('Yellow') ],
            ['value' => '2', 'label' => __('Pink')],
            ['value' => '3', 'label' => __('Spring Green')],
            ['value' => '4', 'label' => __('Dodger Blue')],
        ];
    }
}

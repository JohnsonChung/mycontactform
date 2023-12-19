<?php

namespace JQuest;

use JQuest\Models\Enquiry;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 27, 2015
 */
class EnquiryExport
{

    const CHECKBOX_CHECKED = "■";
    const CHECKBOX_UNCHECKED = "□";

    /**
     *
     * @param string $template_path
     * @param string $save_path
     * @param Enquiry $enquiry
     */
    public static function generateDoc($template_path, $save_path, Enquiry $enquiry)
    {
        $templateProcessor = new TemplateProcessor($template_path);
        $templateProcessor->setValue('id', $enquiry->id);
        // $templateProcessor->setValue('time', Util::JapanDateTime($enquiry->created_at)); // Require Intl extension
        $templateProcessor->setValue('time', $enquiry->created_at->format('Y-m-d H:i:s A'));
        $templateProcessor->setValue('store', $enquiry->getStoreName());
        $templateProcessor->setValue('receipt_number', $enquiry->receipt_number);
        $templateProcessor->setValue('text', $enquiry->opinions_enquiries);
        $templateProcessor->setValue('name', $enquiry->name);
        $templateProcessor->setValue('postal_code', $enquiry->postal_code);
        $templateProcessor->setValue('state', $enquiry->state);
        $templateProcessor->setValue('city', $enquiry->city);
        $templateProcessor->setValue('building', $enquiry->building_name);
        $templateProcessor->setValue('phone', $enquiry->telephone_number);
        $templateProcessor->setValue('email', $enquiry->email);
        $templateProcessor->setValue('contact_method', $enquiry->getContactMethod());

        // Set category
        $templateProcessor->setValue('cat', $enquiry->response ? $enquiry->response->category->name : "ー");

        $templateProcessor->saveAs($save_path);
    }

    public static function generateSheet($template_path, $save_path, Enquiry $enquiry)
    {
        $values = self::getValues($enquiry);
        $cell_attr = [            
            'C9' => 'cat',
            'D10' => 'id',
            'H10' => 'receipt_number',
            'D11' => 'store',
            'D12' => 'time',
            'D13' => 'name',
            'D14' => ['postal_code', 'state', 'city', 'building'],
            'D15' => ['phone', 'email', 'contact_method'],
            'D16' => 'text'
        ];

        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($template_path);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($cell_attr as $cell => $attr) {
            $cell = $sheet->getCell($cell);
            $value = $cell->getValue();

            if (is_string($attr)) {
                $value = self::replacePlaceHolder($attr, $values[$attr], $value);
            } else {
                if (is_array($attr)) {
                    foreach ($attr as $_) {
                        $value = self::replacePlaceHolder($_, $values[$_], $value);
                    }
                }
            }

            $cell->setValue($value);
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($save_path);
    }

    protected static function replacePlaceHolder($attr, $value, $subject)
    {
        if (strpos($subject, $attr) === false) {
            return $value;
        }

        return str_replace(sprintf('${%s}', $attr), $value, $subject);
    }

    protected static function getValues(Enquiry $enquiry)
    {
        return [
            'id' => $enquiry->id,
            'time' => Util::JapanDateTime($enquiry->created_at),
            'time' => $enquiry->created_at->format('Y-m-d H:i:s A'),
            'store' => $enquiry->getStoreName(),
            'receipt_number' => $enquiry->receipt_number,
            'text' => $enquiry->opinions_enquiries,
            'name' => $enquiry->name,
            'postal_code' => $enquiry->postal_code,
            'state' => $enquiry->state,
            'city' => $enquiry->city,
            'building' => $enquiry->building_name,
            'phone' => $enquiry->telephone_number,
            'email' => $enquiry->email,
            'contact_method' => $enquiry->getContactMethod(),
            'cat' => $enquiry->response ? $enquiry->response->category->name : "ー"
        ];
    }
}

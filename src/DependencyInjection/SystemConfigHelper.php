<?php
namespace App\DependencyInjection;

class SystemConfigHelper
{
    function getStatus($value = null): array {
        $returnValue = [
            '1' => 'Active',
            '2' => 'In-Active',
            '99' => 'Delete',
        ];
        
        if($value) {
            return isset($returnValue[$value])?$returnValue[$value]:[];
        } else {
            return $returnValue;
        }
    }

    public function getQuestion($value = null): array {
        $returnValue = [
            '1' => [
                1 => "Proponent's statement of responsibility. The statement is signed and dated by or on behalf of the proponent",
                2 => "Environment bond and calculation formula is included in the report. Formula must include provisions for environment rehabilitation. Civil works costs alone will not be accepted.",
                3 => "All environment studies required in the TOR are included in the report and attachments.",
                4 => "All social studies required in the TOR are include in the report and attachment.",
                5 => "EIA report appendices include all reports, plans, analyses and other information required for the technical review. Information in appendices correlate with relevant sections of the EIA report",
                6 => "5 hard copies of the report and accompanying documents",
                7 => "1 electronic copy on a disc in PDF format",
                8 => "Receiving officers signatures with receiving date",
            ]
        ];
        
        if($value) {
            return isset($returnValue[$value])?$returnValue[$value]:[];
        } else {
            return $returnValue;
        }
    }

}

?>
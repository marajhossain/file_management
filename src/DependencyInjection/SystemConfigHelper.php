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
                1 => "Proponent's statement of responsibility.",
                2 => "Environment bond and calculation formula is included in the report.",
                3 => "All evnironment studies required in the TOR are included in the report and attachments.",
                4 => "All social studies required in the TOR",
                5 => "EIA report appendices include all reports",
                6 => "5 hard copies of the report",
                7 => "1 electronic copy",
                8 => "receiving officers signatures",
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
<?php

namespace App\Extension;

use App\PatientFile;

class Mediator {

    public static function mitigate($trace)
    {
        if(strpos($trace, ':') !== false) {
            $model = explode(':', $trace);
            if(is_numeric($model[1])) {
                switch ($model[0]) {
                    case 'file':
                        $file = PatientFile::find($model[1]);
                        $file->active = true;
                        return $file;
                    break;
                    default:
                        return null;
                    break;
                }
            }

        }


    }
}

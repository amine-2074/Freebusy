<?php

namespace App\Freebusy;

use Carbon\Carbon;
use DateTime;

class Filter
{
    public function filterData($lines)
    {
        $data = [];
        $ids = [];
        foreach ($lines as $key=>$line) {
            $item = explode(";", $line);
            $ids[] = $item[0];
        }
        $ids = array_unique($ids); //store unique ids in array
        if (($key = array_search("\r", $ids)) !== false) {
            unset($ids[$key]);
        }
        foreach ($ids as $id) {
            $data[$id] = [];
        }
        $col = collect($lines);
        foreach ($col as $key=>$line) {
            $f_line = explode(";", $line);
            $col[$key] = $f_line;
        }
        foreach ($ids as $id) {
            $identified_columns = $col->where(0, $id);
            $data[$id] = $identified_columns;
        }
        return $data;
    }

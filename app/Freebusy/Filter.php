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

    public function filterItems($data)
    {
        $filtred_items = [];
        foreach ($data as $key=>$items) {
            $filtred_items[$key] = [];
            $filtred_items[$key]['dates'] = [];
        }
        foreach ($data as $key=>$items) {
            // dd($items);
            foreach ($items as $item) {
                if (array_key_exists('2', $item)) {
                    if ($this->validateDate($item[1]) == true && $this->validateDate($item[2]) == true) {
                        $filtred_items[$key]['dates'][] =
                            [
                            'start' => Carbon::createFromFormat('n/j/Y g:i:s A', $item[1])->format('Y-m-d H:i:s'),
                            'end' => Carbon::createFromFormat('n/j/Y g:i:s A', $item[2])->format('Y-m-d H:i:s')
                            
                                    ];
                    } else {
                        if($this->validateDate($item[1]) == false) {
                            $timestamps_format = strtotime($item[1]);
                            $start = date('Y-m-d H:i:s', $timestamps_format);
                        } else {
                            $start = Carbon::createFromFormat('n/j/Y g:i:s A', $item[1])->format('Y-m-d H:i:s');
                        }
                        if($this->validateDate($item[2]) == false) {
                            $timestamps_format = strtotime($item[2]);
                            $end = date('Y-m-d H:i:s', $timestamps_format);
                        } else {
                            $end = Carbon::createFromFormat('n/j/Y g:i:s A', $item[2])->format('Y-m-d H:i:s');
                        }
                        $filtred_items[$key]['dates'][] = [
                            'start' => $start,
                            'end' => $end
                        ];
                    }
                } else {
                    $filtred_items[$key] += ['name' => preg_replace('/\r/', '', $item[1])];
                }
            }
        }

        dd(collect($filtred_items['57646786307395936680161735716561753784']['dates'])->sortBy('start'));
    }
    function validateDate($date, $format = 'n/j/Y g:i:s A')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
}
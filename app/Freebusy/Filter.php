<?php

namespace App\Freebusy;

use App\Models\Employee;
use App\Models\Freebusy;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Filter
{
    public function filterData($lines)
    {
        $data = [];
        $ids = [];
        foreach ($lines as $key=>$line) {
            $item = explode(";", $line);    //explode the file lines into arrays using the ";"
            $ids[] = $item[0];              //store the ids in one array
        }
        $ids = array_unique($ids); //delete duplicated ids and let only one foreach id
        if (($key = array_search("\r", $ids)) !== false) {
            unset($ids[$key]);  //delete the \r
        }
        foreach ($ids as $id) {
            $data[$id] = [];    //set array foreach id
        }
        $col = collect($lines); //put the arrays in one collection to make control easier
        foreach ($col as $key=>$line) {
            $f_line = explode(";", $line);  //
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
                        if ($this->validateDate($item[1]) == false) {
                            $timestamps_format = strtotime($item[1]);
                            $start = Carbon::parse($timestamps_format)->format('Y-m-d H:i:s');
                        } else {
                            $start = Carbon::createFromFormat('n/j/Y g:i:s A', $item[1])->format('Y-m-d H:i:s');
                        }
                        if ($this->validateDate($item[2]) == false) {
                            $timestamps_format = strtotime($item[2]);
                            $end = Carbon::parse($timestamps_format)->format('Y-m-d H:i:s');
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
        return $filtred_items;
    }

    public function validateDate($date, $format = 'n/j/Y g:i:s A')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function storeData($name, $employee_id, $dates)
    {
        $id = Str::uuid();
        $employee = new Employee();
        $employee->id = $id;
        $employee->name = $name;
        $employee->employee_ref = $employee_id;
        $employee->save();

        foreach ($dates as $date) {
            $record = [
                'start_busy' => Carbon::parse($date->start),
                'end_busy' => Carbon::parse($date->end),
                'employee_id' => $id
            ];
            DB::table('freebusies')->insert($record);
        }
    }
}

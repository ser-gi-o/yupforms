<?php

namespace YupForms\Http;
/**
 * Created by PhpStorm.
 * User: Sergio
 * Date: 1/8/2019
 * Time: 11:11 PM
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

trait DataTables
{
    /**
     * Handles datatables specific fields and applies to query builder
     *
     * @param $fields
     * @param $filterFields
     * @param $builder
     * @return false|string
     */
    public function filterSearchSort($builder, $fields, $filterFields = null)
    {
        //dataset totals;
        $draw = Request::has('draw') ? Request::input('draw') :  1;
        $recordsTotal = method_exists($builder, 'toBase')
            ? $builder->toBase()->getCountForPagination()
            : $builder->getCountForPagination();

        //search
        if (Request::has('search') &&  !is_null(Request::input('search.value'))) {
            $search = Request::input('search');
            //for backward compatibility
            $searchFields = is_null($filterFields) ? $fields : $filterFields;

            $builder->where(function($q) use ($searchFields, $search) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search['value']}%");
                }
            });

            /*$recordsFiltered = DB::table( DB::raw("({$builder->toSql()}) as sub") )
                ->mergeBindings($builder->getQuery()) // you need to get underlying Query Builder
                ->count();*/
        }

        $recordsFiltered = method_exists($builder, 'toBase')
            ? $builder->toBase()->getCountForPagination()
            : $builder->getCountForPagination();

        //sort
        if (Request::has('order')) {
            //this is array
            $orderBys = Request::input('order');

            foreach ($orderBys as $orderBy)
                $builder->orderBy($fields[$orderBy['column']], $orderBy['dir']);
        }

        //limit start
        if (Request::has('start')) {
            $builder->skip(Request::input('start'));
        }

        //limit records to return
        if (Request::has('length')) {
            $builder->limit(Request::input('length'));
        } else {
            $builder->limit(100);
        }

        $records = $builder->get();

        $jsonRecords = json_encode([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $records->toArray()
        ]);

        return $jsonRecords;
    }
}

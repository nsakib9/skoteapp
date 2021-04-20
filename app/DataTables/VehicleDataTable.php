<?php

/**
 * Vehicle DataTable
 *
 * @package     Gofer
 * @subpackage  DataTable
 * @category    Vehicle
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Vehicle;
use Yajra\DataTables\Services\DataTable;
use DB;

class VehicleDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->of($query)
            ->addColumn('action', function ($vehicle) {
                 $edit = '<a href="'.url(LOGIN_USER_TYPE.'/edit_vehicle/'.$vehicle->id).'" class="btn btn-xs btn-primary"><i class="bx bx-edit"></i></a>' ;
                $delete = '<a data-href="'.url(LOGIN_USER_TYPE.'/delete_vehicle/'.$vehicle->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="bx bx-trash"></i></a>';

                return $edit.'&nbsp'.$delete;
            })->addColumn('driver_name', function ($vehicle) {                

                return $vehicle->driver_name." ".$vehicle->last_name;
            })->filter(function($query) {
                       if ($this->request()->has('search.value')) {
                           $query
                               // you'll have to declare your other filters here so that they are used in conjunction
                               ->where(function($q) {
                                   $keyword = $this->request()->input('search.value');
                                   $q->where('vehicle.id', 'like', "%{$keyword}%")
                                       ->orwhereRaw("CONCAT(first_name,' ',last_name) like ?", ["%{$keyword}%"])->orWhereRaw("vehicle.status like ?", ["%{$keyword}%"])->orWhereRaw("vehicle.vehicle_number like ?", ["%{$keyword}%"])->orWhereRaw("vehicle.vehicle_type like ?", ["%{$keyword}%"])->orWhereRaw("companies.name like ?", ["%{$keyword}%"])->orWhereRaw("vehicle.vehicle_name like ?", ["%{$keyword}%"]);
                                       
                               });
                       }
                   });    ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param Vehicle $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Vehicle $model)
    {
        $vehicles = $model->join('users', function ($join) {
                $join->on('users.id', '=', 'vehicle.user_id');
            })
            ->leftJoin('companies', function ($join) {
                $join->on('companies.id', '=', 'vehicle.company_id');
            })
            ->select('vehicle.id as id','vehicle.status as status','vehicle.vehicle_name as vehicle_name','vehicle.vehicle_number as vehicle_number','vehicle.vehicle_type', 'users.first_name as driver_name','users.last_name as last_name','companies.name as company_name');

        //If login user is company then get that company vehicles only
        if (LOGIN_USER_TYPE=='company') {
            $vehicles = $vehicles->where('vehicle.company_id',auth()->guard('company')->user()->id);
        }
        return $vehicles;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('lBfr<""t>ip')
                    ->orderBy(0)
                    ->buttons(
                        ['csv', 'excel', 'print', 'reset']
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'vehicle.id', 'title' => 'Id'],
            ['data' => 'company_name', 'name' => 'companies.name', 'title' => 'Company Name'],
            ['data' => 'driver_name', 'name' => 'users.first_name', 'title' => 'Driver Name'],
            ['data' => 'vehicle_type', 'name' => 'vehicle.vehicle_type', 'title' => 'Vehicle Type'],
            ['data' => 'vehicle_name', 'name' => 'vehicle.vehicle_name', 'title' => 'Make / Model'],
            ['data' => 'vehicle_number', 'name' => 'vehicle.vehicle_number', 'title' => 'Vehicle Number'],
            ['data' => 'status', 'name' => 'vehicle.status', 'title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'exportable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'vehicles_' . date('YmdHis');
    }
}

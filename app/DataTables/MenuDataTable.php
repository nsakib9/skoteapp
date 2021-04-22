<?php

/**
 * Rider DataTable
 *
 * @package     Gofer
 * @subpackage  DataTable
 * @category    Rider
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Menu;
use Yajra\DataTables\Services\DataTable;
use DB;

class MenuDataTable extends DataTable
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
            ->addColumn('action', function ($menu) {
                $edit = (auth('admin')->user()->can('update_rider')) ? '<a href="'.url('admin/edit_menu/'.$menu->id).'" class="btn btn-xs btn-primary"><i class="bx bx-edit"></i></a>&nbsp;' : '';
                $delete = (auth('admin')->user()->can('delete_rider')) ? '<a data-href="'.url('admin/delete_menu/'.$menu->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="bx bx-trash"></i></a>&nbsp;':'';

                return $edit.$delete;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param Menu $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Menu $model)
    {
        $menus = $model->select('menus.id as id', 'menus.name','menus.type')->groupBy('id');
        return $menus;
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
        // $mobile_number_column = (isLiveEnv())?'hidden_mobile':'mobile_number';
        return [
            ['data' => 'id', 'name' => 'menus.id', 'title' => 'Id'],
            ['data' => 'name', 'name' => 'menus.name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'menus.type', 'title' => 'Type'],
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
        return 'menus_' . date('YmdHis');
    }
}
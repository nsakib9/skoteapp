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

use App\Models\Banner;
use Yajra\DataTables\Services\DataTable;
use DB;

class BannerDataTable extends DataTable
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
            ->addColumn('action', function ($banners) {
                $edit = (auth('admin')->user()->can('update_rider')) ? '<a href="'.url('admin/edit_banner/'.$banners->id).'" class="btn btn-xs btn-primary"><i class="bx bx-edit"></i></a>&nbsp;' : '';
                $delete = (auth('admin')->user()->can('delete_rider')) ? '<a data-href="'.url('admin/delete_banner/'.$banners->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="bx bx-trash"></i></a>&nbsp;':'';

                return $edit.$delete;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param Banner $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Banner $model)
    {
        $banners = $model->select('banners.id as id', 'banners.line_one', 'banners.line_two','banners.banner_img','banners.button_one','banners.button_two', 'created_at as created')->groupBy('id');
        return $banners;
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
            ['data' => 'id', 'name' => 'banners.id', 'title' => 'Id'],
            ['data' => 'line_one', 'name' => 'banners.line_one', 'title' => 'Line One'],
            ['data' => 'line_two', 'name' => 'banners.line_two', 'title' => 'Line Two'],
            ['data' => 'button_one', 'name' => 'banners.button_one', 'title' => 'Button One'],
            ['data' => 'button_two', 'name' => 'banners.button_two', 'title' => 'Button Two'],
            ['data' => 'created', 'name' => 'banners.created', 'title' => 'Created At'],
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
        return 'riders_' . date('YmdHis');
    }
}
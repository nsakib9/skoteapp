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

use App\Models\Footer;
use Yajra\DataTables\Services\DataTable;
use DB;

class FooterDataTable extends DataTable
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
            ->addColumn('action', function ($footer) {
                $edit = (auth('admin')->user()->can('update_rider')) ? '<a href="'.url('admin/edit_footer/'.$footer->id).'" class="btn btn-xs btn-primary"><i class="bx bx-edit"></i></a>&nbsp;' : '';
                $delete = (auth('admin')->user()->can('delete_rider')) ? '<a data-href="'.url('admin/delete_footer/'.$footer->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="bx bx-trash"></i></a>&nbsp;':'';

                return $edit.$delete;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param Footer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Footer $model)
    {
        $footer = $model->select('footer.id as id', 'footer.leftColumn', 'footer.middleColumn', 'footer.rightColumn', 'footer.bottomRow')->groupBy('id');
        return $footer;
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
            ['data' => 'id', 'name' => 'footer.id', 'title' => 'Id'],
            ['data' => 'leftColumn', 'name' => 'footer.leftColumn', 'title' => 'Left Column'],
            ['data' => 'middleColumn', 'name' => 'footer.middleColumn', 'title' => 'Middle Column'],
            ['data' => 'rightColumn', 'name' => 'footer.rightColumn', 'title' => 'Right Column'],
            ['data' => 'bottomRow', 'name' => 'footer.bottomRow', 'title' => 'Bottom Row'],
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
        return 'footer_' . date('YmdHis');
    }
}
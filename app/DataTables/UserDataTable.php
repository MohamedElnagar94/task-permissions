<?php

namespace App\DataTables;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('edit', function ($query) {
                return '<a href="#" data-id="' . $query->id . '" class="btn btn-success showEditUser"><i class="fa fa-edit"></i> Edit</a>';
            })
            ->addColumn('delete', function ($query) {
                return '<a href="#" data-id="' . $query->id . '" data-name="' . $query->name . '" class="btn btn-danger showDeleteModel" data-toggle="modal" data-target="#delete-user"><i class="fa fa-eye"></i> Delete</a>';
            })
            ->addColumn('roles', function ($query) {
                return '<a href="#" data-id="' . $query->id . '" data-name="' . $query->name . '" class="btn btn-info showRolesModel" data-toggle="modal" data-target="#add-roles"><i class="fa fa-eye"></i> Roles</a>';
            })
            ->addColumn('role', function ($query) {
//                dump($query->modelHasRoles);
                return count($query->modelHasRoles) ? $query->modelHasRoles[0]->role->name : 'No roles';
            })
            ->rawColumns([
                'edit',
                'delete',
                'roles',
                'role',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @return Builder[]|Collection
     */
    public function query()
    {
        return User::with(array('modelHasRoles' => function ($query) {
            return $query->with('role');
        }))->get();
    }

    public static function lang()
    {
        return [
            "processing" => "Processing...",
            "lengthMenu" => "Show _MENU_ entries",
            "zeroRecords" => "No matching records found",
            "emptyTable" => "No data available in table",
            "info" => "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty" => "Showing 0 to 0 of 0 entries",
            "infoFiltered" => "(filtered from _MAX_ total entries)",
            "infoPostFix" => "",
            "search" => "Apply filter _INPUT_ to table",
            "url" => "",
            "thousands" => ",",
            "loadingRecords" => "Please wait - loading...",
            "decimal" => "-",
            "paginate" => [
                "first" => "First page",
                "last" => "Last page",
                "next" => "Next page",
                "previous" => "Previous page"
            ],
            "aria" => [
                "sortAscending" => ": activate to sort column ascending",
                "sortDescending" => ": activate to sort column descending"
            ]
        ];
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->addAction(['width' => '80px'])
            ->parameters([
                'dom' => 'Blfrtip',
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],
                'order' => [[0, 'desc']],
                'buttons' => [
                    ['extend' => 'print', 'className' => 'btn btn-primary', 'text' => '<i class="fa fa-print" style="margin-right:5px;margin-left:5px"></i>Print'],
                    ['extend' => 'csv', 'className' => 'btn btn-info', 'text' => '<i class="fas fa-file-csv" style="margin-right:5px;margin-left:5px"></i>CSV'],
                ],
                'initComplete' => "function () {
                    this.api().columns([]).every(function () {
                        var column = this;
                        var input = document.createElement(\"input\");
                        $(input).appendTo($(column.footer()).empty())
                        .on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                }",
                // 'scrollX' => true,
                'language' => self::lang(),
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => 'Id', 'orderable' => true, 'searchable' => true],
            ['name' => 'username', 'data' => 'username', 'title' => 'Username', 'orderable' => true, 'searchable' => true],
            ['name' => 'email', 'data' => 'email', 'title' => 'Email', 'orderable' => true, 'searchable' => true],
            ['name' => 'role', 'data' => 'role', 'title' => 'Role', 'orderable' => true, 'searchable' => true],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Created_at', 'orderable' => true, 'searchable' => true],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => 'Updated_at', 'orderable' => true, 'searchable' => true],
            ['name' => 'edit', 'data' => 'edit', 'title' => 'Edit', 'orderable' => false, 'searchable' => false, 'printable' => false, 'exportable' => false],
            ['name' => 'delete', 'data' => 'delete', 'title' => 'Delete', 'orderable' => false, 'searchable' => false, 'printable' => false, 'exportable' => false],
            ['name' => 'roles', 'data' => 'roles', 'title' => 'Roles', 'orderable' => false, 'searchable' => false, 'printable' => false, 'exportable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'User_' . date('YmdHis');
    }
}

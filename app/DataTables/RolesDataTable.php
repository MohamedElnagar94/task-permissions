<?php

namespace App\DataTables;

use App\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
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
                return '<a href="#" data-id="'.$query->id.'" class="btn btn-success showEditModel"><i class="fa fa-edit"></i> Edit</a>';
            })
            ->addColumn('delete', function ($query) {
                return '<a href="#" data-id="'.$query->id.'" data-name="'.$query->name.'" data-guardName="'.$query->guard_name.'" class="btn btn-danger showDeleteModel" data-toggle="modal" data-target="#delete-role"><i class="fa fa-eye"></i> Delete</a>';
            })
            ->addColumn('permissions', function ($query) {
                return '<a href="#" data-id="'.$query->id.'" data-name="'.$query->name.'" data-guardName="'.$query->guard_name.'" class="btn btn-info showPermissionsModel" data-toggle="modal" data-target="#add-permissions"><i class="fa fa-eye"></i> Permissions</a>';
            })
            ->rawColumns([
                'edit',
                'delete',
                'permissions'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Role::query();
    }
    public static function lang()
    {
        return [
            "processing"        =>      "Processing...",
            "lengthMenu"        =>      "Show _MENU_ entries",
            "zeroRecords"       =>      "No matching records found",
            "emptyTable"        =>      "No data available in table",
            "info"              =>      "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty"         =>      "Showing 0 to 0 of 0 entries",
            "infoFiltered"      =>      "(filtered from _MAX_ total entries)",
            "infoPostFix"       =>      "",
            "search"            =>      "Apply filter _INPUT_ to table",
            "url"               =>      "",
            "thousands"         =>      ",",
            "loadingRecords"    =>      "Please wait - loading...",
            "decimal"           =>      "-",
            "paginate"          => [
                "first"     =>      "First page",
                "last"      =>      "Last page",
                "next"      =>      "Next page",
                "previous"  =>      "Previous page"
            ],
            "aria" => [
                "sortAscending"     =>  ": activate to sort column ascending",
                "sortDescending"    =>  ": activate to sort column descending"
            ]
        ];
    }
    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
//            ->setTableId('role-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->addAction(['width' => '80px'])
            ->parameters([
                'dom'          => 'Blfrtip',
                'lengthMenu' => [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                'order'   => [[0, 'desc']],
                'buttons'      => [
                    [
                        'text' => '<i class="fa fa-plus"></i> ' . 'Add Role',
                        'className' => 'btn btn-primary create',
                        'attr' =>  [
                            'data-toggle' => 'modal',
                            'data-target' => '#add-role',
                        ],
                    ]
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
            ['name' => 'id','data' => 'id','title' => 'Id','orderable' => true ,'searchable' => true],
            ['name' => 'name','data' => 'name','title' => 'Name','orderable' => true ,'searchable' => true],
            ['name' => 'guard_name','data' => 'guard_name','title' => 'Guard Name','orderable' => true ,'searchable' => true],
            ['name' => 'created_at','data' => 'created_at','title' => 'Created_at','orderable' => true ,'searchable' => true],
            ['name' => 'updated_at','data' => 'updated_at','title' => 'Updated_at','orderable' => true ,'searchable' => true],
            ['name' => 'edit','data' => 'edit','title' => 'Edit','orderable' => false ,'searchable' => false, 'printable' => false, 'exportable' => false],
            ['name' => 'delete','data' => 'delete','title' => 'Delete','orderable' => false ,'searchable' => false, 'printable' => false, 'exportable' => false],
            ['name' => 'permissions','data' => 'permissions','title' => 'Permissions','orderable' => false ,'searchable' => false, 'printable' => false, 'exportable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Roles_' . date('YmdHis');
    }
}

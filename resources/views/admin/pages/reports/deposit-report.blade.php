@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Deposit Report</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Phone</th>
                                    <th>Original Date</th>
                                    <th>Date</th>
                                    <th>Points</th>
                                    <th>Status</th>
                                    <th>Deposit By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Sanjay</td>
                                    <td>918889836146</td>
                                    <td>02/07/2023 08:55:28 am</td>
                                    <td>07/02/2323</td>
                                    <td>270</td>
                                    <td><span class="badge badge-success">Deposit</span></td>
                                    <td>User</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

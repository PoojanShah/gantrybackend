@extends('layouts.app')
@section('content')
    <style>
        table img {
            height: 50px;
        }
    </style>
    <script>
        <?php if($data['success'] == 'Successfully added!') { ?>
        setTimeout(function () {
            blogAdded();
        }, 1000);
        <?php } ?>
    </script>
    <div class="container">
        <div class="row pb-2">
            <div class="col-md-6">
                <h4 class="page-title">Users (<?= $data['count']; ?>)</h4>
                <span style="color:#ff0000;"><?= $data['error']; ?></span>
                <span style="color:#008000;"><?= $data['success']; ?></span>
            </div>
            <div class="col-md-6" style="text-align: right; color:#fff;">
                <a href="/admin/users/add/" class="btn btn-success">+ Add Item</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-head-bg-default table-striped table-hover">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">User Type</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($data['users']) { ?>
                            <?php $i = 1;
                            foreach ($data['users'] as $item) { ?>
                            <tr>
                                <td width="80px"><?= $item->id; ?></td>
                                <td width="100px"><?= $item->email; ?></td>
                                <td width="200px">
                                    <?php if($item->superadmin == 1) { ?>Superadmin<?php } else { ?>Admin<?php } ?>
                                </td>
                                <td width="100px"><a href="/admin/users/edit/<?= $item->id; ?>/"
                                                     class="btn btn-primary btn-round">Edit</a></td>
                                <td width="100px"><span onClick="deleteUsersItem('<?= $item->id; ?>', $(this));"
                                                        class="btn btn-danger btn-round"> × Delete</span></td>
                            </tr>
                            <?php $i++; } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                        {{ $data['users']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


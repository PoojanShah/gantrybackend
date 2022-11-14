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
                        <table class="table table-head-bg-default table-striped table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Email</th>
                                <th scope="col">User Type</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($data['users']) { ?>
                            <?php $i = 1;
                            foreach ($data['users'] as $item) { ?>
                            <tr>
                                <td><?= $item->id; ?></td>
                                <td><?= $item->email; ?></td>
                                <td>
                                    <?php if($item->superadmin == 1) { ?>Superadmin<?php } else { ?>Customer<?php } ?>
                                </td>
                                <td>
                                    <a href="/admin/users/edit/<?= $item->id; ?>/"
                                       class="btn btn-primary btn-round icon-box"><i class="gg-pen"></i></a>
                                    <span onClick="deleteUsersItem('<?= $item->id; ?>', $(this));"
                                          class="btn btn-danger btn-round  icon-box"><i class="gg-trash"></i> </span>
                                </td>
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


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
                    <h4 class="page-title">Video (<?= $data['count']; ?> items)</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
                <div class="col-md-6" style="text-align: right; color:#fff;">
                    <a href="/admin/video/add/" class="btn btn-success">+ Add Item</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-head-bg-default table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Sort</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Thumbnail</th>
                                    <th scope="col">Addon code</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['video']) { ?>
                                <?php $i = 1;
                                foreach ($data['video'] as $item) { ?>
                                <tr>
                                    <td width="80px"><?= $item->sort; ?></td>
                                    <td><?= $item->title; ?></td>
                                    <td><img src="<?= $item->thumbnail; ?>"></td>
                                    <td width="80px"><?= $item->zoho_addon_code; ?></td>
                                    <td width="200px">
                                        <?php if($item->status == 1) { ?>On<?php } else { ?>Off<?php } ?>
                                    </td>
                                    <td width="100px"><a href="/admin/video/edit/<?= $item->id; ?>/"
                                                         class="btn btn-primary btn-round">Edit</a></td>
                                    <td width="100px"><span onClick="deleteVideoItem('<?= $item->id; ?>', $(this));"
                                                            class="btn btn-danger btn-round"> × Delete</span></td>
                                </tr>
                                <?php $i++; } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                            {{ $data['video']->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection


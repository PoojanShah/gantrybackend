@include('admin.header')
<style>
    table img {
        height:50px;
    }
</style>
<script>
    <?php if($data['success'] == 'Successfully added!') { ?>
    setTimeout( function () {
        blogAdded();
    }, 1000);
    <?php } ?>
</script>
<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <ul class="ml-0" style="padding-left: 15px;">
                    <?php if(isset($data['breadcrumbs'])) {
                    $b_count = count($data['breadcrumbs']); ?>
                    <?php $i=1; foreach($data['breadcrumbs'] as $breadcrumb) { ?>
                    <li class="badge"><a class="btn btn-default btn-xs" href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['title']; ?></a></li>
                    <?php if($i!=$b_count) { ?>><?php } ?>
                    <?php $i++; } ?>
                    <?php } ?>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h4 class="page-title">Blog (<?= $data['count']; ?> articles)</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
                <div class="col-md-6" style="text-align: right; color:#fff;">
                    <a href="/admin/blog/add/" class="btn btn-success">+ Add Item</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-head-bg-default table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['articles']) { ?>
                                <?php $i=1;
                                foreach ($data['articles'] as $item) { ?>
                                <tr>
                                    <td width="80px"><?= date('d.m.Y', strtotime($item->date)); ?></td>
                                    <td width="100px"><img src="<?= $item->image; ?>"></td>
                                    <td><?= $item->title; ?></td>
                                    <td><?= $item->slug; ?></td>
                                    <td width="200px">
                                        <?php if($item->status == 1) { ?>On<?php } else { ?>Off<?php } ?>
                                    </td>
                                    <td width="100px"><a href="/admin/blog/edit/<?= $item->id; ?>/" class="btn btn-primary btn-round">Edit</a></td>
                                    <td width="100px"><span onClick="deleteArticlesItem('<?= $item->id; ?>', $(this));" class="btn btn-danger btn-round"> × Delete</span></td>
                                </tr>
                                <?php $i++; } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                            <?= $data['pagination']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

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
                    <h4 class="page-title">Logofolio (<?= $data['count']; ?> items)</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
                <div class="col-md-6" style="text-align: right; color:#fff;">
                    <a href="/admin/logofolio/add/" class="btn btn-success">+ Add Item</a>
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
                                    <th scope="col">Image</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['logofolio']) { ?>
                                <?php $i=1;
                                foreach ($data['logofolio'] as $item) { ?>
                                <tr>
                                    <td width="80px"><?= $item->sort; ?></td>
                                    <td width="80px"><?= $item->title; ?></td>
                                    <td width="100px" style="background: #ddd;text-align: center;"><img src="<?= $item->image; ?>"></td>
                                    <td width="200px">
                                        <?php if($item->status == 1) { ?>On<?php } else { ?>Off<?php } ?>
                                    </td>
                                    <td width="100px"><a href="/admin/logofolio/edit/<?= $item->id; ?>/" class="btn btn-primary btn-round">Edit</a></td>
                                    <td width="100px"><span onClick="deleteLogofolioItem('<?= $item->id; ?>', $(this));" class="btn btn-danger btn-round"> × Delete</span></td>
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

@include('admin.header')

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
            <h4 class="page-title">Subscribers</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-head-bg-default table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['subscribers']) { ?>
                                <?php
                                    if(isset($_GET['page']) && $_GET['page']!=1) {
                                        $i=$_GET['page']*$data['per_page']-$data['per_page']+1;
                                    }
                                    else {
                                        $i=1;
                                    }

                                foreach ($data['subscribers'] as $item) { ?>
                                <tr>
                                    <td width="100px"><?= $i; ?></td>
                                    <td><?= $item->email; ?></td>
                                    <td width="100px"><span onClick="$(this).parent().parent().remove(); deleteSubscribersItem('<?= $item->id; ?>');" class="btn btn-danger btn-round"> × Delete</span></td>
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
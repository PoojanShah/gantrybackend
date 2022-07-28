@include('admin.header')
<style>
    table img {
        height:50px;
    }
</style>
<script>
    <?php if($data['success'] == 'Successfully added!') { ?>
    setTimeout( function () {
        productAdded();
    }, 1000);
    <?php } ?>
</script>

<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <div class="row" style="margin:0px 0px 20px 0px;">
<?php if(empty($_GET['category_id'])) { $_GET['category_id'] = 0; }?>
                Фильтр по категориям: &nbsp;&nbsp;
                    <select onChange="var cur_val = $(this).val(); location.href='/admin/products/?category_id='+cur_val;">
                        <option value="0">Все категории</option>
                        <?php foreach($data['categories'] as $category) { ?>
                            <option value="<?= $category->id; ?>" <?php if($_GET['category_id'] == $category->id) { ?>selected<?php } ?>><?= $category->title; ?></option>
                        <?php } ?>
                    </select>
            </div>

            <div class="row" style="margin:0px;">
                <form style="width: 50%;margin-bottom: 25px;" id="searchform" action="/admin/products/" method="get">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="Search..." class="form-control" value="<?php if(isset($_GET['search'])) { echo $_GET['search']; } ?>">
                        <div class="input-group-append" onClick="$('#searchform').submit();">
                            <span class="input-group-text">
                                <i class="la la-search search-icon"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

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
                    <h4 class="page-title">Products (<?= $data['count']; ?>)</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
                <div class="col-md-6" style="text-align: right; color:#fff;">
                    <a href="/admin/products/add/" class="btn btn-success">+ Добавить новый товар</a>
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
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Link</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Copy</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if($data['products']) { ?>
                                        <?php $i=1;
                                        foreach ($data['products'] as $item) { ?>
                                            <tr>
                                                <td width="80px"><?= $item->id; ?></td>
                                                <td width="100px"><img src="<?= $item->image; ?>"></td>
                                                <td><?= $item->title; ?></td>
                                                <td><?= $item->link; ?></td>
                                                <td width="200px">
                                                    <?php if($item->status == 1) { ?>On<?php } else { ?>Off<?php } ?>
                                                </td>
                                                <td width="40px"><a href="/admin/products/copy/<?= $item->id; ?>/" class="btn btn-primary btn-round">Copy</a></td>
                                                <td width="100px"><a href="/admin/products/edit/<?= $item->id; ?>/" class="btn btn-primary btn-round">Edit</a></td>
                                                <td width="100px"><span onClick="deleteProductItem('<?= $item->id; ?>', $(this));" class="btn btn-danger btn-round"> × Delete</span></td>
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
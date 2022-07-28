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
            <h4 class="page-title">Footer Menu</h4>
            <p>You must click on Save button after all changes on this page (except Delete button)!</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="menu_footer_form" method="post">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <table class="table table-head-bg-default table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">Sort</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Key</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($data['menu']) { ?>
                                    <?php $i=1;
                                    foreach ($data['menu'] as $item) { ?>
                                    <tr>
                                        <td width="100px"><input style="width:50%;" type="text" name="sort[<?= $i; ?>][]" value="<?= $item->sort; ?>"></td>
                                        <td><input style="width:50%;" type="text" name="value[<?= $i; ?>][]" value="<?= $item->value; ?>"></td>
                                        <td><input style="width:50%;" type="text" name="menu_key[<?= $i; ?>][]" value="<?= $item->menu_key; ?>"></td>
                                        <td width="200px">
                                            <input name="status[<?= $i; ?>][]" type="checkbox" <?php if($item->status == 1) { ?>checked<?php } ?> data-toggle="toggle" data-onstyle="default" data-style="btn-round">
                                        </td>
                                        <td width="100px"><span onClick="deleteMenuFooterItem('<?= $item->id; ?>', $(this));" class="btn btn-danger btn-round"> × Delete</span></td>
                                    </tr>
                                    <?php $i++; } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="submit" value="SAVE" class="btn btn-success">
                                        <span id="save_success"></span>
                                    </div>
                                    <div class="col-md-3" style="text-align: right;">
                                        <a id="add_button" onClick="addItemFooterMenu(<?= $i; ?>);" class="btn btn-default">+ Add Item</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

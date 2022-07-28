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
            <h4 class="page-title">Attributes</h4>
            <p>You must click on Save button after all changes on this page (except Delete button)!</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="attr_form" method="post">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <table class="table table-head-bg-default table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Sort</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($data['attributes']) { ?>
                                            <?php $i=1;
                                            foreach ($data['attributes'] as $item) { ?>
                                                <tr>
                                                    <td><input style="width:80%;" type="text" name="attribute[<?= $item->id; ?>][]" value="<?= $item->name; ?>"></td>
                                                    <td>
                                                        <select name="category[<?= $item->id; ?>][]">
                                                            <?php foreach($data['categories'] as $category) { ?>
                                                                <option <?php if($category->id == $item->category_id) { ?>selected<?php } ?> value="<?= $category->id; ?>"><?= $category->title; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="sort[<?= $item->id; ?>][]" value="<?= $item->sort; ?>"></td>
                                                    <td width="100px"><span onClick="$(this).parent().parent().remove(); deleteAttrItem('<?= $item->id; ?>');" class="btn btn-danger btn-round"> × Delete</span></td>
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
                                        <a id="add_button" onClick="addItemAttr(<?= $i; ?>);" class="btn btn-default">+ Add Item</a>
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
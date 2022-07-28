@include('admin.header')
<style>
    table img {
        height:50px;
    }
    #imgs img {
        height: 75px;
    }
    .card-body {
        width:50%;
    }
    #output_image, #output_big_image, .images {
        height:170px;
    }
    #attrs input {
        width:100%;
    }
</style>
<script>
    <?php if($data['success'] == 'Successfully updated!') { ?>
    setTimeout( function () {
        productAdded();
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
                    <h4 class="page-title">Edit Product - <?= $data['product']->title; ?></h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
                <div class="col-md-6" style="text-align: right; color:#fff;">
                    <a href="/admin/products/add/" class="btn btn-success">+ Добавить новый товар</a>
                    <button onClick="$('form#editForm').submit();" class="btn btn-success">Сохранить</button>
                    <a href="/admin/products/" class="btn btn-danger">Отмена</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="editForm" action="/admin/products/edit/<?= $data['product']->id; ?>/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body" style="float:left;">
                                <div class="form-group">
                                    <label for="title">Title*</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" value="<?= $data['product']->title; ?>" required>
                                    <small style="display: none;" id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="seourl">SEO URL</label>
                                    <input type="text" name="link" class="form-control" id="seourl" placeholder="SEO URL" value="<?php echo (!empty($data['request']['link'])) ? $data['request']['link'] : $data['product']->link; ?>">
                                    <small id="emailHelp" style="color:#ff0000 !important;" class="form-text text-muted"><?= $data['error_link']; ?></small>
                                    <small id="emailHelp" class="form-text text-muted">It will look like this: https://<?= $_SERVER['HTTP_HOST']; ?>/product/{SEO URL}/</small>
                                    <small id="emailHelp" class="form-text text-muted">You need to write only {SEO URL}</small>
                                </div>

                                <div class="form-group">
                                    <label for="price">Артикул*</label>
                                    <input type="text" name="article" id="article" class="form-control" placeholder="Enter article" value="<?= $data['product']->article; ?>" required>
                                </div>

								<div class="form-group">
                                    <label for="price">Цена*</label>
                                    <input type="text" name="price" id="price" class="form-control" placeholder="Enter price" value="<?= $data['product']->price; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="price">Цена Акционная*</label>
                                    <input type="text" name="price_sale" id="price_sale" class="form-control" placeholder="Enter price (sale)" value="<?= $data['product']->price_sale; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Наличие</label>
                                    <select name="stock" class="form-control">
                                        <option value="В наявності" <?php if($data['product']->stock == 'В наявності') { echo "selected"; } ?>>В наявності</option>
                                        <option value="Немає в наявності" <?php if($data['product']->stock == 'Немає в наявності') { echo "selected"; } ?>>Немає в наявності</option>
                                        <option value="Закінчується" <?php if($data['product']->stock == 'Закінчується') { echo "selected"; } ?>>Закінчується</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Category</label>
                                    <select name="category" class="form-control" id="exampleFormControlSelect1">
                                        <?php if(isset($data['categories'])) { ?>
                                            <?php foreach ($data['categories'] as $category) { ?>
                                                <?php if(!empty($data['current_category'])) { ?>
                                                    <option value="<?= $category->id; ?>" <?php if($category->id == $data['current_category']->category_id) { ?>selected=""<?php } ?>><?= $category->title; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $category->id; ?>"><?= $category->title; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group" style="display:none;">
                                    <label for="short_description">Short Description</label>
                                    <textarea name="short_description" class="form-control" id="short_description" rows="5"><?= $data['product']->short_description; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="short_description">Description</label>
                                    <textarea name="description" class="form-control" id="description" rows="5"><?= $data['product']->description; ?></textarea>
                                </div>
                                <div class="form-group" style="display:none;">
                                    <label for="short_description">Big Description</label>
                                    <textarea name="big_description" class="form-control" id="big_description" rows="5"><?= $data['product']->big_description; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Image</label>
                                    <input type="file" name="image" class="form-control-file" onchange="previewImage(event)" accept="image/jpeg,image/png,image/gif" id="exampleFormControlFile1">
                                    <img <?php if(empty($data['product']->image)) { ?>style="display: none;"<?php } ?> src="<?= $data['product']->image; ?>" id="output_image" class="create-event__file-preview"/>
                                </div>

                                <div class="form-group">
                                    <div class="card-title">Images for inner page</div>
                                    <table class="table table-bordered" id="imgs">
                                        <tr>
                                            <td>Image</td>
                                            <td>Sort</td>
                                            <td>Delete</td>
                                        </tr>
                                        <?php $i=1; foreach ($data['product_images'] as $images) { ?>
                                            <tr>
                                                <input type="hidden" name="images[<?= $i; ?>][0]" value="<?= $images->image; ?>">
                                                <td><img src="<?= $images->image; ?>"></td>
                                                <td><input type="text" value="<?= $images->sort; ?>" name="images[<?= $i; ?>][1]"></td>
                                                <td style="width:100px;"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>
                                            </tr>
                                        <?php $i++; } ?>
                                    </table>
                                    <span id="addImgButton" onClick="addImageItem(<?= $i; ?>);" class="btn btn-default">+ Add Item</span>
                                </div>

                                <div class="form-group">
                                    <div class="card-title">Доступные цвета товара</div>
                                    <table class="table table-bordered" id="colors">
                                        <tr>
                                            <td>Изображение</td>
                                            <td>Название</td>
                                            <td>Удалить</td>
                                        </tr>
                                        <?php $i=1; foreach ($data['product_colors'] as $colors) { ?>
                                            <tr>
                                                <input type="hidden" name="colors[<?= $i; ?>][0]" value="<?= $colors->image; ?>">
                                                <td><img src="<?= $colors->image; ?>"></td>
                                                <td><input type="text" value="<?= $colors->name; ?>" name="colors[<?= $i; ?>][1]"></td>
                                                <td style="width:100px;"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>
                                            </tr>
                                        <?php $i++; } ?>
                                    </table>
                                    <span id="addColorButton" onClick="addColorItem(<?= $i; ?>);" class="btn btn-default">+ Add Item</span>
                                </div>


                                <div class="form-check">
                                    <label>Status</label><br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="status" value="1" <?php if($data['product']->status == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="status" value="0" <?php if($data['product']->status == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>

								<div class="form-check">
                                    <label>Топ продаж</label><br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="top" value="1" <?php if($data['product']->top == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Да</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="top" value="0" <?php if($data['product']->top == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Нет</span>
                                    </label>
                                </div>

								<div class="form-check">
                                    <label>Показывать на главной</label><br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="home" value="1" <?php if($data['product']->home == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Да</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="home" value="0" <?php if($data['product']->home == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Нет</span>
                                    </label>
                                </div>

                                <div class="form-check" style="display: none;">
                                    <label>Показывать на странице товара</label><br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="prod" value="1" <?php if($data['product']->prod == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Да</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="prod" value="0" <?php if($data['product']->prod == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Нет</span>
                                    </label>
                                </div>



                            </div>
                            <div class="card-body" style="float:right;">
                                <div class="form-group">
                                    <div class="card-title">Attributes</div>
                                    <table class="table table-bordered" id="attrs">
                                        <tr>
                                            <td>Attribute</td>
                                            <td>Value</td>
                                        </tr>

                                        <?php $i=1; foreach ($data['attributes'] as $attribute) { ?>
                                            <?php if(!empty($attribute)) { ?>
                                                <tr>
                                                    <td><?= $attribute['name']; ?></td>
                                                    <td><input type="text" name="attribute[<?= $attribute['id']; ?>][]" value="<?= $attribute['value']; ?>"></td>
                                                </tr>
                                            <?php } ?>
                                        <?php $i++; } ?>
                                    </table>

                                </div>
                                <div class="form-check">
                                    <img src="/images/svg/product-icons/product-icon-1.svg">
                                    <label>Еко-пакування</label>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="icon1" value="1" <?php if($data['product']->icon1 == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="icon1" value="0" <?php if($data['product']->icon1 == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <img src="/images/svg/product-icons/product-icon-2.svg">
                                    <label>Ручна робота</label>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="icon2" value="1" <?php if($data['product']->icon2 == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="icon2" value="0" <?php if($data['product']->icon2 == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <img src="/images/svg/product-icons/product-icon-3.svg">
                                    <label>Багаторазового використання</label>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="icon3" value="1" <?php if($data['product']->icon3 == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="icon3" value="0" <?php if($data['product']->icon3 == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <img src="/images/svg/product-icons/product-icon-4.svg">
                                    <label>Без тестування на тваринах</label>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="icon4" value="1" <?php if($data['product']->icon4 == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="icon4" value="0" <?php if($data['product']->icon4 == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <img src="/images/svg/product-icons/product-icon-5.svg">
                                    <label>Кругова економіка</label>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="icon5" value="1" <?php if($data['product']->icon5 == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="icon5" value="0" <?php if($data['product']->icon5 == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <img src="/images/svg/product-icons/product-icon-6.svg">
                                    <label>Натуральний продукт</label>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="icon6" value="1" <?php if($data['product']->icon6 == 1) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="icon6" value="0" <?php if($data['product']->icon6 == 0) { ?>checked=""<?php } ?>>
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>
                            </div>
                            <div style="clear:both;"></div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Сохранить</button>
                                <a href="/admin/products/" class="btn btn-danger">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

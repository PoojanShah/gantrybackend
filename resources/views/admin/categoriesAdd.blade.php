@include('admin.header')
<style>
    table img {
        height:50px;
    }
    .card-body {
        width:50%;
    }
    #output_image, #output_big_image {
        height:170px;
    }
</style>
<script>
    <?php if($data['success'] == 'Successfully added!') { ?>
    setTimeout( function () {
        categoryAdded();
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
                    <h4 class="page-title">Добавление новой категории</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/categories/add/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Название категории*</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" value="<?= (!empty($data['request']['title'])) ? $data['request']['title'] : ""; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="seourl">SEO URL</label>
                                    <input type="text" name="link" class="form-control" id="seourl" placeholder="SEO URL" value="<?= (!empty($data['request']['link'])) ? $data['request']['link'] : ""; ?>">
                                    <small id="emailHelp" style="color:#ff0000 !important;" class="form-text text-muted"><?= $data['error_link']; ?></small>
                                    <small id="emailHelp" class="form-text text-muted">Это будет выглядеть так: https://<?= $_SERVER['HTTP_HOST']; ?>/category/{SEO URL}/</small>
                                    <small id="emailHelp" class="form-text text-muted">Вам нужно ввести только {SEO URL}</small>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Parent Category</label>
                                    <select name="category" class="form-control" id="exampleFormControlSelect1">
                                        <option value="0">Выберите родительскую категорию</option>
                                        <?php if(isset($data['categories'])) { ?>
                                        <?php foreach ($data['categories'] as $category) { ?>
                                        <option value="<?= $category->id; ?>"><?= $category->title; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

								<div class="form-group">
									<label for="seo_title">Seo Title*</label>
									<input type="text" name="seo_title" id="seo_title" class="form-control" placeholder="Enter Seo Title" value="<?= (!empty($data['request']['seo_title'])) ? $data['request']['seo_title'] : ""; ?>" required>
								</div>
								<div class="form-group">
									<label for="seo_description">Seo Description*</label>
									<input type="text" name="seo_description" id="seo_description" class="form-control" placeholder="Enter Seo Description" value="<?= (!empty($data['request']['seo_description'])) ? $data['request']['seo_description'] : ""; ?>" required>
								</div>
								<div class="form-group">
									<label for="seo_keywords">Seo Keywords*</label>
									<input type="text" name="seo_keywords" id="seo_keywords" class="form-control" placeholder="Enter Seo Keywords" value="<?= (!empty($data['request']['seo_keywords'])) ? $data['request']['seo_keywords'] : ""; ?>" required>
								</div>


                                <div class="form-group" style="display:none;">
                                    <label for="short_description">Краткое описание</label>
                                    <textarea name="short_description" class="form-control" id="short_description" rows="5"></textarea>
                                </div>

								<div class="form-group">
									<label for="description">Description</label>
									<textarea name="description" class="form-control" id="description" rows="5"><?= (!empty($data['request']['description'])) ? $data['request']['description'] : ""; ?></textarea>
								</div>


                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Изображение</label>
                                    <input type="file" name="image" class="form-control-file" onchange="previewImage(event)" accept="image/jpeg,image/png,image/gif" id="exampleFormControlFile1">
                                    <img style="display: none;" id="output_image" class="create-event__file-preview"/>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Icon</label>
                                    <input type="file" name="big_image" class="form-control-file" onchange="previewBigImage(event)" accept="image/*" id="exampleFormControlFile2">
                                    <img style="display: none;" id="output_big_image" class="create-event__file-preview"/>
                                </div>


                                <div class="form-check">
                                    <label>Статус</label><br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="status" value="1" checked="">
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="status" value="0">
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="sort">Сортировка</label>
                                    <input type="text" name="sort" id="sort" class="form-control" placeholder="Sort">
                                </div>

                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Сохранить</button>
                                <a href="/admin/categories/" class="btn btn-danger">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

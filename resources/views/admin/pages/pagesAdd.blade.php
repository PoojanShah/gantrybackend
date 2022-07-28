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
        pageAdded();
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
                    <h4 class="page-title">Add New Page</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/pages/add/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title*</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" value="<?php if(isset($data['request']['title'])) { echo $data['request']['title']; } ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="seourl">Key*</label>
                                    <input type="text" name="link" class="form-control" id="seourl" placeholder="Key" value="<?php if(isset($data['request']['link'])) { echo $data['request']['link']; } ?>" required>
                                    <small id="emailHelp" style="color:#ff0000 !important;" class="form-text text-muted"><?= $data['error_link']; ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="short_description">Description</label>
                                    <textarea name="description" class="form-control" id="description" rows="5"><?php if(isset($data['request']['description'])) { echo $data['request']['description']; } ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Image</label>
                                    <input type="file" name="image" class="form-control-file" onchange="previewImage(event)" accept="image/jpeg,image/png,image/gif" id="exampleFormControlFile1">
                                    <img style="display: none;" src="" id="output_image" class="create-event__file-preview"/>
                                </div>

                                <div class="form-group">
                                    <label for="seo_title">SEO Title</label>
                                    <input type="text" name="seo_title" id="seo_title" class="form-control" placeholder="Enter SEO Title" value="<?php if(isset($data['request']['seo_title'])) { echo $data['request']['seo_title']; } ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="seo_description">SEO Description</label>
                                    <input type="text" name="seo_description" id="seo_description" class="form-control" placeholder="Enter SEO Description" value="<?php if(isset($data['request']['seo_description'])) { echo $data['request']['seo_description']; } ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="seo_keywords">SEO Keywords</label>
                                    <input type="text" name="seo_keywords" id="seo_keywords" class="form-control" placeholder="Enter SEO Keywords" value="<?php if(isset($data['request']['seo_keywords'])) { echo $data['request']['seo_keywords']; } ?>" required>
                                </div>



                                <div class="form-check">
                                    <label>Status</label><br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="status" value="1" checked="">
                                        <span class="form-radio-sign">On</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="status" value="0">
                                        <span class="form-radio-sign">Off</span>
                                    </label>
                                </div>

                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="/admin/pages/" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

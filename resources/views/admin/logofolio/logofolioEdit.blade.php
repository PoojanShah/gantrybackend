@include('admin.header')
<style>
    table img {
        height:50px;
    }
    #imgs img {
        height: 75px;
    }
    .card-body {
        width:100%;
    }
    #output_image, #output_image2, #output_image3, #output_big_image, .images {
        height:170px;
        background: #ddd;
    }
    #attrs input {
        width:100%;
    }
</style>
<script>
    <?php if($data['success'] == 'Successfully updated!') { ?>
    setTimeout( function () {
        blogEdited();
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
                <div class="col-md-12">
                    <h4 class="page-title">Edit Logo</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/logofolio/edit/<?= $data['logofolio']->id; ?>/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label for="title">Title*</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" value="<?php if(isset($data['logofolio']->title)) { echo $data['logofolio']->title; } ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="subtitle">Subtitle*</label>
                                        <input type="text" name="subtitle" id="subtitle" class="form-control" placeholder="Enter Subtitle" value="<?php if(isset($data['logofolio']->subtitle)) { echo $data['logofolio']->subtitle; } ?>" required>
                                    </div>

                                    <div class="form-check">
                                        <label>Status</label><br>
                                        <label class="form-radio-label">
                                            <input class="form-radio-input" type="radio" name="status" value="1" <?php if($data['logofolio']->status == 1) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">On</span>
                                        </label>
                                        <label class="form-radio-label ml-3">
                                            <input class="form-radio-input" type="radio" name="status" value="0" <?php if($data['logofolio']->status == 0) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">Off</span>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">Sort</label>
                                        <input style="width:200px;" type="text" name="sort" class="form-control" value="<?= $data['logofolio']->sort ? $data['logofolio']->sort : ''; ?>">
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">Image</label>
                                        <input type="file" name="image" class="form-control-file" onchange="previewImage(event)" accept="image/jpeg,image/png,image/gif" id="exampleFormControlFile1">
                                        <img <?php if(empty($data['logofolio']->image)) { ?>style="display: none;"<?php } ?> src="<?= $data['logofolio']->image; ?>" id="output_image" class="create-event__file-preview"/>
                                    </div>

                                </div>

                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="/admin/logofolio/" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

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
                    <h4 class="page-title">Edit Vacancy - <?= $data['vacancies']->title; ?></h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/vacancies/edit/<?= $data['vacancies']->id; ?>/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title*</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" value="<?php if(isset($data['vacancies']->title)) { echo $data['vacancies']->title;} ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="slug">Slug*</label>
                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Enter slug" value="<?php if(isset($data['vacancies']->slug)) { echo $data['vacancies']->slug;} ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="level">Level*</label>
                                        <input type="text" name="level" id="level" class="form-control" placeholder="Enter Level" value="<?php if(isset($data['vacancies']->level)) { echo $data['vacancies']->level;} ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="short_description">Description</label>
                                        <textarea name="description" class="form-control" id="description" rows="5"><?php if(isset($data['vacancies']->description)) { echo $data['vacancies']->description; } ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="sort">Sort</label>
                                        <input type="number" name="sort" id="sort" class="form-control" placeholder="Enter Sort" value="<?php if(isset($data['vacancies']->sort)) { echo $data['vacancies']->sort;} ?>" required>
                                    </div>

                                    <div class="form-check">
                                        <label>Status</label><br>
                                        <label class="form-radio-label">
                                            <input class="form-radio-input" type="radio" name="status" value="1" <?php if($data['vacancies']->status == 1) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">On</span>
                                        </label>
                                        <label class="form-radio-label ml-3">
                                            <input class="form-radio-input" type="radio" name="status" value="0" <?php if($data['vacancies']->status == 0) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">Off</span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="/admin/vacancies/" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

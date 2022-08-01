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
        teamAdded();
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
                    <h4 class="page-title">Add Employee</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/team/add/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Name*</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" value="<?php if(isset($data['request']['name'])) { echo $data['request']['name'];} ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="position">Position*</label>
                                        <input type="text" name="position" id="position" class="form-control" placeholder="Enter Position" value="<?php if(isset($data['request']['position'])) { echo $data['request']['position'];} ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="class">CSS Class*</label>
                                        <input type="text" name="class" id="class" class="form-control" placeholder="Enter CSS Class" value="<?php if(isset($data['request']['class'])) { echo $data['request']['class'];} ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="sort">Sort</label>
                                        <input type="number" name="sort" id="sort" class="form-control" placeholder="Enter Sort" value="<?php if(isset($data['request']['sort'])) { echo $data['request']['sort'];} ?>" required>
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

                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">Image</label>
                                        <input type="file" name="image" class="form-control-file" onchange="previewImage(event)" accept="image/jpeg,image/png,image/gif" id="exampleFormControlFile1">
                                        <img style="display: none;" src="" id="output_image" class="create-event__file-preview"/>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleFormControlFile2">GIF</label>
                                        <input type="file" name="gif" class="form-control-file" onchange="previewImage2(event)" accept="image/gif" id="exampleFormControlFile2">
                                        <img style="display: none;" src="" id="output_image2" class="create-event__file-preview"/>
                                    </div>

                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="/admin/team/" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

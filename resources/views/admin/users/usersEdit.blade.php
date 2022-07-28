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
    #output_image, #output_image2, #output_big_image, .images {
        height:170px;
    }
    #output_image3 {
        height:50px;
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
                    <h4 class="page-title">Edit User - <?= $data['user']->email; ?></h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/users/edit/<?= $data['user']->id; ?>/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Email*</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="<?= $data['user']->email ? $data['user']->email : ''; ?>" required>
                                        <small id="emailHelp" style="color:#ff0000 !important;position: absolute;" class="form-text text-muted"><?= $data['error_link']; ?></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="seourl">Password</label>
                                        <input type="text" minlength="5" name="password" class="form-control" id="password" placeholder="Password">
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-check">
                                        <label>Superadmin</label><br>
                                        <label class="form-radio-label">
                                            <input class="form-radio-input" type="radio" name="superadmin" value="1" <?php if($data['user']->superadmin == 1) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">On</span>
                                        </label>
                                        <label class="form-radio-label ml-3">
                                            <input class="form-radio-input" type="radio" name="superadmin" value="0" <?php if($data['user']->superadmin == 0) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">Off</span>
                                        </label>
                                    </div>
                                </div>


                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="/admin/users/" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

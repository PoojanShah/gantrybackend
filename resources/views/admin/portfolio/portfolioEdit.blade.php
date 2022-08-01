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
                    <h4 class="page-title">Edit Portfolio - <?= $data['portfolio']->title; ?></h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/portfolio/edit/<?= $data['portfolio']->id; ?>/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title*</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" value="<?= $data['portfolio']->title ? $data['portfolio']->title : ''; ?>" required>
                                    </div>


                                    <div class="form-group">
                                        <label for="category">Category*</label>
                                        <select name="category" class="form-control" required>
                                            <option value="">Choose Category</option>
                                            <option value="UI/UX Design" <?php if($data['portfolio']->category == 'UI/UX Design') { ?>selected<?php } ?>>UI/UX Design</option>
                                            <option value="Web Development" <?php if($data['portfolio']->category == 'Web Development') { ?>selected<?php } ?>>Web Development</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">Date of Publication</label>
                                        <input style="width:200px;" type="date" name="date" class="form-control" value="<?= date('Y-m-d', strtotime($data['portfolio']->date)); ?>">
                                    </div>

                                    <div class="form-check">
                                        <label>Status</label><br>
                                        <label class="form-radio-label">
                                            <input class="form-radio-input" type="radio" name="status" value="1" <?php if($data['portfolio']->status == 1) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">On</span>
                                        </label>
                                        <label class="form-radio-label ml-3">
                                            <input class="form-radio-input" type="radio" name="status" value="0" <?php if($data['portfolio']->status == 0) { ?>checked=""<?php } ?>>
                                            <span class="form-radio-sign">Off</span>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">Sort</label>
                                        <input style="width:200px;" type="text" name="sort" class="form-control" value="<?= $data['portfolio']->sort ? $data['portfolio']->sort : ''; ?>">
                                    </div>
                                </div>


                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">Image</label>
                                        <input type="file" name="image" class="form-control-file" onchange="previewImage(event)" accept="image/jpeg,image/png,image/gif" id="exampleFormControlFile1">
                                        <img <?php if(empty($data['portfolio']->image)) { ?>style="display: none;"<?php } ?> src="<?= $data['portfolio']->image; ?>" id="output_image" class="create-event__file-preview"/>
                                    </div>

                                </div>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="short_description">Description</label>
                                        <textarea name="description" class="form-control" id="description" rows="5"><?= $data['portfolio']->description; ?></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <div class="card-title">Our Contributions</div>
                                    <table class="table table-bordered" id="attrs">
                                        <tr>
                                            <td>Value</td>
                                            <td style="width:150px;">Delete</td>
                                        </tr>
                                        <?php $i=1; foreach ($data['contributions'] as $value) { ?>
                                        <tr>
                                            <td><input type="text" class="form-control" name="contributions[]" value="<?= $value->value; ?>" required></td>
                                            <td><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>
                                        </tr>
                                        <?php $i++; } ?>
                                    </table>
                                    <div class="col-md-12" style="text-align: right;">
                                        <a id="add_button" onclick="addContributions();" class="btn btn-default">+ Add Item</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <div class="card-title">Related Links</div>
                                    <table class="table table-bordered" id="attrs1">
                                        <tr>
                                            <td>Text</td>
                                            <td>Link</td>
                                            <td>Delete</td>
                                        </tr>
                                        <?php $i=1; foreach ($data['links'] as $value) { ?>
                                        <tr>
                                            <td><input type="text" class="form-control" name="links[value][]" value="<?= $value->value; ?>" required></td>
                                            <td><input type="text" class="form-control" name="links[link][]" value="<?= $value->link; ?>" required></td>
                                            <td><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>
                                        </tr>
                                        <?php $i++; } ?>
                                    </table>
                                    <div class="col-md-12" style="text-align: right;">
                                        <a id="add_button" onclick="addLinks();" class="btn btn-default">+ Add Item</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <div class="card-title">FlagList</div>
                                    <table class="table table-bordered" id="attrs2">
                                        <tr>
                                            <td>SVG</td>
                                            <td>Sort</td>
                                            <td>Delete</td>
                                        </tr>
                                        <?php $i=1; foreach ($data['flaglist'] as $value) { ?>
                                        <tr>
                                            <td>
                                                <select name="flaglist[svg][]" class="form-control" required>
                                                    <option value="">Choose Flag</option>
                                                    <option value="img/ai.svg" <?php if($value->svg == 'img/ai.svg') { ?>selected<?php } ?>>AI</option>
                                                    <option value="img/aiga.svg" <?php if($value->svg == 'img/aiga.svg') { ?>selected<?php } ?>>AIGA</option>
                                                    <option value="img/behance.svg" <?php if($value->svg == 'img/behance.svg') { ?>selected<?php } ?>>Behance</option>
                                                    <option value="img/gr.svg" <?php if($value->svg == 'img/gr.svg') { ?>selected<?php } ?>>GR</option>
                                                    <option value="img/in.svg" <?php if($value->svg == 'img/in.svg') { ?>selected<?php } ?>>IN</option>
                                                    <option value="img/ps.svg" <?php if($value->svg == 'img/ps.svg') { ?>selected<?php } ?>>PS</option>
                                                    <option value="img/ss.svg" <?php if($value->svg == 'img/ss.svg') { ?>selected<?php } ?>>SS</option>
                                                    <option value="img/xd.svg" <?php if($value->svg == 'img/xd.svg') { ?>selected<?php } ?>>XD</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="flaglist[sort][]" value="<?= $value->sort; ?>" required></td>
                                            <td><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>
                                        </tr>
                                        <?php $i++; } ?>
                                    </table>
                                    <div class="col-md-12" style="text-align: right;">
                                        <a id="add_button" onclick="addFlags();" class="btn btn-default">+ Add Item</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="/admin/portfolio/" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

@include('admin.header')
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
            <h4 class="page-title">Careers</h4>
            <p>You must click on Save button after all changes on this page!</p>
            <span style="color:#ff0000;"><?= $data['error']; ?></span>
            <span style="color:#008000;"><?= $data['success']; ?></span>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="/admin/page/careers/save/" method="post">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <div class="card-body row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="seo_title">Seo Title*</label>
                                            <input type="text" name="seo_title" id="seo_title" class="form-control" placeholder="Enter Seo Title" value="<?= $data['careers']->seo_title; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="seo_description">Seo Description</label>
                                            <input type="text" name="seo_description" id="seo_description" class="form-control" placeholder="Enter Seo Description" value="<?= $data['careers']->seo_description; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="seo_keywords">Seo Keywords</label>
                                            <input type="text" name="seo_keywords" id="seo_keywords" class="form-control" placeholder="Enter Seo Keywords" value="<?= $data['careers']->seo_keywords; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="submit" value="SAVE" class="btn btn-success">
                                        <span id="save_success"></span>
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

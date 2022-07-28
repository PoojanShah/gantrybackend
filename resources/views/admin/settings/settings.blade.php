@include('admin.header')
<style>
    .form-group input {
        width:100%;
    }
    .form-group label {
        text-transform: uppercase;
        margin-bottom: .1rem !important;
    }
    .form-group {
        padding:10px 10px;
        border-top:1px solid #eee;
        border-left:1px solid #eee;
        border-right:1px solid #eee;
    }
</style>
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
            <h4 class="page-title">Settings</h4>
            <p>On this page you can edit all static parameters (e.g. Instagram, Facebook etc)</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="settings_form" method="post">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <table class="table table-head-bg-default table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">Key</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Additional Attribute</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($data['settings']) { ?>
                                    <?php $i=1;
                                    foreach ($data['settings'] as $item) { ?>
                                    <tr>
                                        <td><input style="width:80%;" type="text" name="settings_key[<?= $i; ?>][]" value="<?= $item->settings_key; ?>"></td>
                                        <td><input style="width:90%;" type="text" name="settings_value[<?= $i; ?>][]" value="<?= $item->settings_value; ?>"></td>
                                        <td><input style="width:80%;" type="text" name="settings_attr[<?= $i; ?>][]" value="<?= $item->settings_attr; ?>"></td>
                                        <td width="100px"><span onClick="deleteSettingsItem('<?= $item->id; ?>', $(this));" class="btn btn-danger btn-round"> × Delete</span></td>
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
                                        <a id="add_button" onClick="addItemSettings(<?= $i; ?>);" class="btn btn-default">+ Add Item</a>
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
<footer class="footer">
    <div class="container-fluid">

        <div class="copyright ml-auto">
            &copy; <?= date('Y'); ?>
        </div>
    </div>
</footer>
</div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title"><i class="la la-frown-o"></i> Under Development</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Currently the pro version of the <b>Ready Dashboard</b> Bootstrap is in progress development</p>
                <p>
                    <b>We'll let you know when it's done</b></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.ckeditor.com/4.13.1/full-all/ckeditor.js"></script>
<script>

    CKEDITOR.replace( 'description', {
        height: 500,
        filebrowserUploadMethod: "form",
        filebrowserUploadUrl:'/admin/upload/?_token={!! csrf_token() !!}'
    } );

</script>
<script src="/assets/js/core/jquery.3.2.1.min.js"></script>
<script src="/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="/assets/js/core/popper.min.js"></script>
<script src="/assets/js/core/bootstrap.min.js"></script>
<script src="/assets/js/plugin/chartist/chartist.min.js"></script>
<script src="/assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
<script src="/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="/assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="/assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
<script src="/assets/js/plugin/jquery-mapael/maps/world_countries.min.js"></script>
<script src="/assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="/assets/js/ready.min.js"></script>
<script src="/assets/js/demo.js"></script>

<script>
    $(document).ready(function() {
        $('#menu_form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/admin/menu/save/',
                data: $('#menu_form').serialize(),
                success: function (data) {
                    if(data=='Added') {
                        $('#save_success').html('Saved successfully');
                        setTimeout(function() {
                            $('#save_success').html('');
                            location.reload();
                        }, 1000);
                    }
                    else if(data=='Error') {
                        $('#save_success').html('<span style="color:#ff0000;">Value must not be empty!</span>');
                    }
                    console.log(data);
                },
                error: function () {

                }
            });
        });

        $('#menu_mobile_form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/admin/menu_mobile/save/',
                data: $('#menu_mobile_form').serialize(),
                success: function (data) {
                    if(data=='Added') {
                        $('#save_success').html('Saved successfully');
                        setTimeout(function() {
                            $('#save_success').html('');
                            location.reload();
                        }, 1000);
                    }
                    else if(data=='Error') {
                        $('#save_success').html('<span style="color:#ff0000;">Value must not be empty!</span>');
                    }
                    console.log(data);
                },
                error: function () {

                }
            });
        });

        $('#menu_footer_form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/admin/menu_footer/save/',
                data: $('#menu_footer_form').serialize(),
                success: function (data) {
                    if(data=='Added') {
                        $('#save_success').html('Saved successfully');
                        setTimeout(function() {
                            $('#save_success').html('');
                            location.reload();
                        }, 1000);
                    }
                    else if(data=='Error') {
                        $('#save_success').html('<span style="color:#ff0000;">Value must not be empty!</span>');
                    }
                    console.log(data);
                },
                error: function () {

                }
            });
        });

        $('#settings_form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/admin/settings/save/',
                data: $('#settings_form').serialize(),
                success: function (data) {
					console.log(data);
                    if(data=='Added') {
                        $('#save_success').html('Saved successfully');
                        setTimeout(function() {
                            $('#save_success').html('');
                            location.reload();
                        }, 1000);
                    }
                    else if(data=='Error') {
                        $('#save_success').html('<span style="color:#ff0000;">Key must not be empty!</span>');
                    }
                    console.log(data);
                },
                error: function () {

                }
            });
        });

        $('#attr_form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/admin/attributes/save/',
                data: $('#attr_form').serialize(),
                success: function (data) {
                    if(data=='Added') {
                        $('#save_success').html('Saved successfully');
                        setTimeout(function() {
                            $('#save_success').html('');
                            //location.reload();
                        }, 1000);
                    }
                    console.log(data);
                },
                error: function () {

                }
            });
        });

    });

    function addItemMenu(num) {
        html = '<tr><td width="100px"><input style="width:50%;" type="text" name="sort['+num+'][]" value="0"></td>' +
               '<td><input style="width:50%;" type="text" name="value['+num+'][]" value=""></td>' +
               '<td><input style="width:50%;" type="text" name="menu_key['+num+'][]" value=""></td>' +
               '<td width="200px">' +
               '<input name="status['+num+'][]" type="checkbox" checked data-toggle="toggle" data-onstyle="default" data-style="btn-round">' +
               '</td>' +
               '<td width="100px"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
               '</tr>';
        $('#menu_form table tbody').append(html);
        $('#add_button').attr('onClick', 'addItemMenu('+parseInt(num+1)+');');
    }

    function addItemMobileMenu(num) {
        html = '<tr><td width="100px"><input style="width:50%;" type="text" name="sort['+num+'][]" value="0"></td>' +
            '<td><input style="width:50%;" type="text" name="value['+num+'][]" value=""></td>' +
            '<td><input style="width:50%;" type="text" name="menu_key['+num+'][]" value=""></td>' +
            '<td width="200px">' +
            '<input name="status['+num+'][]" type="checkbox" checked data-toggle="toggle" data-onstyle="default" data-style="btn-round">' +
            '</td>' +
            '<td width="100px"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
            '</tr>';
        $('#menu_mobile_form table tbody').append(html);
        $('#add_button').attr('onClick', 'addItemMobileMenu('+parseInt(num+1)+');');
    }

    function addItemFooterMenu(num) {
        html = '<tr><td width="100px"><input style="width:50%;" type="text" name="sort['+num+'][]" value="0"></td>' +
            '<td><input style="width:50%;" type="text" name="value['+num+'][]" value=""></td>' +
            '<td><input style="width:50%;" type="text" name="menu_key['+num+'][]" value=""></td>' +
            '<td width="200px">' +
            '<input name="status['+num+'][]" type="checkbox" checked data-toggle="toggle" data-onstyle="default" data-style="btn-round">' +
            '</td>' +
            '<td width="100px"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
            '</tr>';
        $('#menu_footer_form table tbody').append(html);
        $('#add_button').attr('onClick', 'addItemFooterMenu('+parseInt(num+1)+');');
    }

    function addItemSettings(num) {
        html = '<tr><td><input style="width:80%;" type="text" name="settings_key['+num+'][]" value=""></td>' +
            '<td><input style="width:90%;" type="text" name="settings_value['+num+'][]" value=""></td>' +
            '<td><input style="width:80%;" type="text" name="settings_attr['+num+'][]" value=""></td>' +
            '<td width="100px"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
            '</tr>';
        $('#settings_form table tbody').append(html);
        $('#add_button').attr('onClick', 'addItemSettings('+parseInt(num+1)+');');
    }

<?php if(isset($data['attributes'])) { ?>
    function addItemAttr(num) {
        html = '<tr>' +
            '<td><input style="width:50%;" type="text" name="attribute['+num+'][]" value=""></td>' +
            '<td>' +
            '<select name="category['+num+'][]">'+
            <?php foreach($data['categories'] as $category) { ?>
            '<option value="<?= $category->id; ?>"><?= $category->title; ?></option>'+
            <?php } ?>
            '</select>'+
            '</td>'+
            '<td>'+
            '<input type="text" name="sort['+num+'][]" value="">'+
            '</td>'+
            '<td width="100px"><span onClick="$(this).parent().parent().remove(); deleteAttrItem('+num+');" class="btn btn-danger btn-round"> × Delete</span></td>' +
        '</tr>';

        $('#attr_form table tbody').append(html);
        $('#add_button').attr('onClick', 'addItemAttr('+parseInt(num+1)+');');
    }
<?php } ?>

    function addItemSlider(num) {
        html = '<tr>' +
                '<td width="100px"><input onChange="$(this).fadeOut(0); previewImages(event, '+num+');" type="file" accept="image/jpeg,image/png,image/gif" name="image['+num+'][]">' +
                '<img id="output_image_'+num+'">' +
                '</td>' +
                '<td><input style="width:50%;" type="text" name="title['+num+'][]" value=""></td>' +
                '<td><textarea style="width:100%;" name="description['+num+'][]"></textarea></td>' +
                '<td width="200px">' +
                '<input name="status['+num+'][]" type="checkbox" data-toggle="toggle" data-onstyle="default" data-style="btn-round">' +
                '</td>' +
                '<td width="100px"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
                '</tr>';

        $('#slider_form table tbody').append(html);
        $('#add_button').attr('onClick', 'addItemSlider('+parseInt(num+1)+');');
    }

    function addImageItem(num) {
        html = '<tr>' +
               '<td><input required onChange="$(this).fadeOut(0); previewImages(event, '+num+');" type="file" accept="image/jpeg,image/png,image/gif" name="images['+num+'][0]">' +
               '<img id="output_image_'+num+'">' +
               '</td>'+
                '<td>' +
                '<input type="text" name="images['+num+'][1]">'+
                '</td>' +
               '<td style="width:100px;"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
               '</tr>';

        $('#imgs').append(html);
        $('#addImgButton').attr('onClick', 'addImageItem('+parseInt(num+1)+');');
    }

    function addColorItem(num) {
        html = '<tr>' +
               '<td><input required onChange="$(this).fadeOut(0); previewImages(event, '+num+');" type="file" accept="image/jpeg,image/png,image/gif" name="colors['+num+'][0]">' +
               '<img id="output_image_'+num+'">' +
               '</td>'+
                '<td>' +
                '<input type="text" name="colors['+num+'][1]">'+
                '</td>' +
               '<td style="width:100px;"><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
               '</tr>';

        $('#colors').append(html);
        $('#addColorButton').attr('onClick', 'addColorItem('+parseInt(num+1)+');');
    }

    function addContributions() {
        html = '<tr>' +
                '<td><input type="text" class="form-control" name="contributions[]" required></td>' +
                '<td><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
            '</tr>';
        $('#attrs').append(html);
    }

    function addLinks() {
        html = '<tr>' +
            '<td><input type="text" class="form-control" name="links[value][]" required></td>' +
            '<td><input type="text" class="form-control" name="links[link][]" required></td>' +
            '<td><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
            '</tr>';
        $('#attrs1').append(html);
    }

    function addFlags() {
        html = '<tr>' +
            '<td><select name="flaglist[svg][]" class="form-control" required>' +
            '<option value="">Choose Flag</option>' +
            '<option value="img/ai.svg">AI</option>' +
            '<option value="img/aiga.svg">AIGA</option>' +
            '<option value="img/behance.svg">Behance</option>' +
            '<option value="img/gr.svg">GR</option>' +
            '<option value="img/in.svg">IN</option>' +
            '<option value="img/ps.svg">PS</option>' +
            '<option value="img/ss.svg">SS</option>' +
            '<option value="img/xd.svg">XD</option>' +
            '</select></td>' +
            '<td><input type="text" class="form-control" name="flaglist[sort][]" required></td>' +
            '<td><span onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-round"> × Delete</span></td>' +
            '</tr>';
        $('#attrs2').append(html);
    }

    function deleteUsersItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/users/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteMenuItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/menu/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteArticlesItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/blog/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteSettingsItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/settings/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteMenuMobileItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/menu_mobile/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteMenuFooterItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/menu_footer/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteAttrItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/attributes/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteCategoryItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/categories/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteProductItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/products/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deletePageItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/pages/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteTeamItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/team/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteVacanciesItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/vacancies/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deletePortfolioItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/portfolio/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteLogofolioItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/logofolio/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteSliderItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/slider/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    that.parent().parent().remove();
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

    function deleteSubscribersItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/subscribers/delete/',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                },
                error: function () {

                }
            });
        } else {
            return false;
        }
    }

	function changeStatus(order_id, value) {
		$.ajax({
                type: 'GET',
                url: '/admin/change-status/',
                data: {order_id: order_id, value: value},
                success: function (data) {
                    console.log(data);
                },
                error: function () {

                }
            });
	}

    function categoryAdded() {
        $.notify({
            icon: 'la la-bell',
            title: 'Successfully added!',
            message: 'New item was added successfully',
        },{
            type: 'success',
            placement: {
                from: "top",
                align: "right"
            },
            time: 2000,
        });
    }

    function pageAdded() {
        $.notify({
            icon: 'la la-bell',
            title: 'Successfully added!',
            message: 'New item was added successfully',
        },{
            type: 'success',
            placement: {
                from: "top",
                align: "right"
            },
            time: 2000,
        });
    }

    function blogEdited() {
        $.notify({
            icon: 'la la-bell',
            title: 'Successfully updated!',
            message: 'This item was updated successfully!',
        },{
            type: 'success',
            placement: {
                from: "top",
                align: "right"
            },
            time: 2000,
        });
    }

    function blogAdded() {
        $.notify({
            icon: 'la la-bell',
            title: 'Successfully added!',
            message: 'New item was added successfully!',
        },{
            type: 'success',
            placement: {
                from: "top",
                align: "right"
            },
            time: 2000,
        });
    }

    function teamAdded() {
        $.notify({
            icon: 'la la-bell',
            title: 'Successfully added!',
            message: 'New item was added successfully!',
        },{
            type: 'success',
            placement: {
                from: "top",
                align: "right"
            },
            time: 2000,
        });
    }

    function previewImage(event){
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('output_image');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        $('#output_image').fadeIn();
    }

    function previewImage2(event){
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('output_image2');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        $('#output_image2').fadeIn();
    }

    function previewImage3(event){
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('output_image3');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        $('#output_image3').fadeIn();
    }

    function previewBigImage(event){
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('output_big_image');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        $('#output_big_image').fadeIn();
    }

    function previewImages(event, num){
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('output_image_'+num);
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        $('#output_image_'+num).fadeIn();
    }
    //После сохранения каких-то данных с помощью AJAX - можно выводить это:
    /*
    $.notify({
        icon: 'la la-bell',
        title: 'Saved Successfully',
        message: 'Lorem ipsum dolor sit amet...',
    },{
        type: 'success',
        placement: {
            from: "bottom",
            align: "right"
        },
        time: 1000,
    });
    */
</script>
</html>

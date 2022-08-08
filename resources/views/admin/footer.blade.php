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
</body>

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

    function deleteVideoItem(id, that) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '/admin/video/delete/',
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

</script>
</html>

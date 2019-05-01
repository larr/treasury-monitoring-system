$(document).ready(function () {
    $('#users').addClass('active');

    $('#datatable').on('click', '.user-roles', function (e) {

        var url = $(this).attr('data-url');

        $.ajax({
            url: url,
            method:'GET',
            success: function (data) {
                $('#roles').html(data.view);
                $('.user-name').text(data.user);
            },
            error: function (e) {

            }
        });

    });

    $('#roles').on('change', 'input[type="checkbox"]', function (res) {
        $('#form').submit();
    });

    $('#form').on('submit', function (e) {
        e.preventDefault();
        var data = $(this).serialize(),
            url = $(this).attr('action');
        $.ajax({
            url: url,
            method:'POST',
            data: data,
            success: function (data) {

            },
            error: function (e) {

            }
        });
    });
});
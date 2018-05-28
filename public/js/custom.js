$(document).ready(function() {
    $('#clicks').DataTable();

    $('#bad-domains').DataTable();

    $(document).on('click', '#tracking-link button', function() {
        window.open($('#tracking-link input').val(), "_self");
    })
});
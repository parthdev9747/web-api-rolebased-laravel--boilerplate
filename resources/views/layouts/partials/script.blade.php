<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/axios.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
{!! Toastr::message() !!}
<script>
    paceOptions = {
        ajax: {
            trackMethods: ['GET', 'POST', 'PUT', 'DELETE', 'REMOVE']
        }
    };

    window.Laravel = <?php echo json_encode([
                            'csrfToken' => csrf_token(),
                        ]); ?>

    $(document).ready(function() {
        deleteRecordByAjax = (url, moduleName, dTable) => {
            Swal.fire({
                title: "Are you sure?",
                text: `You will not be able to recover this ${moduleName}!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((willDelete) => {
                if (willDelete.value) {
                    axios.delete(url).then((response) => {
                        if (response.data.status) {
                            dTable.draw();
                            if (response.data.type === 'warning') {
                                toastr.warning(response.data.message);
                            } else {
                                toastr.success(response.data.message);
                            }
                        } else {
                            toastr.error(response.data.message);
                        }
                    }).catch((error) => {
                        let data = error.response.data
                        toastr.error(data.message);
                    });
                }
            })
        }
    })
</script>
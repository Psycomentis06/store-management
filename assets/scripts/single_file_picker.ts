/*$('#myframe').on('load', function () {
    $(this).contents().on('click','.select',function () {
        var path = $(this).attr('data-path')
        $('#path').val(path);
        $('#image')
            .attr('src', path)
            .attr('style', 'object-fit: cover; width: 300px;height: 500px')
        $('#myModal').modal('hide')
    });
});*/
window.addEventListener('load', () => {
    const singleFilePickerFrames = <HTMLIFrameElement>document.getElementById('single_file_picker_frame');
    const inputField = <HTMLInputElement>document.querySelector('.single-file-picker-form-row input')
    const selectedImgPreview = <HTMLImageElement>document.getElementById('filePickerSelectedImage')
    // If there is already data inside input
    if (inputField.value.length > 0) {
        selectedImgPreview.setAttribute('src', inputField.value)
        selectedImgPreview.setAttribute('style', 'object-fit: cover; width: 300px;height: 500px')
    }
    const selectField = singleFilePickerFrames.contentDocument?.querySelectorAll('.select')
        if (selectField) {
            for (let j = 0; j < selectField.length; j++) {
                const item = selectField[j]
                item.addEventListener('click', evt => {
                    const target = evt.target as HTMLElement
                    const path = target.getAttribute('data-path')
                    if (path) {
                        inputField.value = path
                        selectedImgPreview.setAttribute('src', path)
                        selectedImgPreview.setAttribute('style', 'object-fit: cover; width: 300px;height: 500px')
                        $('#singleFilePicker').modal('hide');
                    }
                })
            }
        }
})
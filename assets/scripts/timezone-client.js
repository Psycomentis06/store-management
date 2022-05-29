window.addEventListener('load', () => {
    let offset = new Date().getTimezoneOffset() / 60;
    if (offset > 0) {
        offset = offset * +1;
    } else {
        offset = offset * -1;
    }
    document.cookie = `tz_offset=${offset}`;
})
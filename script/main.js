function create_UUID() {
    var dt = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,
        function(c) {
            var r = (dt + Math.random() * 16) % 16 | 0;
            dt = Math.floor(dt/16);
            return (c == 'x' ? r : (r&0x3 | 0x8)).toString(16);
        });
        var address_input = document.getElementById('wal_usr_address').value = uuid;
    }
// get the id of the generate wallet button
var genrt_wal_btn = document.getElementById('generate-btn');
genrt_wal_btn.addEventListener("click", create_UUID);

function display() {
    var disp_addr = document.getElementById('disp_addr');
    if (disp_addr.style.display == 'none') {
        disp_addr.style.display = 'inline-block';
    } else {
        disp_addr.style.display = 'none';
    }
    return disp_addr.style.display;
}

function copy() {
    // get the value of the input form
    var copyText = document.getElementById('ref_link');
    // select the value of the input form
    copyText.select();
    // set the range of the text to be copied
    copyText.setSelectionRange(0,99999);
    document.execCommand("copy");
    // alert with a message if copy is successful
    alert("Copied");
}
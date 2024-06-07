function getData() {
    return $.ajax({
        url : 'zoho.php',
        type: 'POST'
    });
}

function handleData(data /* , textStatus, jqXHR */ ) {
    return data;
    //do some stuff
}

getData().done(handleData);

/ exporting variables and function
export { handleData };

Vue.filter('numberFormat', function (nStr) {
    nStr += '';
    let x = nStr.split('.');
    let x1 = x[0];
    let x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return  x1 + x2;
});
Vue.filter('dateFormat', function (nStr) {
    return moment(nStr).isValid() ? moment(nStr).format('dddd, DD MMM YYYY') : '-'
});
Vue.filter('datetimeFormat', function (nStr) {
    return moment(nStr).isValid() ? moment(nStr).format('dddd, DD MMM YYYY HH:mm:ss') : '-'
});
Vue.filter('datetimeLong', function (nStr) {
    return moment(nStr).isValid() ? moment(nStr).format('dddd, DD/MM/YYYY HH:mm') : '-'
});
Vue.filter('datetimeSort', function (nStr) {
    return moment(nStr).isValid() ? moment(nStr).format('DD/MM/YYYY HH:mm') : '-'
});

Vue.filter('hoursToDay', (str) => {
    let day = Math.floor(str/24)
    let hour = str%24
    if(hour > 0 && day > 0){
        return `${day} hari ${hour} jam`
    }
    if(hour == 0 && day > 0){
        return `${day} hari`
    }
    if(hour > 0 && day == 0){
        return `${hour} jam`
    }
})

Vue.use(VueNumeric.default);
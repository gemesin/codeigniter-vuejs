moment.locale('id')
let baseApiUri = window.location.origin + '/api/'
let baseApiUpload = window.location.origin + '/wp-content/image.php'

const omValidation = {
  showErrorValidation : (data) => {
    for (let key in data) {
      // skip loop if the property is from prototype
      if (!data.hasOwnProperty(key)) continue;
      let obj = data[key];
      $(`#input-${key}`).removeClass('is-valid').addClass('is-invalid')
      $(`#error-${key}`).html(obj)
    }
  },
  clearErrorValidation : (form) => {
    $(`#error-message-${form}`).html('');
    $(`form#${form} :input`).each(function() {
      $(this).removeClass('is-invalid');
    });
    $(`#error-message-${form}`).html(``);
  },
  showErrorMessage: (form,message) => {
    $(`#modal-body-${form}`).animate({
      scrollTop: $(`#form-body-${form}`).prop("scrollHeight")
    }, 1000);
    $(`#error-message-${form}`).html(`
      <div class="alert border-danger alert-dismissible mb-2" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
          <div class="d-flex align-items-center">
              <i class="bx bx-error"></i>
              <span>
                  ${message}
              </span>
          </div>
      </div>
    `);
  },
  toggleLoadingForm:(form, state, text) => {
    if(state == 'show'){
      $(`form#${form} :submit`).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`).prop('disabled', true)
    }else{
      $(`form#${form} :submit`).html(text).prop('disabled', false)
    }
  }
}

const getHeader = async () => {
  return {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  };
};

const logOut = async () => {
  window.location.reload();
}

const generateParams = (params) =>{
  let queryString = '';
    for (let key in params) {
      if (!params.hasOwnProperty(key)) continue;
      if (typeof params[key] == 'object') {
        params[key].forEach((item, index) => {
          for (let keyItem in item) {
            queryString += `${key}[${index}][${keyItem}]=${encodeURI(item[keyItem],)}&`;
          }
        });
      } else {
        queryString += `${key}=${encodeURIComponent(params[key])}&`;
      }
    }
    return queryString == '' ? '' : `?${queryString.replace(/&+$/, '')}`;
}

const getOS = () => {
  let userAgent = navigator.userAgent || navigator.vendor || window.opera;

  // Windows Phone must come first because its UA also contains "Android"
  if (/windows phone/i.test(userAgent)) {
    return "Windows Phone";
  }

  if (/android/i.test(userAgent)) {
    return "Android";
  }

  // iOS detection from: http://stackoverflow.com/a/9039885/177710
  if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
    return "iOS";
  }

  return "unknown";
}

const ApiHelper = {
  generateFormData: (form) =>{
    let arrForm = $(`#${form}`).serializeArray();
    let formData = {}
    arrForm.forEach(function(data){
        formData[data.name] = data.value
    });
    return formData
  },
  get: async (url, params) => {
    let Header = await getHeader()
    return new Promise((resolve) => {
      let uri = baseApiUri + url + generateParams(params);
      fetch(uri, {
        method: 'GET',
        headers: {
          ...Header,
        },
      })
      .then((response) => {
        if (response.status == '401') {
          logOut();
        }
        return response.json();
      })
      .then((responseData) => {
          return resolve(responseData);
      })
      .catch((error) => {
        return resolve({
          status: 500,
          message: 'Terjadi Kesalahan Server.',
          error_code: '',
          results: {
            data: {}
          }
        });
      });
    });
  },
  post: async (url, body) => {
    let Header = await getHeader();
    return new Promise((resolve) => {
      fetch(baseApiUri + url, {
        method: 'POST',
        headers: {
          ...Header,
        },
        body: JSON.stringify(body),
      })
      .then((response) => {
        if (response.status == '401') {
          logOut();
        }
        return response.json();
      })
      .then((responseData) => {
        return resolve(responseData);
      })
      .catch((error) => {
        return resolve({
          status: 500,
          message: 'Terjadi Kesalahan Server.',
          error_code: '',
          results: {
            data: {}
          }
        });
      });
    });
  },
  uploadImage: async (body) => {
    return new Promise((resolve) => {
      fetch(baseApiUpload, {
        method: 'POST',
        body
      })
      .then((response) => response.json())
      .then((responseData) => {
        return resolve(responseData);
      })
      .catch((error) => {
        return resolve({
          status: 500,
          message: 'Terjadi Kesalahan Server.',
          error_code: '',
          results: {
            data: {}
          }
        });
      });
    })
  }
}

const Format = {
  date: (text) => {
    return moment(text).format('ddd, DD MMM YYYY')
  },
  datetime: (text) => {
    return moment(text).format('ddd, DD MMM YYYY HH:mm')
  },
  number: (text) => {
    return text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
  },
  numberRp: (text) => {
    return 'Rp. ' + text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
  }
}

$(document).ready(() =>{

  $(document).ready(() => {
    $('input').attr('autocomplete', 'off');
  })

  $('.change-store').on('click', async(e) => {
    let store_id = $(e.target).data("store")
    let changeStore = await ApiHelper.post('account/profile/select_store', {store_id})
    if(changeStore.status == '200'){
      $('#modalChangeStore').modal('hide')
      Swal.fire({
        title: 'Barhasil!',
        text: 'Berhasil Ganti Toko',
        icon: 'success',
        showCancelButton: false,
        confirmButtonText: 'Ok',
      }).then((result)=> {
        if(result.value){
          window.location.reload()
        }
      });
    }else {
      swal(
        'Gagal',
        'Gagal ganti toko.',
        'error'
      )
    }
  })
  $('#change-store').on('click', async() => {
    $('#modalChangeStore').modal('show')
  });
  $('#input-file-image').on('change', async (e) =>{
    const formData  = new FormData();
    formData.append('image', e.target.files[0])
    let upload = await ApiHelper.uploadImage(formData)
    if(upload.status == 200){
      $('#input-image_url').val(upload.results.data.fileuri)
      $('#file-image').attr('src',upload.results.data.fileuri);
    }else{
      alert('Gagal Upload Gambar!');
      $('#input-file-image').val('');
    }
  })
  $('#change-theme').on('click', () => {
    console.log('masuk');
    let url = window.location.origin + '/admin/dashboard/change-theme'
    fetch(url, {
        method: 'GET',
        headers:{
          Accept: 'application/json',
          'Content-Type': 'application/json',
        }
      })
      .then((response) => {
        return response.json();
      })
      .then((responseData) => {
          window.location.reload()
      })
      .catch((error) => {
        // window.location.reload()
      });
  })
})

if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}